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

class Http
{
    // 域名
    protected static $domain = null;

    // 域名后缀
    protected static $domainSuffix = null;

    /**
     * 获取HTTP请求的协议
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function getProtocol()
    {
        $httpProtocol = ($_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
        return $httpProtocol;
    }

    /**
     * 获取一级域名名称部分
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function getDomain()
    {
        if (\Pandaphp::get('isCgi') && is_null(static::$domain)) {
            $domain         = $_SERVER['HTTP_HOST'];
            $domain         = strstr($domain, static::getDomainSuffix(), true);
            $domainArr      = explode('.', $domain);
            $domain         = array_pop($domainArr);
            static::$domain = $domain;
        }
        return static::$domain;
    }

    /**
     * 获取二级域名
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function getSubDomain()
    {
        $subDomain = '';
        $domain    = $_SERVER['HTTP_HOST'];
        $domain    = strstr($domain, static::getDomainSuffix(), true);
        $domainArr = explode('.', $domain);
        $count     = count($domainArr);
        if ($count > 1) {
            array_pop($domainArr);
            $subDomain = implode('.', $domainArr);
        }
        return $subDomain;
    }

    // 获取域名后缀
    public static function getDomainSuffix()
    {
        if (\Pandaphp::get('isCgi') && is_null(static::$domainSuffix)) {
            $tempHost = '';
            $httpHost = $_SERVER['HTTP_HOST'];
            if (\Pandaphp::shell('Config::get', 'url_domain_deploy_on')) {
                $mapping     = \Pandaphp::shell('Config::get', 'url_domain_deploy_mapping');
                $mappingKeys = array_keys($mapping);
                $temp        = [];
                foreach ($mappingKeys as $item) {
                    $temp[ $item ] = strlen($item);
                }
                arsort($temp);
                foreach ($temp as $k => $v) {
                    if (0 === strpos($httpHost, $k)) {
                        $tempHost = ltrim($httpHost, $k . '.');
                        break;
                    }
                }
            }

            if (empty($tempHost)) {
                if ('www' === strstr($httpHost, '.', true)) {
                    $tempHost = ltrim(strstr($httpHost, '.'));
                } else {
                    $hostOther = strstr($httpHost, '.');
                    $poslen    = substr_count($hostOther, '.');
                    if ($poslen === 1) {
                        return static::$domainSuffix = ltrim($hostOther);
                    } else {
                        \Pandaphp::shell('Error::halt', '访问地址不存在');
                    }
                }
            }

            static::$domainSuffix = strstr($tempHost, '.');
        }
        return static::$domainSuffix;
    }

    /**
     * 获取HTTP请求的类型
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return string
     */
    public static function getRequestType()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * 是否是GET请求
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return boolean
     */
    public static function isGet()
    {
        $ret = false;
        if (self::getRequestType() === 'GET') {
            $ret = true;
        }

        return $ret;
    }

    /**
     * 是否是POST请求
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return boolean
     */
    public static function isPost()
    {
        $ret = false;
        if (self::getRequestType() === 'POST') {
            $ret = true;
        }

        return $ret;
    }

    /**
     * 是否是put请求
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return boolean
     */
    public static function isPut()
    {
        $ret = false;
        if (self::getRequestType() === 'PUT') {
            $ret = true;
        }

        return $ret;
    }

    /**
     * 是否是DELETE请求
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return boolean
     */
    public static function isDelete()
    {
        $ret = false;
        if (self::getRequestType() === 'DELETE') {
            $ret = true;
        }

        return $ret;
    }

    /**
     * 是否是ajax请求
     * @access public
     * @author songdengtao <http://www.songdengtao.cn>
     * @return boolean
     */
    public static function isAjax()
    {
        $ret                     = false;
        $issetHttpXRequestedWith = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
        if ($issetHttpXRequestedWith && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            $ret = true;
        }

        return $ret;
    }

    /**
     * 跳转
     * @access public
     * @param  string $url
     * @param  int $stat
     * @author songdengtao <http://www.songdengtao.cn>
     * @return void
     */
    public static function redirect($url = '', $stat = 302)
    {
        if (!empty($url)) {
            if ($stat === 301) {
                header("HTTP/1.1 301 Moved Permanently");
                header('location:' . $url);
            } else {
                header('location:' . $url);
            }
            exit();
        }
    }

    /**
     * 404页面
     * @access public
     * @param  string $msg 展示的信息
     * @param  string $_404PageUrl 404页面URL
     * @author songdengtao <http://www.songdengtao.cn>
     * @return void
     */
    public static function _404($msg = '', $_404PageUrl = '')
    {
        if (empty($_404PageUrl)) {
            $_404PageUrl = \Pandaphp::shell('Config::get', '404');
            $_404PageUrl = \Pandaphp::get('webroot') . $_404PageUrl;
        }

        if (\Pandaphp::shell('File::isExist', $_404PageUrl)) {
            require_once $_404PageUrl;
        } else {
            \Pandaphp::shell('Error::halt', $msg);
        }

        #todo 读秒跳转

        exit();
    }
}