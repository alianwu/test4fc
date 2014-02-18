<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Api extends Controller_Template {

  public $auto_render = FALSE; 

  protected $body = array('error'=>1, 'data'=>NULL);

  public function before()
  {
    parent::before();
  }

  public function after()
  {
    parent::after();
    $this->response->body(json_encode($this->body))->headers('Content-Type', 'application/json');
  }

} // End API
