<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_System_City extends Controller_Manager_Template {

  public $type = 1;
  public $cid = 0;
  public $pcid = 0;
  
  public function before()
  {
    parent::before();
    
    $this->model = Model::factory('City');

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
      
      $this->template->container = View::factory('manager/system/city');
    }
  }

  public function action_index()
  {
    $city = $this->model->get_city($this->pcid, $this->type);

    $view = View::factory('manager/system/city_index');
    $view->bind_global('city', $city);

    $this->template->container->detail = $view;
  }
  
  public function action_add()
  {  
    $this->template->container->detail = View::factory('manager/system/city_add');
  }
  
  public function action_editor()
  {
    $data = $this->model->get_city_one($this->cid);

    if($data === FALSE) {
      throw new Kohana_HTTP_Exception_404();
    }
    $_POST = $data;
    $this->action_add();
  }
  public function action_update()
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
      $data = $post->as_array();
      $ret = $this->model->save($data);
      $this->template->set_global('message', $ret);
    }
    else {
      $error = $post->errors('city/add');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_add();
  }

  public function action_display()
  {
    $data = $this->model->update_display($this->cid);
    $this->template->bind_global('message', $data);
    $this->action_index();
  }

  public function action_delete()
  {
    $data = $this->model->delete($this->cid);
    $this->template->bind_global('message', $data);
    $this->action_index();
  }

  public function action_logout()
  {
    Model::factory('User')->logout();
    $this->redirect('manager_sigin');
  }
} 
