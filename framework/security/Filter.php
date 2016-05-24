<?php
/**
 * +----------------------------------------------------------------
 * + pandaphp [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */
namespace pandaphp\security;

/**
 * 输入输出过滤
 * @author songdengtao <http://www.songdengtao>
 */
class Filter
{
	/**
	 * 输入数据全局过滤
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function globalFilter()
	{
		array_walk_recursive($_GET, '\pandaphp\security\Filter::inputSpecialFilter');
		array_walk_recursive($_POST, '\pandaphp\security\Filter::inputSpecialFilter');
		array_walk_recursive($_REQUEST, '\pandaphp\security\Filter::inputSpecialFilter');
	}

	/**
	 * 系统输入特殊字符过滤
	 *
	 * @access public
	 * @param string $input 输入字符串
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return void
	 */
	public static function inputSpecialFilter(&$input)
	{
		// 过滤查询特殊字符
		$specialPattern = '/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i';
		if (preg_match($specialPattern, $input)) {
			$input .= ' ';
		}
	}

	/**
	 * 系统输入过滤
	 *
	 * @access public
	 * @param mixed $input 输入数据
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return mixed
	 */
	public static function inputFilter($input)
	{
		$ret = $input;
		if (is_array($ret)) {
			array_walk_recursive($ret, function ($inp) {
				return self::inputFilterOne($inp);
			});
		} else {
			$ret = self::inputFilterOne($ret);
		}

		return $ret;
	}

	/**
	 * 系统输入单个过滤
	 *
	 * @access public
	 * @param string $input 输入数据
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return mixed
	 */
	public static function inputFilterOne($input)
	{
		$ret = $input;
		if (!is_array($ret) && !is_null($input)) {
			$ret = strval($ret);
			$ret = trim($ret); // 去掉字符串两端的预定义空格
			$ret = addslashes($ret); // 在指定的预定义字符前添加反斜杠
			$ret = htmlspecialchars($ret); // 把一些预定义的字符转换为html实体
		}

		return $ret;
	}

	/**
	 * 系统输出过滤
	 *
	 * @access public
	 * @param mixed $output 输出数据
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return mixed
	 */
	public static function outputFilter($output)
	{
		$ret = $output;
		if (is_array($ret)) {
			array_walk_recursive($ret, function ($inp) {
				return self::outputFilterOne($inp);
			});
		} else {
			$ret = self::outputFilterOne($ret);
		}

		return $ret;
	}

	/**
	 * 系统输出单个过滤
	 *
	 * @access public
	 * @param string $output 输出数据
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return mixed
	 */
	public static function outputFilterOne($output)
	{
		$ret = $output;
		if (!is_array($ret)) {
			$ret = stripcslashes($ret); // 在指定的预定义字符前添加反斜杠
			$ret = htmlspecialchars_decode($ret); // 把一些预定义的字符转换为html实体
		}

		return $ret;
	}
}