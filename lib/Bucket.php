<?php
/**
 * The Bucket class provides abstraction to a file system to handles assets storage.
 * If you never heard of buckets, it's simply a folder that can't have any folders inside.
 */
class Bucket {
    /**
     * The name of the bucket (or folder).
     *
     * @var string
     */
    protected $bucketName;

    /**
     * The prefix of the bucket, which could be a file path prefix but depends on server properties.
     *
     * @var string
     */
    protected $origin;

    /**
     * Creates a new bucket with a given name.
     *
     * @param string $name The name of the bucket
     *
     * @return void
     */
    function __construct($name) {
        $this->setBucketName($name);
    }

    /**
     * Sets the bucket name and also figures out the file path prefix of the bucket
     *
     * @param string $name The name of the bucket
     *
     * @return void
     */
    public function setBucketName($name) {
        $this->bucketName = $name;
        $this->origin = app_path().DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.$name;
    }

    /**
     * Returns the bucket name previously set.
     *
     * @return string The bucket name.
     */
    public function getBucketName() {
        return $this->bucketName;
    }

    /**
     * Sanitize filename, stripping invalid characters.
     *
     * @param string $name The untreated filename.
     * @return string Sanitized string.
     */
    protected function sanitize($name) {
        return preg_replace("/\-+/", "-", preg_replace("/[^\w&^\-]/", "", trim(preg_replace("/\ /", "-", trim($name)), "-")));
    }

    /**
     * Expand a filename by appending the bucket file path prefix and an extension to a file.
     *
     * @return string The full path of the object in the file system, including extension.
     */
    protected function expandFilename($name, $ext = 'json') {
        return $this->origin.DIRECTORY_SEPARATOR.(this->sanitize($name)).'.'.$ext;
    }

    /**
     * Loads a file content as string from the file system in this bucket.
     *
     * @param string $name The name of the file to load from.
     * @return bool|string A string with the full file contents on success. False on failure.
     */
    public function load($name) {
        $filename = $this->expandFilename($name);
        if (!file_exists($filename)) {
            return false;
        }
        return file_get_contents($filename);
    }

    /**
     * Saves the file content in the specified filename inside this bucket.
     *
     * @param string $name The name of the file to save to.
     * @param string $data The content to be written to the file.
     * @param bool $append (Optional) If the file exists, this flag controls whenever the file contents will be replaced or added to. (Default false)
     *
     * @return bool|int False on failure, otherwise the number of bytes written to the file.
     */
    public function save($name, $data, $append = false) {
        $filename = $this->expandFilename($name);
        if (!is_string($data)) {
            return false;
        }
        return file_put_contents($filename, $data, $append?FILE_APPEND:0);
    }

    /**
     * Deletes a file content from the file system in this bucket.
     *
     * @param string $name The name of the file to delete.
     * @return bool True on success, false if it couldn't delete the file (or if it didn't exist).
     */
    public function delete($name) {
        $filename = $this->expandFilename($name);
        if (!file_exists($filename)) {
            return false;
        }
        return unlink($filename);
    }

    /**
     * Deletes a file content from the file system in this bucket by redirecting the call to the delete method.
     *
     * @param string $name The name of the file to delete.
     * @return bool True on success, false if it couldn't delete the file (or if it didn't exist).
     */
    public function remove($name) {
        return $this->delete($name);
    }

    /**
     * Checks if a file exists or not in this bucket
     *
     * @param string $name The name of the file to check.
     * @return bool True if it exists, false if it doesn't.
     */
    public function exists($name) {
        $filename = $this->expandFilename($name);
        return file_exists($filename);
    }
}

/*
// Makeshift test suite
function app_path() {
    return __DIR__.DIRECTORY_SEPARATOR.'..';
}
$b = new Bucket('userList');
$b->save('user', 'user-data-goes-here');
echo '<pre>'.$b->load('user').'</pre>';
*/