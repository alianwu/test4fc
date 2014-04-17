<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_User extends Controller_Manager_Template {
  
  public function before()
  {
    parent::before();
    $this->model = Model::factory('User'); 
  }

  public function action_index()
  {
    $user = $this->model->get_one($this->user['id']); 
    $view = View::factory('manager/user');
    $view->bind('user', $user);
    $this->template->container = $view;
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
          array('Upload::valid'),
          array('Upload::type', array(':value', array('jpg', 'png'))),
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
        ));

    if( $post->check() ) {
      $data = $post->data();
      try {
        $dir = $this->core['upload_dir'] . DIRECTORY_SEPARATOR;
        $real_dir = DOCROOT . $dir;
        $name = uniqid().'.'.strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $ret = Upload::save($_FILES['photo'], $name, $real_dir);
        if ($ret) {
          $data['photo'] = $dir.$name;
        }
      }
      catch (Kohana_Exception  $e) {
      }
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
