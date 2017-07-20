<?php
use LSYS\Config\INI;
include __DIR__."/Bootstarp.php";
INI::$section='cccc';//选择区段
$c=INI::instance("application.application");
print_r($c->get('dispatcher'));
