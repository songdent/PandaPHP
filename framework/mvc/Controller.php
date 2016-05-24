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
 * 控制器
 * @author songdengtao <http://www.songdengtao.cn/>
 */
abstract class Controller
{
	/**
	 * 构造函数
	 * @author songdengtao <http://www.songdengtao.cn/>
	 */
	public function __construct()
	{
		// 模板初始化
		if (method_exists($this, '_initialize')) {
			$this->_initialize();
		}
	}

	// 模板赋值
	public function assign($key = '', $value = '')
	{
		View::assign($key, $value);
	}

	// 模板展示
	public function display($template = '')
	{
		if (method_exists($this, '_beforeDisplay')) {
			// 执行显示模板之前需要的操作
			$this->_beforeDisplay();
		}

		$appPath = \Pandaphp::get('appPath');
		if (empty($template) || (0 !== strpos($template, $appPath))) {
			$viewDirname      = \Pandaphp::shell('Config::get', 'dirname_view');
			$templateFilePath = $appPath . MODULE_NAME . '/' . $viewDirname . '/';
			$templateTheme    = \Pandaphp::shell('Config::get', 'template_theme');
			if ($templateTheme) {
				$templateFilePath .= $templateTheme . '/';
			}
			$templateFilePath .= lcfirst(CONTROLLER_NAME) . '-' . ACTION_NAME;
			$templateFilePath .= \Pandaphp::shell('Config::get', 'template_suffix');
		} else {
			$templateFilePath = $template;
		}

		if (\Pandaphp::shell('File::isExist', $templateFilePath)) {
			View::display($templateFilePath);
		} else {
			\Pandaphp::shell('Error::halt', $templateFilePath . ' 模板不存在！');
		}
	}

	/**
	 * 调用不存在的控制器方法时的响应
	 * @access public
	 * @param string $name
	 * @param array $arguments
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return void
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this, '_empty')) {
			$this->_empty();
		} else {
			// 访问的页面不存在，跳转之404页面
			\Pandaphp::shell('Error::halt', '404 Not Found:' . $name);
		}
	}
}