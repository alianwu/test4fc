<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_User extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('User'); 
  }

  public function action_index()
  {
    $data = $this->model->get_one($this->user['id']); 
    $view = View::factory('manager/user');
    $_POST = $data;
    $this->view($view);
  }

  public function action_update()
  {
    $post = Validation::factory( Arr::extract($_POST + $_FILES, 
          array('id', 'username', 'photo', 'oldpassword', 'password', 'repassword', 'csrf')) );
    $post->rules('id', array(
          array('digit'),
          array('not_empty'),
        ))
        ->rules('username', array(
          array('not_empty'),
          array('max_length', array(':value', 30)),
          array('min_length', array(':value', 3))
        ))
        ->rules('photo', array(
          array('max_length', array(':value', 100)),
        ))
        ->rules('oldpassword', array(
          array('trim'),
        ))
        ->rules('password', array(
          array('max_length', array(':value', 32)),
          array('min_length', array(':value', 6))
        ))
        ->rules('repassword', array(
          array('matches', array(':validation', 'repassword', 'password'))
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ));

    if( $post->check() ) {
      $data = $post->data();
      $data['id'] = $this->user['id'];
      $ret = $this->model->update_passport($data);
      $this->result($ret);
    }
    else {
      $error = $post->errors('user');
      $this->template->bind_global('error', $error);
      $this->result(5);
    }
    
    $this->action_index();
  }


  public function action_logout()
  {
    Model::factory('User')->logout();
    $this->redirect('manager_sigin');
  }
} 
