# 配置层
> 封装此类库是为了实现功能与配置的分离
> 接口参考yaf的config接口

使用示例:
```
//-----------------------通过文件------------------------
//配置文件 :dome/config/aa.php
use LSYS\Config\File;
$c=File::instance("aa.a");
var_dump($c->get('fasd'));
```
```
//-----------------------可写的文件------------------------
//配置文件 :dome/config/aa.php
use LSYS\Config\FileRW;
$c = new FileRW("aa");
$c->set("a",array("fasd"=>"fasdf","faasdsd"=>"fadafdssdf"));
var_dump($c->get("a"));
```
```
//-----------------------INI文件------------------------
//配置文件 :dome/config/application.ini
//此方式跟yaf的config的ini类似
use LSYS\Config\INI;
//选择区段,区段间可继承,方便各种环境切换
INI::$section='cccc';
$c=INI::instance("application.application");
print_r($c->get('dispatcher'));
```
```
//-----------------------Redis------------------------
//使用此适配需先链接redis
//方式1
//你有存在一个已链接的redis对象
$redis= new Redis();//your redis obj
$redis->connect();//
LSYS\Config\Redis::set_redis($redis);
//方式2,redis配置存放文件或其他..
LSYS\Config\Redis::set_config(File::instance("redis.default"));

//使用
$config =LSYS\Config\Redis::instance("database.default");
var_dump($config->get("host"));
```

```
//---------------------数组配置----------------------------
//通过数组生成配置对象,方便你已有配置,需转换成config接口对象时使用
$config=new LSYS\Config\Arr("name",array(
'dome'=>'domevalue'
/*示例数组*/
));
```

> 如果你的配置需求上面的还不能满足,你实现下config接口吧...