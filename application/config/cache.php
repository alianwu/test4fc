<?php defined('SYSPATH') or die('No direct script access.');
return array
(
  'redis' => array(
    'driver'             => 'redis',
    'group'              => 'default',
    'db'                 =>  8,
    'default_expire'     => 3600,
  )
);
