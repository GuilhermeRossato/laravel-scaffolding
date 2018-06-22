<?php

class UserList {
    /**
     * The bucket instance that holds the user list.
     *
     * @var Bucket
     */
    static private $bucket = null;

    /**
     * Return the bucket instance that holds the user data.
     *
     * @return Bucket
     */
    private static function getBucket() {
        if (!self::$bucket) {
            self::$bucket = new Bucket('userList');
        }
        return self::$bucket;
    }

    /**
     * Returns the current date, works similar to now() in MySQL.
     *
     * @return string Date in the format "2018-07-28 13:45:12".
     */
    public static function now() {
        $dt = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dt->setTimestamp(time());
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * Loads an user by its email and return its user instance.
     *
     * @param  string $email The email of the user to load from.
     * @return User Returns the user instance or false when it fails.
     */
    public static function load($email) {
        $bucket = self::getBucket();
        if (!$bucket->exists($email)) {
            return false;
        }
        $data = $bucket->load($email);
        if (!$data) {
            return false;
        }
        $user = User::import($data);
        if (!$user) {
            return false;
        }
        return $user;
    }

    /**
     * Checks if a given email is registered.
     *
     * @param  string $email Email to be checked.
     * @return bool
     */
    public static function exists($email) {
        return (self::getBucket())->exists($email);
    }

    /**
     * Verifies if a password matches for a given user.
     *
     * @param  string $email Email to be verified.
     * @param  string $pass The password to be checked.
     * @return bool Whenever the password is valid or not.
     */
    public static function verify($email, $pass) {
        $user = $this->load($email);
        if (!$user) {
            return 'No user with specified email found';
        }
        $passwordHash = $content['hash'];
        return password_verify($pass, $passwordHash);
    }

    /**
     * Creates a new user.
     *
     * @param  string $email A unique email of the new user.
     * @param  string $name The name of the user.
     * @param  string $pass Password.
     * @param  string $access The access level of the new user.
     * @return bool|string True if successful, an error message as string if it fails.
     */
    public static function create($email, $name, $pass, $access) {
        if (strlen($pass) < 4) {
            return 'Password too small';
        }
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $bucket = self::getBucket();
        if (self::exists($email)) {
            return 'User already exists';
        }
        $user = new User([
            'email' => $email,
            'name' => $name,
            'hash' => $hash,
            'access' => $access,
            'created-at' => self::now()
        ]);
        $saved = $bucket->save($email, $user->export(true));
        if (!$saved) {
            return 'Could not save specified user';
        }
        return true;
    }

    /**
     * Changes the password of an user.
     *
     * @param string $email The unique email of the user.
     * @param string $pass The new password to be saved.
     * @return bool|string True if it succeded in changing the password, an error message as string if it fails.
     */
    public static function changePassword($email, $pass) {
        if (strlen($pass) < 4) {
            return 'Password too small';
        }
        $user = self::load($email);
        if (!$user) {
            return 'Could not retrieve user';
        }
        $bucket = self::getBucket();

        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $user->getData()['hash'] = $hash;

        $saved = $bucket->save($email, $user->export(true));
        if (!$saved) {
            return 'Could not save specified user';
        }
        return true;
    }

    /**
     * Delete a user permanently.
     *
     * @param  string.
     * @return bool Whenever it succeded in deleting the user.
     */
    public static function delete($email) {
        $bucket = self::getBucket();
        return $bucket->delete($email);
    }
}
