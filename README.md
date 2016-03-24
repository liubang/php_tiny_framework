#simple php frame
---

##简介
这就是一个简单的php框架.用来教学用,帮助大家深入理解php框架的原理.

##文件结构
```
.
├── app
│   ├── Bootstrap.php
│   ├── conf
│   │   └── config.php
│   ├── model
│   │   └── UserModel.class.php
│   └── module
│       └── home
│           ├── controller
│           │   └── IndexController.class.php
│           └── view
│               ├── index.html
│               └── list.html
├── Linger
│   ├── Core
│   │   ├── App.php
│   │   ├── Controller.php
│   │   ├── Hook.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Router.php
│   │   └── View.php
│   ├── Driver
│   │   ├── Cache
│   │   │   ├── CacheDriver.php
│   │   │   ├── Memcached.php
│   │   │   └── Redis.php
│   │   └── Db
│   │       ├── DbDriver.php
│   │       ├── MySql.php
│   │       └── Oracle.php
│   ├── Libs
│   └── Linger.php
├── public
│   └── index.php
└── README.md

```

##使用方法

类似于ThinkPHP, 配置好虚拟站点,单一入口.
根目录在`${SITE}/public/`目录下

模块在`app/module/`下,用文件夹区分
控制器在`app/module/${module}/controller`下,所有的controller建立方法跟ThinkPHP类似
视图在`app/module/${module}/view`下,所有的视图中暂时使用原生php作为模板引擎, 如果想自定义模板引擎大家可以扩展.

