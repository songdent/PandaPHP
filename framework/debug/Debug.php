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
namespace pandaphp\debug;

/**
 * 系统测试
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Debug
{
	/**
	 * 打印信息
	 * @access public
	 * @param mixed $var
	 * @param boolean $isPrint
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function dump($var, $isPrint = false)
	{
		echo '<pre>';
		if ($isPrint) {
			print_r($var);
		} else {
			var_dump($var);
		}
		echo '</pre>';
	}

	// 直接输出到浏览器JS控制台进行调试
	public static function js($data)
	{
		echo '<script>console.log(';
		echo json_encode($data);
		echo ')</script>';
	}
}