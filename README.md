# PhpInlineTest ![English version](http://upload.wikimedia.org/wikipedia/en/thumb/a/ae/Flag_of_the_United_Kingdom.svg/22px-Flag_of_the_United_Kingdom.svg.png)

*PhpInlineTest is script to run inline tests for PHP functions and class methods*

### WTF is inline tests?

Inline tests are asserts embedded right into PHPDOC-comments.

__Benefits__
* **Easy and fast** to add tests for your functions.
* Tests can complement/replace **documentation** for method.
* Can be written for **private/protected** class methods.
* You will **never lose** them: they are always inside scripts.

__Downsides__
* Good only for **simple** test cases.
* Can be written only for **isolated** functions: input - arguments, output - return value.

### How to use? It is easy:

Step 1. Write assert line in PHPDOC-comment for class method or function:

```php
<?php
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

Step 2. Download [latest version of phpinlinetest](https://github.com/ptrofimov/phpinlinetest/blob/master/phpinlinetest.php).

Step 3. Run phpinlinetest to check your asserts:

	$ php phpinlinetest.php
	.\example.php
	Test "_add(2, 2) == 4": OK
	Files: 1, tests: 1, succeed: 1, failed: 0
	
Step 4. Enjoy!

--------------------------------------------------

# PhpInlineTest ![Русская версия](http://upload.wikimedia.org/wikipedia/en/thumb/f/f3/Flag_of_Russia.svg/22px-Flag_of_Russia.svg.png)

*PhpInlineTest is script to run inline tests for PHP functions and class methods*

### WTF is inline tests?

Inline tests are asserts embedded right into PHPDOC-comments.

__Benefits__
* **Easy and fast** to add tests for your functions.
* Tests can complement/replace **documentation** for method.
* Can be written for **private/protected** class methods.
* You will **never lose** them: they are always inside scripts.

__Downsides__
* Good only for **simple** test cases.
* Can be written only for **isolated** functions: input - arguments, output - return value.

### How to use? It is easy:

Step 1. Write assert line in PHPDOC-comment for class method or function:

```php
<?php
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

Step 2. Download [latest version of phpinlinetest](https://github.com/ptrofimov/phpinlinetest/blob/master/phpinlinetest.php).

Step 3. Run phpinlinetest to check your asserts:

	$ php phpinlinetest.php
	.\example.php
	Test "_add(2, 2) == 4": OK
	Files: 1, tests: 1, succeed: 1, failed: 0
	
Step 4. Enjoy!