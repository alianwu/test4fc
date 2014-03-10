<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_Search extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Setting');    
  }

  public function action_index()
  {
    $view = View::factory('manager/system/search');
    $keyword = $this->model->get_list('search');
    $view->bind_global('keyword', $keyword);
    $this->template->container = $view;
  }

  public function action_update()
  {
    $post = Validation::factory( Arr::extract($_POST, 
                                  array('cid', 'name', 'value', 'csrf')) );
    $post->rules('cid', array(
            array('digit'),
        ))
        ->rules('name', array(
          array('not_empty'),
        ))
        ->rules('value', array(
          array('not_empty'),
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ));

    if( $post->check() ) {
      $data = $post->data();
      $ret = $this->model->update($data);
      $this->result($ret);
    }
    else {
      $error = $post->errors('system');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_index();
  }

  public function action_editor()
  {
    $name = Arr::get($_GET, 'name', 'null');
    $data = $this->model->get($name, 'search');
    $_POST = $data;
    $this->action_index();

  }

  public function action_delete()
  {
    $name = Arr::get($_GET, 'name', 'null');
    $ret = $this->model->clear($name);
    $this->result($ret);
    $this->action_index();
  }

} 
