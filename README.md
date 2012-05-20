PhpInlineTest
=============

PhpInlineTest - script to run inline tests for PHP functions and class methods.

WTF is inline tests?
--------------------

Inline tests are asserts embedded right into PHPDOC-comments.

__Benefits__
* Easy and fast to add tests for your functions.
* Tests can complement/replace documentation for method.
* Can be written for private/protected class methods.
* You will never lose them: they are always inside scripts.

__Downsides__
* Good only for simple test cases.
* Can be written only for isolated functions: input - arguments, output - return value.

Examples
--------

```php
<?php
/**
 * Inline test example for bubble sort function
 * @assert (array(2,1,3)) == array(1,2,3)
 */ 
function sort(array $arr) {
	$size = sizeof($arr) - 1;
	for ($i = $size; $i >= 0; $i--) {
		for ($j = 0; $j <= ($i - 1); $j++)
			if ($arr[$j] > $arr[$j + 1]) {
				$k = $arr[$j];
				$arr[$j] = $arr[$j + 1];
				$arr[$j + 1] = $k;
			}
	}
	return $arr;
}
class A {
	/**
	 * Inline test example
	 *     for private class method
	 * @assert (2, 2) == 4
	 */
	private function _add($a, $b) {
		return $a + $b;
	}
}
```

	$ php phpinlinetest.php
	.\example.php
	Test "sort(array(2,1,3)) == array(1,2,3)": OK
	Test "_add(2, 2) == 5": FAIL
	Files: 1, tests: 2, succeed: 1, failed: 1

Usage
-----

1. Download [latest version](https://github.com/ptrofimov/phpinlinetest/blob/master/phpinlinetest.php).
2. Run 
	* php phpinlinetest.php example.php - for single file
	* php phpinlinetest.php - for all PHP files in current directory