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

// 系统服务注册表

return [
    // cache
    'Cache'     => '\pandaphp\cache\Cache',

    // config
    'Config'    => '\pandaphp\config\Config',

    // db
    'DB'        => '\pandaphp\db\DB',

    // debug
    'Debug'     => '\pandaphp\debug\Debug',

    // errexp
    'Error'     => '\pandaphp\errexp\Error',
    'Exception' => '\pandaphp\errexp\Exception',

    // http
    'Http'      => '\pandaphp\http\Http',
    'Input'     => '\pandaphp\http\Input',
    'Output'    => '\pandaphp\http\Output',
    'WebSocket' => '\pandaphp\http\WebSocket',
    'Socket'    => '\pandaphp\http\Socket',
    'Session'   => '\pandaphp\http\Session',
    'Cookie'    => '\pandaphp\http\Cookie',

    // log
    'Log'       => '\pandaphp\log\Log',

    //router
    'Router'    => '\pandaphp\router\Router',

    // security
    'Filter'    => '\pandaphp\security\Filter',
    'Crypto'    => '\pandaphp\security\Crypto',

    // sysfile
    'File'      => '\pandaphp\sysfile\File',
    'Dir'       => '\pandaphp\sysfile\Dir',
    'Image'     => '\pandaphp\sysfile\Image',
    'Upload'    => '\pandaphp\sysfile\Upload',

    // syshelper
    'Ip'        => '\pandaphp\syshelper\Ip',
    'Dtime'     => '\pandaphp\syshelper\Dtime',
    'Str'       => '\pandaphp\syshelper\Str',
    'Arr'       => '\pandaphp\syshelper\Arr',
    'Uri'       => '\pandaphp\syshelper\Uri',
    'Cji'       => '\pandaphp\syshelper\Cji',
    'Fenye'     => '\pandaphp\syshelper\Fenye',
];