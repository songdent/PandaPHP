<?php
/**
 * 项目入口文件
 */
require_once dirname(__DIR__) . '/framework/Pandaphp.php';

// 生产环节请将debug 设置为false
Pandaphp::set('debug', true);

// 根据环境，自定义读取配置文件
Pandaphp::set('status', 'development');
Pandaphp::run();