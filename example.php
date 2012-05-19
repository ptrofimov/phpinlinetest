<?php
/**
 * Bubble sort: inline-test example
 * @test (array(2,1,3)) == array(1,2,3)
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