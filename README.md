## About NeuraFrame
NeuraFrame is a PHP application framework based on MVC design pattern. This framework is coming with tools which simplify common tasks used in web programming such as:

 - Routing
 - IoC containers
 - Database connection and querying
 - Database ORM
 - Middleware functions
 - Models and Controllers

## How to install NeuraFrame
After downloading this framework, you need to run Composer command for updating dependencies:

> **composer update**

This step is necessary because NeuraFrame currently using Twig as Template Engine for rendering pages.  Also this command will generate autoloader file required for NeuraFrame application. After this step, you should get "Wellcome" page, when you access ***public*** folder inside your NeuraFrame application.

## Routes
All routes for our NeuraFrame application are created inside ***Routes.php*** file at location ***NeuraFrameApplication/app/***

For creating new route we use router service, as shown:

```php
// First parameter is url of route we want to use
// Second parameter is controller name and method
// Third parameter is HTTP request method
$app->router->addRoute('/','HomeController@index','GET');
```
Also we can define name for our route, by using method chaining:
```php
// Creating and naming new route
$app->router->addRoute('/','HomeController@index','GET')->name('getIndex');
```

## Controllers
All controllers for our NeuraFrame application are defined at location:

> ***NeuraFrameApplication/app/Controllers/***

After installing fresh NeuraFrame application, there should be file ***HomeController.php***. Inside this file is defined our ***HomeController*** class as shown:
```php
namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;

// Each controller class have to extend base Controller
class HomeController extends Controller
{
	/**
	* Rendering HomeView.html page on request
	*
	* @param NeuraFrame\Http\Request $request
	* @return string
	*/
    public function index(Request $request)
    {
        // Call template engine to render view
        // First parameter -> name of view file 
        // Second parameter -> array of data sending to view file
        return $this->view->render('HomeView.html',[
			'message'	=>	'Hello World'
		]);
    }
}
```
All you need to creating new controller class is to create file, name it as your controller name and define class wich extends base ***Controller*** class.  Also namespace is required!
Each method inside controller class get ***Request*** object as parameter. With ***Request*** object you could get data passed through request, using methods:
```php
/**
* Get Value from _GET array by the given key
*
* @param string $key
* @param mixed default
* @return mixed
*/
public function get($key,$default = null);

/**
* Get all Values from _GET array
*
* @return array
*/
public function getAll();

/**
* Get Value from _POST array by the given key
*
* @param string $key
* @param mixed default
* @return mixed
*/
public function post($key,$filter = true,$default = null);

/**
* Get Value from _FILE by the given key
*
* @param string $key
* @param mixed default
* @return mixed
*/
public function file($key,$default = null);
```

## Models
All models for our NeuraFrame application are defined at location:

> ***NeuraFrameApplication/app/Models/***

Models are used for representing logical structure of data. Each model in NeuraFrame application should extends base class ***Model***, which have private array **$container** for storing and accessing data. Example of using Models in NeuraFrame application is shown below:
```php
// Namespace of User class
namespace App\Models;
	
use NeuraFrame\Model;

// Example of using Models in NeuraFrame application
class User extends Model
{
	/**
	* Check does user have enought money for expenses
	*
	* @param int $expenses
	*/
	public function checkWallet($expenses)
	{
		return $this->wallet > $expenses ? true : false
	}
}
```

After creating model class, we can use it inside controller, as show:
```php
namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
	/**
	* Instance of user model
	*
	* @var App\Models\User
	*/
	private $user;

	/**
	* Creating new user, and adding money to wallet
	*
	* @param NeuraFrame\Http\Request $request
	*/
	public function createUser(Request $request)
	{
		$this->user = new User();
		$this->user->wallet = $request->get('money');
	}

	/**
	* Check does money in user wallet contains enought for expenses
	* and render correct page
	*
	* @param NeuraFrame\Http\Request $request
	* @return string
	*/
	public function buyItem(Request $request)
	{
		if ($this->user->checkWallet($request->get('expenses')))
			return $this->view('BuyingItem.html',[
				'itemId'  =>  $request->get('itemId')
			]);

		return $this->view('Index.html');
	}
}
```

## Middlewares
Middlewares are functions called in between HTTP request, and executing Controller method. All middlewares are defined at location:

> ***NeuraFrameApplication/app/Middlewares/***

Each middleware implements ***MiddlewareInterface*** which contain method ***handle()***. This method have two parameters. First one is ***Requests*** object, and its same as Controller's one. Second one is ***Closure*** and its used for calling next middleware function. Example of using middlewares inside NeuraFrame application is shown below:
```php
namespace App\Middleware;

use NeuraFrame\Contracts\Middleware\MiddlewareInterface;
use NeuraFrame\Http\Request;
use Closure;

// Example of using middlewares in NeuraFrame application
class ErrorChekingMiddleware implements MiddlewareInterface
{
	/**
	* Check does request object has error message
	* 
	* @param NeuraFrame\Http\Request $request
	* @param \Closure $next
	*/
    public function handle(Request $request,Closure $next)
    {		
        if($request->get('error'))
			return $this->app->view('ErrorPage.html');
		
		return $next();
    }
}
```

## Views
Currently NeuraFrame use Twig framework as Template Engine, for rendering pages. All pages are defined at location:
> ***NeuraFrameApplication/app/Views/***

## Connecting to database
Currently NeuraFrame supports only MySql adapter for connecting to database. To successfully connect to your database, you need to change configurations in file ***database.php***  at location:
> ***NeuraFrameApplication/config/***

After this step, you can fetch data from database as shown:
```php
namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;
use NeuraFrame\Database\SqlStatemants\SelectStatemant;

class HomeController extends Controller
{
	/**
	* Rendering UserProfile.html page on request
	*
	* @param NeuraFrame\Http\Request $request
	* @return string
	*/
    public function getUserProfile(Request $request)
    {
	    $selectStatemant = new SelectStatemant();
	    $selectStatemant->from('users')->where('id = '.request->get('userId'));
        $userData = $this->database->execute($selectStatemant)->fetch();

		return $this->view('UserProfile.html',[
			'userData'   => $userData
		]);
    }
}
```
There are four statemant classes, for generating SQL queries. Those classes are:

 - SelectStatemant - used for selection, and fetching data from database
 - InsertStatemant - used for inserting new data in database
 - DeleteStatemant - used for deleting data from database
 - UpdateStatemant - used for updating data inside database

## ORM database mapper
Another approach to get data from database, is by using database mappers. Database mappers classes which mapped data from database to models. First step in using database mappers, is to create mapper class, which extends base class ***Mapper*** as shown:
```php
namespace App\Models\Mappers;

use NeuraFrame\Orm\Mapper;

// Example of using Mappers in NeuraFrame application
class UserMapper extends Mapper
{
	// This variable must be defined
    protected $modelClassName = 'App\Models\User'
}
```
Protected variable ***$modelClassName*** is used for detecting class we want to map, and must be defined.  You can also define variable ***table*** or ***primaryKey***, if its required. If you don't define protected variable ***table***, it will be auto generated, according to model class name. After creating mapper class, you can get mapped data from database as shown:
```php
namespace App\Controllers;

use NeuraFrame\Controller;
use NeuraFrame\Http\Request;

class HomeController extends Controller
{
	/**
	* Rendering Users.html page on request
	*
	* @param NeuraFrame\Http\Request $request
	* @return string
	*/
    public function getUsers(Request $request)
    {
	    $userMapper = $this->dbMapper->getMapper('App\Mappers\UserMapper');
	    // Get all users from database
	    $users = $userMapper->all();
		
		//Required user, searched by primaryKey, default -> id
		$user = $userMapper->find(41);
		
		$user->name = 'TestUser';
		
		// Updating user in database
		$userMapper->update($user);
		
		//Create new instance of user in database
		$userMapper->save($user);
		
		//Delete user by primaryKey
		$userMapper->delete(41);

		return $this->view('Users.html',[
			'users'   => $users
		]);
    }
}
```

