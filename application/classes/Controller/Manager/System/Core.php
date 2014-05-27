<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_Core extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
  }

  public function action_index()
  {
    $_POST = $config = $this->model_config->get_all();
    $view = View::factory('manager/system/core');
    $this->view($view);
  }


  public function action_update()
  {
    $post = Validation::factory($_POST);
    $post->rules('core', array(
          array('not_empty'),
        ))
        ->rules('faq', array(
          array('not_empty'),
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ));

    if( $post->check() ) {
      $data = $post->data();
      unset($data['csrf']);
      $this->model_config->update_one($data);
      $this->result(0);
    }
    else {
      $error = $post->errors('system/core');
      $this->result(1, NULL, array('error'=>$error));
    }
    
    $this->action_index();
  }

} 
