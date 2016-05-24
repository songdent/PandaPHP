<?php
/**
 * +----------------------------------------------------------------
 * + pandaphp.com [WE LOVE PANDA, WE LOVE PHP]
 * +----------------------------------------------------------------
 * + Copyright (c) 2015 http://www.pandaphp.com All rights reserved.
 * +----------------------------------------------------------------
 * + Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------
 * + Author songdengtao <http://www.songdengtao.cn/>
 * +----------------------------------------------------------------
 */
namespace pandaphp\syshelper;

/**
 * @Description
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Uri
{
    protected $defaultController = '';

    protected $defaultAction = '';

    protected $urlHtmlSuffix = '';

    protected $urlRouterOn = false;

    protected $urlRouterRules = [];

    protected $urlDomainDeployOn = false;

    protected $urlDomainDeployMapping = [];

    protected static $_instance = null;

    public function __construct()
    {
        $this->defaultController      = \Pandaphp::shell('Config::get', 'default_controller');
        $this->defaultAction          = \Pandaphp::shell('Config::get', 'default_action');
        $this->urlHtmlSuffix          = \Pandaphp::shell('Config::get', 'url_html_suffix');
        $this->urlRouterOn            = \Pandaphp::shell('Config::get', 'url_router_on');
        $this->urlRouterRules         = \Pandaphp::shell('Config::get', 'url_router_rules');
        $this->urlDomainDeployOn      = \Pandaphp::shell('Config::get', 'url_domain_deploy_on');
        $this->urlDomainDeployMapping = \Pandaphp::shell('Config::get', 'url_domain_deploy_mapping');
    }

    protected static function getInstance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new Uri();
        }

        return static::$_instance;
    }

    public static function create($args = null, $suffix = '', $module = null)
    {
        return static::getInstance()->createURL($args, $suffix, $module);
    }

    /**
     * 生成URL地址
     * @access public
     * @param  mixed $args 参数 可以是数组，
     *               当为数组时，则至少需要包括controller 和 action 两个元素 ，其他的都为GET参数；
     *               当为字符串时，则表示使用正则路由
     *               当以上2个都不符合时，则默认使用常规路由，使用默认的生成当前模块控制器方法
     * @param  string $module 模块名称, 默认使用当前模块名称
     * @param  mixed $suffix URL后缀 false 表示不使用url后缀 为空则表示使用默认配置的后缀, 不为空则表示使用该后缀
     * @author songdengtao <http://www.songdengtao.cn/>
     * @return string
     */
    protected function createURL($args = null, $suffix = '', $module = null)
    {
        $url = \Pandaphp::shell('Http::getProtocol') . '://' . $this->_getUrlHost($module);

        if (is_string($args) && $args) {
            // 使用了路由的URL
            $url .= ltrim($args, '/');
        } else {
            // $args 的第一个元素就是控制器名称
            $controllerName = $this->_getControllerName($args);
            // $args 的第二个元素就是操作名称
            $actionName = $this->_getActionName($args);
            // $args 的其余的关联元素都是URL查询参数
            $queryStr = $this->_getQueryStr($args);
            $queryStr = empty($queryStr) ? '' : '/' . $queryStr;

            $url .= lcfirst($controllerName) . '/' . $actionName . $queryStr;
        }

        if (false !== $suffix) {
            if (!empty($suffix)) {
                $url .= '.' . ltrim($suffix, '.');
            } else {
                $url .= $this->urlHtmlSuffix;
            }
        }

        return $url;
    }

    // 获取URL HOST
    private function _getUrlHost($module = null)
    {
        if (is_null($module) || !is_string($module) || empty($module)) {
            $module = MODULE_NAME;
        }
        $domain = \Pandaphp::shell('Http::getDomain');

        $domainSuffix = \Pandaphp::shell('Http::getDomainSuffix');
        $urlHost      = $domain . $domainSuffix . '/' . $module . '/';
        if ($this->urlDomainDeployOn) {
            $reDomainDeployMapping = array_flip($this->urlDomainDeployMapping);
            if (isset($reDomainDeployMapping[$module])) {
                $urlHost = $reDomainDeployMapping[$module] . '.' . $domain . $domainSuffix . '/';
            }
        }

        return $urlHost;
    }

    // 获取控制器的名称
    private function _getControllerName(array &$args)
    {
        $temp = (!empty($args)) ? array_shift($args) : '';
        if (!empty($temp)) {
            $controllerName = $temp;
        } else {
            $controllerName = $this->defaultController;
        }

        return $controllerName;
    }

    // 获取操作名称
    private function _getActionName(array &$args)
    {
        $temp = (!empty($args)) ? array_shift($args) : '';
        if (!empty($temp)) {
            $actionName = $temp;
        } else {
            $actionName = $this->defaultAction;
        }

        return $actionName;
    }

    // 获得除了控制器，操作之外的URL 参数字符串
    private function _getQueryStr(array &$args)
    {
        $dataext = [];
        if (!empty($args)) {
            foreach ($args as $k => $v) {
                $dataext[] = $k . '/' . $v;
            }
        }

        $queryStr = '';
        if (!empty($dataext)) {
            $queryStr = implode('/', $dataext);
        }

        return $queryStr;
    }
}