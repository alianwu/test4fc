<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Test extends Model {
  
  public static function time()
  {
    sleep(3);
    return time();
  }
  
}
