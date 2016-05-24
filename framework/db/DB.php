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
namespace pandaphp\db;

/**
 * 数据库管理
 * @author songdengtao <http://www.songdengtao.cn>
 */
class DB
{
	/**
	 * 数据库类型
	 * @var string
	 * @access private
	 */
	private $type = 'mysql';

	/**
	 * 允许的数据库类型
	 * @var array
	 * @access private
	 */
	private $allowedTypes = ['mysql'];

	/**
	 * 数据库服务器地址
	 * @var string
	 * @access private
	 */
	private $hostname = '127.0.0.1';

	/**
	 * 数据库服务器端口
	 * @var string
	 * @access private
	 */
	private $port = '3306';

	/**
	 * 数据库编码
	 * @var string
	 * @access private
	 */
	private $charset = 'utf8';

	/**
	 * 数据库用户名
	 * @var string
	 * @access private
	 */
	private $username = '';

	/**
	 * 数据库密码
	 * @var string
	 * @access private
	 */
	private $password = '';

	/**
	 * 数据库名称
	 * @var string
	 * @access private
	 */
	private $dbname = '';

	/**
	 * 数据库表前缀
	 * @var string
	 * @access private
	 */
	private $tablePrefix = '';
	/**
	 * 数据库连接
	 * @var resource
	 */
	private $pdo = null;

	/**
	 * 数据库管理对象
	 * @var object
	 */
	private static $_instance = null;

	/**
	 * 构造函数
	 * @param array $config 数据库配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	private function __construct(array $config = [])
	{
		$this->pdo = $this->_connect($config);
	}

	/**
	 * 获取构建并执行SQL的对象实例
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return object
	 */
	public function getSqler()
	{
		$class = '\pandaphp\db\\' . ucwords($this->type) . 'Sqler';
		return new $class($this->pdo, $this->tablePrefix);
	}

	/**
	 * 获取数据库管理对象实例
	 * @param array $config 数据库配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return DB
	 */
	public static function getInstance(array $config = [])
	{
		if (empty($config)) {
			// 默认链接
			if (is_null(static::$_instance)) {
				$config            = \Pandaphp::shell('Config::get', 'database@default');
				static::$_instance = new DB($config);
			}
			return static::$_instance;
		} else {
			// 临时链接
			return (new DB($config));
		}
	}

	/**
	 * 数据库连接
	 * @access private
	 * @param array $config 数据库配置
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return object 数据库PDO连接对象
	 */
	private function _connect(array $config = [])
	{
		$this->hostname    = $config['hostname'];
		$this->port        = $config['port'];
		$this->charset     = $config['charset'];
		$this->username    = $config['username'];
		$this->password    = $config['password'];
		$this->dbname      = $config['name'];
		$this->tablePrefix = $config['table_prefix'];

		if (isset($type) && !empty($type)) {
			$type = strtolower($type);
			if (in_array($type, $this->allowedTypes)) {
				$this->type = $type;
			} else {
				if ($type === 'mysqli') {
					$this->type = 'mysql';
				}
			}
		}

		$handler = null;
		try {
			$dsn     = $this->type . ':host=' . $this->hostname . ';port=' . $this->port . ';dbname=' . $this->dbname;
			$handler = new \PDO($dsn, $this->username, $this->password);
		} catch (\PDOException $e) {
			\Pandaphp::shell('Error::halt', 'Database connection failed：' . $e->getMessage());
		}

		if (!is_null($handler)) {
			$handler->query('set names ' . $this->charset);
		}

		return $handler;
	}
}