<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
interface Config{
	/**
	 * config
	 * @param string $name
	 */
	public function __construct ($name);
	/**
	 * return config name
	 * @return string
	 */
	public function name();
	/**
	 * is loaded config
	 * @return bool
	 */
	public function loaded();
	/**
	 * get config
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get ($key,$default=NULL);
	/**
	 * set config
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function set ($key,$value = NULL);
	/**
	 * check config is readonly
	 * @return bool
	 */
	public function readonly ();
	/**
	 * to config is array
	 * @return array
	 */
	public function as_array ();
}