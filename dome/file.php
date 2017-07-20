<?php
use LSYS\Config\File;
include __DIR__."/Bootstarp.php";
$c=new File("aa.a");
var_dump($c->get('fasd'));
