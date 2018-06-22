<?php

class Session {
	/**
	 * Used to check if the session has been started or not
     *
     * @var bool
	 */
    protected static $sessionHasStarted = false;

    /**
     * Starts the session or return an error
     *
     * @throws Warning when headers are already sent
     * @return string on failure, void on success
     */
    static function start() {
        self::$sessionHasStarted = true;
        session_start();
    }

    /**
     * Sets a value into the server session storage
     *
     * @return void
     */
    static function set($key, $value) {
        if (!self::$sessionHasStarted) {
            Session::start();
        }
        $_SESSION[$key] = $value;
    }

    /**
     * Reads a value from the server session storage
     *
     * @return string with the value saved, if it isn't set returns an empty string
     */
    static function get($key) {
        if (!self::$sessionHasStarted) {
            Session::start();
        }
        return isset($_SESSION[$key])?$_SESSION[$key]:'';
    }
}
