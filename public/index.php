<?php

/**
 * Define error handling and display levels for development mode
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Define a basic helper variable with the current URI for routing and other things
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

/**
 * Define autoloading from the lib folder
 */
spl_autoload_register(function ($class_name) {
    //error_log("class ".$class_name, 4);
    include __DIR__.'/../lib/' . $class_name . '.php';
});

/**
 * Define some basic helpers that mimic Laravel default helpers
 */
require __DIR__.'/../lib/helpers.php';

/**
 * Development servers sometimes redirect static resource to the main index.php file
 * By returning false, we tell the server to 'keep looking' for that file
 */
if ($uri !== '/' && file_exists(str_replace('/', DIRECTORY_SEPARATOR, app_path().$uri))) {
    return false;
}

/**
 * Loads configuration from their respective file
 */
$config = [];
$config['app'] = array_merge($config, require __DIR__.'/../config/app.php');

/**
 * Allows routing to be done from web.php
 */
require __DIR__.'/../routes/web.php';

/**
 * If no match was found, serve the 404 error view
 */
Route::finish(function($request) {
    return view('404');
});

return true; // Was having trouble with non-thread-safe php hanging my local servers
