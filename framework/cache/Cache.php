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
namespace pandaphp\cache;

/**
 * 缓存管理
 * @author songdengtao <http://www.songdengtao.cn/>
 *
 * demo:
 * # 启用redis缓存，并获得redis缓存对象，使用配置文件里的redis配置
 * \Pandaphp::shell('Cache', 'redis')
 *
 * # 启用redis缓存，并获得redis缓存对象，使用自定义的配置$config
 * \Pandaphp::shell('Cache', 'redis', $config)
 */
class Cache
{
	/**
	 * 具体处理缓存操作的对象数组
	 * @var object array
	 */
	private static $_Handlers = [

	];

	private function __constrcut()
	{
	}

	/**
	 * 构造函数
	 * @access public
	 * @param  string $type 缓存类型
	 * @param  array $config 缓存临时自定义配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return object
	 */
	public static function getInstance($type = '', array $config = [ ])
	{
		if (!isset(static::$_Handlers[$type]) || is_null(is_null(static::$_Handlers[$type]))) {
			static::$_Handlers[$type] = static::_GetCacheHanlder($type, $config);
		}
		return static::$_Handlers[$type];
	}

	private static function _GetCacheHanlder($type = '', array $config = [ ])
	{

		if (empty(!$type)) {
			$types = \Pandaphp::shell('Config::get', 'cache_types');
			if (!in_array($type, $types)) {
				\Pandaphp::shell('Error::halt', '不支持缓存或者cache_types未配置');
			}
		} else {
			$type = \Pandaphp::shell('Config::get', 'cache_type');
		}

		if (empty($config)) {
			$config = \Pandaphp::shell('Config::get', 'cache_' . $type);
		}

		$handler = null;
		$class   = '\pandaphp\cache\\' . \Pandaphp::shell('Str::toBigHump', $type) . 'Cache';
		if (class_exists($class)) {
			$handler = new $class($config);
		} else {
			\Pandaphp::shell('Error::halt', '缓存驱动' . $class . '不存在');
		}

		return $handler;
	}

	/**
	 * 设置缓存
	 * @access public
	 * @param  string $key 缓存键
	 * @param  mixed $value 缓存值
	 * @param  string $type 缓存类型
	 * @param  array $config 缓存配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function set($key = '', $value, $type = '', array $config = [ ])
	{
		return static::getInstance($type, $config)->set($key, $value);
	}

	/**
	 * 获取缓存
	 * @access public
	 * @param  string $key 缓存键
	 * @param  string $type 缓存类型
	 * @param  array $config 缓存配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function get($key = '', $type = '', array $config = [ ])
	{
		return static::getInstance($type, $config)->get($key);
	}

	/**
	 * 删除缓存
	 * @access public
	 * @param  string $key 缓存键
	 * @param  string $type 缓存类型
	 * @param  array $config 缓存配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function delete($key = '', $type = '', array $config = [ ])
	{
		return static::getInstance($type, $config)->delete($key);
	}

	/**
	 * 清空缓存
	 * @access public
	 * @param  string $type 缓存类型
	 * @param  array $config 缓存配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function flush($type = '', array $config = [ ])
	{
		return static::getInstance($type, $config)->flush();
	}
}