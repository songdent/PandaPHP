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
 * cookie
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Cookie
{

    private static function _setCookiePrefix($name = '')
    {
        return \Pandaphp::shell('Config::get', 'cookie_prefix') . $name;
    }

    public static function set($name = '', $value = '', $expire = 0, $path = '/', $domain = '')
    {
        $name     = static::_setCookiePrefix($name);
        $expire   = intval($expire) > 0 ? $expire : \Pandaphp::shell('Config::get', 'cookie_expire');
        $path     = empty($path) ? \Pandaphp::shell('Config::get', 'cookie_path') : $path;
        $domain   = empty($domain) ? \Pandaphp::shell('Config::get', 'cookie_domain') : $domain;
        $secure   = \Pandaphp::shell('Config::get', 'cookie_secure');
        $httponly = \Pandaphp::shell('Config::get', 'cookie_httponly');
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public static function get($name = '', $isSetPrefix = true)
    {
        if ($isSetPrefix) {
            $name = static::_setCookiePrefix($name);
        }
        if (array_key_exists($name, $_COOKIE)) {
            return $_COOKIE[$name];
        }
        return null;
    }

    public static function delete($name = '')
    {
        $name = static::_setCookiePrefix($name);
        setcookie($name, '', time() - 3600);
    }

    public static function flush()
    {
        $_COOKIE = [];
    }
}