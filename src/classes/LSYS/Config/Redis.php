<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Config;
use LSYS\Config;
use LSYS\Exception;
class Redis implements Config{
	/**
	 * redis save config key
	 * @var string
	 */
	public static $save='lsys_config';
	/**
	 * @var \Redis
	 */
	protected static $redis;
	public static function set_redis(\Redis $redis){
		self::$redis=$redis;
	}
	public static function set_config(Config $config){
		$redis=new \Redis();
		$_config=$config->as_array()+array(
			'host'             	=> 'localhost',
			'port'             	=> '6379',
			'timeout'			=> '60',
			'db'				=> NULL,
		);
		$redis = new \Redis();
		try{
			$redis->connect($_config['host'],$_config['port'],$_config['timeout']);
			$redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
			if (isset($_config['auth']))$redis->auth($_config['auth']);
			if (isset($_config['db']))$redis->select($_config['db']);
		}catch (\Exception $e){
			throw new Exception($e->getMessage().strtr(" [Host:host Port:port]",array("host"=>$_config['host'],"port"=>$_config['port'])),$e->getCode());
		}
		self::$redis=&$redis;
	}
	protected $_load;
	protected $_key;
	protected $_node=array();
	/**
	 * php file config
	 * @param string $name
	 */
	public function __construct ($name){
		if (self::$redis==null||!self::$redis->isConnected()) throw new Exception(__("plase first set redis object"));
		$this->_load=false;
		$this->_name=$name;
		$names=explode(".",$name);
		$this->_key=array_shift($names);
		$data=self::$redis->hGet(self::$save,$this->_key);
		$data&&$data=@json_decode($data,true);
		if (!$data) return ;
		$this->_node=&$data;
		$this->_load=true;
		while (count($names)){
			$node=array_shift($names);
			if(isset($this->_node[$node])){
				$this->_node=&$this->_node[$node];
			}else{
				$this->_load=false;
				$this->_node=array();
				break;
			}
		}
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::loaded()
	 */
	public function loaded(){
		return $this->_load;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::name()
	 */
	public function name(){
		return $this->_name;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::get()
	 */
	public function get($key,$default=NULL){
		$group= explode('.', $key);
		$t=$this->_node;
		while (count($group)){
			$node=array_shift($group);
			if(isset($t[$node])){
				$t=&$t[$node];
			}else return $default;
		}
		return $t;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::as_array()
	 */
	public function as_array (){
		return $this->_node;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::set()
	 */
	public function set ($key,$value = NULL){
		$keys=explode(".",$key);
		$config=&$this->_node;
		foreach ($keys as $v){
			if(!isset($config[$v]))$config[$v]=array();
			$config=&$config[$v];
		}
		if ($config!=$value){
			$config=$value;
		}
		$this->_load=true;
		return self::$redis->hset(self::$save,$this->_key,json_encode($this->_node));
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::readonly()
	 */
	public function readonly (){
		return false;
	}
}
