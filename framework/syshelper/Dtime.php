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
namespace pandaphp\syshelper;

/**
 * 日期时间辅助
 * @author　 songdengtao <http://www.songdengtao.cn>
 */
class Dtime
{
	/**
	 * 设置系统时区
	 * @access public
	 * @param string $timezone
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	public static function setDateDefaultTimeZone($timezone = 'Asia/shanghai')
	{
		date_default_timezone_set($timezone);
	}
}