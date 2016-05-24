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
 * 系统注册表管理
 * @author　 songdengtao <http://www.songdengtao.cn>
 */
class Registry
{
	/**
	 * 注册表
	 * @var array
	 */
	private static $_registry = [];

	// 初始化系统注册表
	public static function init($registryPath = '')
	{
		$sysRegistryFile = $registryPath . 'system_registry.php';
		if (file_exists($sysRegistryFile)) {
			static::$_registry = require_once $sysRegistryFile;
		}
	}

	// 新增注册表
	public static function add(array $registry = [])
	{
		foreach ($registry as $k => $v) {
			static::$_registry[$k] = $v;
		}
	}

	// 获取注册表内容
	public static function get($name = '', $type = 'system', $namespaceRoot = '')
	{
		$type = strtolower($type);
		switch ($type) {
			case 'helper':
				$namespaceRoot = empty($namespaceRoot) ? $namespaceRoot : 'helper';
				$class         = '\\' . $namespaceRoot . '\\' . $name;
				return static::_ClassExist($class);
				break;
			default:
				if (array_key_exists($name, static::$_registry)) {
					$class = static::$_registry[$name];
					return static::_ClassExist($class);
				}
				die ('[系统错误]：' . $name . '在' . $type . '_registry注册表中未注册');
				break;
		}
	}

	private static function _ClassExist($class = '')
	{
		if (!class_exists($class)) {
			die ('[系统错误]：Class: ' . $class . '不存在');
		}
		return $class;
	}
}