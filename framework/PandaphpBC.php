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

/**
 * Pandaphp 属性类
 * @author songdengtao <http://www.songdengtao.cn/>
 */
class PandaphpBC
{
	// PANDAPHP的版本号
	const PANDAPHP_VERSION = '1.0.0-beta';

	// PANDAPHP框架需要的最低PHP版本号
	const PANDAPHP_PHP_VERSION = '5.4.0';

	/**
	 * 系统根路径
	 * @var string
	 * @access protected
	 */
	protected $root = '';

	/**
	 * Web根路径
	 * @var string
	 * @access protected
	 */
	protected $webroot = '';

	/**
	 * 配置文件根路径
	 * @var string
	 * @access protected
	 */
	protected $configPath = '';

	/**
	 * 框架根路径
	 * @var string
	 * @access protected
	 */
	protected $frameworkPath = '';

	/**
	 * 运行时根目录
	 * @var string
	 * @access protected
	 */
	protected $runtimePath = '';

	/**
	 * 第三方类库根路径
	 * @var string
	 * @access protected
	 */
	protected $venderPath = '';

	/**
	 * 应用/模块根路径
	 * @var string
	 * @access protected
	 */
	protected $appPath = '';

	/**
	 * 自定义库根路径
	 * @var string
	 * @access protected
	 */
	protected $helperPath = '';

	/**
	 * 数据根路径
	 * @var string
	 * @access protected
	 */
	protected $dataPath = '';

	/**
	 * 自定义注册表根目录
	 * @var string
	 * @access protected
	 */
	protected $registryPath = '';

	/**
	 * 是否是CLI模式
	 * @var boolean
	 * @access protected
	 */
	protected $isCli = false;

	/**
	 * 是否是CGI模式
	 * @var boolean
	 * @access protected
	 */
	protected $isCgi = true;

	/**
	 * 是否是Windows环境
	 * @var boolean
	 * @access protected
	 */
	protected $isWin = false;

	/**
	 * 当前运行的模块名称
	 * @var string
	 * @access protected
	 */
	protected $runtimeModule = '';

	/**
	 * 类文件扩展名
	 * @var string
	 * @access protected
	 */
	protected $classFileExt = '.php';

	/**
	 * 地址重写隐藏了入口文件后，保存所有URL请求参数的变量名
	 * 例如 index.php/?s=... 中的s
	 * @var boolean
	 * @access protected
	 */
	protected $httpQueryStr = 's';

	/**
	 * 调式
	 * @var boolean
	 * @access protected
	 */
	protected $debug = false;

	/**
	 * 上传文件目录根路径
	 * @var boolean
	 * @access protected
	 */
	protected $uploadPath = '';

	/**
	 * 状态(对应着config目录下的目录名称)
	 * @var string
	 * @access protected
	 */
	protected $status = 'development';

	protected function __construct()
	{
		$root                = dirname(__DIR__) . '/';
		$this->root          = $root;
		$this->webroot       = $root . 'web/';
		$this->frameworkPath = $root . 'framework/';
		$this->configPath    = $root . 'config/';
		$this->runtimePath   = $root . 'runtimes/';
		$this->helperPath    = $root . 'helper/';
		$this->venderPath    = $root . 'vender/';
		$this->dataPath      = $root . 'data/';
		$this->appPath       = $root . 'app/';
		$this->registryPath  = $root . 'framework/registry/';
		$this->uploadPath    = $this->webroot . 'uploads/';
		$this->isCgi         = (false !== strpos(PHP_SAPI, 'cgi')) || (false !== strpos(PHP_SAPI, 'fcgi'));
		$this->isWin         = strstr(PHP_OS, 'WIN') ? true : false;
		$this->isCli         = (PHP_SAPI === 'cli');
	}

	public function __set($name, $value)
	{
		$disabledSet = [
			'root',
			'isCli',
			'isWin',
			'isCgi',
			'runtimeModule',
			'classFileExt',
			'registryPath'
		];
		if (!in_array($name, $disabledSet)) {
			$this->$name = $value;
		} else {
			die('Setting failed:' . $name . ' is not allowed to be setted');
		}
	}

	public function __get($name)
	{
		return (isset($this->$name)) ? $this->$name : null;
	}

	// 获取框架版本号
	public static function getVersion()
	{
		return static::PANDAPHP_VERSION;
	}
}