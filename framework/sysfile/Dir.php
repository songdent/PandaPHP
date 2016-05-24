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
 * 目录处理
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Dir
{
	/**
	 * 判断目录是否存在
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function isExist($path = '')
	{
		$ret = false;
		if (!empty($path)) {
			$ret = is_dir($path);
		}
		return $ret;
	}

	/**
	 * 目录是否可写
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function isWritable($path = '')
	{
		$ret = false;
		if (!empty($path)) {
			$ret = is_writable($path);
		}
		return $ret;
	}

	/**
	 * 创建目录
	 * @access public
	 * @param string $path
	 * @param boolean $recursive 是否迭代创建
	 * @param int $mode
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean 创建是否成功
	 */
	public static function mkdir($path = '', $recursive = false, $mode = 0775)
	{
		$ret = false;
		if (!empty($path)) {
			if (static::isExist($path)) {
				$ret = true;
			} else {
				$ret = mkdir($path, $mode, $recursive);
			}
		}
		return $ret;
	}

	/**
	 * 列出目录中的所有目录和文件，可选择是否迭代列出
	 * @access public
	 * @param string $path
	 * @param boolean $recursive
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return array
	 */
	public static function lt($path = '', $recursive = false)
	{

	}

	/**
	 * @decription
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	public static function cp($path = '', $target = '')
	{

	}

	/**
	 * @decription
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	public static function mv($path = '', $target = '')
	{

	}

	/**
	 * @decription
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	public static function rm($path = '')
	{

	}
}