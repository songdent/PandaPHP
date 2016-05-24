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
 * 默认的全局配置文件
 * @author songdengtao <http://www.songdengtao.cn/>
 */

return [
	# @Group development Config
	// 开发者IP数组
	'developer_ips'                   => [ '127.0.0.1' ],

	# @Group default Config
	// 默认的模块
	'default_module'                  => 'home',
	// 默认的控制器
	'default_controller'              => 'Index',
	// 默认的操作
	'default_action'                  => 'index',

	# @Group Database Config
	// css js image(页面图标)的http访问域名
	'http_domain_cji'                 => '',
	// image的http访问域名
	'http_domain_img'                 => '',

	# @fenye
	// 分页变量名
	'fenye_var'                       => 'p_now',

	# @Group Cache Config
	// 是否开启缓存
	'cache_start'                     => true,
	// 缓存类型，支持file,redis,memcache
	'cache_type'                      => 'redis',
	'cache_types'                     => [ 'redis', 'memcache' ],
	'cache_redis'                     => [
		'ispconnect' => false, // 是否持久链接
		'host'       => '127.0.0.1', // redis地址
		'port'       => '6379', // redis端口
		'timeout'    => false, // 是否超时
		'expire'     => 0, // 有效期，单位秒
		'prefix'     => '', // 缓存前缀
		'passwd'     => '', // redis密码
		'length'     => 0, // redis缓存队列的长度
	],
	'cache_memcache'                  => [
		'ispconnect' => false, // 是否持久链接
		'host'       => '127.0.0.1', // redis地址,集群，则用逗号隔开
		'port'       => '11211', // memcache端口，集群，则用逗号隔开
		'timeout'    => false, // 是否超时
		'expire'     => 0, // 有效期，单位秒
		'prefix'     => '', // 缓存前缀
		'length'     => 0, // redis缓存队列的长度
	],

	# @Group URL Config
	// URL参数分隔符
	'url_depr'                        => '/',
	// URL伪静态化后缀
	'url_html_suffix'                 => '.html',
	// 是否开启URL路由
	'url_router_on'                   => false,
	// URL路由规则
	'url_router_rules'                => [ ],
	// 是否开启二级域名部署
	'url_domain_deploy_on'            => false,
	// URL二级域名部署映射数组
	'url_domain_deploy_mapping'       => [ ],


	# @Group Upload Config
	// 图片上传配置
	// 允许上传的图片mime
	'image_upload_mimes'              => [
		'image/gif',
		'image/jpeg',
		'image/png',
		'application/x-MS-bmp',
		'image/vnd.wap.wbmp'
	],
	// 允许上传的图片后缀
	'image_upload_exts'               => [ 'gif', 'jpg', 'jpeg', 'png', 'bmp', 'wbmp' ],
	// 上传的文件大小限制 (0-不做限制)
	'image_upload_max_size'           => 0,
	//自动子目录保存文件
	'image_upload_auto_sub'           => true,
	// 子目录名称创建方式
	'image_upload_name_sub'           => [ 'date', 'Y-m-d' ],
	// 保存的目录
	'image_upload_save_dir'           => 'images',
	// 保存的名称  传文件命名规则，[0]-函数名，[1]-参数
	'image_upload_save_name'          => [ 'uniqid', '' ],
	// 存在同名的是否覆盖
	'image_upload_overwrite'          => false,

	// 非图片和多媒体文件上传配置
	// 允许上传的文件mime
	'file_upload_mimes'               => [
		'application/msword',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.ms-powerpoint',
		'text/html',
		'text/plain',
		'application/zip',
		'application/x-rar-compressed',
		'application/x-gzip'
	],
	// 允许上传的文件后缀
	'file_upload_exts'                => [ 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'html', 'txt', 'zip', 'rar', 'gz' ],
	// 上传的文件大小限制 (0-不做限制)
	'file_upload_max_size'            => 0,
	// 子目录名称创建方式
	'file_upload_auto_sub'            => true,
	//自动子目录保存文件
	'file_upload_name_sub'            => [ 'date', 'Y-m-d' ],
	// 保存的目录
	'file_upload_save_dir'            => 'files',
	// 保存的名称  传文件命名规则，[0]-函数名，[1]-参数
	'file_upload_save_name'           => [ 'uniqid', '' ],
	// 存在同名的是否覆盖
	'file_upload_overwrite'           => false,

	// 多媒体文件上传配置
	// 允许上传的多媒体文件mime
	'media_upload_mimes'              => [
		'application/x-shockwave-flash',
		'flv-application/octet-stream',
		'audio/mpeg',
		'video/mp4',
		'audio/x-wav',
		'audio/x-ms-wma',
		'audio/x-ms-wmv',
		'audio/mid',
		'video/x-msvideo',
		'video/mpeg',
		'video/x-ms-asf',
		'audio/x-pn-realaudio',
		'audio/x-pn-realaudio'
	],
	// 允许上传的多媒体文件后缀
	'media_upload_exts'               => [
		'swf',
		'flv',
		'mp3',
		'wav',
		'wma',
		'wmv',
		'mid',
		'avi',
		'mpg',
		'asf',
		'rm',
		'rmvb'
	],
	// 上传的文件大小限制 (0-不做限制)
	'media_upload_max_size'           => 0,
	//自动子目录保存文件
	'media_upload_auto_sub'           => true,
	// 子目录名称创建方式
	'media_upload_name_sub'           => [ 'date', 'Y-m-d' ],
	// 保存的目录
	'media_upload_save_dir'           => 'medias',
	// 保存的名称  传文件命名规则，[0]-函数名，[1]-参数
	'media_upload_save_name'          => [ 'uniqid', '' ],
	// 存在同名的是否覆盖
	'media_upload_overwrite'          => false,

	# @Group Cookie Config
	'cookie_expire'                   => 0,
	// cookie有效期
	'cookie_path'                     => '/',
	'cookie_domain'                   => '',
	'cookie_secure'                   => true,
	'cookie_httponly'                 => true,
	'cookie_prefix'                   => 'pandaphp_',

	# @Group Session Config
	// session 有效期
	'session_cookie_expire'           => '7200',
	// session cookie域名
	'session_cookie_domain'           => '',
	// session cookie路径
	'session_cookie_path'             => '/',
	// session前缀
	'session_prefix'                  => '',

	# @Group Dirname Config
	// 控制器目录名
	'dirname_controller'              => 'controller',
	// 视图目录名
	'dirname_view'                    => 'view',
	// 模型目录名
	'dirname_model'                   => 'model',

	# @Group Template Config
	// 模板类型
	'template_type'                   => 'smarty',
	// 模板类型数组
	'template_types'                  => [ 'smarty' ],
	// 模板主题
	'template_theme'                  => 'default',
	// 模板文件后缀
	'template_suffix'                 => '.html',
	// smarty的左定界符
	'template_smarty_left_delimiter'  => '{{',
	// smarty的右定界符
	'template_smarty_right_delimiter' => '}}',
	// 是否模板缓存
	'template_cache_on'               => false,
	// 模板缓存生命周期
	'template_cache_lifetime'         => 3600,

	# @Group Error Config
	// 默认的错误信息
	'error_message'                   => 'Sorry, there have been some unknown system errors or exceptions.',
	// 显示错误信息的页面
	'error_page'                      => '/errexp.php',

	// 404页面
	'404'                             => '/404.php',
	// 50x页面
	'50x'                             => '/50x.php',
];