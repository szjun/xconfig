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
class FileRW extends File implements Config{
	protected $_is_write=false;
	protected $_is_change=false;
	protected static $_file_ref=array();
	public function __construct($name){
		$this->_load=false;
		$this->_name=$name;
		$group=$this->_init();
		if($group===false)return;
		$this->_node=&self::$_cahe[$this->_file];
		$this->_load=true;
		while (count($group)){
			$node=array_shift($group);
			if(!isset($this->_node[$node])||!is_array($this->_node[$node])){
				$this->_load=false;
				$this->_node[$node]=array();
			}
			$this->_node=&$this->_node[$node];
		}
		$this->_is_write=is_writable($this->_file);
		if (isset(self::$_file_ref[$this->_file]))self::$_file_ref[$this->_file]++;
		self::$_file_ref[$this->_file]=1;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::set()
	 */
	public function set ($key,$value = NULL){
		if(!isset(File::$_cahe[$this->_file])){
			$file=array_shift(explode(".",$this->_name));
			throw new Exception(__("config :file not exist",array("file"=>$file)));//文件不支持写入操作
		}
		if (!$this->_is_write) throw new Exception(__("file :file can't be write",array("file"=>$this->_file)));//文件不支持写入操作
		$keys=explode(".",$key);
		$config=&$this->_node;
		foreach ($keys as $v){
			if(!isset($config[$v]))$config[$v]=array();
			$config=&$config[$v];
		}
		if ($config!=$value){
			$config=$value;
			$this->_is_change=true;
		}
		$this->_load=$this->readonly()?$this->_load:true;
	}
	public function __destruct(){
		if (!$this->_is_change)return true;
		if(!isset(File::$_cahe[$this->_file]))return true;
		self::$_file_ref[$this->_file]--;
		if (self::$_file_ref[$this->_file]<=0){
			$this->_write($this->_file,File::$_cahe[$this->_file]);
		}
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Config::readonly()
	 */
	public function readonly (){
		return !$this->_is_write;
	}
	/**
	 * 写配置入文件
	 * @param string $filename
	 * @param array $data
	 */	
	protected function _write($filename,$data){
		ob_start();
		echo "<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */\nreturn ";
		var_export($data);
		echo ";";
		$data=ob_get_contents();
		ob_end_clean();
		if(@file_put_contents($filename, $data))return true;
		throw new Exception(__("can't write config to :file",array('file'=>$filename)));
	}
}
