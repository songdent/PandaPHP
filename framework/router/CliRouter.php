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
namespace pandaphp\router;

/**
 * CLI路由
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class CliRouter extends RouterAbstract
{
	/**
	 * 路由调度
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return void
	 */
	public function dispatch()
	{
		$pandaUri = $this->getPandaUri();
		$data     = $this->parsePandaUri($pandaUri);

		define('MODULE_NAME', $data['module']);
		define('CONTROLLER_NAME', $data['controller']);
		define('ACTION_NAME', $data['action']);
		define('CONTROLLER_NAMESPACE', $this->getCtrlNamespace(MODULE_NAME));

		$_GET     = $data['_GET'];
		$_POST    = \Pandaphp::shell('Input::post');
		$_REQUEST = array_merge($_GET, $_POST);
	}

	/**
	 * 解析请求的URI，获得PANDAURI
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return string
	 */
	public function getPandaUri()
	{
		$argv = $_SERVER['argv'];
		// 移除请求文件路径
		array_shift($argv);

		$requestUri = '';
		if (!empty($argv)) {
			$requestArr = [];
			foreach ($argv as $key => $value) {
				$value = str_replace('&', '/', $value);
				if (false !== strpos($value, '?')) {
					foreach (explode('?', $value) as $k => $v) {
						$requestArr[] = $v;
					}
				} else {
					$requestArr[] = $value;
				}
			}

			// 去掉伪静态后缀，第一个出现的静态后缀的字符串被认为是静态后缀
			foreach ($requestArr as $k => $val) {
				$val = preg_replace('/\/+$/', '', $val);
				$pos = strlen($val) - strlen($this->urlHtmlSuffix);
				if ($pos === strrpos($val, $this->urlHtmlSuffix)) {
					$requestArr[$k] = str_replace($this->urlHtmlSuffix, '', $val);
					break;
				}
			}
			$requestUri = implode('/', $requestArr);
		}

		if (!empty($requestUri)) {
			$pandaUri = $requestUri;
			if (0 !== strpos($pandaUri, '/')) {
				$pandaUri = '/' . $pandaUri;
			}
			if ((strlen($pandaUri) - 1) !== strrpos($pandaUri, '/')) {
				$pandaUri = $pandaUri . '/';
			}
			// 去掉多余的/
			$pandaUri = preg_replace('/\/+/', '/', $pandaUri);
		} else {
			$pandaUri = '/' . $this->defaultModule . '/' . $this->defaultController . '/' . $this->defaultAction . '/';
		}

		return $pandaUri;
	}
}