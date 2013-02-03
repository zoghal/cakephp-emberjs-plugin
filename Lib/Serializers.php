<?php


class Serializers {

	private $data = array();
	private $include = array();
	private $embed = false; 
	private $config = array();
	private $output = array();
	private $configDef = array(
				'embed' => false,
				'fields' => false,
				'include' => array(),
			);
	private $includeDef = array(
				'fields' => false, 
				'embed' => false,
				'key' => false,
				'include' => false,
			);	
	
	
	/**
	 * Serializers::__construct()
	 * 
	 * @param mixed $data
	 * @param mixed $config
	 * @return void
	 */
	public function __construct($data,$config,$include=false) {
					
		$this->data = $data;
		$this->config = $config;
		
	}


	public function  convert(){
		
		if ( !is_array($this->data) or empty($this->data)){
			return array();
		}
		
		$this->checkConfig($this->config);
		$this->checkInclude($this->include);
		
		$res = $this->scan($this->data);
		return $this->output;
	}

	
	private function scan($data,$level=0) {
		if (!isset($data[0])){
			$data = array($data);
		}
		$model = $this->config['model'];
		foreach($data as $key => $val){
		//	if (isset($val[0])){
//				return $this->scan($val,0);
//			}
	
			$res = $val[$model];
			unset($val[$this->config['model']]);
			$res = Hash::merge($res,$this->relations($val,$this->include));
			$this->output[] = $res;
			//$val[$key] = 
		}
		return $data;			
	}


	/**
	 * Serializers::normal()
	 * 
	 * @param mixed $data
	 * @param mixed $level
	 * @return
	 */
	private function relations( $data,$config){
		$keys = (!empty($config))? array_keys($config) : array_keys($data);
		$out =array();
		foreach( $keys as $key){
		  if (!is_array($data[$key]))  continue;
		  
		  $alias = (isset($data[$key][0]) )?  Inflector::tableize($key) : strtolower($key) ;
		  $out[$alias] = $data[$key];  	
		}
		
		return $out ;
	}
	
	
	
	
	/**
	 * Serializers::checkConfig()
	 * 
	 * @param mixed $config
	 * @return void
	 */
	private function checkConfig($config){
		$config = Hash::merge($this->configDef,$config);
		$this->include = $config['include'];
		unset($config['include']);
    	$this->config= $config;
	}


	
	/**
	 * Serializers::checkInclude()
	 * 
	 * @param mixed $include
	 * @param bool $deep
	 * @return
	 */
	private function checkInclude($include,$deep = false){
		if (is_string($include)){
			$include = array($include);
		}
		$include = Hash::normalize($include);

		foreach($include as $key => $val){
			$val = Hash::merge($this->includeDef,$val);
			// if Embed in config is true all include array set true
			if($this->config['embed']){
				$val['embed'] = true;
			}
			if ($val['include']){
				$val['include'] = $this->checkInclude($val['include'],true);
			}
			$include[$key] = $val;

		}
		
		
		if (!$deep){
			$this->include = $include;
		}
    	return $include;
	}


	
	public	function err($str){
		return array('Error',$str);
	}
}