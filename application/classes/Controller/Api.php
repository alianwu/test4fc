<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller_Template {

  public $auto_render = FALSE; 

  public function before()
  {
    parent::before();
    //  $this->response->headers('cache-control', 'max-age=5');
  }

  public function action_get_city()
  {
    $cid = Arr::get($_GET, 'cid', 0);
    $type = Arr::get($_GET, 'type', 0);
    $body = $this->model_city->get_city_pretty($cid, $type);
    $this->response->body(json_encode($body))->headers('Content-Type', 'application/json');
  }

} // End API
