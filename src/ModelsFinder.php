<?php
namespace Abdelrahmanrafaat\RepositoriesMaker;

use Illuminate\Filesystem\Filesystem;
use Abdelrahmanrafaat\RepositoriesMaker\ModelsFilter;

class ModelsFinder {

    /**
     * deals with files and directories.
     * @var object
     */
	protected $fileSystem;

    /**
     * Array of directories options['path','nestedDirectories']
     * @var Array
     */
	protected $directoryOptions;

    /**
     * Object of ModelsFilter Class.
     * @var Object
     */
    private   $modelsFilter;

	public function __construct($directoryOptions , $filteringOptions){
		$this->fileSystem   = new Filesystem;
        $this->modelsFilter = new ModelsFilter($filteringOptions);
        $this->directoryOptions = $directoryOptions;
		$this->directoryOptions['path']  = $this->setModelsDirectory($directoryOptions['path']);
	}

    /**
     * Find classes in a specific Directory and filter them.
     * @return Array sorted filtered classes names(namespace\className)
     */
	public function find(){
        $unfilterdClasses = $this->getDirectoryClasses();
		return $this->sortModels( @$this->modelsFilter->applyFilters($unfilterdClasses) );
	}

    /**
     * Filter an array of file names into php only files.
     * @return Array 
     */
	public function filterByPhpExtension(){
        return array_filter($this->getDirectoryFiles() , function($file){
            if($this->fileSystem->extension($file) == 'php'){
                return $file;
            }
        });
    }

    /**
     * Get Files on specified directory option.
     * if nestedDirectory option is set we get all files recursively.
     * @return [type] [description]
     */
    public function getDirectoryFiles(){
        return $this->checkNestedDirectory() ?
            $this->fileSystem->allFiles($this->directoryOptions['path']) 
            : $this->fileSystem->files($this->directoryOptions['path']);
    }

    /**
     * is the nestedDirectory filter set or not.
     * @return Boolean
     */
    public function checkNestedDirectory(){
        return $this->directoryOptions['nestedDirectories'];
    }

    private function setModelsDirectory($directory){
        if($directory != 'app'){
            if( $this->isFirstCharDirectorySeperator($directory) ){
                throw new \Exceptions("Directory name can`t start with any directory seperators / Or \ .... please remove them");
            }
            return base_path()."/".$directory;
        }
        return app_path();
    }

    /**
     * Get the classes names(namespace\className) from directory.
     * @return Array classes names.
     */
    public function getDirectoryClasses(){
        $classes = [];
        $files = $this->filterByPhpExtension();

        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $namespace = $class = "";
            $getting_namespace = $getting_class = false;

            foreach (token_get_all($contents) as $token) {

                if (is_array($token) && $token[0] == T_NAMESPACE) {
                    $getting_namespace = true;
                }

                if (is_array($token) && $token[0] == T_CLASS) {
                    $getting_class = true;
                }

                if ($getting_namespace === true) {

                    if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                        $namespace .= $token[1];
                    }
                    else if ($token === ';') {
                        $getting_namespace = false;
                    }
                }

                if ($getting_class === true) {
                    if(is_array($token) && $token[0] == T_STRING) {
                        $class = $token[1];
                        break;
                    }
                }

            }

            if(!empty($namespace) && !empty($class)){
                $classes[] = $namespace . '\\' . $class;
            }
        }
        return $classes;
    }

    /**
     * is first char in a string is a directory seperator
     * @param  string  $string 
     * @return boolean         
     */
	public function isFirstCharDirectorySeperator($string){
        return ($string[0] == "\\" || $string[0] == "/") ? true : false;
    }


    /**
     * Sort the models in descending order.
     * depending on the Model name length
     * @param $models filtered Models
     */
    public function sortModels($models){
        usort($models , function($firstElement , $secoundElement){
            return strlen($secoundElement) - strlen($firstElement);
        });

        return $models; 
    }
}