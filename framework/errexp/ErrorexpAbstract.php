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


abstract class ErrorexpAbstract
{
	/**
	 * 错误输出
	 *
	 * debug状态下，谁都可以看到错误信息
	 * developer状态下，只有开发者才能开间错误信息,开发者[指定的ip和帐号]
	 * 其他的不能看到错误信息
	 *
	 * @access public
	 * @param  mixed $errdata 错误信息
	 * @param  string $errpage 错误页面URL
	 * @author songdengtao <http://www.songdengtao.cn>
	 *
	 * @return void
	 */
	public static function halt($errdata, $errpage = '')
	{
		$e            = [];
		$ip           = '';
		$developerIps = [];
		$isDebug      = \Pandaphp::get('debug');
		$isCli        = \Pandaphp::get('isCli');

		$isOpen = true;
		if (false === $isCli) {
			$ip           = \Pandaphp::shell('Ip::getClientIp');
			$developerIps = \Pandaphp::shell('Config::get', 'developer_ips');
			if (!in_array($ip, $developerIps)) {
				$isOpen = false;
			}
		}

		if ($isOpen || $isDebug || $isCli) {
			if (!is_array($errdata)) {
				$trace        = debug_backtrace();
				$e['message'] = '<b style="color:#FF9900;">[ pandaphp_error ] :</b><br> ' . $errdata;
				$e['file']    = $trace[1]['file'];
				$e['line']    = $trace[1]['line'];
				ob_start();
				debug_print_backtrace();
				$e['trace'] = ob_get_clean();
			} else {
				$e = $errdata;
			}
			if ($isCli) {
				$errstr = $e['message'];
				if (\Pandaphp::get('isWin')) {
					$errstr = iconv('UTF-8', 'gbk', $e['message']);
				}
				$errstr .= PHP_EOL;
				$errstr .= 'FILE:' . $e['file'] . PHP_EOL;
				$errstr .= 'LILE:' . $e['line'] . PHP_EOL;
				$errstr .= $e['trace'];

				die($errstr);
			}
		} else {
			if (in_array($ip, $developerIps)) {
				$message = $errdata['message'];
			} else {
				$message  = is_array($errdata) ? $errdata['message'] : $errdata;
				$errorMsg = \Pandaphp::shell('Config::get', 'error_message');
				$message  = $isDebug ? $message : $errorMsg;
			}
			$e['message'] = $message;
		}

		if (empty($errpage)) {
			$errpage = \Pandaphp::shell('Config::get', 'error_page');
			$errpage = \Pandaphp::get('webroot') . $errpage;
		}

		if (\Pandaphp::shell('File::isExist', $errpage, true)) {
			include $errpage;
			exit();
		} else {
			static::defaultErrorPrint($e, $isDebug);
		}

		die($e);
	}

	public static function defaultErrorPrint($e, $isDebug)
	{
		$emessage = $e['message'];
		if ($isDebug && isset($e['file']) && isset($e['line']) && isset($e['trace'])) {
			$elinefile = 'Some errors or exceptions occurred on line ' . $e['line'] . ' of the file ' . $e['file'] . '.';
			$etrace    = $e['trace'];
		} else {
			$elinefile = '';
			$etrace    = '';
		}

		$errexpHtml
			= <<<EOE
<!doctype html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>error</title>
	<style type="text/css">
		* { font-family: Arial; }
		body { margin: 0; padding: 0; }
        #errexp-wrap {
            overflow: hidden;
            margin-top: 30px;
        }

        #errexp-container {
            width: 80%;
            min-width: 1200px;
            overflow: hidden;
            margin-right: auto;
            margin-left: auto;
            word-break: break-all; /*支持IE，chrome，FF不支持*/
            word-wrap: break-word; /*支持IE，chrome，FF*/
        }

        #errexp-trace {
            width: inherit;
            color: #777777;
        }
	</style>
</head>
<body>
	<div id="errexp-wrap">
		<div id="errexp-container">
			<p style="font-size: 28px;color:#666666;border-bottom: 1px solid #EAEAEA;padding-bottom: 20px">
				{$emessage}
			</p>
			<div id="errexp-trace">
			{$elinefile}
			<pre style="font-size: 16px;">{$etrace}</pre>
			</div>
		</div>
	</div>
</body>
</html>
EOE;
		die($errexpHtml);
	}
}