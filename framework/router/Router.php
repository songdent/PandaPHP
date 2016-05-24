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
namespace pandaphp\router;

/**
 * 路由
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Router
{
	private static $_routerInstance = null;

	private static function _getRouterInstance()
	{
		if (is_null(static::$_routerInstance)) {
			$class = 'pandaphp\router\\';
			$class .= (\Pandaphp::get('isCli')) ? 'CliRouter' : 'CgiRouter';
			static::$_routerInstance = new $class();
		}

		return static::$_routerInstance;
	}

	/**
	 * @decription
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	public static function dispatch()
	{
		$routerInstance = static::_getRouterInstance();
		$routerInstance->dispatch();
	}
}