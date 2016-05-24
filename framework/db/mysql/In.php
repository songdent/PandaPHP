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
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class In extends WhereOperator
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
        $field = $whereElement[0];

        $inV = $whereElement[1][1];
        if (is_string($inV)) {
            $inV = explode(',', $inV);
        }
        $inV = is_array($inV) ? $inV : [$inV];
        foreach ($inV as $k => $v) {
            $inV[ $k ] = intval($v);
        }
        $inV = array_unique($inV);

        $ret['where']   = $field . ' IN ' . '(' . implode(',', $inV) . ')';
        $ret['bindKey'] = '';
        $ret['bindVal'] = '';
        return $ret;
    }
}