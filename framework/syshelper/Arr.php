<?php
/**
 * +----------------------------------------------------------------
 * + pandaphp.com [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */
namespace pandaphp\syshelper;

/**
 * 数组辅助类
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Arr
{
	public static function arrayColumn(array $arr = [ ], $columnKey = '', $indexKey = null)
	{
		if (function_exists('array_column')) {
			return array_column($arr, $columnKey, $indexKey);
		} else { // for php 5.6 一下版本
			$arrayColumnFunc = function ($input, $column_key, $index_key = null) {
				$arr = array_map(function ($d) use ($column_key, $index_key) {
					if (!isset($d[$column_key])) {
						return null;
					}
					if ($index_key !== null) {
						return [ $d[$index_key] => $d[$column_key] ];
					}
					return $d[$column_key];
				}, $input);

				if ($index_key !== null) {
					$tmp = [ ];
					foreach ($arr as $k => $v)
						if (is_array($v)) {
							foreach ($v as $i => $t) {
								$tmp[key($v)] = current($v);
							}
						}
					$arr = $tmp;
				}
				return $arr;
			};
			return $arrayColumnFunc($arr, $columnKey, $indexKey);
		}
	}
}