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
namespace pandaphp\syshelper;

/**
 * IP地址辅助类
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Ip
{
	/**
	 * 获取客户端IP地址,
	 * 如果开发者的开发环境ip和服务器在同一个内网内，则获取的是局域网内的ip地址
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return string
	 */
	public static function getClientIp()
	{
		if (false === \Pandaphp::get('isCli')) {
			$client = '';
			if (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$client = $_SERVER['HTTP_CLIENT_IP'];
			}
			$forward = '';
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$forward = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			$remote = $_SERVER['REMOTE_ADDR'];

			if (filter_var($client, FILTER_VALIDATE_IP)) {
				$ip = $client;
			} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
				$ip = $forward;
			} else {
				$ip = $remote;
			}

			return $ip;
		} else {
			if (isset($_SERVER['SSH_CLIENT'])) {
				return strstr($_SERVER['SSH_CLIENT'], ' ', true);
			} else {
				return '';
			}
		}
	}
}