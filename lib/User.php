<?php

/**
 * This class is just a simple data wrapper
 */
class User {
    /**
     * Holds all user data in a simple array
     *
     * @var array
     */
    protected $data;

    /**
     * Creates the user with the specified data array
     *
     * @param array $data
     * @return User
     */
    public function __construct($data = []) {
        $this->data = [];
        $this->setData($data);
    }

    /**
     * Adds all key,value pairs to user data
     *
     * @param array $data
     * @return void
     */
    public function setData($data) {
        foreach ($data as $key=>$value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Retrieves all saved data
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Gets a user instance from the exported string
     *
     * @example $user = User::import('{"email": "gui@app.com", "name": "hello-world"}');
     * @param string $data The previously exported string
     * @return User Returns false if it fails to decode the string
     */
    public static function import($data) {
        $content = json_decode($data, true);
        $user = new User($content);
        return $user;
    }

    /**
     * Export the user as string
     *
     * @param bool $password (Optional) Whenever to include the password hash (Default false)
     * @return string The decoded string of the user data
     */
    public function export($password = false) {
        $data = $this->getData();
        if (!$password) {
            $data = (array)clone(object)$data;
            unset($data['hash']);
        }
        return json_encode($data);
    }
}
