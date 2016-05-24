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
namespace pandaphp\mvc;

/**
 * 模型
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Model
{
	/**
	 * sqler
	 * @var object
	 */
	protected $sqler = null;

	// 表名称
	protected $tableName = '';

	// 构造函数
	public function __construct()
	{
		// 初始化
		if (method_exists($this, '_initialize')) {
			$this->_initialize();
		}
	}

	// 获取db sql处理对象 sqler
	protected function db($tableName = '', array $dbConfig = [])
	{
		$tableName = $this->_getTableName($tableName);
		if (!empty($dbConfig)) {
			$temporarySqler = \Pandaphp::shell('DB::getInstance', $dbConfig)->getSqler();
			return $temporarySqler->table($tableName);
		} else {
			if (is_null($this->sqler)) {
				$this->sqler = \Pandaphp::shell('DB::getInstance')->getSqler();
			}
			return $this->sqler->table($tableName);
		}
	}

	// 获取表名称
	private function _getTableName($tableName = '')
	{
		if (empty($tableName)) {
			if (empty($this->tableName)) {
				$modleClassName = get_class($this);
				$modleClassName = strrchr($modleClassName, '\\');
				$modleClassName = str_replace(['\\', 'Model'], '', $modleClassName);
				$tableName      = \Pandaphp::shell('Str::toUnderline', lcfirst($modleClassName));
			}
		}
		return $tableName;
	}

	// 获取最近一条被执行的SQL
	public function getLastSql()
	{
		return $this->db()->getLastSql();
	}

	// 返回格式统一
	public function ret($stat = 0, $msg = '', array $data = [])
	{
		return ['stat' => $stat, 'msg' => $msg, 'data' => $data];
	}
}