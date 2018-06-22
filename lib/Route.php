<?php

/*
 * This Route class follows Laravel's routing, it's just not fully implemented
 */

class Route {
	/**
	 * Controls whenever a route match has occured
	 *
	 * @var bool
	 */
	protected static $hasMatched = false;

	/**
	 * Check if this request matches a specified string path-like variable
	 * Will only return true once in each request
	 *
	 * @param string $path formatted path like '/test/{id}'
	 */
	protected static function checkMatch($path) {
		global $uri;
		if (!self::$hasMatched && $uri == $path) {
			self::$hasMatched = true;
			return true;
		}
		return false;
	}

	/**
	 * Handle a mixed parameter from a route and call it, returning it's result
	 *
	 * @throws Exception with the corresponding message if the action is incorrect
	 *
	 * @param string[or callable] $multi a function or a string that represents a function or a method of a class to be executed
	 * @param Request $request An instance with it's method set (usually from $_SERVER)
	 * @param array $data Optional data to send to action's method
	 * @return mixed
	 */
	static function treatAction($action, $request, $data) {
		if (is_callable($action)) {
			if ($data) {
				return $action($request, $data);
			}
			return $action($request);
		} else if (is_string($action)) {
			if (strpos($action, '@') !== false) {
				$multiParts = explode('@', $action);
				if (count($multiParts) != 2) {
					throw new Exception('Too many @ separators as route\'s action');
				}
				$class = $multiParts[0];
				require app_path().'/app/controllers/'.$class.'.php';
				$instance = new $class();
				$method = $multiParts[1];
				if (!method_exists($instance, $method)) {
					throw new Exception('Method '.$method.' of an instance of '.$class.' was not found or does not exist');
				}
				if ($data) {
					echo $instance->$method($request, $data);
				} else {
					echo $instance->$method($request);
				}
				return;
			} else if (function_exists($action)) {
				if ($data) {
					return call_user_func($action, $request, $data);
				}
				return call_user_func($action, $request);
			} else {
				throw new Exception('Could not determine route\'s action');
			}
		}
	}

	/**
	 * Redirect an url, if the headers were already sent, attempts to use javascript
	 * to redirect the user, outputting a script tag directly
	 *
	 * @param string $uri The origin url that should be matched to redirect
	 * @param boolean $uri If the boolean is true, it redirects without checking a matching uri.
	 * @param string $target The target url to redirect the user, can be relative or not
	 *
	 * @return boolean Indicating whenever it used header redirection or not
	 */
	static function redirect($uri, $target, $code=301) {
		if ($code != 301 && $code != 302 && $code != 303 && $code != 304) {
			throw new Exception('Invalid redirect code '.$code);
		}
		if ($uri === true || self::checkMatch($uri)) {
			if (!headers_sent()) {
				header('Location: '.$target, true, $code);
				return true;
			} else {
				echo 'Redirecting...<script>window.location = '.json_encode($target).';</script>';
			}
		}
		return false;
	}

	/**
	 * Routes a list of methods to a uri if it matches, executing the action and returning it
	 *
	 * @usage examples
	 *	Route::match(['post', 'put'], '/user/delete', 'UserController@delete');
	 *	Route::match('get', '/user/list', function($request) { return 'userList'; });
	 *
	 * @param array|string $methods Possible values: 'get', 'post', 'put', 'delete' or an array containing any combination of these.
	 * @param string $uri The URI to be checked to consider it a match
	 * @param Closure|array|string $action The action to be executed only if the function matches
	 * @param array $data Optional data to send to action's method
	 *
	 * @return mixed The return of the action or null if it doesn't match
	 */
	static function match($methods, $uri, $action, $data = null) {
		if (self::checkMatch($uri)) {
			if (!is_array($methods)) {
				$methods = [$methods];
			}
			$method = strtolower($_SERVER['REQUEST_METHOD']);
			if (in_array($method, $methods)) {
				$request = new Request();
				$request->setMethod($method);
				return self::treatAction($action, $request, $data);
			}
		}
	}

	/**
	 * Checks a URI for matching 'get' method and run the action parameter if it matches. Its nothing but a shortcut to Route::match('get', $uri, $action).
	 *
	 * @param string $uri The URI to be checked for matching
	 * @param Closure|array|string $action The action to be executed only if the function matches
	 * @param array $data Optional data to send to action's method
	 *
	 * @return mixed The return of the action or null if it doesn't match
	 */
	static function get($uri, $action, $data = null) {
		return self::match('get', $uri, $action, $data);
	}

	/**
	 * Checks a URI for matching 'post' method and run the action parameter if it matches. Its nothing but a shortcut to Route::match('post', $uri, $action).
	 *
	 * @param string $uri The URI to be checked for matching
	 * @param Closure|array|string $action The action to be executed only if the function matches
	 * @param array $data Optional data to send to action's method
	 *
	 * @return mixed The return of the action or null if it doesn't match
	 */
	static function post($uri, $action, $data = null) {
		return self::match('post', $uri, $action, $data);
	}

	/**
	 * Checks a GET request for matching uri and execute a view directly
	 *
	 * @param string $uri The URI to be checked for matching
	 * @param string $view The name of the view to be loaded
	 * @param array $data Optional data to send to action's method

	 * @return mixed The return of the action or null if it doesn't match
	 */
	static function view($uri, $view, $data = null) {
		if (!self::$hasMatched) {
			$data['view'] = $view;
			return self::match('get', $uri, function($request, $data) {
				return View::html($data['view']);
			}, $data);
		}
	}

	/**
	 * To be called after all routing is done, as a fallback, execute action only if no other match was successful. Primarily created to handle 404
	 *
	 * @param Closure|array|string $action The action to be executed only if the function matches
	 *
	 * @return mixed The return of the action or null if it doesn't match
	 */
	static function finish($action) {
		if (!self::$hasMatched) {
			$method = strtolower($_SERVER['REQUEST_METHOD']);
			$request = new Request();
			$request->setMethod($method);
			return self::treatAction($action, $request, null);
		}
	}
}
