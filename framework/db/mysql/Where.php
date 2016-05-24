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
namespace pandaphp\db\mysql;

/**
 * WHERE条件构建器
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Where
{
	/**
	 * WHERE表达式构建对象
	 * @var object
	 * @access private
	 */
	private $_whereOperator = null;

	/**
	 * where条件元素: [fieldKey, fieldValue]
	 * @var array
	 * @access private
	 */
	private $_whereElement = [];

	public function __construct(array $whereElement = [])
	{
		$this->_whereElement = $whereElement;
		$this->_setWhereOperator();
	}

	/**
	 * 设置WHERE表达式对象
	 * @access private
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return void
	 */
	private function _setWhereOperator()
	{
		if (count($this->_whereElement) >= 2 && isset($this->_whereElement[1][0])) {
			$operatorType   = strtolower($this->_whereElement[1][0]);
			$normalOperator = ['eq' => '=', 'neq' => '<>', 'lt' => '<', 'elt' => '<=', 'gt' => '>', 'egt' => '>='];
			if (in_array($operatorType, ['eq', 'neq', 'lt', 'elt', 'gt', 'egt'])) {
				$this->_whereOperator = new Normal($normalOperator[$operatorType]);
			} else {
				$className = ucwords($operatorType);
				if ($operatorType === 'and' || $operatorType === 'or') {
					$className = 'AndOr';
				}
				$class = 'pandaphp\db\mysql\\' . $className;
				if (class_exists($class)) {
					$this->_whereOperator = new $class();
				} else {
					\Pandaphp::shell('Error::halt', ' class is not exist!');
				}
			}
		} else {
			#todo _setWhereOperator
		}
	}

	/**
	 * 获取whereArr 的element 和bindArr 的element
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return array
	 */
	public function getWhereAndBind()
	{
		return $this->_whereOperator->getWhereAndBind($this->_whereElement);
	}
}