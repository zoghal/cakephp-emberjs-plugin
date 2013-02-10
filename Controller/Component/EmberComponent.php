<?php
class EmberComponent extends Component{
	
	

	
	/**
	 * EmberComponent::__construct()
	 * 
	 * @param mixed $collection
	 * @param mixed $settings
	 * @return void
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge($this->settings, (array)$settings);
		$this->Controller = $collection->getController();
		parent::__construct($collection, $settings);
	}
	
	
	public function startup(Controller $controller){
	  if (isset($controller->request->params['prefix']) and $controller->request->params['prefix'] == 'api' ){
	  		$controller->RequestHandler->renderAs($controller,'json');
	  		//$controller->autoRender = false;
	  }	
	}
			
	/**
	 * EmberComponent::initialize()
	 * 
	 * @param mixed $controller
	 * @return void
	 */
	public function serializer($data,$map = array()) {
		$def = array(
			'model' => null,
			'bind' => array()	
		);
		$singltion= false;
		
		if(!isset($data[0])){
		 $singltion = true;
		 $data = array( $data );
	    }
	    $out = array();
		foreach($data as $key => $val ){
			
			$rec = $val[$map['model']];
			foreach($map['bind'] as $alias => $path ){
				$x = $val[$path];
		//		if(!isset($x[0]) == 1) $x = array($x);
				$rec[$alias] = $x;
			}		
			$out[] = $rec;
		}
			if(!$singltion){
				$alias =  Inflector::tableize($map['model']);		
			} else{
				$alias = strtolower($map['model']);
				$out = $out[0];
			} 
		  return array($alias => $out);

	}

	public function seriali111zer($data,$map = array()) {
		$def = array(
			'model' => null,
			'bind' => array()	
		);
		$singltion= false;
		
		if(!isset($data[0])){
		 $singltion = true;
		 $data = array( $data );
	    }
	    $out = array();
		foreach($data as $key => $val ){
			
			$rec = $val[$map['model']];
			foreach($map['bind'] as $alias => $path ){
				$x = $val[$path];
		//		if(!isset($x[0]) == 1) $x = array($x);
				$rec[$alias] = $x;
			}		
			$out[] = $rec;
		}
			if(!$singltion){
				$alias =  Inflector::tableize($map['model']);		
			} else{
				$alias = strtolower($map['model']);
				$out = $out[0];
			} 
		  return array($alias => $out);

	}
	

	
}