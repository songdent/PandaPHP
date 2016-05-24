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
 * SMARTY驱动视图
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class SmartyView extends \Smarty
{
	public function __construct()
	{
		parent::__construct();

		$ds     = '/';
		$module = MODULE_NAME;

		$leftDelimiter        = \Pandaphp::shell('Config::get', 'template_smarty_left_delimiter');
		$this->left_delimiter = $leftDelimiter;

		$rightDelimiter        = \Pandaphp::shell('Config::get', 'template_smarty_right_delimiter');
		$this->right_delimiter = $rightDelimiter;

		// 设置模板文件目录
		$viewDirname = \Pandaphp::shell('Config::get', 'dirname_view');
		$theme       = \Pandaphp::shell('Config::get', 'template_theme');
		$theme       = $theme ? ($theme . $ds) : '';
		$templateDir = \Pandaphp::get('appPath') . $module . $ds . $viewDirname . $ds . $theme;
		$this->setTemplateDir($templateDir);

		// 设置编译后的模板文件目录]
		$runtimeDir = \Pandaphp::get('runtimePath');
		$compileDir = $runtimeDir . $module . $ds . 'template_complie' . $ds;
		$this->setCompileDir($compileDir);

		// 设置缓存的模板文件目录
		$cacheDir = $runtimeDir . $module . $ds . 'template_cache' . $ds;
		$this->setCacheDir($cacheDir);

		$isTemplateCache = \Pandaphp::shell('Config::get', 'template_cache_on');
		$this->setCaching($isTemplateCache);

		$templateCacheLifetime = \Pandaphp::shell('Config::get', 'template_cache_lifetime');
		$this->setCacheLifetime($templateCacheLifetime);
	}
}