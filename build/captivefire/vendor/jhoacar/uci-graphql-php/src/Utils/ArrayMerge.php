<?php  namespace UciGraphQL\Utils;class ArrayMerge{public static function merge_arrays($arr1,$arr2):array{$keys=array_keys($arr2);foreach($keys as $key){if(isset($arr1[$key])&&is_array($arr1[$key])&&is_array($arr2[$key])){$arr1[$key]=self::merge_arrays($arr1[$key],$arr2[$key]);}else{$arr1[$key]=$arr2[$key];}}return $arr1;}}