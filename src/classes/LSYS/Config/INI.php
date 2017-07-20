<?php
/**
 * lsys config
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Config;
use LSYS\Config;
class INI extends File{
	public static $section=null;
	protected function _init(){
		$group= explode('.', $this->_name);
		$path=array_shift($group);
		foreach (self::$_dirs as $v){
			$file=$v.$path.".ini";
			if (is_file($file))break;
			else unset($file);
		}
		if (!isset($file)) return false;
		if(!isset(self::$_cahe[$file])){
			if (!empty(self::$section)){
				$ini=parse_ini_file($file,true);
				$p=self::$section;
				$val=array();
				$_tmp=array();
				foreach ($ini as $k=>$v){
					$o=strpos($k, ':');
					if ($o===false){
						if ($k==$p){
							$val=$v;
							break;
						}else{
							$_tmp[$k]=$v;
						}
					}else{
						$kk=trim(substr($k, 0,$o));
						$pkk=trim(substr($k, $o+1));
						$_tmp[$kk]=array_merge(isset($_tmp[$pkk])?$_tmp[$pkk]:array(),$v);
						if ($kk==$p){
							$val=$_tmp[$kk];
							break;
						}
					}
				}
				unset($_tmp);
			}else{
				$val=parse_ini_file($file);
			}
			$_cache=array();
			foreach ($val as $k=>$v){
				$__cache=&$_cache;
				$ks=explode(".",$k);
				while (count($ks)>0){
					$tk=array_shift($ks);
					if(!isset($__cache[$tk])||!is_array($__cache[$tk]))$__cache[$tk]=array();
					$__cache=&$__cache[$tk];
				}
				$__cache=$v;
			}
			self::$_cahe[$file]=$_cache;
		}
		$this->_file=$file;
		return $group;
	}
}
