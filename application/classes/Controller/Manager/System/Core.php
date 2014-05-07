<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_Core extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('Config'); 
    $this->template->container = View::factory('manager/system/system');
  }

  public function action_index()
  {
    $core = $this->model->get_all();
    $view = View::factory('manager/system/core');
    $view->bind('post', $core);
    $this->template->container->detail = $view;
  }


  public function action_update()
  {
    $post = Validation::factory( Arr::extract($this->request->post(), 
                                  array('core', 'map', 'csrf')) );
    $post->rules('core', array(
          array('not_empty'),
        ))
        ->rules('map', array(
          array('not_empty'),
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ));

    if( $post->check() ) {
      $data = $post->data();
      unset($data['csrf']);
      foreach($data as $k => $v) {
        foreach($v as $_k => $_v) {
          $this->model->update_one($_k, $_v, $k);
        }
      } 
      $this->result(0);
    }
    else {
      $error = $post->errors('system/core');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_index();
  }

} 
