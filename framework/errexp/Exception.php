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


class Exception extends ErrorexpAbstract
{
	/**
	 * 设置自定义的异常处理函数
	 *
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return void
	 */
	public static function setExceptionHandler()
	{
		$exceptionFunc = function ($e) {
			$error            = [];
			$error['message'] = $e->getMessage();
			$trace            = $e->getTrace();
			if ('E' == $trace[0]['function']) {
				$error['file'] = $trace[0]['file'];
				$error['line'] = $trace[0]['line'];
			} else {
				$error['file'] = $e->getFile();
				$error['line'] = $e->getLine();
			}
			$error['trace'] = $e->getTraceAsString();

			// 发送404信息
			header('HTTP/1.1 404 Not Found');
			header('Status:404 Not Found');

			static::halt($error);
		};
		set_exception_handler($exceptionFunc);
	}
}