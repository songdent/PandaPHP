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
 * SESSION
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Session
{
    /**
     * 开启SESSION
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return void
     */
    public static function start()
    {
        $sessionCookiePath = \Pandaphp::shell('Config::get', 'session_cookie_path');
        ini_set('session.cookie_path', $sessionCookiePath);

        $sessionCookieDomain = \Pandaphp::shell('Config::get', 'session_cookie_domain');
        ini_set('session.cookie_domain', $sessionCookieDomain);

        $sessionCookieExpire = \Pandaphp::shell('Config::get', 'session_cookie_expire');
        ini_set('session.cookie_lifetime', $sessionCookieExpire);

        session_start();
    }

    // 批量设置
    public static function setAll(array $data = [])
    {
        foreach ($data as $key => $val) {
            static::set($key, $val);
        }
    }

    // 设置SESSION
    public static function set($name = '', $value = '')
    {
        $_SESSION[$name] = $value;
    }

    // 获取SESSION
    public static function get($key = '')
    {
        if (!empty($key)) {
            if (strpos($key, '.')) {
                $keyArr = explode('.', $key);
                $fKey   = $keyArr[0];
                $sKey   = $keyArr[1];
                return isset($_SESSION[$fKey][$sKey]) ? $_SESSION[$fKey][$sKey] : null;
            } else {
                return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
            }
        } else {
            return $_SESSION;
        }
    }

    // 删除
    public static function delete($key = '')
    {
        if (!empty($key) && isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        } else {
            $_SESSION = [];
        }
    }

    // 销毁内存中的SESSION和SESSION ID ，session文件
    public static function destroy()
    {
        session_unset();

        return session_destroy();
    }
}