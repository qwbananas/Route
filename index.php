<?php
/*
 * index.php单一入口文件
 * 导入配置文件，函数库文件，数据库文件，路由文件
 * 定义分发路由
 */
include "config/config.php";

get("/index/{username}/user/{userid}", "test@test");

get("/user/(.*?)/(.*?)", "test@test2");
