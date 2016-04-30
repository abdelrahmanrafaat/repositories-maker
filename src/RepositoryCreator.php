<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

class RepositoryCreator extends AbstractStubCreator{

	public function __construct(){
		parent::__construct();
		$this->stubContent = $this->getStubContent();
	}

	/**
	 * Creates Repository file.
	 * @param   $model model name(namespace\modelName)
	 * @return  void
	 */
	public function createFromStub($model){
		$this->fileSystem->put(
			app_path().'/Repositories/Interfaces/'.$this->getRepositoryInterfaceName($model).'.php',
			$this->populateStub($model)	
		);
	}
	
	/**
	 * Gets stup path.
	 * @return String stub path
	 */
	public function getStubPath(){
		return $this->stubsBasePath."RepositoryStub.stub";
	}

	/**
	 * search and replace operation on the stubContent.
	 * @param  String $model name(namespace\modelName)
	 * @return String stub Content after replacement.
	 */
	public function populateStub($model){
		return strtr($this->stubContent , [
					'AppNamespace' => $this->appNamespace,
					'RepositoryInterfaceName' => $this->getRepositoryInterfaceName($model),
					'RepositoryUses' => $this->getUses($model),
					'RepsitoryName'  => $this->getRepositoryName($model),
					'ModelInstance'  => $this->getModelInstanceName($model),
					'ModelName' => class_basename($model)
			   ]);
	}

	/**
	 * Generate the RepositoryUses section in stub.
	 * @param  String $model name(namespace\modelName)
	 * @return String        RepositoryUses
	 */
	public function getUses($model){
		return 'use '.$this->appNamespace.class_basename($model).';'.PHP_EOL
			  .'use '.$this->appNamespace.'Repositories\Interfaces\\'.$this->getRepositoryInterfaceName($model).';'.PHP_EOL;
	}

}