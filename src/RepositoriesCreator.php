<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

use Illuminate\Filesystem\Filesystem;
use Abdelrahmanrafaat\RepositoriesMaker\RepositoryCreator;
use Abdelrahmanrafaat\RepositoriesMaker\RepositoryInterfaceCreator;
use Abdelrahmanrafaat\RepositoriesMaker\RepositoriesServiceProviderCreator;

/**
 * @author Abdelrahman Rafaat
 * Wrapper Class for creating repositories , repositoriesInterfaces and repositoriesServiceProvider
 */
class RepositoriesCreator {

	/**
	 * Array of models names(namespace\modelName)
	 * @var Array
	 */
	protected $models;


	/**
	 * Object to create repositories.
	 * @var RepositoryCreator Object
	 */
	protected $repositoryCreator;


	/**
	 * Object to create repositories Interfaces.
	 * @var RepositoryCreator Object
	 */
	protected $repositoryInterfaceCreator;
	

	/**
	 * Object to create repositories Service Provider.
	 * @var RepositoryCreator Object
	 */
	protected $repositoriesServiceProviderCreator;
	

	/**
     * deals with files and directories.
     * @var object
     */
	protected $fileSystem;

	public function __construct($models){
		$this->models = $models;
		$this->fileSystem = new Filesystem;
		$this->repositoryCreator = new RepositoryCreator;
		$this->repositoryInterfaceCreator = new RepositoryInterfaceCreator;
		$this->repositoriesServiceProviderCreator = new RepositoriesServiceProviderCreator;
	}

	/**
	 * handle the process of creating repositories
	 * @return Array generated respositories names
	 */
	public function create(){
		$this->createRepositoriesDirectory();

		foreach ($this->models as $model) {
			$this->repositoryCreator->createFromStub($model);
			$this->repositoryInterfaceCreator->createFromStub($model);
		}
		
		$this->repositoriesServiceProviderCreator->createFromStub($this->models);

		return $this->getCreatedRepositoriesNames();
	}

	/**
	 * Create Repositories Directory and Repositories Interface
	 * @return void
	 */
	public function createRepositoriesDirectory(){
		$this->fileSystem->makeDirectory(app_path()."/Repositories");
		$this->fileSystem->makeDirectory(app_path()."/Repositories/Interfaces");
	}

	/**
	* Get the created Repositories , RepositoriesInterfaces names 
	* @return Array files Names
	*/
	public function getCreatedRepositoriesNames(){
		$repositoriesPath = app_path().'/Repositories';
		$createdFiles     = $this->fileSystem->allFiles($repositoriesPath);
		$filesNames = [];
		foreach ($createdFiles as $file) {
			$filesNames[] = $this->fileSystem->name($file);
		}
		return $filesNames;
	}

} 