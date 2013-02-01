<?php

class EmberBehavior extends ModelBehavior {


	private $paths = array();
	private $embeded = array();


	/**
	 * SerializerBehavior::setup()
	 * 
	 * @param mixed $Model
	 * @param mixed $settings
	 * @return void
	 */
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array( 
				'Serializer' => array( 
					'status' => false , 
					'embeded' => false
				)
			);
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
	}


	/**
	 * SerializerBehavior::serializer()
	 * 
	 * @param mixed $Model
	 * @param bool $status
	 * @return void
	 */
	public function serialized(Model $Model,$status = true,$embeded = false) {
		$this->settings[$Model->alias]['Serializer']['status'] = $status;
		$this->settings[$Model->alias]['Serializer']['embeded'] = $embeded;

		if ( $status == false ) {
			$this->settings[$Model->alias]['Serializer']['embeded'] = false;
		}
	}	
	
	/**
	 * SerializerBehavior::beforeFind()
	 * 
	 * @param mixed $Model
	 * @param mixed $query
	 * @return
	 */
	public function beforeFind(Model $Model, $query) {
		$this->settings[$Model->alias]['Serializer']['model'] = $Model->alias;
		if (isset($query['serialized'])){
	
			if ( is_array($query['serialized']) ){
				$embeded = (isset($query['serialized']['embeded']))? $query['serialized']['embeded'] : false;
				$this->settings[$Model->alias]['Serializer']['embeded'] = $embeded;
			}else{
				$this->settings[$Model->alias]['Serializer']['status'] = true;
				$this->settings[$Model->alias]['Serializer']['embeded'] = false;
						
			}
		}
		return true;
	}	

	/**
	 * SerializerBehavior::afterFind()
	 * 
	 * @param mixed $Model
	 * @param mixed $results
	 * @param mixed $primary
	 * @return
	 */
	public function afterFind(Model $Model, $results, $primary) {
		$options = $this->settings[$Model->alias]['Serializer'];
		if($primary && $options['status'] ){
			$results = $this->__convert($Model,$results,$options);
		}
		return $results;
	}	
	
	

	

	
	
	
	protected function __convert(Model $Model,$results,$options,$parent = false){
	
		$this->embeded[$Model->alias] = array();	
		$method = ($options['embeded'])? '__serializerEmbeded': '__serializer'	;
		foreach( $results as $key => $rec ){
		 	$results[$key] = $this->{$method}($Model,$rec);
		}
		return $results;
		
	}
	protected function __serializer(Model $Model, $record){
		
		
		$out = $record[$Model->alias];
		unset($record[$Model->alias]);
		$rels = $Model->getAssociated();		

		foreach($record as $modelName => $values ){
			rlog($modelName);
			if($rels[$modelName] == 'belongsTo'){
				$out[strtolower($modelName)] = $values;
			}else{
			 $out[Inflector::tableize($modelName)] = $values;
				
			}
		}
		return $out;
		//$out = $data[]
				
	}


}
