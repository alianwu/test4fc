<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House extends Controller_Template {
  
  protected $view_home;
  
  protected $model_article, 
            $model_category; 

  public function before()
  {
    parent::before();
    $this->model_house = Model::factory('House');
    if ($this->auto_render) {
    }
  }

  public function action_index()
  {
    $htype = Arr::get($_GET, 'htype', 'house_near');
    if (!in_array($htype
        , array('house_hot', 'house_latest', 'house_article', 'house_near'))) {
      $htype = 'house_near';
    }
    $view =  View::factory('house/house');
    $view->set('htype', $htype);
    $this->view($view);
  }

  public function action_detail()
  {
    $hid = (int) $this->request->param('id');
    if ($this->manager) {
      $data = $this->model_house->get_one($hid);
    }
    else {
      $data = $this->model_house->get_one_front($hid);
    }
    if ($data == NULL) {
      throw new Kohana_HTTP_Exception_404();
    }

    $where = array(
        'page' => 1,
        'shop' => $data->city_area_shop
      );
    $school = Model::factory('School')->get_list_front($this->city_id, $where);
    if ($school) {
      $data->school_near = $school->as_array();
    }
    $company = Model::factory('Company')->get_list_front($this->city_id, $where);
    if ($company) {
      $data->company_near = $company->as_array();
    }
    $this->model_house->update_hot($hid, 'hit');
    $view =  View::factory('house/house_detail');
    $data->schools = json_decode($data->schools);
    $data->image = json_decode($data->image);
    $view->bind_global('house', $data);
    $this->view($view);
  }

  public function action_search()
  {
    $view =  View::factory('search/reslut');
    $this->view($view);
  }


} // End Home
