<?php

namespace Abdelrahmanrafaat\RepositoriesMaker\Command;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Abdelrahmanrafaat\RepositoriesMaker\ModelsFinder;
use Abdelrahmanrafaat\RepositoriesMaker\RepositoriesCreator;

/**
 * @author Abdelrahman Rafaat Ahmed
 * make:repositories Command Handler
 */
class MakeRepositories extends Command
{   
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repositories {--parent=Model : parent class of your models , default is Model}
                                              {--directory=app   : directory that contains your models starting from the project root directory, default is app directory}
                                              {--nestedDirectories : add this option if your models directory contain models in nested directories , default is false}
                                              {--except= : Comma seperated list of models that will don`t have any repositories}
                                              {--only= : create repositories only for this Comma seperated list of models}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates models repositories.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $fileSystem)
    {   
        
        if( $this->repositoriesDirectoryExist($fileSystem) ){
            $this->info("Repositories Directory already exist in app directory."); 
            return;
        }

        if( $this->repositoriesServiceProviderExist($fileSystem) ){
            $this->info("Repositories Service Provider already exist.");
            return;
        }

        if($this->onlyAndExcept()){
            $this->info('Only and except options don`t work together , use one of them.');
            return;
        }

        if( !$fileSystem->isDirectory(base_path()."/".$this->option('directory')) && !empty($this->option('directory')) ){
            $this->info("Directory does not exist.");
            return;
        }

        $options = $this->getPassedOptions();
        $modelsFinder = new ModelsFinder( $options['directoryOptions'] , $options['filteringOptions'] );
        $models = $modelsFinder->find();

        if( empty($models) ){
            $this->info("No Models were created , make sure you provide the right options and directory option don`t start with directory sperator / Or \ ");
            return;
        }

        $repositoriesCreator = new RepositoriesCreator($models);
        $createdFiles = $repositoriesCreator->create();
        $this->logCreatedFiles($createdFiles);
    }

    /**
     * Gather the passed options into 2 arrays
     * @return Array Options Array.
     */
    private function getPassedOptions(){
        return [
            'directoryOptions' => [
                'path' => $this->option('directory'),
                'nestedDirectories' => $this->option('nestedDirectories')
            ],
            'filteringOptions' => [
                'only'   => $this->option('only'),
                'except' => $this->option('except'),
                'parent' => $this->option('parent')
            ]
        ];
    }

    /**
     * Log created files to the terminal.
     * @param  Array $createdFiles Contains the names of created files.
     * @return void
     */
    private function logCreatedFiles($createdFiles){
         foreach ($createdFiles as $file) {
            $this->info($file . ' was created.');
        }
        $this->info('Repositories Service Provider was created.');
    }
    
    /**
     * Check if the repositories directory already exist in the app folder.
     * @param  Object $fileSystem 
     * @return Boolean
     */
    private function repositoriesDirectoryExist($fileSystem){
        return $fileSystem->isDirectory(app_path()."/Repositories");
    }

    /**
     * Check if the repositoriesServiceProvider file already exist in the app/providers folder.
     * @param  Object $fileSystem 
     * @return Boolean
     */
    private function repositoriesServiceProviderExist($fileSystem){
        return $fileSystem->exists(app_path()."/Providers/RepositoriesServiceProvider.php");
    }

    /**
     * Check if only and except options are passed together.
     * @return Boolean
     */
    private function onlyAndExcept(){
        return $this->option('only') && $this->option('except');
    }

}
