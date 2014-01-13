<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Redis extends CTemplate {

  public function action_index()
  {
    $redis = new Redis();
    $connected = $redis->connect('/tmp/redis.sock');
    if ($connected) {
      $message =  'connected';
    }
    else {
      $message = 'unconnected';
    }
    $this->template->content = $message;
  }
  
} // End Redis
