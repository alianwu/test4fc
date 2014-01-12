<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cache extends Controller {

  public function action_redis()
  {
    Cache::instance('redis')->set('cache_name', 'cache_value');
    Cache::instance('redis')->set('cache_name2', 'cache_value2');
    echo 'cache_name: ' . Cache::instance('redis')->get('cache_name');
    //~ Redis_Client::instance()->getDB(8)->flushDB();
  }
  
} // End Cache
