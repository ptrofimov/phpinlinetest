<?php
/**
 * PhpInlineTest - script to run inline-tests for PHP functions
 * Usage: php phpinlinetest.php [file|dir] [file|dir] ...
 * @link https://github.com/ptrofimov/phpinlinetest
 * @author Petr Trofimov
 * @version 0.1
 */
error_reporting(E_ALL);
ini_set('display_errors', false);

function getFiles($argc, array $argv) {
	$dirs = $files = array();
	if ($argc == 1) $dirs[] = '.';
	else foreach (array_slice($argv, 1) as $arg) {
		if (is_file($arg)) $files[] = $arg;
		elseif (is_dir($arg)) $dirs[] = $arg;
		else exit('Invalid argument:' . $arg . PHP_EOL);
	}
	while ($dir = array_shift($dirs)) {
		if (!($dh = opendir($dir))) exit('Failed to open dir: ' . $dir);
		while (($file = readdir($dh)) !== FALSE) {
			if (in_array($file, array('.', '..'))) continue;
			$file = $dir . DIRECTORY_SEPARATOR . $file;
			if (is_file($file)) {
				if (pathinfo($file, PATHINFO_EXTENSION) == 'php') $files[] = $file;
			} elseif (is_dir($file)) array_push($dirs, $file);
			else exit('Unknown path: ' . $file . PHP_EOL);
		}
		closedir($dh);
	}
	return $files;
}

function getAsserts($comment) {
	$asserts = array();
	$lines = preg_split("#\n|\r#", $comment);
	foreach ($lines as $line) {
		$line = trim($line, "\r\n/* \t");
		if (substr($line, 0, 7) == '@assert') {
			$assert = trim(substr($line, 7));
			if ($assert) $asserts[] = $assert;
		}
	}
	return $asserts;
}

function getFunctions($code) {
	$step = 0;
	$functions = array();
	$tokens = token_get_all($code);
	$skip = array(T_WHITESPACE, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC);
	foreach ($tokens as $token) {
		if ($step == 0 && is_array($token) && $token[0] == T_DOC_COMMENT) {
			$asserts = getAsserts($token[1]);
			if ($asserts) $step = 1;
		} elseif ($step == 1) {
			if (is_array($token) && $token[0] == T_FUNCTION) {
				$code = $token[1];
				$step = 2;
			} elseif (!is_array($token) || !in_array($token[0], $skip)) $step = 0;
		} elseif ($step == 2) {
			if (is_array($token) && $token[0] == T_STRING) {
				$name = $token[1];
				$id = $token[1] = 'f' . md5(uniqid(microtime(), true));
				$step = 3;
				$level = 0;
			} elseif (!is_array($token) || $token[0] != T_WHITESPACE) $step = 0;
			$code .= is_array($token) ? $token[1] : $token;
		} elseif ($step == 3) {
			$code .= is_array($token) ? $token[1] : $token;
			if (!is_string($token)) continue;
			elseif ($token == '{') $level++;
			elseif ($token == '}') {
				if (!--$level) {
					$step = 0;
					$functions[] = array( 
						'asserts' => $asserts,
						'id' => $id,
						'name' => $name,
						'code' => $code);
				}
			}
		}
	}
	return $functions;
}

function testFunctions(array $functions) {
	global $errstr;
	foreach ($functions as $function) {
		eval($function['code']);
		$GLOBALS['tests'] += count($function['asserts']);
		foreach ($function['asserts'] as $assert) {
			$call = ($assert[0] == '(' ? $function['id'] . $assert : $assert);
			$test = ($assert[0] == '(' ? $function['name'] . $assert : $assert);
			$errstr = $result = false;
			echo 'Test "' . $test . '": ';
			eval('$result = ' . $call . ';');
			if (!$errstr && $result) {
				$GLOBALS['succeed']++;
				echo 'OK', PHP_EOL;
			} else {
				$GLOBALS['failed']++;
				echo 'FAIL ', $errstr, PHP_EOL;
			}
		}
	}
}

function error_handler($errno, $errstr, $errfile, $errline) {
	$GLOBALS['errstr'] = $errstr;
}

function shutDownFunction() { 
	$error = error_get_last(); 
	if ($error['type'] == 1) { 
		echo 'Fatal error: ', $error['message'];
	} 
} 

set_error_handler('error_handler', E_ALL);
register_shutdown_function('shutdownFunction'); 

$files = $tests = $succeed = $failed = 0;
foreach (getFiles($argc, $argv) as $file) {
	$code = file_get_contents($file);
	if (strpos($code, '@assert') === FALSE) continue;
	$functions = getFunctions($code);
	if (!$functions) continue;
	echo $file, PHP_EOL;
	testFunctions($functions);
	$files++;
}
echo 'Files: ', $files,
	', tests: ', $tests,
	', succeed: ', $succeed,
	', failed: ', $failed, PHP_EOL;
exit($failed ? 1 : 0);
