<?php defined('SYSPATH') OR die('No direct script access.');

return array(
  'native' => array(
      /**
       * Database settings for session storage.
       *
       * string   group  configuation group name
       * string   db  session db name
       * integer  gc     number of requests before gc is invoked
       */
      'group'   => 'default',
      'db'      => 9,
      'gc'      => 600
    )
);
