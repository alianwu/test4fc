<?php defined('SYSPATH') OR die('No direct script access.');

class User {

	public static function auth($user, $path)
  {
    return isset($user['auth']) && Arr::path($user['auth'], $path, FALSE);
  }

}
