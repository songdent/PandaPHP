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
 * 一般表达式对象
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Normal extends WhereOperator
{
    /**
     * 操作符
     * @var string
     * @access private
     */
    private $_operator;

    /**
     * 构造函数
     * @access public
     * @param string $operator
     * @author songdengtao <http://www.songdengtao.cn>
     */
    public function __construct($operator = '')
    {
        $this->_operator = $operator;
    }

    /**
     * 获取WHERE部分的字符串和绑定的数组
     * @access public
     * @param  array $whereElement
     * @author songdengtao <http://www.songdengtao.cn>
     * @return array
     */
    public function getWhereAndBind(array $whereElement = [])
    {
        $ret = [];
        if (!empty($this->_operator)) {
            $bindkey = $this->bindKey($whereElement[0]);
            $ret     = [
                'where'   => $whereElement[0] . ' ' . $this->_operator . ' ' . $bindkey,
                'bindKey' => $bindkey,
                'bindVal' => $whereElement[1][1],
            ];
        }
        return $ret;
    }
}