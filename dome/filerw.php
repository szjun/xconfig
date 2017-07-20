<?php
use LSYS\Config\FileRW;
include __DIR__."/Bootstarp.php";
$c = new FileRW("aa");
$c->set("a",array("fasd"=>"fasdf","faasdsd"=>"fadafdssdf"));
var_dump($c->get("a"));
