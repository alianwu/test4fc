<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_Var extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('Config'); 
  }

  public function action_index()
  {
    $core = $this->model->get_all();
    $view = View::factory('manager/system/var');
    $view->bind('var', $this->setting['var']);
    $_POST = $core;
    $this->view($view);
  }


  public function action_save()
  {
    $post = Validation::factory($_POST);
    $post->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ));
    foreach($this->setting['var'] as $k=>$v) {
      $post->rules($k, array(
          array('not_empty')
        ));
    }
    if( $post->check() ) {
      $data = $post->data();
      unset($data['csrf']);
      $this->model->update_one($data);
      $this->result(0);
    }
    else {
      $error = $post->errors('system/var');
      $this->result(1, NULL, array('error'=>$error));
    }
    
    $this->action_index();
  }

} 
