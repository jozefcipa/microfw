# MicroFW
### When Laravel is overkill and "from scratch" is for nerds

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

- example
- middlewares


### helper classes and functions

- Support\Storage
	- uploadFile($sourcePath, $destinationPath)
	- uploadImage($sourcePath, $destinationPath, $createThumb = false)
	- list($path, $pattern = '*')
	- deleteFile($path)
	- moveFile($path, $newPath)


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
