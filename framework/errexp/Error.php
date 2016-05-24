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
namespace pandaphp\errexp;


class Error extends ErrorexpAbstract
{
	/**
	 * 注册程序执行关闭时调用的函数
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function registerShutdownFunction()
	{
		$shutdownFunc = function () {
			if ($e = error_get_last()) {
				switch ($e['type']) {
					case E_ERROR:
					case E_PARSE:
					case E_CORE_ERROR:
					case E_COMPILE_ERROR:
					case E_USER_ERROR:
						ob_end_clean();
						static::halt($e);
						break;
				}
			}
		};
		register_shutdown_function($shutdownFunc);
	}

	/**
	 * 设置自定义的错误处理函数
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function setErrorHandler()
	{
		$errorType = E_ALL;
		$errorFunc = function ($errno, $errstr, $errfile, $errline) {
			switch ($errno) {
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
					ob_end_clean();
					$errorStr = "$errstr " . $errfile . " 第 $errline 行.";
					static::halt($errorStr);
					break;
				default:
					$errorStr = "[$errno] $errstr " . $errfile . " 第 $errline 行.";
					static::halt($errorStr);
					break;
			}
		};
		set_error_handler($errorFunc, $errorType);
	}
}