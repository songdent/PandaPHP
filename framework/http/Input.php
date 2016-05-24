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
 * 系统输入
 * @author songdengtao <http://www.songdengtao.cn>
 */
class Input
{
    /**
     * 获取put变量
     * @param string $name 数据名称
     * @param string $default 默认值
     * @param string $filter 过滤方法
     * @return mixed
     */
    public static function put($name = '', $default = null, $filter = null)
    {
        static $_PUT = null;
        if (is_null($_PUT)) {
            parse_str(file_get_contents('php://input'), $_PUT);
        }
        return self::data($_PUT, $name, $default, $filter);
    }

    /**
     * 获取delete变量
     * @param string $name 数据名称
     * @param string $default 默认值
     * @param string $filter 过滤方法
     * @return mixed
     */
    public static function delete($name = '', $default = null, $filter = null)
    {
        static $_DELETE = null;
        if (is_null($_DELETE)) {
            parse_str(file_get_contents('php://input'), $_DELETE);
            $_DELETE = array_merge($_DELETE, $_GET);
        }
        return static::data($_DELETE, $name, $default, $filter);
    }

    /**
     * 获取$_GET
     * @param string $name 数据名称
     * @param string $default 默认值
     * @param string $filter 过滤方法
     * @return mixed
     */
    public static function get($name = '', $default = null, $filter = null)
    {
        return static::data($_GET, $name, $default, $filter);
    }

    /**
     * 获取$_POST
     * @param string $name 数据名称
     * @param string $default 默认值
     * @param string $filter 过滤方法
     * @return mixed
     */
    public static function post($name = '', $default = null, $filter = null)
    {
        return static::data($_POST, $name, $default, $filter);
    }

    /**
     * 获取$_REQUEST
     * @param string $name 数据名称
     * @param string $default 默认值
     * @param string $filter 过滤方法
     * @return mixed
     */
    public static function request($name = '', $default = null, $filter = '')
    {
        return static::data($_REQUEST, $name, $default, $filter);
    }

    /**
     * 获取$_FILES
     * @param string $name 数据名称
     * @param string $default 默认值
     * @param string $filter 过滤方法
     * @return mixed
     */
    public static function file($name = '', $default = null, $filter = '')
    {
        $data = static::data($_FILES, $name, $default, $filter);
        return $data;
    }

    /**
     * 获取变量 支持过滤和默认值
     * @param array $input 数据源
     * @param string $name 字段名
     * @param mixed $default 默认值
     * @param mixed $filter 过滤函数的名称 strip_tags trim intval htmlspecialchars
     * @return mixed
     */
    public static function data($input, $name = '', $default = null, $filter = null)
    {
        $data = $input;
        $name = trim((string)$name);
        if ('' != $name) {
            if (!empty($data) && isset($data[ $name ]) && !empty($data[ $name ])) {
                $data = $data[ $name ];
                if (is_array($data)) {
                    foreach ($data as $k => $v) {
                        $data[ $k ] = static::filter($v, $filter);
                    }
                } else {
                    $data = static::filter($data, $filter);
                }
            } else {
                $data = $default;
            }
        }

        return $data;
    }

    // 过滤非数组和对象数据
    public static function filter($data, $filter = null)
    {
        if (!empty($data) && !is_array($data) && !empty($filter)) {
            $filter = (array)$filter;
            foreach ($filter as $item) {
                if (function_exists($item)) {
                    $data = trim($item($data));
                }
            }
        }

        return $data;
    }
}