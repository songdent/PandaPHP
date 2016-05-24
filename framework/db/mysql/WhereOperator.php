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
 * where表达式抽象类
 * @author songdengtao <http://www.songdengtao.cn/>
 */
abstract class WhereOperator
{
    /**
     * 获取WHERE部分的字符串和绑定的数组
     * @access public
     * @param  array $whereElement
     * @author songdengtao <http://www.songdengtao.cn>
     * @return array ['where' => , 'bind']
     */
    abstract public function getWhereAndBind(array $whereElement = []);

    // 返回参数绑定的key
    protected function bindKey($key = '')
    {
        return \Pandaphp::shell('Str::bindReplace', ':' . $key);
    }
}