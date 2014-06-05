<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_House extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('House');
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $data = array();
    $data['area'] = Arr::get($_GET, 'area', 0);
    $data['keyword'] = Arr::get($_GET, 'keyword', '');
    $data['display'] = Arr::get($_GET, 'display', '');
    $house = $this->model->get_list($this->city_id, $page, $data);
    $view = View::factory('manager/house/house_index');
    $view->bind('list', $house);
    $this->view($view);
  }
  
  public function action_search()
  { 
    $key = Arr::get($_GET, 'key');
    $house = $this->model->get_search($this->city_id, $key);
    $view = View::factory('manager/house/house_index');
    $this->template->container->detail = $view;
  }

  public function action_editor()
  { 
    if ($this->request->method() == HTTP_Request::GET) {
      $id = (int) Arr::get($_GET, 'id', 0);
      $data = $this->model->get($id, FALSE);
      if ($data) {
        $_POST = $data;
        $_POST['schools'] = json_decode($_POST['schools'], TRUE); 
        $_POST['image'] = json_decode($_POST['image'], TRUE); 
      }
    }
    else {
      if ($sl = Arr::get($_POST, 'school', array(array('s'=>0,'sb'=>'')))) {
        foreach ($sl as $k => $v) {
          $_POST['schools'][] =  array('s'=>$v, 'sb'=>Arr::path($_POST, 'school_building.'.$k));
        }
      }
      if ($ih = Arr::get($_POST, 'image_history', FALSE)) {
        foreach ($ih as $k => $v) {
          $_POST['image'][] =  array(
            'src'=>$v, 
            'group'=>Arr::path($_POST, 'image_group.'.$k),
            'alt'=>Arr::path($_POST, 'image_desc.'.$k));
        }
      }
    }
    $school = Model::factory('School')->get_pretty($this->city_id);
    $underground = $this->model_city->get_city_pretty($this->city_id, 2);
    $view =  View::factory('manager/house/house_editor')
               ->bind('underground', $underground)
               ->bind('school', $school);
    $this->view($view);
  }

  public function action_save()
  {
    $fields = array(
      'hid' => array(
            array('not_empty'),
            array('digit'),
          ),
      'type' => array(
            array('digit'),
            array('not_empty'),
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
      'stick' => array(
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
            array('is_array')),
      'school_building' => array(
            array('is_array')),
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
      'image_history' => array(
            array('not_empty'),
            array('is_array')),
      'image_desc' => array(
            array('not_empty'),
            array('is_array')),
      'image_group' => array(
            array('not_empty'),
            array('is_array')),
      'lat' => array(
            array('numeric'),
          ),
      'lng' => array(
            array('numeric'),
          ),
      'description' => array(
            array('min_length', array(':value', 1)),
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
          ),
    );
    $post = Validation::factory( Arr::extract($_POST,  array_keys($fields)) );
    foreach ($fields as $k => $v) {
      $post->rules($k, $v);
    }
    if( $post->check() ) {
      $data = $post->data();
      $ret = $this->model->save_one($data, $this->user);
      if ($ret) {
        $this->result(0);
        $result = View::factory('manager/house/house_editor_success');
        $result->set('ret', $ret);
        $this->view($result);
        return 0;
      }
      else {
        $this->result(1);
      }
    }
    else {
      $error = $post->errors('city/add');
      $this->template->bind_global('error', $error);
    }
    $this->action_editor();
  }

  public function action_display()
  {
    $id = (int) Arr::get($_GET, 'id', 0);
    if ($id) {
      $ret = $this->model->update_hot($id, 'display');
      $this->result($ret?TRUE:FALSE);
    }
    $this->action_index();
  }

  public function action_api()
  {
    $check = parent::action_api();
    if ($check) {
      //
    }
    $this->action_index();
  }

} 
