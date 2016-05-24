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
 * 路由抽象类
 * @author songdengtao <http://www.songdengtao.cn/>
 */
abstract class RouterAbstract
{
	protected $defaultModule = '';

	protected $defaultController = '';

	protected $defaultAction = '';

	protected $controllerDirname = '';

	protected $urlHtmlSuffix = '';

	protected $urlRouterOn = false;

	protected $urlRouterRules = [ ];

	protected $urlDomainDeployOn = false;

	protected $urlDomainDeployMapping = [ ];

	public function __construct()
	{
		$this->defaultModule          = \Pandaphp::shell('Config::get', 'default_module');
		$this->controllerDirname      = \Pandaphp::shell('Config::get', 'dirname_controller');
		$this->urlHtmlSuffix          = \Pandaphp::shell('Config::get', 'url_html_suffix');
		$this->urlRouterOn            = \Pandaphp::shell('Config::get', 'url_router_on');
		$this->urlRouterRules         = \Pandaphp::shell('Config::get', 'url_router_rules');
		$this->urlDomainDeployOn      = \Pandaphp::shell('Config::get', 'url_domain_deploy_on');
		$this->urlDomainDeployMapping = \Pandaphp::shell('Config::get', 'url_domain_deploy_mapping');
	}

	/**
	 * 解析$pandaUri
	 * @access public
	 * @param string $pandaUri
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return array ['module' => '', 'controller' => '', 'action' => '', '_GET' => '']
	 */
	public function parsePandaUri($pandaUri = '')
	{
		$mdlCtrlActGET = $this->getMdlCtrlActGET($pandaUri);
		$module        = $mdlCtrlActGET['module'];

		$GET = [ ];
		if (!empty($pandaUri)) {
			if ($this->urlRouterOn) {
				if (isset($this->urlRouterRules[$module]) && !empty($this->urlRouterRules[$module])) {
					$pandaUri = str_replace('/' . $module, '', $pandaUri);
					$pandaUri = ltrim($pandaUri, '/');
					$pandaUri = substr_replace($pandaUri, '', -1);
					#foreach
					foreach ($this->urlRouterRules[$module] as $key => $val) {
						if (preg_match($key, $pandaUri, $matchet)) {
							$controller = $val[0] ? $val[0] : '';
							$action     = $val[1] ? $val[1] : '';
							array_shift($matchet);
							if (isset($val[2]) && $val[2]) {
								foreach ($matchet as $k => $v) {
									$index = $k + 1;
									foreach ($val[2] as $k2 => $v2) {
										if ($v2 === ':' . $index) {
											$GET[$k2] = $v;
										}
									}
								}
							}
							break;
						}
					}
					#foreach
				}
			}
		}

		if (!isset($controller)) {
			$controller = $mdlCtrlActGET['controller'];
		}

		if (!isset($action)) {
			$action = $mdlCtrlActGET['action'];
		}

		if (empty($GET)) {
			$GET = $mdlCtrlActGET['_GET'];
		}

		return [
			'module'     => $module,
			'controller' => $controller,
			'action'     => $action,
			'_GET'       => $GET
		];
	}

	/**
	 * 获取module，controller, action
	 * @access public
	 * @param string $pandaUri
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return array
	 */
	public function getMdlCtrlActGET($pandaUri = '')
	{
		$ret = [
			'module'     => \Pandaphp::get('runtimeModule'),
			'controller' => $this->defaultController,
			'action'     => $this->defaultAction,
			'_GET'       => [ ]
		];

		if (!empty($pandaUri)) {
			$pandaUriArr = explode('/', trim($pandaUri, '/'));
			array_shift($pandaUriArr);
			if (count($pandaUriArr) >= 1) {
				$ret['controller'] = ucwords(array_shift($pandaUriArr));
			}

			if (count($pandaUriArr) >= 1) {
				$ret['action'] = array_shift($pandaUriArr);
			}
			$ret['_GET'] = $pandaUriArr;
		} else {
			\Pandaphp::shell('Error::halt', '请检查默认的模块,控制器,操作是否空或不存在！');
		}

		if (!empty($ret['_GET'])) {
			$queryArr = $ret['_GET'];
			$keyArr   = [ ];
			$valArr   = [ ];
			foreach ($queryArr as $key => $val) {
				if ($key % 2 === 0) {
					$keyArr[] = $val;
				} else {
					$valArr[] = $val;
				}
			}
			$GET = [ ];
			foreach ($keyArr as $k => $v) {
				if (isset($valArr[$k]) && false === is_null($valArr[$k])) {
					$GET[$v] = $valArr[$k];
				}
			}
			$ret['_GET'] = $GET;
		}

		$ret['_GET'] = (array)$ret['_GET'];

		return \Pandaphp::shell('Filter::inputFilter', $ret);
	}

	/**
	 * 获取控制类的命名空间
	 * @access public
	 * @param string $module
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return string
	 */
	public function getCtrlNamespace($module = '')
	{
		$ret = $module . '\\' . $this->controllerDirname;

		return $ret;
	}

	abstract public function dispatch();
}