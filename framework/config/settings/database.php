<?php
/**
 * +----------------------------------------------------------------
 * + panda [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */

/**
 * 数据库配置
 * @author songdengtao <http://www.songdengtao.cn/>
 */

return [
	'default' => [
		'type'         => 'mysql', // 数据库类型
		'hostname'     => 'localhost', // 数据库服务器地址
		'port'         => '3306', // 数据库端口
		'charset'      => 'utf8', // 数据库编码
		'username'     => '', // 数据库用户名
		'password'     => '', // 数据库密码
		'name'         => '', // 数据库名
		'table_prefix' => '', // 数据库表前缀
	]
];