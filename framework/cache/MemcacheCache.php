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
 * memcache缓存
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class MemcacheCache extends CacheAbstract implements CacheInterface
{
	const EXTENSION_NAME = 'memcache';

	/**
	 * 构造函数
	 * @param  array $options 缓存参数数组
	 * @author songdengtao <http://www.songdengtao.cn/>
	 */
	public function __construct(array $options = [])
	{
		$this->checkExtensionLoad(self::EXTENSION_NAME);
		$this->connect($options);
	}

	/**
	 * 缓存驱动初始化
	 * @param  array $options 缓存参数数组
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return $this
	 */
	public function connect(array $options = [])
	{
		$this->options = \Pandaphp::shell('Config::get', 'cache_memcache');
		if (!empty($options)) {
			$this->options = array_merge($this->options, $options);
		}
		$this->handler = new \Memcache;
		// 支持集群
		$hosts = explode(',', $this->options['host']);
		$ports = explode(',', $this->options['port']);
		foreach ((array)$hosts as $i => $host) {
			$port = isset($ports[$i]) ? $ports[$i] : $ports[0];
			if (false === $this->options['timeout']) {
				$this->handler->addServer($host, $port, $this->options['ispconnect'], 1);
			} else {
				$this->handler->addServer($host, $port, $this->options['ispconnect'], 1, $this->options['timeout']);
			}
		}
	}

	/**
	 * 读取缓存
	 * @access public
	 * @param string $key 缓存变量名
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function get($key = '')
	{
		return $this->handler->get($this->options['prefix'] . $key);
	}

	/**
	 * 写入缓存
	 * @access public
	 * @param string $key 缓存变量名
	 * @param mixed $value 存储数据
	 * @param integer $expire 有效时间（秒）
	 * @return bool
	 */
	public function set($key = '', $value, $expire = null)
	{
		if (is_null($expire)) {
			$expire = $this->options['expire'];
		}
		$name = $this->options['prefix'] . $key;
		if ($this->handler->set($name, $value, 0, $expire)) {
			if ($this->options['length'] > 0) {
				$queue = $this->handler->get('__memcache_queue__');// 记录缓存队列
				if (!$queue) {
					$queue = [];
				}
				if (false === array_search($name, $queue)) {
					array_push($queue, $name);
				}

				if (count($queue) > $this->options['length']) {
					$key = array_shift($queue);// 出列
					$this->handler->delete($key);// 删除缓存
				}
				$this->handler->set('__memcache_queue__', $queue);
			}
			return true;
		}
		return false;
	}

	/**
	 * 删除缓存
	 * @param  string $key 缓存的键名
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function delete($key = '')
	{
		return $this->handler->delete($this->options['prefix'] . $key);
	}

	/**
	 * 清除缓存
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function flush()
	{
		return $this->handler->flush();
	}
}