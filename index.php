<?php
/*
 * index.php单一入口文件
 * 导入配置文件，函数库文件，数据库文件，路由文件
 * 定义分发路由
 */
include "config/config.php";
include "lib/functions.php";
include "lib/db.php";
include "Route.php";

get("/index", "test@result");

get('/user', "test@getuser");

get('/pub', "test@ecrypt_test");

get('/db', "test@db_test");