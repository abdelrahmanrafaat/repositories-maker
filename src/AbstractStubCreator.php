<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\AppNamespaceDetectorTrait;

/**
 * @author Abdelrahman Rafaat 
 * Contains helper methods for creating stubs.
 */
abstract class AbstractStubCreator{
	use AppNamespaceDetectorTrait;

	/**
	 * Where the stubs are located
	 * @var string
	 */
	protected $stubsBasePath;

	/**
	 * Laravel project app namespace
	 * @var string
	 */
	protected $appNamespace;

	/**
	 * deals with files and directories.
	 * @var object
	 */
	protected $fileSystem;

	/**
	 * Content of every stub.
	 * @var String.
	 */
	public $stubContent;

	public function __construct(){
		$this->stubsBasePath = __DIR__."/Stubs/";
		$this->appNamespace  = $this->getAppNamespace(); 
		$this->fileSystem = new Filesystem;
	}

	/**
	 * creates file/files from stub.
	 * @param  String/Array $model name(namespace\modelName)
	 */
	abstract public function createFromStub($model);

	/**
	 * gets stup path.
	 * @return String 
	 */
	abstract public function getStubPath();

	/**
	 * search and replace operation on the stubContent.
	 * @param  String $model name(namespace\modelName)
	 * @return String stub Content after replacement.
	 */
	abstract public function populateStub($model);

	/**
	 * Get the model instance name.
	 * by getting the base name of the model and lowerCase the first char.
	 * @param  String $model name(namespace\modelName)
	 * @return String instance name
	 */
	public function getModelInstanceName($model){
		return lcfirst( class_basename($model) );
	}

	/**
	 * Get the model repository name.
	 * by getting the base name of the model then making it plural and upperCase the first letter.
	 * @param  String $model name(namespace\modelName)
	 * @return String repository name
	 */
	public function getRepositoryName($model){
		return ucfirst( str_plural(class_basename($model)) )."Repository";
	}

	/**
	 * Get the model repository interface name.
	 * @param  String $model name(namespace\modelName)
	 * @return String instance name
	 */
	public function getRepositoryInterfaceName($model){
		return $this->getRepositoryName($model)."Interface";
	}
	
	/**
	 * Get the stubContent.
	 * if stubContent is already set we return it.
	 * and if it`s not set we set it and then return it.
	 * @return String stubContent
	 */
	protected function getStubContent(){
		if($this->stubContent != NULL){
			return $this->stubContent;
		}
		return $this->fileSystem->get($this->getStubPath());
	}

}