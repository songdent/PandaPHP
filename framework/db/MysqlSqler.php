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
 * MYSQL 操作对象
 * @author songdengtao <http://www.songdengtao.cn>
 */
class MysqlSqler extends SqlerAbstarct
{
	/**
	 * where数组
	 * @var array
	 * @access protected
	 */
	protected $whereArr = [];

	/**
	 * 参数绑定数组
	 * @var array
	 * @access protected
	 */
	protected $bindArr = [];

	/**
	 * join数组
	 * @var array
	 * @access protected
	 */
	protected $joinArr = [];

	/**
	 * ORDER BY
	 * @var string
	 * @access protected
	 */
	protected $orderStr = '';

	/**
	 * LIMIT,默认1000
	 * @var string
	 * @access protected
	 */
	protected $limitStr = ' LIMIT 1000 ';

	/**
	 * LIMIT,默认1000
	 * @var int
	 * @access protected
	 */
	protected $limit = 1000;

	/**
	 * 查询的字段
	 * @var string
	 * @access protected
	 */
	protected $fieldStr = '*';

	/**
	 * HAVING
	 * @var string
	 * @access protected
	 */
	protected $havingStr = '';

	/**
	 * GROUP BY
	 * @var string
	 * @access protected
	 */
	protected $groupStr = '';

	/**
	 * UNION [ALL]
	 * @var string
	 * @access protected
	 */
	protected $unionStr = '';

	/**
	 * 数据
	 * @var array
	 * @access protected
	 */
	protected $data = [];

	/**
	 * 分页
	 * @var array
	 * @access protected
	 */
	protected $isPage = false;

	// 执行完最终操作之后需要清空参数
	protected function clearArgs()
	{
		$this->bindArr  = [];
		$this->joinArr  = [];
		$this->whereArr = [];
		$this->orderStr = '';
		$this->limit    = 1000;
		$this->limitStr = '';
		$this->data     = [];
		$this->isPage   = false;
	}

	/**
	 * 构建where查询条件
	 * @access public
	 * @param  array $where
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function where(array $where = [])
	{
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$bindkey = \Pandaphp::shell('Str::bindReplace', ':' . $key);
				if (is_array($value)) {
					$data             = (new \pandaphp\db\mysql\Where([$key, $value]))->getWhereAndBind();
					$this->whereArr[] = $data['where'];
					if (isset($data['bindKey']) && isset($data['bindVal']) && !empty($data['bindKey'])) {
						if (is_array($data['bindKey'])) {
							foreach ($data['bindKey'] as $k => $v) {
								$this->bindArr[$v] = $data['bindVal'][$k];
							}
						} else {
							$this->bindArr[$data['bindKey']] = $data['bindVal'];
						}
					}
				} else {
					$this->whereArr[]        = $key . ' = ' . $bindkey;
					$this->bindArr[$bindkey] = $value;
				}
			}
		}
		return $this;
	}

	/**
	 * 构建join
	 * @access public
	 * @param  string $joinStr 完整的一个join 如 LEFT JOIN TABLE_1 ON TABLE_2.id = TABLE_1.id
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function join($joinStr = '')
	{
		if (is_string($joinStr)) {
			$this->joinArr[] = $joinStr;
		}

		return $this;
	}


	/**
	 * 字段
	 * @access public
	 * @param  mixed $field
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function field($field = '*')
	{
		$isArray = is_array($field);
		if (!empty($field) && $isArray) {
			$this->fieldStr = implode(', ', $field);
		} else {
			$this->fieldStr = $field;
		}
		$this->fieldStr = \Pandaphp::shell('Str::lblank', $this->fieldStr);

		return $this;
	}

	/**
	 * HAVING
	 * @access public
	 * @param string $having
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function having($having = '')
	{
		if (is_string($having)) {
			$this->havingStr = \Pandaphp::shell('Str::lblank', ' HAVING ' . $having);
		}

		return $this;
	}

	/**
	 * GROUP BY
	 * @access public
	 * @param string $group
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function group($group = '')
	{
		if (is_string($group)) {
			$this->groupStr = \Pandaphp::shell('Str::lblank', ' GROUP BY ' . $group);
		}

		return $this;
	}

	/**
	 * UNION
	 * @access public
	 * @param  string $union
	 * @param  boolean $isAll
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function union($union = '', $isAll = false)
	{
		if (is_string($union) && !empty($union)) {
			if ($isAll) {
				$this->unionStr = ' UNION ' . $union;
			} else {
				$this->unionStr = ' UNION ALL ' . $union;
			}
			$this->unionStr = \Pandaphp::shell('Str::lblank', $this->unionStr);
		}

		return $this;
	}

	/**
	 * 分页
	 * @access public
	 * @param  int $pagenow 当前页码
	 * @param  int $pagesize 页码显示的数据数目
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function page($pagenow = 0, $pagesize = 20)
	{
		$this->isPage = true;
		$this->limit(($pagenow - 1) * $pagesize, $pagesize);
		return $this;
	}

	/**
	 * ORDER BY
	 * @access public
	 * @param string $order
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function order($order = '')
	{
		if (is_string($order) && !empty($order)) {
			$this->orderStr = \Pandaphp::shell('Str::lblank', ' ORDER BY ' . $order);
		}

		return $this;
	}

	/**
	 * LIMIT
	 * @access public
	 * @param int $offset 偏移量
	 * @param int $length 长度
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function limit($offset = 0, $length = 0)
	{
		$offset = intval($offset);
		if (($length = intval($length)) > 0) {
			$this->limitStr = ' LIMIT ' . $offset . ',' . $length;
			$this->limit    = $length;
		} else {
			$this->limitStr = ' LIMIT ' . $offset;
			$this->limit    = $offset;
		}
		$this->limitStr = \Pandaphp::shell('Str::lblank', $this->limitStr);

		return $this;
	}

	/**
	 * 设置数据
	 * @access public
	 * @param array $data
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return $this
	 */
	public function data(array $data = [])
	{
		$this->data = array_merge($this->data, $data);

		return $this;
	}

	/**
	 * 查询
	 * @access public
	 * @param  array $where
	 * @param  mixed $field
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return array
	 */
	public function select(array $where = [], $field = null)
	{
		$joinStr = $this->getJoinStr();

		if (!empty($where)) $this->where($where);
		$whereStr = $this->getWhereStr();

		if (!is_null($field)) $this->field($field);

		$sql = 'SELECT' . $this->fieldStr . ' FROM ' . $this->tableName . $this->tableAlias . $joinStr . $whereStr;
		$sql .= $this->groupStr . $this->havingStr . $this->orderStr . $this->limitStr;
		$sql .= $this->unionStr;

		$pdoex = $this->pdo->prepare($sql);
		$pdoex->execute($this->bindArr);
		$data = $pdoex->fetchAll(\PDO::FETCH_ASSOC);

		$errorInfo = $pdoex->errorInfo();
		if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
			if ($this->limit === 1 && $data && false === $this->isPage) {
				$data = $data[0];
			}
			$ret = $this->opret(true, '', $data);
		} else {
			$ret = $this->opret(false, $errorInfo[2], []);
		}

		$this->setLastSql($sql);
		$this->clearArgs();

		return $ret;
	}

	/**
	 * 总数
	 * @access public
	 * @param  string $countField count 字段
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int
	 */
	public function count($countField = '*')
	{
		$joinStr  = $this->getJoinStr();
		$whereStr = $this->getWhereStr();
		$sql      = 'SELECT COUNT(' . $countField . ') AS count FROM ' . $this->tableName . $this->tableAlias . $joinStr . $whereStr;
		$sql .= $this->groupStr . $this->havingStr . $this->unionStr;

		$pdoex = $this->pdo->prepare($sql);
		$pdoex->execute($this->bindArr);
		$count = $pdoex->fetch(\PDO::FETCH_COLUMN);

		$errorInfo = $pdoex->errorInfo();
		if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
			$ret = $this->opret(true, '', $count);
		} else {
			$ret = $this->opret(false, $errorInfo[2], 0);
		}

		$this->setLastSql($sql);
		$this->clearArgs();

		return $ret;
	}

	/**
	 * 单个删除或者批量删除
	 * @access public
	 * @param  array $where
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int 删除影响的行数
	 */
	public function delete(array $where = [])
	{
		if (!empty($where)) $this->where($where);
		$whereStr = $this->getWhereStr();

		$sql = 'DELETE FROM ' . $this->tableName . $whereStr;

		$pdoex = $this->pdo->prepare($sql);
		if ($this->bindArr) {
			foreach ($this->bindArr as $key => $val) {
				$pdoex->bindParam($key, $val);
			}
		}
		$pdoex->execute();
		$count = $pdoex->rowCount();

		$errorInfo = $pdoex->errorInfo();
		if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
			$ret = $this->opret(true, '', $count);
		} else {
			$ret = $this->opret(false, $errorInfo[2], 0);
		}

		$this->setLastSql($sql);
		$this->clearArgs();

		return $ret;
	}

	/**
	 * 整型字段自递增Or自递减
	 * @access public
	 * @param string $field 整型字段名
	 * @param  int $type 1 是递增 0 递减
	 * @param int $num 自递增的数
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int 删除影响的行数
	 */
	public function updateByIndecrease($field = '', $num = 1, $type = 1)
	{
		if (!empty($field)) {
			if (!empty($where)) $this->where($where);
			$whereStr = $this->getWhereStr();
			if (!empty($whereStr)) {
				$opt    = $type ? '+' : '-';
				$setStr = $field . '=' . $field . $opt . $num . ' ';
				$sql    = 'UPDATE ' . $this->tableName . ' SET ' . $setStr . $whereStr . $this->orderStr;
				$pdoex  = $this->pdo->prepare($sql);
				if ($this->bindArr) {
					foreach ($this->bindArr as $key => $val) {
						$pdoex->bindParam($key, $val);
					}
				}
				$pdoex->execute();
				$count = $pdoex->rowCount();

				$errorInfo = $pdoex->errorInfo();
				if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
					$ret = $this->opret(true, '', $count);
				} else {
					$ret = $this->opret(false, $errorInfo[2], 0);
				}

				$this->setLastSql($sql);
				$this->clearArgs();
			} else {
				$ret = $this->opret(false, '更新条件为空');
			}
		} else {
			$ret = $this->opret(false, '递增的字段名为空');
		}
		return $ret;
	}

	/**
	 * 更新
	 * @access public
	 * @param array $where
	 * @param array $data
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return int
	 */
	public function update(array $where = [], array $data = [])
	{
		if (!empty($data) || !empty($this->data)) {
			if (!empty($where)) $this->where($where);
			$whereStr = $this->getWhereStr();
			if (!empty($whereStr)) {
				$setArr = [];
				$data   = array_merge($this->data, $data);
				foreach ($data as $k => $v) {
					$setArr[] = $k . '="' . $v . '"';
				}
				$setStr = implode(' , ', $setArr);

				$sql   = 'UPDATE ' . $this->tableName . ' SET ' . $setStr . $whereStr . $this->orderStr;
				$pdoex = $this->pdo->prepare($sql);
				if ($this->bindArr) {
					foreach ($this->bindArr as $key => $val) {
						$pdoex->bindParam($key, $val);
					}
				}
				$pdoex->execute();
				$count = $pdoex->rowCount();

				$errorInfo = $pdoex->errorInfo();
				if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
					$ret = $this->opret(true, '', $count);
				} else {
					$ret = $this->opret(false, $errorInfo[2], 0);
				}

				$this->setLastSql($sql);
				$this->clearArgs();
			} else {
				$ret = $this->opret(false, '条件为空', 0);
			}
		} else {
			$ret = $this->opret(false, '数据为空', 0);
		}

		return $ret;
	}

	/**
	 * 新增
	 * @access public
	 * @param array $data 新增的数据
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public function insert(array $data = [])
	{
		if (!empty($data) || !empty($this->data)) {
			$columnArr = [];
			$valueArr  = [];
			$data      = array_merge($this->data, $data);
			foreach ($data as $k => $v) {
				if (!is_array($v)) {
					$columnArr[] = $k;
					$valueArr[]  = '"' . $v . '"';
				} else {
					return $this->opret(false, '字段的值不能为数组', 0);
				}
			}
			$columnStr = implode(',', $columnArr);
			$valueStr  = implode(',', $valueArr);

			$sql = 'INSERT INTO ' . $this->tableName . ' ( ' . $columnStr . ') VALUES (' . $valueStr . ')';

			$pdoex = $this->pdo->prepare($sql);
			if ($this->bindArr) {
				foreach ($this->bindArr as $key => $val) {
					$pdoex->bindParam($key, $val);
				}
			}

			$result = $pdoex->execute();
			$id     = $this->pdo->lastInsertId();

			$errorInfo = $pdoex->errorInfo();
			if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
				$ret = $this->opret(true, '', $id);
			} else {
				$ret = $this->opret(false, $errorInfo[2], 0);
			}

			$this->setLastSql($sql);
			$this->clearArgs();
		} else {
			$ret = $this->opret(false, '数据为空', 0);
		}

		return $ret;
	}

	/**
	 * 批量新增
	 * @access public
	 * @param  array $data 新增的数据
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return mixed
	 */
	public function insertAll(array $data = [])
	{
		if (!empty($data) || !empty($this->data)) {
			$columnArr = [];
			$valueArr  = [];
			$data      = array_merge($this->data, $data);
			foreach ($data as $k => $v) {
				$tempColumnArr = [];
				$tempValueArr  = [];
				foreach ($v as $ik => $iv) {
					$tempColumnArr[] = $ik;
					$tempValueArr[]  = '"' . $iv . '"';
				}
				$columnArr[] = '(' . implode(',', $tempColumnArr) . ')';
				$valueArr[]  = '(' . implode(',', $tempValueArr) . ')';
			}
			$columnStr = implode(',', $columnArr);
			$valueStr  = implode(',', $valueArr);

			$sql = 'INSERT INTO ' . $this->tableName . $columnStr . ' VALUES ' . $valueStr;

			$pdoex = $this->pdo->prepare($sql);
			if ($this->bindArr) {
				foreach ($this->bindArr as $key => $val) {
					$pdoex->bindParam($key, $val);
				}
			}

			$result = $pdoex->execute();
			$id     = $this->pdo->lastInsertId();

			$errorInfo = $pdoex->errorInfo();
			if ($errorInfo[0] === '00000' && empty($errorInfo[1])) {
				$ret = $this->opret(true, '', $id);
			} else {
				$ret = $this->opret(false, $errorInfo[2], 0);
			}

			$this->setLastSql($sql);
			$this->clearArgs();
		} else {
			$ret = $this->opret(false, '数据为空', 0);
		}

		return $ret;
	}

	/**
	 * 构建where字符串
	 * @access protected
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	protected function getWhereStr()
	{
		$ret = '';
		if (!empty($this->whereArr)) {
			$ret = 'WHERE ' . implode(' AND ', $this->whereArr);
		}

		return \Pandaphp::shell('Str::lblank', $ret);
	}

	/**
	 * 构建join字符串
	 * @access protected
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	protected function getJoinStr()
	{
		$ret = '';
		if (!empty($this->joinArr)) {
			$ret = implode(' ', $this->joinArr);
		}

		return \Pandaphp::shell('Str::lblank', $ret);
	}

	/**
	 * 开启事务处理
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public function beginTranscation()
	{
		return $this->pdo->beginTransaction();
	}

	/**
	 * 事务回滚
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public function rollback()
	{
		return $this->pdo->rollback();
	}

	/**
	 * 事务提交
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return boolean
	 */
	public function commit()
	{
		return $this->pdo->commit();
	}
}