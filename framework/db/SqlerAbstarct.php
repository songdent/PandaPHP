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

/**数据库管理抽象类*/
abstract class SqlerAbstarct
{
	/**
	 * 数据库表前缀
	 * @var string
	 * @access protected
	 */
	protected $tablePrefix = '';

	/**
	 * 数据库表名称
	 * @var string
	 * @access protected
	 */
	protected $tableName = '';

	/**
	 * 表的别名
	 * @var string
	 * @access protected
	 */
	protected $tableAlias = '';

	/**
	 * 最后执行的
	 * @var string
	 * @access protected
	 */
	protected $lastSql = '';

	public $pdo = null;

	public function __construct($pdo = null, $tablePrefix = '')
	{
		$this->pdo         = $pdo;
		$this->tablePrefix = $tablePrefix;
	}

	// 设置表全称
	public function table($name = '')
	{
		$this->tableName = $this->tablePrefix . $name;
		return $this;
	}

	// 设置表别名
	public function alias($alias = '')
	{
		$this->tableAlias = !empty($alias) ? ' AS ' . $alias : ' ';

		return $this;
	}

	// 获取表全称
	public function getTableName()
	{
		return $this->tableName;
	}

	// 获取表前缀
	public function getTablePrefix()
	{
		return $this->tablePrefix;
	}

	/**
	 * 设置最后一次查询的SQL
	 * @access protected
	 * @param  string $lastSql
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	protected function setLastSql($lastSql = '')
	{
		$this->lastSql = $lastSql;
	}

	/**
	 * 获取最后一次查询的SQL
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	public function getLastSql()
	{
		return $this->lastSql;
	}

	/**
	 * 统一SQL查询的返回值的数据结构
	 * @access public
	 * @param boolean $stat 查询状态 成功与否
	 * @param string $msg 查询信息 失败返回错误信息
	 * @param mixed $data 查询的结果
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	protected function opret($stat = true, $msg = '', $data = null)
	{
		return ['stat' => $stat, 'msg' => $msg, 'data' => $data];
	}
}