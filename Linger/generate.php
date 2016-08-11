#!/usr/bin/env php
<?php

$res = [];
if (is_array($argv)) {
    foreach($argv as $v) {
        if (preg_match('/^\-\-([a-zA-Z_]+?)=(.*?)$/', $v, $info)) {
            $res[$info[1]] = $info[2];
        }
    }
}

extract($res);


/*
```
     _
 ___/__) ,
(, /      __   _    _  __
  /    _(_/ (_(_/__(/_/ (_
 (_____      .-/
        )   (_/
```
*/

$d_f = [
    'dirs' => [
        $app_name . '/conf',
        $app_name . '/library/tool',
        $app_name . '/library/data',
        $app_name . '/model',
        $app_name . '/module/home/controller',
        $app_name . '/module/home/view',
        $app_name . '/plugin',
        'public/js',
        'public/css',
        'public/images',
    ],
    'files' => [
        $app_name . '/Bootstrap.php' => 'bootstrap_php',
        $app_name . '/conf/config.php' => 'config_php',
        $app_name . '/plugin/UserPlugin.php' => 'userplugin_php',
        $app_name . '/model/IndexModel.php' => 'indexmodel_php',
        $app_name . '/module/home/controller/IndexController.php' => 'indexcontroller_php',
        $app_name . '/module/home/view/index.html' => 'index_html',
        'public/test.php' => 'index_php',
    ]
];

$userplugin_php = <<<PHP
<?php

namespace plugin;

use Linger\Kernel\Plugin;
use Linger\Kernel\Request;
use Linger\Kernel\Response;

class UserPlugin extends Plugin
{
    function routerStartup(Request \$request, Response \$response)
    {
        // TODO: Implement routerStartup() method.
    }

    function routerShutdown(Request \$request, Response \$response)
    {
        // TODO: Implement routerShutdown() method.
    }

    function dispatchStartup(Request \$request, Response \$response)
    {
        // TODO: Implement dispatchStartup() method.
    }

    function dispatchShutdown(Request \$request, Response \$response)
    {
        // TODO: Implement dispatchShutdown() method.
    }

}
PHP;

$bootstrap_php = <<<PHP
<?php

namespace $app_name;

//use plugin\UserPlugin;

class Bootstrap
{
    public function _initSession(\Linger\Kernel\Dispatcher \$dispatcher)
    {
        session_start();
    }

//    public function _initPlugin(\Linger\Core\Dispatcher \$dispatcher)
//    {
//        \$user = new UserPlugin();
//        \$dispatcher->registerPlugin(\$user);
//    }
}
PHP;

$config_php = <<<PHP
<?php

return array(
    //debug and log config. If debug is enabled, the error message and exception will not
    //be written to the file, otherwise it will.
    'DEBUG'              => 1,
    'LOG_PATH'           => '/',
    'LOG_ARCHIVE_TYPE'   => 'Y/md/',
    //database config
    'DB'                 => [
        'TEST_MASTER' => [
            'DB_HOST'   => '127.0.0.1',
            'DB_USER'   => 'root',
            'DB_PWD'    => 'fendou2011',
            'DB_NAME'   => 'test',
            'DB_PREFIX' => '',
        ]
    ],
    //...
    'DEFAULT_MODULE'     => 'home',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION'     => 'index',
    'MODULE_ALLOW_LIST'  => ['index', 'admin', 'home'],
    'ROUTE'              => array(
        'home.html'        => 'home/index/index',
        'list/(\d+)\.html' => 'home/index/list/id/\1',
    ),
    'TPL_CACHE_TIME'     => 1000,
    'URL_MODEL'          => 2,
);
PHP;

$indexmodel_php = <<<PHP
<?php

namespace model;

use Linger\Kernel\Model;

class IndexModel extends Model
{
    /**
     * @var string
     */
    protected \$database = 'TEST_MASTER';

   
}
PHP;

$indexcontroller_php = <<<PHP
<?php

namespace home\controller;

use Linger\Kernel\Controller;

class IndexController extends Controller
{
    public function _init()
    {
        parent::_init();
    }

    public function indexAction()
    {
        \$this->display();
    }
}
PHP;

$index_html = <<<PHP
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>hello linger!</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <h1>Hello Linger!</h1>
</body>
</html>
PHP;


$index_php = <<<PHP
<?php

define('APP_ROOT', realpath(dirname(__FILE__) . '/../'));

define('APP_NAME', '$app_name');

require APP_ROOT . '/Linger/Linger.php';

app(APP_ROOT . '/$app_name/conf/config.php')->bootstrap()->run();

PHP;


array_shift($argv);

if (!isset($argv[0]) || $argv[0] == '--help' || $argv[0] == '-h') {
    echo '     _', PHP_EOL;
    echo ' ___/__) ,', PHP_EOL;
    echo '(, /      __   _    _  __', PHP_EOL;
    echo '  /    _(_/ (_(_/__(/_/ (_', PHP_EOL;
    echo ' (_____      .-/', PHP_EOL;
    echo '        )   (_/', PHP_EOL, PHP_EOL;

    echo 'Usage:', PHP_EOL;
    echo '  --help, -h                for help', PHP_EOL;
    echo '  --app_name=<name>         set app name', PHP_EOL;
    echo '  --version, -v             show linger frame\'s version', PHP_EOL;
    echo PHP_EOL;
} elseif ($argv[0] == '-v' || $argv[0] == '--version') {
    echo 'v1.0.1', PHP_EOL;
    echo PHP_EOL;
} else {
    if (isset($app_name) && !empty($app_name)) {
        $appPath = realpath(dirname(__FILE__) . '/../');
        if (is_writeable($appPath)) {
            foreach ($d_f['dirs'] as $v) {
                $dir = $appPath . '/' . $v;
                echo '正在创建目录', $dir, '......', PHP_EOL;
                if (mkdir($dir, 0777, true)) {
                    echo '创建目录', $dir, '成功！', PHP_EOL;
                } else {
                    echo '创建目录', $dir, '失败！', PHP_EOL;
                }
            }

            foreach ($d_f['files'] as $key => $v) {
                $file = $appPath . '/' . $key;
                echo '正在创建文件', $file, '......', PHP_EOL;
                if (false !== file_put_contents($file, $$v)) {
                    echo '创建文件', $file, '成功！', PHP_EOL;
                } else {
                    echo '创建文件', $file, '失败！', PHP_EOL;
                }
            }

            echo PHP_EOL;
            echo '项目初始化完成！', PHP_EOL;
        }
    }
}
