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
 * 文件抽象
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class FileAbstarct
{
	/**
	 * 获取文件名称
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	public static function getFileName($path = '')
	{
		$ret = '';
		if (!empty($path)) {
			$ret = pathinfo($path, PATHINFO_FILENAME);
		}
		return $ret;
	}

	/**
	 * 获取文件的扩展名
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	public static function getFileExt($path = '')
	{
		$ret = '';
		if (!empty($path)) {
			$ret = pathinfo($path, PATHINFO_EXTENSION);
		}
		return $ret;
	}

	/**
	 * 获取文件的mimetype
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	public static function getFileMimeType($path = '')
	{
		$ret = '';
		if (!empty($path)) {
			$ret = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
		}
		return $ret;
	}

	/**
	 * 获取文件的质量大小
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int
	 */
	public static function getFileSize($path = '')
	{
		$ret = 0;
		if (!empty($path)) {
			$ret = filesize($path);
		}
		return $ret;
	}

	/**
	 * 获得文件所在目录的路径
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	public static function filepath2Dirpath($path = '')
	{
		$ret = '';
		if (!empty($path)) {
			$ret = substr($path, 0, strrpos($path, '/')) . '/';
		}
		return $ret;
	}

	/**
	 * 文件是否存在
	 * @access public
	 * @param string $path
	 * @param boolean $isNormal
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function isExist($path = '', $isNormal = false)
	{
		$ret = false;
		if (!empty($path)) {
			$ret = $isNormal ? is_file($path) : file_exists($path);
		}
		return $ret;
	}

	/**
	 * 是否可写
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
	 * @decription
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	public static function lastModified($path = '')
	{
		$ret = 0;
		if (static::isExist($path, true)) {
			$ret = filemtime($path);
		}
		return $ret ? $ret : 0;
	}

	/**
	 * 文件复制
	 * @access public
	 * @param string $path
	 * @param string $target
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function cp($path = '', $target = '')
	{
		$ret = false;
		if (!empty($path) && static::isExist($path) && !empty($target)) {
			$ret = copy($path, $target);
		}
		return $ret;
	}

	/**
	 * 移动
	 * @access public
	 * @param string $path
	 * @param string $target
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function mv($path = '', $target = '')
	{
		$ret = false;
		if (!empty($path) && static::isExist($path) && !empty($target)) {
			$ret = rename($path, $target);
		}
		return $ret;
	}

	/**
	 * 文件重命名
	 * @access public
	 * @param string $path
	 * @param string $target
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function rname($path = '', $target = '')
	{
		$ret = false;
		if (!empty($path) && static::isExist($path) && !empty($target)) {
			$ret = rename($path, $target);
			static::rm($path);
		}
		return $ret;
	}

	/**
	 * 删除文件
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public static function rm($path = '')
	{
		$ret = false;
		if (!empty($path) && static::isExist($path)) {
			$ret = unlink($path);
		}
		return $ret;
	}

	/**
	 * 往文件中添加数据
	 * @access public
	 * @param string $path
	 * @param mixed $data
	 * @param boolean $lock
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int
	 */
	public static function put($path = '', $data = null, $lock = false)
	{
		$ret = 0;
		if (!empty($path) && !is_null($data)) {
			$dirPath = static::filepath2Dirpath($path);
			$flag = true;
			if (false === Dir::isExist($path)) {
				if (false === Dir::makdir($dirPath, true)) {
					$flag = false;
				}
			}
			if (Dir::isWritable($dirPath)) {
				if ($flag) {
					$content = trim(var_export($data, true), "'");
					$content = trim($content, '"');
					$content = stripslashes($content);
					$ret = file_put_contents($path, $content, ($lock ? LOCK_EX : 0));
				}
			}
		}
		return $ret;
	}

	/**
	 * 追加文件内容
	 * @access public
	 * @param string $path
	 * @param mixed $data
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int
	 */
	public static function append($path = '', $data = null)
	{
		$ret = 0;
		if (!empty($path) && !is_null($data)) {
			$ret = file_put_contents($path, var_export($data, true), FILE_APPEND);
		}
		return $ret;
	}

	/**
	 * 获取文件内容
	 * @access public
	 * @param string $path
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function get($path = '')
	{
		$ret = '';
		if (!empty($path)) {
			if (static::isExist($path, true)) {
				$ret = file_get_contents($path);
			}
		}
		return $ret;
	}
}