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
namespace pandaphp\sysfile;

/**
 * 文件处理
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class File extends FileAbstarct
{
	/**
	 * 获取包含文件的内容
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function returnInclude($path = '')
	{
		$ret = '';
		if (static::isExist($path, true)) {
			$ret = include $path;
		}
		return $ret;
	}
}