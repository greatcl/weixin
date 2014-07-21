<?php
define('APP_PATH',dirname(__FILE__));
define('SP_PATH',dirname(__FILE__).'/SpeedPHP');

define("BASE_PATH",'.');
define("THEME", "default");
define("IMAGE_PATH",BASE_PATH."/themes/" . THEME . "/images");
define("CSS_PATH",BASE_PATH."/themes/" . THEME . "/css");
define("JS_PATH",BASE_PATH."/js");
define("SEAJS", JS_PATH."/sea-debug.js");
define("JQUERY", JS_PATH."/jquery/jquery-1.11.1.js");

define("TOKEN", "weixin");
define("APPID", "wx4cc95ff36369d7af");
define("APPSECRET", "53fa2fd2226c24ba79c9445d450788d4");
define("TOKEN_URL", "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET);

define("DOMAIN", "http://wechat.changyan.com/");
// 账号绑定页面
define("REDIRECT_URI", DOMAIN . "index.php?c=tourist&a=bindAccountEntry");

define("URL_TEMPLATE", "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . APPID . "&redirect_uri=%s&response_type=code&scope=snsapi_base&state=iflytek#wechat_redirect");
define("OAUTH_URL", sprintf(URL_TEMPLATE, urlencode(REDIRECT_URI)));

$spConfig = array(
    'mode'=>'release',
    'default_controller' => 'main', // 默认的控制器名称
    'default_action' => 'index',  // 默认的动作名称
    'url_controller' => 'c',  // 请求时使用的控制器变量标识
    'url_action' => 'a',  // 请求时使用的动作变量标识
    'dispatcher_error' => "spController::jump(spUrl('main','error404'));", // 定义处理路由错误的函数

    'url' => array(
        'url_path_info' => TRUE,
    ),

    'db' => array(  // 数据库连接配置
        'driver' => 'mysql',   // 驱动类型
        'host' => 'localhost', // 数据库地址
        'port' => 3306,        // 端口
        'login' => 'root',     // 用户名
        'password' => '',      // 密码
        'database' => 'wechat_demo',      // 库名称
        'persistent' => false,    // 是否使用长链接
    ),

    //日志配置
    'ext' => array (
        'logsize' => '10240000', // 日志文件大小
        'logpath' => APP_PATH.'/log', // 日志保存目录
        'logprefix' => 'log_', // 日志文件前缀’
        'mail' => 'NULL', // 是否发送日志邮件，
        'mailto' => ' ',  // 发送到的邮件地址
    ),
    'view' => array( // 视图配置
        'enabled' => TRUE, // 开启视图
        'config' =>array(
            'template_dir' => APP_PATH.'/template', // 模板目录
            'compile_dir' => APP_PATH.'/tmp', // 编译目录
            'cache_dir' => APP_PATH.'/tmp', // 缓存目录
            'left_delimiter' => '<{',  // smarty左限定符
            'right_delimiter' => '}>', // smarty右限定符
            'auto_literal' => TRUE, // Smarty3新特性
        ),
        'debugging' => FALSE, // 是否开启视图调试功能，在部署模式下无法开启视图调试功能
        'engine_name' => 'Smarty', // 模板引擎的类名称，默认为Smarty
        'engine_path' => SP_PATH.'/Drivers/Smarty/Smarty.class.php', // 模板引擎主类路径
        'auto_ob_start' => TRUE, // 是否自动开启缓存输出控制
        'auto_display' => FALSE, // 是否使用自动输出模板功能
        'auto_display_sep' => '/', // 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，以此类推
        'auto_display_suffix' => '.html', // 自动输出模板的后缀名
    ),
    'include_path' => array(
        APP_PATH.'/include'
    ) // 用户程序扩展类载入路径
);
require(SP_PATH."/SpeedPHP.php");
import('controller/base/adminController.php');

spRun();