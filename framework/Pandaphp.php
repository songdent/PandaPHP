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

require_once 'Registry.php';
require_once 'PandaphpBC.php';
require_once 'Autoloader.php';

/**
 * 框架基类
 * @author songdengtao <http://www.songdengtao.cn>
 */
class Pandaphp extends PandaphpBC
{
	/**
	 * Pandaphp单例
	 * @var object
	 */
	protected static $_instance = null;

	/**
	 * 获取Pandaphp单例
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return object
	 */
	public static function getInstance()
	{
		if (is_null(static::$_instance)) {
			static::$_instance = new Pandaphp();
		}

		return static::$_instance;
	}

	/**
	 * 设置属性
	 * @access public
	 * @param string $name 属性名称
	 * @param mixed $value 属性值
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function set($name = '', $value = '')
	{
		static::getInstance()->$name = $value;
	}

	/**
	 * 获取属性
	 * @access public
	 * @param string $name 属性名称
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed 属性值
	 */
	public static function get($name = '')
	{
		return static::getInstance()->$name;
	}

	/**
	 * 运行一个请求生命周期
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function run()
	{
		// 系统注册表
		Registry::init(static::get('registryPath'));

		// 运行环境检查
		static::checkEnv();

		// 类文件自动加载
		Autoloader::init(static::getInstance());
		Autoloader::autoload();

		// 设置系统时区
		static::shell('Dtime::setDateDefaultTimeZone');
		// 设置错误处理方法
		static::shell('Error::registerShutdownFunction');
		static::shell('Error::setErrorHandler');
		// 设置异常处理方法
		static::shell('Exception::setExceptionHandler');

		if (false === static::get('isCli')) {
			static::shell('Filter::globalFilter');
			static::shell('Session::start');
		}

		// 路由
		static::shell('Router::dispatch');

		$controller = CONTROLLER_NAME . 'Controller';
		$action     = ACTION_NAME . 'Action';
		$class      = CONTROLLER_NAMESPACE . '\\' . $controller;
		if (!class_exists($class)) {
			$class = CONTROLLER_NAMESPACE . '\\EmptyController';
			if (!class_exists($class)) {
				$module = strstr(CONTROLLER_NAMESPACE, '\\', true);
				if (static::get('debug')) {
					if (!is_dir(static::get('appPath') . $module)) {
						static::shell('Error::halt', 'Call to undefined module ' . $module);
					} else {
						static::shell('Error::halt', 'Call to undefined controller ' . $class);
					}
				} else {
					static::shell('Error::halt', '404 Not found');
				}
			}
		}
		(new $class())->$action();

		return;
	}

	/**
	 * 检测框架必须的环境部件是否已存在或部署好
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function checkEnv()
	{
		// PHP版本检测
		if (version_compare(PHP_VERSION, static::PANDAPHP_PHP_VERSION, '<')) {
			die('[系统错误]：需要' . static::PANDAPHP_PHP_VERSION . '版本以上的PHP！');
		}
	}

	/**
	 * 构建框架应用结构
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function build()
	{

	}

	/**
	 * 框架外壳管道，通过此方法访问框架内核的服务
	 * @access public
	 *
	 * @example:
	 *
	 * # 执行静态方法
	 * \Pandaphp::shell('Example::get', $arg1, $arg2,..); <=> \pandaphp\xxxx\Example::get($arg1,$arg2,..);
	 * # 执行常规方法
	 * \Pandaphp::shell('Example->get', $arg1, $arg2,..); <=> (new \pandaphp\xxxx\Example())->get($arg1,$arg2,..);
	 * # 实例化对象
	 * \Pandaphp::shell('Example', $arg1, $arg2,..); <=> new \pandaphp\xxxx\Example($arg1,$arg2,..);
	 *
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function shell()
	{
		$args = func_get_args();

		if (count($args) > 0) {
			$classmethod = array_shift($args);
		}

		if (isset($classmethod) && !empty($classmethod)) {
			return static::_ShellHelper($classmethod, $args);
		} else {
			return null;
		}
	}

	/**
	 * 框架辅助器，通过此方法访问应用的辅助服务
	 * @access public
	 *
	 * @example:
	 *
	 * # 执行静态方法
	 * \Pandaphp::helper('Example::get', $arg1, $arg2,..); <=> \helper\xxxx\Example::get($arg1,$arg2,..);
	 * # 执行常规方法
	 * \Pandaphp::helper('Example->get', $arg1, $arg2,..); <=> (new \helper\xxxx\Example())->get($arg1,$arg2,..);
	 * # 实例化对象
	 * \Pandaphp::helper('Example', $arg1, $arg2,..); <=> new \helper\xxxx\Example($arg1,$arg2,..);
	 *
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function helper()
	{
		$args = func_get_args();

		if (count($args) > 0) {
			$classmethod = array_shift($args);
		}

		if (isset($classmethod) && !empty($classmethod)) {
			$namespaceRoot = str_replace(static::get('root'), '', trim(static::get('helperPath'), '/'));
			return static::_ShellHelper($classmethod, $args, 'helper', $namespaceRoot);
		} else {
			return null;
		}
	}

	/**
	 * 加载第三方程序文件
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public static function vender()
	{
		$args = func_get_args();

		if (count($args) > 0) {
			$file = array_shift($args);
		}

		if (count($args) > 0) {
			$classmethod = array_shift($args);
		}

		if (isset($file) && !empty($file)) {
			$filepath = static::get('venderPath') . ltrim($file, '/');
			if (!file_exists($filepath)) {
				static::shell('Error::halt', $file . '不存在');
			}
			@require_once $filepath;
			if (isset($classmethod) && !empty($classmethod)) {
				if (strpos($classmethod, '::')) {
					return call_user_func_array($classmethod, $args);
				} elseif (strpos($classmethod, '->')) {
					$classmethodArr = explode('->', $classmethod);
					return call_user_func_array([ $classmethodArr[0], $classmethodArr[1] ], $args);
				} else {
					return (new $classmethod($args));
				}
			}
		} else {
			return null;
		}
	}

	// shell 和 helper 方法中执行静态方法，常规方法以及实例化对象
	private static function _ShellHelper($classmethod = '', array $args = [ ], $type = 'system', $namespaceRoot = '')
	{
		if (strpos($classmethod, '::')) {
			$ret = static::_CallStaticMethod($classmethod, $args, $type, $namespaceRoot);
		} elseif (strpos($classmethod, '->')) {
			$ret = static::_CallMethod($classmethod, $args, $type, $namespaceRoot);
		} else {
			$ret = static::_Class($classmethod, $args, $type, $namespaceRoot);
		}
		return $ret;
	}

	// 执行类的静态方法
	private static function _CallStaticMethod($classmethod = '', array $args = [ ], $type = 'system', $namespaceRoot = '')
	{
		$temp      = explode('::', $classmethod);
		$classname = Registry::get($temp[0], $type, $namespaceRoot);
		$method    = $classname . '::' . $temp[1];
		return call_user_func_array($method, $args);
	}

	// 执行类的非静态方法
	private static function _CallMethod($classmethod = '', array $args = [ ], $type = 'system', $namespaceRoot = '')
	{
		$temp  = explode('->', $classmethod);
		$class = Registry::get($temp[0], $type, $namespaceRoot);
		return call_user_func_array([ (new $class()), $temp[1] ], $args);
	}

	// 实例化类
	private static function _Class($classmethod = '', array $args = [ ], $type = 'system', $namespaceRoot = '')
	{
		$class = Registry::get($classmethod, $type, $namespaceRoot);
		$class = new \ReflectionClass($class);
		return $class->newInstanceArgs($args);
	}
}