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
 * Like
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Like extends WhereOperator
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
        $ret            = [];
        $bindKey        = $this->bindKey($whereElement[0]);
        $field          = $whereElement[0];
        $ret['where']   = $field . " LIKE :" . \Pandaphp::shell('Str::bindReplace', $field);
        $ret['bindKey'] = $whereElement[1][0];
        $ret['bindVal'] = $whereElement[1][1];
        return $ret;
    }
}