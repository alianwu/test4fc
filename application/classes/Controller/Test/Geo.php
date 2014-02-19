<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test_Geo extends Controller {

  public function action_index()
  {

    echo View::factory('test/geo')->render();
    //~ Redis_Client::instance()->getDB(8)->flushDB();
  }
  
} // End Cache
