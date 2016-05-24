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
function smarty_function_U($params = [])
{
    $args   = [];
    $module = '';
    if (isset($params['module'])) {
        $module = $params['module'];
        unset($params['module']);
    } else {
        $module = lcfirst(MODULE_NAME);
    }

    $suffix = '';
    if (isset($params['suffix'])) {
        $suffix = $params['suffix'];
        unset($params['suffix']);
    }

    if (isset($params['route'])) {
        if (!empty($params['route'])) {
            $args = $params['route'];
        }
        unset($params['route']);
    } else {
        $controller = '';
        if (isset($params['controller'])) {
            $controller = $params['controller'];
            unset($params['controller']);
        } else {
            $controller = lcfirst(CONTROLLER_NAME);
        }
        $args['c'] = $controller;

        $action = '';
        if (isset($params['action'])) {
            $action = $params['action'];
            unset($params['action']);
        } else {
            $action = ACTION_NAME;
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