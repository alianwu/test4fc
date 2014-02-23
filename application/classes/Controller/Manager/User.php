<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_User extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('User'); 
  }

  public function action_index()
  {
    $this->template->container = View::factory('manager/user');
  }

  public function action_update()
  {
    $post = Validation::factory( Arr::extract($_POST, 
                                  array('id', 'username', 'oldpassword', 'password', 'repassword', 'csrf')) );
    $post->rules('id', array(
          array('digit'),
          array('not_empty'),
        ))
        ->rules('username', array(
          array('not_empty'),
          array('max_length', array(':value', 30)),
          array('min_length', array(':value', 3))
        ))
        ->rules('oldpassword', array(
          array('trim'),
        ))
        ->rules('password', array(
          array('trim'),
          array('max_length', array(':value', 32)),
          array('min_length', array(':value', 6))
        ))
        ->rules('repassword', array(
          array('matches', array(':validation', 'repassword', 'password'))
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ))
        ->rules('display', array());

    if( $post->check() ) {
      $data = $post->data();
      $data['id'] = $this->user['id'];
      $ret = $this->model->update_one($data);
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
