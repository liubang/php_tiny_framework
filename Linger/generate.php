#!/usr/bin/env php
<?php


function parseParams($arr)
{
    $res = [];
    if (is_array($arr)) {
        foreach($arr as $v) {
            if (preg_match('/^\-\-([a-zA-Z_]+?)=(.*?)$/', $v, $info)) {
                $res[$info[1]] = $info[2];
            }
        }
    }

    return $res;
}
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
    echo '  --app_namt=<name>         set app name', PHP_EOL;
    echo '  --version, -v             show linger frame\'s version', PHP_EOL;
    echo PHP_EOL;
} elseif ($argv[0] == '-v' || $argv[0] == '--version') {
    echo 'v1.0.1', PHP_EOL;
    echo PHP_EOL;
} else {
    $params = parseParams($argv);
}
