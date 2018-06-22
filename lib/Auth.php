<?php

class Auth {
    /**
     * Retrieves the currently logged-in user.
     *
     * @return User or false on failure.
     */
    static function user() {
        $email = Session::get('email');
        $name = Session::get('name');
        $access = Session::get('access');
        if (!empty($email) && !empty($name) && !empty($access)) {
            return new User([
                'email' => $email,
                'name' => $name,
                'access' => $access
            ]);
        }
        return false;
    }

    /**
     * Checks whenever a user is logged in or not.
     *
     * @return bool
     */
    static function check() {
        return (strlen(self::id()) > 1);
    }

    /**
     * Returns the unique identified of an authenticated user.
     *
     * @return string
     */
    static function id() {
        return Session::get('email');
    }

    /**
     * Returns the access level of an authenticated user.
     *
     * @return string
     */
    static function access() {
        return Session::get('access');
    }

    /**
     * Log a user into the application.
     *
     * @param  User
     * @return void
     */
    static function login(User $user) {
        Session::set('email', $user['email']);
        Session::set('name', $user['name']);
        Session::set('access', $user['access']);
    }
}
