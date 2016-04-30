<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

class RepositoryInterfaceCreator extends AbstractStubCreator{

	public function __construct(){
		parent::__construct();
		$this->stubContent = $this->getStubContent();
	}

	/**
	 * Creates Repository Interface file.
	 * @param  $model model name(namespace\modelName)
	 * @return void
	 */
	public function createFromStub($model){
		$this->fileSystem->put(
			app_path().'\/Repositories\/'.$this->getRepositoryName($model).'.php',
			$this->populateStub($model)
		);
	}
	
	/**
	 * Gets stup path.
	 * @return String stub path
	 */
	public function getStubPath(){
		return $this->stubsBasePath."RepositoryInterfaceStub.stub";
	}

	/**
	 * search and replace operation on the stubContent.
	 * @param  String $model name(namespace\modelName)
	 * @return String stub Content after replacement.
	 */
	public function populateStub($model){
		return strtr($this->stubContent , [
					'AppNamespace' => $this->appNamespace,
					'RepositoryInterfaceName' => $this->getRepositoryInterfaceName($model)
			   ]);
	}
	
}