<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Redis extends Controller {

  public function action_index()
  {
    $redis = new Redis();
    $connected = $redis->connect('/tmp/redis.sock');
    if ($connected) {
      echo 'connected';
    }
    else {
      echo 'unconnected';
    }
  }
  
} // End Redis
