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
namespace pandaphp\config;

/**
 * 配置管理
 * @author songdengtao <http://www.songdengtao.cn/>
 *
 * demo:
 * # 获取配置
 * \Pandaphp::shell('Cache::get', $name)
 */
class Config
{
	/**
	 * 配置文件后缀
	 * @var string
	 * @access protected
	 */
	protected $configFileExt = '';

	/**
	 * 全局默认的配置文件名称
	 * @var string
	 * @access protected
	 */
	protected $globalConfigFilename = '';

	/**
	 * 系统配置文件名称
	 * @var string
	 * @access protected
	 */
	protected $systemConfigFilename = '';


	/**
	 * 全局默认配置文件存放的目录的路径
	 * @var string
	 * @access protected
	 */
	protected $globalConfigDirPath = '';

	/**
	 * 配置文件存放的目录的路径
	 * @var string
	 * @access protected
	 */
	protected $configDirPath = '';

	/**
	 * 配置管理对象实例
	 * @var object
	 */
	protected static $_Instance = null;

	/**
	 * 构造函数
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn>
	 */
	protected function __construct()
	{
		$this->globalConfigFilename = 'convention';
		$this->globalConfigDirPath  = __DIR__ . '/settings/';

		$this->configFileExt        = '.php';
		$this->systemConfigFilename = 'config';

		$status = \Pandaphp::get('status');
		$status = empty($status) ? 'production' : $status;

		$this->configDirPath = \Pandaphp::get('configPath') . $status . '/';
	}

	/**
	 * 获取配置管理对象单例
	 * @access public
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return Config
	 */
	public static function getInstance()
	{
		if (is_null(static::$_Instance)) {
			static::$_Instance = new Config();
		}
		return static::$_Instance;
	}

	/**
	 * 获取配置项的值: 模块配置 > 应用配置 > 全局默认配置
	 * @access public
	 * @param string $key 配置项的名称
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	public static function get($key = '')
	{
		$oThis = static::getInstance();

		$filename = '';
		if (false !== strpos($key, '@')) {
			$keyArr   = explode('@', $key);
			$filename = $keyArr[0];
			$key      = $keyArr[1];
		}
		$configFilePaths = $oThis->_GetConfigFilePaths($filename);
		$data            = $oThis->_GetConfigValue($key, $configFilePaths);
		if (is_null($data)) {
			$e       = [ 'message' => 'Configure ' . $key . ' does not exist.' ];
			$isDebug = \Pandaphp::get('debug');
			\Pandaphp::shell('Error::defaultErrorPrint', $e, $isDebug);
		}

		return $data;
	}

	/**
	 * 获取所有的配置文件的路径
	 * @access private
	 * @param string $filename 配置文件名
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return array
	 */
	private function _GetConfigFilePaths($filename = '')
	{
		$paths = [ ];
		if (!empty($filename)) {
			$paths[] = $this->configDirPath . $filename . $this->configFileExt;
		}
		$runtimeModule = \Pandaphp::get('runtimeModule');
		if (!empty($runtimeModule)) {
			$paths[] = $this->configDirPath . strtolower($runtimeModule) . $this->configFileExt;
		}
		$paths[] = $this->configDirPath . $this->systemConfigFilename . $this->configFileExt;
		$paths[] = $this->globalConfigDirPath . $this->globalConfigFilename . $this->configFileExt;

		return $paths;
	}

	/**
	 * 获取配置
	 * @access private
	 * @param string $key 配置键
	 * @param array $configFilePaths 配置文件路径数组
	 * @author songdengtao <http://www.songdengtao.cn/>
	 * @return mixed
	 */
	protected function _GetConfigValue($key = '', array $configFilePaths = [ ])
	{
		$ret = null;
		if (!empty($key)) {
			foreach ($configFilePaths as $k => $v) {
				$GLOBALSKEY = md5($v);
				if (array_key_exists($GLOBALSKEY, $GLOBALS)) {
					$GLOBALSVAL = json_decode($GLOBALS[$GLOBALSKEY], true);
				} else {
					$data = \Pandaphp::shell('File::returnInclude', $v);
					if ($data) {
						$GLOBALSVAL           = $data;
						$GLOBALS[$GLOBALSKEY] = json_encode($data);
					} else {
						continue;
					}
				}

				if (array_key_exists($key, $GLOBALSVAL)) {
					$ret = $GLOBALSVAL[$key];
					break;
				}
			}
		}
		return $ret;
	}
}