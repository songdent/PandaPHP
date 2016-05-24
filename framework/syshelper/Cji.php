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
 * 生成CSS link标签的href
 * 生成javascript script 标签的src
 * 生成样式图片image image 标签的src
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class Cji
{
	/**
	 * 生成CSS link标签的href
	 * 生成javascript script 标签的src
	 * 生成样式图片image image 标签的src
	 * @access public
	 * @param  string $file
	 * @param  array $options
	 * @author songdengtao <http://www.songdengtao.cn>
	 * @return string
	 */
	public static function create($file = '', array $options = [ ])
	{
		$ret = '';

		if (empty($file)) {
			return $ret;
		}

		$cjiHttpDomain = \Pandaphp::shell('Config::get', 'http_domain_cji');

		$module = defined('MODULE_NAME') ? MODULE_NAME : '';
		if (isset($options['module']) && !empty($options['module'])) {
			$module = $options['module'];
		}

		$theme = \Pandaphp::shell('Config::get', 'template_theme');
		if (isset($options['theme']) && !empty($options['theme'])) {
			$theme = $options['theme'];
		}

		$isComplete = false;
		if (isset($options['isComplete'])) {
			$isComplete = $options['isComplete'];
		}

		$cjiHttpDomain = rtrim($cjiHttpDomain, '/');
		if (!$isComplete) {
			$theme      = !empty($theme) ? '/' . $theme : '';
			$ret        = $cjiHttpDomain . '/' . $module . $theme;
			$fileSuffix = substr($file, strrpos($file, '.') + 1);

			switch ($fileSuffix) {
				case 'css':
					$ret .= '/css/';
					break;
				case 'js':
					$ret .= '/js/';
					break;
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'gif':
				case 'ico':
				case 'bmp':
					$ret .= '/images/';
					break;
				default:
					break;
			}

			$ret .= ltrim($file, '/');
		} else {
			$ret = $cjiHttpDomain . '/' . ltrim($file, '/');
		}

		return $ret;
	}
}