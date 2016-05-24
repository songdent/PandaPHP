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
 * And Or
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class AndOr extends WhereOperator
{
	/**
	 * 获取WHERE部分的字符串和绑定的数组
	 * @access public
	 * @param  array $whereElement
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return array ['where' => , 'bind']
	 */
	public function getWhereAndBind(array $whereElement = [])
	{
		$ret   = [];
		$where = [];
		$bind  = [];
		$depr  = '';
		$field = array_shift($whereElement);
		foreach ($whereElement[0] as $index => $item) {
			if (!is_array($item)) {
				if (is_string($index)) {
					$data    = (new \pandaphp\db\mysql\Where([$field, [$index, $item]]))->getWhereAndBind();
					$where[] = $data['where'];
					$bind[]  = ['key' => $data['bindKey'], 'val' => $data['bindVal']];
				} else {
					$depr = ' ' . strtoupper($item) . ' ';
				}
			}
		}

		$whereElemSpices = [];
		foreach ($where as $w => $e) {
			$whereElemBefore = strstr($e, ':', true);
			$whereElemAfter  = strstr($e, ':');
			if (in_array($whereElemAfter, $whereElemSpices)) {
				$whereElemAfter .= '_' . $w;
			}
			$whereElemSpices[$whereElemBefore] = $whereElemAfter;
		}

		$where = [];
		foreach ($whereElemSpices as $kk => $vv) {
			$where[] = $kk . $vv;
		}

		$ret['where']   = implode($depr, $where);
		$ret['bindKey'] = [];
		$ret['bindVal'] = [];
		foreach ($bind as $k => $v) {
			$bindKey = $v['key'];
			if (in_array($bindKey, $ret['bindKey'])) {
				$bindKey .= '_' . $k;
			}
			$ret['bindKey'][$k] = $bindKey;
			$ret['bindVal'][$k] = $v['val'];
		}

		return $ret;
	}
}