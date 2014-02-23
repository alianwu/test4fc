<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Map extends Controller_Api {

  public function action_getgeo()
  {
    $location = Arr::get($_GET, 'location', '');
    $reverse   = Arr::get($_GET, 'reverse', FALSE);
    if ($location == '' ) { return 0; }
    $data = Map::instance()->getgeo($location, $reverse);
    if ($data) {
      $this->result(0, $data, $data);
    }
  }

} // End Map
