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
