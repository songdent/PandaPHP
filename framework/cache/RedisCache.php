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
 * Redis缓存
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class RedisCache extends CacheAbstract implements CacheInterface
{
	const EXTENSION_NAME = 'redis';

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
		$this->options = \Pandaphp::shell('Config::get', 'cache_redis');
		if (!empty($options)) {
			$this->options = array_merge($this->options, $options);
		}
		$this->handler = new \Redis;
		$func          = $this->options['ispconnect'] ? 'pconnect' : 'connect';
		if (false === $this->options['timeout']) {
			$res = $this->handler->$func($this->options['host'], $this->options['port']);
		} else {
			$res = $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);
		}

		if (!$res) {
			\Pandaphp::shell('Error::halt', 'redis未启动');
		}

		if (!empty($this->options['passwd'])) {
			$authRes = $this->handler->auth($this->options['passwd']);
			if (!$authRes) {
				\Pandaphp::shell('Error::halt', 'redis认证失败');
			}
		}
	}

	/**
	 * 设置缓存
	 * @param  string $key 缓存的键名
	 * @param  mixed $value 缓存的键值
	 * @param  mixed $expire 缓存的有效期
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function set($key = '', $value, $expire = null)
	{
		if (is_null($expire)) {
			$expire = $this->options['expire'];
		}
		$name = $this->options['prefix'] . $key;
		//对数组/对象数据进行缓存处理，保证数据完整性
		$value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
		if (is_int($expire)) {
			$result = $this->handler->setex($name, $expire, $value);
		} else {
			$result = $this->handler->set($name, $value);
		}

		// 对缓存的长度进行控制
		if ($result && $this->options['length'] > 0) {
			// 记录缓存队列
			$queue = $this->handler->get('__redis_queue__');
			if (!$queue) $queue = [];
			if (false === array_search($name, $queue)) {
				array_push($queue, $name);
			}

			if (count($queue) > $this->options['length']) {
				$key = array_shift($queue); // 出列
				$this->handler->delete($key); // 删除缓存
			}
			$this->handler->set('__redis_queue__', $queue);
		}

		return $result;
	}

	/**
	 * 获取缓存
	 * @param  string $key 缓存的键名
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public function get($key = '')
	{
		return $this->handler->get($this->options['prefix'] . $key);
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
		return $this->handler->flushDB();
	}
}