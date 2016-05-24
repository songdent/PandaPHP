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
namespace pandaphp\http;

/**
 * 系统输出
 * @author songdengtao <http://www.songdengtao.cn>
 */
class Output
{
    public static function json($stat = 0, $msg = '', array $data = [])
    {
        $ret = ['stat' => $stat, 'msg' => $msg, 'data' => $data];
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($ret);
        exit();
    }

    // 直接输出json
    public static function echoJson($data = '')
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }
}