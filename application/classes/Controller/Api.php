<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller_Template {

  public $auto_render = FALSE; 

  public function before()
  {
    parent::before();
  }

  public function action_error()
  {
    $code = (int) $this->request->param('id');
    $this->result($code == 0?1:$code);
  }

  public function error_user($error = NULL)
  {
    $this->result(1, $error == NULL ? '请登录': $error);
  }
  
  public function after()
  {
    parent::after();
    $this->response->body(json_encode($this->result))->headers('Content-Type', 'application/json');
  }

} // End API
