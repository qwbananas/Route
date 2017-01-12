<?php
include "lib/functions.php";
include "lib/Database.php";
include "Route.php";

//定义常量
define("CONFIG_PATH", str_replace('\\', '/', __DIR__));
define("PATH",substr(CONFIG_PATH,0,strrpos(CONFIG_PATH,'/')));
define("CONTROLLER_PATH", PATH."/controller/");
//数据库配置
define('DB_TYPE','mysqli');//数据库类型
define('DB_HOST','localhost');//数据库主机
define('DB_USER','root');//数据库用户名
define('DB_PWD','123456');//数据库密码
define('DB_PORT',3306);//数据库端口，mysql默认是3306
define('DB_NAME','test');//数据库名
define('DB_CHARSET','utf8');//数据库编码
define('DB_PCONNECT',false);//true表示使用永久连接，false表示不使用永久连接，一般不使用永久连接

