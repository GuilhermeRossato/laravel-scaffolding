<?php

/**
 * Get the file path of this application.
 *
 * @return string The path separated by DIRECTORY_SEPARATORs.
 */
function app_path()
{
    static $path = null;

    if ($path === null) {
        $lastSeparator = strrpos(__DIR__, DIRECTORY_SEPARATOR);
        // Remove the last separator, because it will be the lib folder.
        if ($lastSeparator === false) {
            throw new Exception('Unable to determine a directory separator\'s index for the current directory');
        }
        $path = substr(__DIR__, 0, $lastSeparator);
    }

    return $path;
}

/**
 * Get the HTML content of a view by its name
 * @param  string $name Name of the view
 * @return string HTML code
 */
function view($name)
{
    return View::html($name);
}

/**
 * Returns the full url from a relative url.
 *
 * @param  string $str The relative url, in any form, with or without slashes at start.
 * @return string The absolute url.
 */
function url($str) {
    return Url::to($str);
}
/**
 * Gets the value of an environment variable. Supports boolean, empty and null.
 *
 * @param  string  $key
 * @param  mixed   $default
 * @return mixed
 */
function env($key, $default = null)
{
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;

        case 'false':
        case '(false)':
            return false;

        case 'empty':
        case '(empty)':
            return '';

        case 'null':
        case '(null)':
            return;
    }

    if ($value[0] == '"' && substr($value, -1) == '"') {
        return substr($value, 1, -1);
    }

    return $value;
}

/**
 * Returns a configuration from the config folder
 * @param  string $name The option separated by dots
 * @return mixed The defined value or a empty string if not defined
 */
function config($name)
{
    global $config;
    $node = $config;
    $parts = explode('.', $name);
    foreach ($parts as $part) {
        if (is_array($node) && isset($node[$part])) {
            $node = $node[$part];
        } else {
            return '';
        }
    }
	return $node;
}

/**
 * Returns the request object of the ongoing request
 * @return Request instance 
 */
function request() {
    static $request = null;

    if ($request === null) {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $request = new Request();
		$request->setMethod($method);
    }

    return $request;
}
