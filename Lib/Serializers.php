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

	


	public function  convert($data=false,$options= false){
		if (!$data) {
			return $data;
		};
		$isMany = false;
		if( !isset($data[0]) ) {
			$data = array($data);	
		}		
		
		foreach($data as $key => $val){
				rlog($val,'xxx');
			
				$keysss = array(array_keys($val));
				$model = key($val);
				if( is_int($key and !Hash::numeric()) ){
									rlog( $model);

				}
		}


		return $data;
	}


	protected function CakeToEntity($data){
		reset($data);
		$key = key($data);
		$out = array();
		$out[$key] = $data[$key];
		unset($data[$key]);
		$out = Hash::merge( $out ,$data );
	//	rlog($out);
		pr($out);
		return $out;
	}



	public function scan($data,$level=0) {
		rlog('asd');

		    
//	
//		$v = function($x,$vz){
//			//pr($x);
//			//pr($vz);
//			return true;
//		};
//		$key = array_reduce($data,$v);
//		rlog($key);
		
//		foreach($data as $key => $val){
//		//	if (isset($val[0])){
////				return $this->scan($val,0);
////			}
//	
//			$res = $val[$model];
//			unset($val[$this->config['model']]);
//			$res = Hash::merge($res,$this->relations($val,$this->include));
//			$this->output[] = $res;
//			//$val[$key] = 
//		}
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



	private function allIndexIsNumbric( $data ){
		
	}

	
	public	function err($str){
		return array('Error',$str);
	}
}