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
namespace pandaphp\mvc;

/**
 * 视图
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class View
{
    private static $_viewInstance = null;

    private static function _getViewInstance()
    {
        if (!static::_isRuntimesExistAndWritable()) {
            \Pandaphp::shell('Error::halt', '运行时目录runtimes不存在或不可写');
        }

        if (is_null(static::$_viewInstance)) {
            $templateType  = \Pandaphp::shell('Config::get', 'template_type');
            $templateTypes = \Pandaphp::shell('Config::get', 'template_types');
            if (in_array($templateType, $templateTypes)) {
                $class                 = '\pandaphp\mvc\\' . \Pandaphp::shell('Str::toBigHump', $templateType . 'View');
                static::$_viewInstance = new $class();
            } else {
                \Pandaphp::shell('Error::halt', ':系统不支持' . $templateType . '模板引擎！');
            }
        }
        return static::$_viewInstance;
    }

    // 运行时目录是否存在且是否可写
    private static function _isRuntimesExistAndWritable()
    {
        $runtimesPath = \Pandaphp::get('runtimePath');
        $isExist      = \Pandaphp::shell('Dir::isExist', $runtimesPath);
        $isWriteable  = \Pandaphp::shell('Dir::isWritable', $runtimesPath);
        return ($isExist && $isWriteable);
    }

    // 模板赋值
    public static function assign($key = '', $value = '')
    {
        $viewInstance = static::_getViewInstance();
        $viewInstance->assign($key, $value);
    }

    // 模板展示
    public static function display($file = '')
    {
        $viewInstance = static::_getViewInstance();
        $viewInstance->display($file);
    }
}