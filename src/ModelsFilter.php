<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

/**
 * @author Abdelrahman Rafaat
 */
class ModelsFilter{
	/**
	 * Array of filtering options['parent','except','only'] 
	 * @var Array
	 */
	protected $filteringOptions;


	public function __construct($filteringOptions){
		$this->filteringOptions = $filteringOptions;
	}

	/**
	 * Apply filters on unfilterd classes. 
	 * @param  Array $unfilterdClasses  classes names(namespace\className)
	 * @return Array filtered classes.
	 */
	public function applyFilters($unfilterdClasses){
		if($this->checkExcept()){
			$unfilterdClasses = $this->filterByExcept($unfilterdClasses);
		}

		if($this->checkOnly()){
			$unfilterdClasses = $this->filterByOnly($unfilterdClasses);
		}

		return ( $this->checkParent() ) ? $this->filterByParent($unfilterdClasses) : $unfilterdClasses;
	}

	/**
	 * is the parent filter set or not
	 * @return Boolean
	 */
	public function checkParent(){
		return ( !empty($this->filteringOptions['parent']) ) ? true : false;
	}

	/**
	 * Filter the classes by parent filter.
	 * @param  Array $unfilterdClasses  classes names(namespace\className)
	 * @return Array classes filtered by parent filter
	 */
	public function filterByParent($unfilterdClasses){
		return array_filter($unfilterdClasses , function($class){
            if( class_basename(get_parent_class($class)) == class_basename($this->filteringOptions['parent']) ){
                return $class;
            }
    	});
	}

	/**
	 * is the except filter set or not
	 * @return Boolean
	 */
	public function checkExcept(){
		return ( !empty($this->filteringOptions['except']) ) ? true : false;
	}

	/**
	 * Filter the classes by except filter.
	 * @param  Array $unfilterdClasses  classes names(namespace\className)
	 * @return Array classes filtered by except filter
	 */
	public function filterByExcept($unfilterdClasses){
		$exceptBaseClassesArray = $this->getBaseClasses( trim($this->filteringOptions['except']) );

		return array_filter($unfilterdClasses , function($class) use ($exceptBaseClassesArray){
            if( ! in_array(class_basename($class) , $exceptBaseClassesArray) ){
                return $class;
            }
    	});
	}

	/**
	 * is the only filter set or not
	 * @return Boolean
	 */
	public function checkOnly(){
		return ( !empty($this->filteringOptions['only']) ) ? true : false;
	}

	/**
	 * Filter the classes by only filter.
	 * @param  Array $unfilterdClasses  classes names(namespace\className)
	 * @return Array classes filtered by only filter
	 */
	public function filterByOnly($unfilterdClasses){
		$onlyBaseClassesArray = $this->getBaseClasses( trim($this->filteringOptions['only']) );

		return array_filter($unfilterdClasses , function($class) use ($onlyBaseClassesArray){
            if( in_array(class_basename($class) , $onlyBaseClassesArray) ){
                return $class;
            }
    	});
	}

	/**
	 * get base classes of a string(comma seperated string)
	 * @param  String $string 
	 * @return Array base classes array
	 */
	public function getBaseClasses($string){
		$array = explode( ',' , $string );
		$baseClassesArray = [];
		for($i = 0 ; $i < count($array) ; $i++){
			$baseClassesArray[] = class_basename( trim($array[$i]) );
		}
		return $baseClassesArray;
	}

}
