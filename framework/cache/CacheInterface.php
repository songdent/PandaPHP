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
 * 缓存接口
 * @author songdengtao <http://www.songdengtao.cn/>
 */
interface CacheInterface
{
	/**
	 * 缓存驱动初始化
	 * @param  array $options 缓存参数数组
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return $this
	 */
	public function connect(array $options = []);

	/**
	 * 设置缓存
	 * @param  string $key 缓存的键名
	 * @param  mixed $value 缓存的键值
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function set($key = '', $value);

	/**
	 * 获取缓存
	 * @param  string $key 缓存的键名
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function get($key = '');

	/**
	 * 删除缓存
	 * @param  string $key 缓存的键名
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function delete($key = '');

	/**
	 * 清除缓存
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function flush();
}