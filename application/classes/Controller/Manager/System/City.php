<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_City extends Controller_Manager_Template {

  public $type = 1;
  public $cid = 0;
  public $pcid = 0;
  
  public function before()
  {
    parent::before();
    
    $this->model = $this->model_city;;

    $this->type = (int) Arr::get($_GET, 'type', $this->type);
    $this->cid  = (int) Arr::get($_GET, 'cid', 0);
    $this->pcid = (int) Arr::get($_GET, 'pcid', 0);

    if(isset($this->setting['type'][$this->type]) == FALSE) {
      $this->type = 1;
    }

    if ($this->auto_render == TRUE) { 
      $this->template->bind_global('type', $this->type);
      $this->template->bind_global('cid', $this->cid);
      $this->template->bind_global('pcid', $this->pcid);
    }
  }

  public function action_index()
  {
    $city = $this->model->get_city($this->pcid, $this->type);

    $view = View::factory('manager/system/city_index');
    $view->bind_global('city', $city);

    $this->view($view);
  }
  
  public function action_editor()
  {  
    if ($this->cid) {
      $data = $this->model->get_city_one($this->cid);
      if($data) {
        $_POST = $data;
      }
    }
    $view = View::factory('manager/system/city_editor');
    $this->view($view);
  }
  
  public function action_save()
  {
    $post = Validation::factory( Arr::extract($_POST, 
                                  array('cid', 'parent_cid', 'name', 'value', 'type', 'display', 'csrf', 'weight')) );
    $post->rules('cid', array(
            array('digit'),
        ))
        ->rules('parent_cid', array(
          array('not_empty'),
          array('digit'),
        ))
        ->rules('name', array(
          array('not_empty'),
        ))
        ->rules('value', array(
        ))
        ->rules('type', array(
          array('digit'),
          array('not_empty'),
        ))
        ->rules('weight', array(
          array('digit'),
          array('not_empty'),
        ))
        ->rules('csrf', array(
          array('not_empty'),
          array('Security::check'),
        ))
        ->rules('display', array());

    if( $post->check() ) {
      $data = $post->data();
      $ret = $this->model->save_one($data);
      $this->cache->delete('cache');
      $this->result($ret);
    }
    else {
      $error = $post->errors('city/add');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_editor();
  }

  public function action_display()
  {
    $ret = $this->model->display_one($this->cid);
    $this->result($ret);
    $this->action_index();
  }

  public function action_delete()
  {
    $ret = $this->model->delete_one($this->cid);
    $this->result($ret);
    $this->action_index();
  }

  public function action_logout()
  {
    Model::factory('User')->logout();
    $this->redirect('manager_sigin');
  }
} 
