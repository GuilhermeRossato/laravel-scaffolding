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

Route::get('/message', 'MessageController@index');
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

Laravel is a great tool, extensive and easy to use to some extent. However, it comes loaded with 3k+ files of dependencies and whatever the developer intended on having, like twig, guzzle and others dependencies that you might or might now use. I know that the file count doesn't cause performance issues while the aplication is running but it still makes my deploy process take far too long for small projects.

Anyways, I prefer dependency-less apps, simple, fully undestandable. Have you ever asked yourself: What are Service Containers, Contracts or Queues? When I develop for laravel, I want to learn about these things AS i need them, not before that.

Best part of that repository is that it's being developed with laravel in mind without copying anything but the way we use the tool, so that it can keep being simple to use.

Finally, this codebase grows with my understanding of laravel, so if something is not implemented here, either I don't understand it yet or it hasn't been useful yet.

# Special thanks

To Laravel and every contributor to it. I love the concepts it taught me and it's a great framework.

# License

Since all code is mine (proprietary), I plan to make it open for any and all usage, you can copy and redistribute, sell or whatever.

However, I will use MIT because it's what Laravel uses and I believe that something that mimicks it should not change the licence, not only because of legal consequences, but because people are already used to MIT. Also, I trust the original Laravel creator to have done a good research on licencing.

:)
