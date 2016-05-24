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
 * CGI路由
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class CgiRouter extends RouterAbstract
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
		$queryVar  = \Pandaphp::get('httpQueryStr');
		$inputData = \Pandaphp::shell('Input::get');
		$queryStr  = '';

		if (isset($inputData[$queryVar])) {
			$mdlCtrlActStr = $inputData[$queryVar];
			if (\Pandaphp::shell('Str::isEnd', $mdlCtrlActStr, $this->urlHtmlSuffix)) {
				$queryStr .= str_replace($this->urlHtmlSuffix, '', $mdlCtrlActStr);
			} else {
				$queryStr .= $mdlCtrlActStr;
			}
			$queryStr = str_replace('/index.php', '', $queryStr) . '/';
		}

		$subDomain      = \Pandaphp::shell('Http::getSubDomain');
		$isDomainDeploy = $this->urlDomainDeployOn && (isset($this->urlDomainDeployMapping[$subDomain]) || empty($subDomain));
		if ($isDomainDeploy) {
			if (empty($subDomain)) {
				$subDomain = 'www';
			}
			$runtimeModule = $this->urlDomainDeployMapping[$subDomain];
		} else {
			if (isset($inputData[$queryVar])) {
				$runtimeModule = substr($queryStr, 1, strpos(trim($queryStr, '/'), '/'));
			} else {
				$runtimeModule = $this->defaultModule;
			}
		}
		\Pandaphp::set('runtimeModule', $runtimeModule);

		$this->defaultController = \Pandaphp::shell('Config::get', 'default_controller');
		$this->defaultAction     = \Pandaphp::shell('Config::get', 'default_action');

		if (isset($inputData[$queryVar])) {
			$queryStr = '/' . $runtimeModule . $queryStr;
			unset($inputData[$queryVar]);
		} else {
			$queryStr .= '/' . $runtimeModule . '/' . $this->defaultController . '/' . $this->defaultAction . '/';
		}

		foreach ($inputData as $key => $val) {
			$queryStr .= $key . '/' . $val . '/';
		}

		return $queryStr;
	}
}