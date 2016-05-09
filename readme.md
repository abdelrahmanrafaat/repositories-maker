##**Repositories Maker**##
 


Repository pattern is an abstraction layer for your models.

Instead of writing tones of duplicated queries in your controllers.

You can make a repository that has a readable name and implements an interface(for changing the dependencies later) and bind the 
repository with it\`s interface using the IOC container.
Looks like lots of work to be done .....

**Repositories Maker** makes this process as easy as typing one artisan command.

1.Go to your laravel project root and type 

`composer require abdelrahmanrafaat`/`repositories-maker:dev-master`



2.you need to register the package service provider .. Go to **config/app.php** and add this line to the end of the providers array.
`Abdelrahmanrafaat\RepositoriesMaker\Provider\RepositoriesMakerServiceProvider::class`



3.This command should add a new command to your artisan list .. make:repositories



4.This Command Assumes that your models are at at **app** directory and extends **Model** class , but of course you can change this options as i will explain later.

`php artisan make:repositories`

This artisan command if you add **--help** to the end of it you will get the some options.

- \-\-**parent** : Specify the parent class of your models (Only the **class base name** not the full name space) or you can leave it **blank** if your models don\`t extend a parent class or they extend different parent classes.

- \-\-**directory** : Directory that contains your models starting from the project root directory (this option can\`t start with **/** Or **\**).

- \-\-**nestedDirectories** : boolean option(**false** by default) indicates if your models are in nested directories.

- \-\-**except** : Comma seperated list of models that you don\`t want to generate a repositories for.

- \-\-**only** : create repositories only for this Comma seperated list of models.

Note : 
- except and only options don\`t work togther you need to specifiy one of them.
- except and only model names are (**class base name** not the full name space)

5.If you run the command it will generate the repositories and interfaces in **app\Repositories** and RepositoriesServiceProvider in the **app\Provider** and the terminal will output the names of generated files.

6-you need to register the RepositoriesServiceProvider .. Go to **config/app.php** and add this line to the end of the providers array(make sure you change **App** if you have different namespace for your application).

`App\Providers\RepositoriesServiceProvider::class`

........

Now you are good to go , you can go to any class that has automatic resolution (controller , event , command ..) and type-hint the repository interface , and you will get a repository that has an instance of it\`s model.

Happy coding ..
