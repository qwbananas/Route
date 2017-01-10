<?php
//定义常量
define("CONFIG_PATH", str_replace('\\', '/', __DIR__));
define("PATH",substr(CONFIG_PATH,0,strrpos(CONFIG_PATH,'/')));
define("CONTROLLER_PATH", PATH."/controller/");