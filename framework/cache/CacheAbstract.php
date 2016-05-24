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
 * 缓存抽象类
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class CacheAbstract
{
	/**
	 * 配置参数
	 * @var array
	 */
	protected $options = [];

	/**
	 * 操作句柄
	 * @var object
	 */
	protected $handler = null;

	/**
	 * 检查PHP扩展是否已加载
	 * @access protected
	 * @param  string $extensionName php扩展名称
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return void
	 */
	protected function checkExtensionLoad($extensionName)
	{
		if (!empty($extensionName)) {
			if (!extension_loaded($extensionName)) {
				// 系统不支持$extensionName
				\Pandaphp::shell('Error::halt', '目前系统不支持' . $extensionName . '扩展,请安装相关扩展!');
			}
		}
	}
}