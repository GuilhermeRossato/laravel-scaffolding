<?php

// TODO: Add comments for this class and its methods

class Request {
    protected $method = false;

    public function setMethod($m) {
        $this->method = $m;
    }

    public function method() {
        return $this->method;
    }

    /**
     * Gets the host with the port as string, port is ommited if it's 80
     * 
     * @return string The host and the port in the format "host:port"
     */
    public function getHttpHost() {
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        if (isset($_SERVER['SERVER_HOST'])) {
            if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != "80") {
                return $_SERVER['SERVER_HOST'].':'.$_SERVER['SERVER_PORT'];
            }
            return $_SERVER['SERVER_HOST'];
        }
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != "80") {
            return 'localhost:'.$_SERVER['SERVER_PORT'];
        }
        return 'localhost';
    }

    public function input($name) {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return '';
    }
}
