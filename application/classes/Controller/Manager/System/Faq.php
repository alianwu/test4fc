<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_Faq extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('Config'); 
    $this->template->container = View::factory('manager/faq/faq');
  }

  public function action_setting()
  {
    $user = $this->model->get_list($data);
    $view = View::factory('manager/faq/setting');
    $view->bind_global('users', $user);
    $this->template->container->detail = $view;
  }

  public function action_auth_update()
  {
    $post = Validation::factory( Arr::extract($_POST, 
                                  array('id', 'auth',  'csrf')) );
    $post->rules('id', array(
          array('digit'),
          array('not_empty'),
        ))
        ->rules('auth', array(
          array('not_empty'),
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ));
    if( $post->check() ) {
      $data = $post->data();
      $ret = $this->model->auth_update($data);
      $this->result($ret);
    }
    else {
      $error = $post->errors('user/auth');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_auth();
  }

  public function action_delete()
  {
    $id = (int) Arr::get($_GET, 'id');
    if ($id) {
      $this->model->delete($id);
    }
    $this->action_index();
  }
} 
