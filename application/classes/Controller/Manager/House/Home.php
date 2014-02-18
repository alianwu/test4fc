<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_House_Home extends Controller_Manager_Template {

  public $hid;

  public function before()
  {
    parent::before();
    $this->hid = Arr::get($_GET, 'hid', 0);
    $this->model = Model::factory('House_New');
    if ($this->auto_render == TRUE) { 
      $this->template->container = View::factory('manager/house/house');
    }
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $house = $this->model->get_house($this->city_id, $page);

    $view = View::factory('manager/house/house_index');
    $view->bind('house', $house);

    $this->template->container->detail = $view;
  }
  
  public function action_search()
  { 
    $key = Arr::get($_GET, 'key');
    $house = $this->model->get_house_search($this->city_id, $key);
    $view = View::factory('manager/house/house_index');
    $this->template->container->detail = $view;
  }

  public function action_add()
  { 
    $underground = $this->model_city->get_city_pretty($this->city_id, 2);
    $this->template->container->detail = View::factory('manager/house/house_add')
                                              ->set('underground', $underground);
  }
  
  public function action_editor()
  {
    $data = $this->model->get_house_one($this->hid);
    if($data === FALSE) {
      throw new Kohana_HTTP_Exception_404();
    }

    $_POST = $data;
    $this->action_add();
  }

  public function action_update()
  {
    $fields = array(
      'hid' => array(
            array('not_empty'),
            array('digit'),
          ),
      'city_id' => array(
            array('digit'),
            array('not_empty'),
          ),
      'name' => array(
            array('not_empty'),
          ),
      'status' => array(
            array('max_length', array(':value', 30)),
          ),
      'hot' => array(
            array('digit'),
          ),
      'price' => array(
            array('digit'),
          ),
      'price_history' => array(
            array('max_length', array(':value', 100)),
          ),
      'house_date' => array(
            array('date'),
          ),
      'house_date_sale' => array(
            array('date'),
          ),
      'house_corp' => array(
            array('max_length', array(':value', 30)),
          ),
      'house_speculator' => array(
            array('max_length', array(':value', 30)),
          ),
      'house_service' => array(
            array('max_length', array(':value', 30)),
          ),
      'house_service_price' => array(
            array('numeric'),
          ),
      'acreage' => array(
            array('digit'),
          ),
      'acreage_building' => array(
            array('digit'),
          ),
      'copy' => array(
            array('digit'),
          ),
      'car' => array(
            array('digit'),
          ),
      'rate_1' => array(
            array('numeric'),
          ),
      'rate_2' => array(
            array('numeric'),
          ),
      'phone' => array(
            array('max_length', array(':value', 300)),
          ),
      'school' => array(
            array('max_length', array(':value', 30)),
          ),
      'school_near' => array(
            array('max_length', array(':value', 30)),
          ),
      'city_area' => array(
            array('digit'),
          ),
      'city_area_shop' => array(
            array('digit'),
          ),
      'underground' => array(
            array('digit'),
          ),
      'underground_platform' => array(
            array('digit'),
          ),
      'building' => array(
            array('digit'),
          ),
      'decorate' => array(
            array('digit'),
          ),
      'address' => array(
            array('not_empty'),
            array('max_length', array(':value', 30)),
          ),
      'lat' => array(
            array('numeric'),
          ),
      'lng' => array(
            array('numeric'),
          ),
      'description' => array(
            array('max_length', array(':value', 500)),
          ),
      'weight' => array(
            array('digit'),
            array('not_empty'),
          ),
      'display' => array(
            array('digit'),
          ),
      'csrf' => array(
            array('not_empty'),
            array('Security::check'),
          ),
    );
    $post = Validation::factory( Arr::extract($_POST,  array_keys($fields)) );
    foreach ($fields as $k => $v) {
      $post->rules($k, $v);
    }
    if( $post->check() ) {
      $data = $post->as_array();
      $ret = $this->model->save($data);
      $this->template->set_global('message', $ret);
      if ($ret['error'] == FALSE) {
        $this->action_update_success($ret['data']);
        return 0;
      }
    }
    else {
      $error = $post->errors('city/add');
      $this->template->bind_global('error', $error);
    }
    
    $this->action_add();
  }
  

  public function action_update_success($hid = NULL)
  {
    $this->template->container->detail = View::factory('manager/house/house_add_success')
                                              ->set('hid', $hid);
  }

  public function action_attachment($hid = NULL)
  {
    $hid = Arr::get($_GET, 'hid', $hid);
    $attachment = $this->model->get_house_one($hid);
    if ($attachment) {
      $this->template->container->detail = View::factory('manager/house/house_attachment')
        ->set('hid', $hid)
        ->set('attachment', $attachment);
    }
    else {
      throw new Kohana_HTTP_Exception_404();
    }
  }
  public function action_display()
  {
    $data = $this->model->update_display($this->hid);
    $this->template->bind_global('message', $data);
    $this->action_index();
  }

  public function action_delete()
  {
    $data = $this->model->delete($this->hid);
    $this->template->bind_global('message', $data);
    $this->action_index();
  }

} 
