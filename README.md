# MicroFW
### When Laravel is overkill and "from scratch" is for nerds

![](http://i.imgur.com/Y4pPEEt.png)

## Get started

1. Download this project and start using! 

**No dependencies**,  just a couple of files to speed up your prototyping, testing or creating awesome stuff :) 

## Documentation

### *app/config.php*
- Here you can set your DB settings, timezone and other configuration  

### *app/routes.php* 
- Every single route has to be defined here.

**Examples:**

```
// route with callback
$router->get('/', function() {	
	return view('home');
});

// route with controller handler
$router->get('/login', 'AuthController@login');

// route with params
$router->get('/detail/:id', function($id) {	
	return 'Showing detail for ID: ' . $id;
});

/*
* $router->get()  -> to define GET request
* $router->post() -> to define POST request
*/
```

### Controllers

```
// app/Controllers directory

namespace Controllers;
use Support\Controller;

class IndexController extends Controller {

	public function before() {
		// before middleware
	}
	
	public function home($paramFromUrl) {

		// handling code


		// set HTML template
		return view('home', [
			'paramToView' => 'value'
		]);
	}

	public function after() {
		// after middleware
	}
}
```

### Views
- When you are returning view, there are automatically included `views/_header.phtml` and `views/_footer.phtml` files.
- You can set view using `view()` helper or in controller using bound `$this->view()` with the same params
```

// in your controller or route handler
return view('home', ['foo' => 'bar']);

// views/home.phtml
<span>Something's here: <?=$foo?></span>

```

### helper classes and functions

- *Support\Storage*
	- uploadFile($sourcePath, $destinationPath)
	- uploadImage($sourcePath, $destinationPath, $createThumb = false)
	- list($path, $pattern = '*')
	- deleteFile($path)
	- moveFile($path, $newPath)
- *Support\DB*
	- connect($config) `// creates connection, can be used like: Support\DB::connect(config('DB'))`
	- getRow($query, $params = [])
	- getAll($query, $params = [])
	- execute($query, $params = [])
- *Support\Laravel\Arr*
	- Official [Laravel documentation](https://laravel.com/docs/5.4/helpers#arrays)
- *Support\Laravel\Collection*
	- Official [Laravel documentation](https://laravel.com/docs/5.4/eloquent-collections)
- *Support\Laravel\Str*
	- Official [Laravel documentation](https://laravel.com/docs/5.4/helpers#strings)
- *helpers*
	- **asset($filename)**
	``` 
	asset('main.css') // returns <link rel='stylesheet' href={BASE_URL_FROM_CONFIG}/public/css/main.css"/>
	asset('app.js') // returns <script src='{BASE_URL_FROM_CONFIG}/public/js/app.js'></script>
	```
	- **urlContains($uri)**
		- returns true, if given uri is contained in current URL

	- **flash($name, $msg)**
		- saves flash message

	- **getFlashMessages($msg)**
		- returns array of messages for given `$msg`

	- **redirect($url)**
		- redirects to given `$url`

	- **dump(), dd()**
		- debug functions
			
	- **config($name)**
		- returns value from config array in *app/config.php*

	- **view($viewName, array $data = [])**
		- returns HTML view
	- **collect($data)**
		- Creates `Support\Laravel\Collection` instance


*Note*: All files in namespace `Support\Laravel` are from official [Laravel framework](https://github.com/laravel/framework/)
