<?php
return [
    /**
	 * Application Name
	 *
	 * This value is used when the app needs to place its name in a notification
	 * or any other location required by either a module or the app itself.
     */
    'name' => env('APP_NAME', 'Laravel Scaffolding'),

    /**
	 * Base Application Url
	 *
	 * This value makes sure all anchors and static content is correctly directed
	 * even when the app is placed in a different folder (not root)
     */
    'url' => env('APP_BASE_URL', 'http://'.(isset($_SERVER['SERVER_HOST'])?$_SERVER['SERVER_HOST']:'localhost').'/')
];