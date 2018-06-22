<?php

class Url {
    /**
     * Gets the full URL to a specified relative path
     *
     * @param  string $str The relative url, in any form, with or without slashes at start.
     * @return string The absolute url.
     */
    public static function to($url = '/') {
        global $config;
        if (substr($url, 0, 1) == '\\') {
            $url = substr($url, 0, 1);
        }
        if ($url[0] == '/') {
            return $config['app']['url'].substr($url, 1);
        } else {
            return $config['app']['url'].$url;
        }
    }
}
