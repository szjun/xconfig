<?php

use LSYS\Config\File;

include __DIR__."/Bootstarp.php";
//方式1
//你有存在一个已链接的redis对象
// $redis= new Redis();//your redis obj
// $redis->connect();//
// LSYS\Config\Redis::set_redis($redis);
//方式2,redis配置存放文件或其他..
LSYS\Config\Redis::set_config(new File("redis.default"));

//得到一个配置对象
$config = new LSYS\Config\Redis("database.default");
var_dump($config->get("host"));



