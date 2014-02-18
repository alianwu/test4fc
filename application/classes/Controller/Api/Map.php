<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Map extends Controller_Api {

  public function action_getgeo()
  {
    $localtion = Arr::get($_GET, 'localtion', '');
    $reverse   = Arr::get($_GET, 'reverse', FALSE);
    if ($localtion == '' ) { return 0; }
    $data = Map::instance()->getgeo($localtion, $reverse);
    if ($data) {
      $this->body['error'] = 0;
      $this->body = array_merge($this->body, $data);
    }
  }

} // End Map
