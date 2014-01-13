<?php

/**
 * The default extension of resource files. If you change this, all resources
 * must be renamed to use the new extension.
 *
 * @link http://kohanaframework.org/guide/about.install#ext
 */
define('EXT', '.php');
define('STATIC_SITE_URL', 'http://data.perhome.cn/');

/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 * @link http://www.php.net/manual/errorfunc.configuration#ini.error-reporting
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 *
 * When using a legacy application with PHP >= 5.3, it is recommended to disable
 * deprecated notices. Disable with: E_ALL & ~E_DEPRECATED
 */
error_reporting(E_ALL | E_STRICT);

/**
 * End of standard configuration! Changing any of the code below should only be
 * attempted by those with a working knowledge of Kohana internals.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 */

// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

define('KOHANA', realpath(DOCROOT.DIRECTORY_SEPARATOR.'..'. DIRECTORY_SEPARATOR .'kohana').DIRECTORY_SEPARATOR);

// Define the absolute paths for configured directories
define('APPPATH', DOCROOT.'application'.DIRECTORY_SEPARATOR);
define('MODPATH', KOHANA.'modules'.DIRECTORY_SEPARATOR);
define('SYSPATH', KOHANA.'system'.DIRECTORY_SEPARATOR);


/**
 * Define the start time of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_TIME'))
{
	define('KOHANA_START_TIME', microtime(TRUE));
}

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_MEMORY'))
{
	define('KOHANA_START_MEMORY', memory_get_usage());
}

// Bootstrap the application
require APPPATH.'bootstrap'.EXT;

/**
 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
 * If no source is specified, the URI will be automatically detected.
 */
 
$cache = HTTP_Cache::factory(Cache::instance('redis'));
$cache->cache_key_callback(function (Request $request) {
    $uri     = $request->uri();
    $query   = $request->query();
    return sha1($uri.'?'.http_build_query($query, NULL, '&'));
  });

$quest = Request::factory(TRUE, array(), FALSE);
$quest->client()->cache($cache);

echo $quest->execute()
      ->send_headers(TRUE)
      ->body();

