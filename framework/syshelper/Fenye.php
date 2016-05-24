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
namespace pandaphp\syshelper;

/**
 * 分页
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Fenye
{
    // 分页栏每页显示的分页导航链接数
    private $_fenyeNavAnum = 10;

    // 当前页码在URL中的变量名
    private $_pageNumberVarName = 'p_now';

    // URL页码占位符
    private $_pageNumberPlaceholder = '__FENYEPNUM__';

    private $_config = ['num' => 10, 'prev' => '<<', 'next' => '>>'];

    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            foreach ($config as $ie => $item) {
                if (array_key_exists($ie, $this->_config) && !empty($item)) {
                    $this->_config[$ie] = $item;
                }
            }
        }
        $this->_pageNumberVarName = \Pandaphp::shell('Config::get', 'fenye_var');
        $this->_fenyeNavAnum      = $this->_config['num'];
    }

    public function show($pnow = 1, $psize = 20, $totalRows = 0, $urlParam = [], $suffix = '', $module = '')
    {
        $html       = '';
        $psize      = intval($psize) > 0 ? intval($psize) : 1;
        $totalPages = ceil($totalRows / $psize);
        if ($totalRows > 0 && $totalPages > 1) {
            $fenyeAlinkArr         = [];
            $url                   = $this->_createNavAUrl($urlParam, $suffix, $module);
            $pageNumberPlaceholder = $this->_pageNumberPlaceholder;
            for ($i = 1; $i <= $this->_fenyeNavAnum; $i++) {
                $lj              = (ceil($pnow / $this->_fenyeNavAnum) - 1) * $this->_fenyeNavAnum;
                $pnum            = ($pnow >= $lj) ? ($lj + $i) : $i;
                $current         = ($pnow == $pnum) ? 'class="fenye-current"' : '';
                $fenyeAlinkArr[] = '<a href="' . $this->_createUrl($pnum, $url, $pageNumberPlaceholder) . '" ' . $current . '>' . $pnum . '</a>';
                if ($pnum >= $totalPages) break;
            }

            $theFirst = $this->_showTheFirst($pnow, $url, $pageNumberPlaceholder);
            $theLast  = $this->_showTheLast($pnow, $totalPages, $url, $pageNumberPlaceholder);
            $thePrev  = $this->_showThePrev($pnow, $url, $pageNumberPlaceholder);
            $theNext  = $this->_showTheNext($pnow, $totalPages, $url, $pageNumberPlaceholder);

            $theTotal = '<span class="fenye-total">共' . $totalRows . '条</span>';

            $fenyeAlinkStr = '<div class="fenye">';
            $fenyeAlinkStr .= $theFirst . $thePrev;
            $fenyeAlinkStr .= implode('', $fenyeAlinkArr);
            $fenyeAlinkStr .= $theNext . $theLast . $theTotal . '</div>';

            $html = $this->navTheme() . $fenyeAlinkStr;
        }

        return $html;
    }

    // 打印style
    public function navTheme()
    {
        $ret = '<style type="text/css">';
        $ret .= '.fenye { width:100%;height:45px;line-height:45px; float:left;margin:10px 0; }';
        $ret
            .= '.fenye-current,.fenye a, .fenye-total { display:inline-block;padding:0 15px;height:40px;line-height:40px;
		margin:0 5px; border: 1px solid #EAEAEA;border-radius:3px;background-color:#FFFFFF; }';
        $ret .= '.fenye a { text-decoration:none; }';
        $ret .= '.fenye a:first-child { margin-left:0; }';
        $ret .= '.fenye a:last-child { margin-right:0; }';
        $ret .= '.fenye a:focus,.fenye a:hover,';
        $ret .= 'a.fenye-current { background-color:#0099FF;border-color:#0088FF;color:#ffffff; }';
        $ret .= '.fenye-total { height:41px;line-height:41px; }';
        $ret .= '</style>';

        return $ret;
    }

    // 生成分页导航URL
    private function _createNavAUrl($urlParam = [], $suffix = '', $module = '')
    {
        $url    = '';
        $module = empty($module) ? MODULE_NAME : $module;
        if (is_string($urlParam)) {
            if (\Pandaphp::shell('Str::isEnd', $urlParam, '/')) {
                $urlParam = rtrim($urlParam, '/');
            }
            $urlParam = (array)$urlParam;
        }
        $pageNumArr = [$this->_pageNumberVarName => $this->_pageNumberPlaceholder];
        $urlParam   = array_merge($urlParam, $pageNumArr);
        $url        = \Pandaphp::shell('Uri::create', $urlParam, $suffix, $module);

        return $url;
    }

    // 第一页
    private function _showTheFirst($pnow = 1, $url = '', $pageNumberPlaceholder = '__FENYEPNUM__')
    {
        $theFirst = '';
        if ($pnow > $this->_fenyeNavAnum) {
            $theFirst = '<a class="first" href="' . $this->_createUrl(1, $url, $pageNumberPlaceholder) . '">1' . '</a>';
        }

        return $theFirst;
    }

    // 上一页
    private function _showTheLast($pnow = 1, $totalPages = 0, $url = '', $pageNumberPlaceholder = '__FENYEPNUM__')
    {
        $theLast      = '';
        $fenyeNavAnum = $this->_fenyeNavAnum;
        $lj           = (ceil($totalPages / $fenyeNavAnum) - 1) * $fenyeNavAnum + 1;
        if ($totalPages > 0 && $totalPages > $fenyeNavAnum && $pnow < $lj) {
            $theLast = '<a class="last" href="' . $this->_createUrl($totalPages, $url, $pageNumberPlaceholder) . '">' . $totalPages . '</a>';
        }

        return $theLast;
    }

    // 最后一页
    private function _showThePrev($pnow = 1, $url = '', $pageNumberPlaceholder = '__FENYEPNUM__')
    {
        $thePrev       = '';
        $prevFentePnum = $pnow - 1;
        if ($prevFentePnum > 0) {
            $thePrev = '<a class="prev" href="' . $this->_createUrl($prevFentePnum, $url, $pageNumberPlaceholder) . '">' . $this->_config['prev'] . '</a>';
        }

        return $thePrev;
    }

    // 下一页
    private function _showTheNext($pnow = 1, $totalPages = 0, $url = '', $pageNumberPlaceholder = '__FENYEPNUM__')
    {
        $theNext       = '';
        $nextFenyePnum = $pnow + 1;
        if ($totalPages > 0 && $nextFenyePnum <= $totalPages) {
            $theNext = '<a class="next" href="' . $this->_createUrl($nextFenyePnum, $url, $pageNumberPlaceholder) . '">' . $this->_config['next'] . '</a>';
        }

        return $theNext;
    }

    // 生成分页URL
    private function _createUrl($pnum = 1, $url = '', $pageNumberPlaceholder = '__FENYEPNUM__')
    {
        $ret = '';
        if (!empty($url)) {
            $ret = str_replace($pageNumberPlaceholder, $pnum, $url);
        }

        return $ret;
    }
}