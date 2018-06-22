<?php

class View {
    static function toFilename($str) {
        $ext = pathinfo($str, PATHINFO_EXTENSION);
        if (!$ext) {
            return app_path().'/resources/views/'.$str.'.php';
        }
        return app_path().'/resources/views/'.$str;
    }
    static function exists($str) {
        return file_exists(self::toFilename($str));
    }
    static function html($str) {
        if (!self::exists($str)) {
            throw new Exception('The view file "'.$str.'" does not exist and cannot be served from "'.self::toFilename($str).'"');
        }
        require self::toFilename($str);
    }
}
