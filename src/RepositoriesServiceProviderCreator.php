<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

class RepositoriesServiceProviderCreator extends AbstractStubCreator{

	public function __construct(){
		parent::__construct();
		$this->stubContent = $this->getStubContent();
	}

	/**
	 * Creates RepositoriesServiceProvider file.
	 * @param   $model model name(namespace\modelName)
	 * @return  void
	 */	
	public function createFromStub($models){
		$this->fileSystem->put(
			app_path().'/Providers/RepositoriesServiceProvider.php',
			$this->populateStub($models)
		);
	}
	
	/**
	 * Gets stup path.
	 * @return String stub path
	 */
	public function getStubPath(){
		return $this->stubsBasePath.'RepositoriesServiceProviderStub.stub';
	}

	/**
	 * search and replace operation on the stubContent.
	 * @param  Array $models name(namespace\modelName)
	 * @return String stub Content after replacement.
	 */
	public function populateStub($models){
		return strtr($this->stubContent , [
					'AppNamespace' => $this->appNamespace,
					'RepositoriesServiceProviderUses' => $this->getUses($models),
			   		'RepositoriesBindings' => $this->getBindings($models)
			   ]);
	}

	/**
	 * Generate the RepositoriesBindings section in stub.
	 * @param  String $model name(namespace\modelName)
	 * @return String $bindings
	 */
	public function getBindings($models){
		$bindings = "";
		foreach ($models as $model) {
			$bindings .='$this->app->bind('.$this->getRepositoryInterfaceName($model).'::class ,'.$this->getRepositoryName($model).'::class);'.PHP_EOL;
		}
		return $bindings;
	}

	/**
	 * Generate the RepositoryUses section in stub.
	 * @param  String $model name(namespace\modelName)
	 * @return String 
	 */
	public function getUses($models){
		$repositories = "";
		$repositoriesInterfaces = "";

		foreach ($models as $model) {
			$repositories .= 'use '.$this->appNamespace.'Repositories\\'.$this->getRepositoryName($model).';'.PHP_EOL;
			$repositoriesInterfaces .= 'use '.$this->appNamespace.'Repositories\\'.'Interfaces\\'.$this->getRepositoryInterfaceName($model).';'.PHP_EOL;
		}
		return $repositories.$repositoriesInterfaces;
	}
	
}