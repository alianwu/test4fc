<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_User extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('User'); 
    $this->template->container = View::factory('manager/user/user');
  }

  public function action_index()
  {
    $data = Arr::extract($_GET, array('actived', 'keyword'));
    $user = $this->model->get_list($data);
    $view = View::factory('manager/user/index');
    $view->bind_global('users', $user);
    $this->view($view);
  }

  public function action_actived()
  {
    $id = (int) Arr::get($_GET, 'id');
    if ($id) {
      $this->model->actived($id);
    }
    $this->action_index();
  }

  public function action_auth()
  {
    $id = (int) Arr::get($_GET, 'id');
    $user = $this->model->get_one($id);
    $user['auth'] = json_decode($user['auth'], true);
    if ($user) {
      $view = View::factory('manager/user/auth');
      $view->bind('user', $user);
      $this->view($view);
    }
    else {
      throw new Kohana_HTTP_Exception_404();
    }
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
      $this->result((bool) $ret);
    }
    else {
      $error = $post->errors('user/auth');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_auth();
  }

  public function action_editor()
  {
    $this->action_404();
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
