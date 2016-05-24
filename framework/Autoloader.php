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

/**
 * 类文件自动加载
 * @author songdengtao <http://www.songdengtao.cn>
 */
class Autoloader
{
	private static $_pandaphp = null;

	public static function init($pandaphp)
	{
		static::$_pandaphp = $pandaphp;
	}

	// 类文件自动加载
	public static function autoload()
	{
		$autoloadFunc = function ($class) {
			$basepath  = static::_GetClassFileBasePath($class);
			$class     = str_replace('\\', '/', $class);
			$classFile = $basepath . $class . static::$_pandaphp->get('classFileExt');
			if (!is_file($classFile)) {
				$classFile = $basepath . $class . '.class.php';
			}

			if (is_file($classFile)) {
				include $classFile;
			}
		};

		spl_autoload_register($autoloadFunc, true, false);
	}

	/**
	 * 获取自动加载中的文件定位（基本路径）
	 * @access private
	 * @param  mixed $class
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	private static function _GetClassFileBasePath(&$class)
	{
		$root          = static::$_pandaphp->get('root');
		$appPath       = static::$_pandaphp->get('appPath');
		$frameworkPath = static::$_pandaphp->get('frameworkPath');
		$venderPath    = static::$_pandaphp->get('venderPath');
		$name          = strstr($class, '\\', true);
		if ($name) {
			if (lcfirst($name) === 'pandaphp') {
				$class    = str_replace('pandaphp\\', '', $class);
				$basepath = $frameworkPath;
			} elseif (is_dir($root . $name)) {
				$basepath = $root;
			} else {
				$basepath = $appPath;
			}
		} else {
			$temp = lcfirst($class);
			if (is_dir($frameworkPath . 'sysvender/' . $temp)) {
				$basepath = $frameworkPath . 'sysvender/' . $temp . '/';
			} else {
				$basepath = $venderPath;
			}
		}

		return $basepath;
	}
}