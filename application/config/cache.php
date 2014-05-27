<?php defined('SYSPATH') or die('No direct script access.');
return array
(
  'redis' => array(
    'driver'             => 'redis',
    'group'              => 'default',
    'db'                 =>  8,
		'default_expire'     => Kohana::$environment == Kohana::PRODUCTION ? 3600 : 5,
  ),
	'file'    => array(
		'driver'             => 'file',
		'cache_dir'          => APPPATH.'cache',
		'default_expire'     => Kohana::$environment == Kohana::PRODUCTION ? 3600 : 5,
		'ignore_on_delete'   => array(
			'.gitignore',
			'.git',
			'.svn'),
  )
);
