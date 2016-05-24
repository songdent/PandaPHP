<?php
/**
 * +----------------------------------------------------------------
 * + panda [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */

/**
 * 生成链接地址
 * @param array $params
 * @author songdengtao <http://www.songdengtao.cn>
 * @return string
 */
function smarty_function_html_link($params = [])
{
    $args   = [];
    $module = '';
    if (isset($params['data-m'])) {
        $module = $params['data-m'];
        unset($params['data-m']);
    }

    $suffix = '';
    if (isset($params['data-suffix'])) {
        $suffix = $params['data-suffix'];
        unset($params['data-suffix']);
    }

    if (isset($params['data-r'])) {
        if (!empty($params['data-r'])) {
            $args = $params['data-r'];
        }
        unset($params['data-r']);
    } else {
        $controller = '';
        if (isset($params['data-c'])) {
            $controller = $params['data-c'];
            unset($params['data-c']);
        }
        $args['c'] = $controller;

        $action = '';
        if (isset($params['data-a'])) {
            $action = $params['data-a'];
            unset($params['data-a']);
        }
        $args['a'] = $action;
        foreach ($params as $key => $val) {
            if (0 === strpos($key, 'data-')) {
                $key          = str_replace('data-', '', $key);
                $args[ $key ] = $val;
            }
        }
    }

    return \pandaphp\syshelper\Uri::create($args, $suffix, $module);
}