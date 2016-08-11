<?php

define('APP_ROOT', realpath(dirname(__FILE__) . '/../'));

define('APP_NAME', 't1');

require APP_ROOT . '/Linger/Linger.php';

app(APP_ROOT . '/t1/conf/config.php')->bootstrap()->run();
