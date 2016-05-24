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
 * 生成CSS的href JS的src 样式图片的 src
 * @param array $params
 * @author songdengtao <http://www.songdengtao.cn>
 * @return string
 */
function smarty_function_html_cji($params = [])
{
    $file = '';
    if (isset($params['file'])) {
        $file = $params['file'];
    }

    $isComplete = false;
    if (isset($params['iscomplete'])) {
        $isComplete = $params['iscomplete'];
    }

    $module = '';
    if (isset($params['module'])) {
        $module = $params['module'];
    }

    $theme = '';
    if (isset($params['theme'])) {
        $theme = $params['theme'];
    }

    return \pandaphp\syshelper\Cji::create($file, [
        'isComplete' => $isComplete,
        'module' => $module,
        'theme' => $theme
    ]);
}