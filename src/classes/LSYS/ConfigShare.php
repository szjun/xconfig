<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
class ConfigShare{
	/**
	 * default config class
	 * @var string
	 */
	public static $config=\LSYS\Config\File::class;
	/**
	 * @var Config[]
	 */
	protected static $_cache=array();
	/**
	 * get share config object
	 * @param string $name
	 * @return \LSYS\Config
	 */
	public static function instance($name){
		if (!class_exists(static::$config,true)
			||!in_array('LSYS\Config',class_implements(static::$config))
			) static::$config=\LSYS\Config\File::class;
		if (!isset(self::$_cache[$name])) self::$_cache[$name] = new static::$config($name);
		return self::$_cache[$name];
	}
	/**
	 * create config from config object child
	 * @param Config $config
	 * @param string $key
	 * @return NULL|\LSYS\Config
	 */
	public static function sub_config(Config $config,$key){
		$name=$config->name().".".$key;
		if (!isset(self::$_cache[$name])){
			$_config=$config->get($key,null);
			if (is_array($_config)){
				$class=get_class($config);
				self::$_cache[$name] = new $class($name);
			}else return null;
		}
		return self::$_cache[$name];
	}
}