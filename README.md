# laravel-scaffolding

A scaffolding that aims to mimic laravel framework, following most of its principles but with NO dependencies.

Basically an experiment to make it lighter, because I feel laravel is far too large for some small projects.

The idea is that any server regardless of php version or configuration can run this, and every problem is easily found, for the application is simple and with very a very little codebase.

# Routing

Routes can be defined only in `/routes/web.php`, the options are as follow:

````php
Route::view('/', 'index'); // Redirect index directly to view at /resources/views/hello.php by requiring it

Route::get('/greetings', function($request) {
    return view('hello'); // File must exist: /resources/views/hello.php
});

Route::post('/message/save', function($request) {
    $user = $request->input("email");
    $message = $request->input("message"); // Warning: raw input
    if (!$user || !$message) {
        return view('not-valid'); // at /resources/views/not-valid.php
    }
    $bucket = new Bucket('messages'); // Already exists at /lib/Bucket.php
    $bucket->save($user, $message, ); // 
    return "Success!";
});

Route::Get('/message', 'MessageController@index');
// A class in /app/controllers/MessageController.php must have the "index" method with $request as parameter
// Should return message as string or echo directly
````

# Helpers

Not all laravel helpers are implemented, most of them are defined in `/lib/helpers.php`:

````php
function app_path(); // Get the file path of this application.
function view($name); // Get the HTML content of a view by its name
function url($str); // Returns the full url from a relative url.
function env($key, $default = null); // Gets the value of an environment variable. Supports boolean, empty and null.
function config($name) // [Not fully implemented] Returns a configuration from the config folder
````

# Other classes

Every class is defined as `/lib/<classname>.php`, they are loaded lazily and automatically whenever they are needed, so if you want you can just create a new class and use it somewhere else and it will be loaded correctly. The code that lazily loads it is at `/public/index.php`.

# Inspiration

I made this after realizing Laravel is just very clumbersome and comes loaded with 3k+ files of dependencies, which I know doesn't necessarily cause relevant performance issues while the aplication is running, but makes my deploy process take far too long for small projects.

Even though Laravel is very extensive and easy to use, I prefer to use dependency-less applications, but I also love the easy way to define things in laravel like routes, views, file system and authentication.

Anyways, my deploy of around 30 files is 1000 faster than a laravel application. Another great thing about developing this is that it grows as my undertanding of Laravel also grows, not only that, I learn how to implement, and the best part is that this project is growing with my knowleadge of laravel. For example, I don't understand Service Containers, and what the hell is `Illuminate\Contracts\Queue\Queue`?

# Special thanks

Laravel and every contributor to it. I love the concepts learned and believe it's a great framework.

# License

Since all code is mine (proprietary), I plan to make it open for any and all usage, you can copy and redistribute, sell or whatever.

At the moment it's MIT because it's what Laravel uses and I don't understand how to choose correctly so i'll just trust the original Laravel creator to have done a good research.

:)
