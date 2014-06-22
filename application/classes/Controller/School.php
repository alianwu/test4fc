<?php defined('SYSPATH') or die('No direct script access.');

class Controller_School extends Controller_Template {

  protected $model_school;

  public function before()
  {
    parent::before();
    $this->model_school = Model::factory('School');
  }

  public function action_index()
  {
    $type = Arr::get($_GET, 'type', 'school_near');
    $view =  View::factory('school/school');
    $view->set_global('type', $type);
    $this->view($view);
  }


  public function action_detail()
  {
    $id = (int) $this->request->param('id'); 
    if ($this->manager) {
      $data = $this->model_school->get_one($id, TRUE);
    }
    else {
      $data = $this->model_school->get_one_front($id);
    }
    if ($id 
      && $data) {
      if ($data->city_area_shop) {
        $where = array(
            'page' => 1,
            'shop' => $data->city_area_shop
          );
        $house = Model::factory('House')->get_list_front($this->city_id, $where);
        if ($house) {
          $data->house_near = $house->as_array();
        }
        $company = Model::factory('Company')->get_list_front($this->city_id, $where);
        if ($company) {
          $data->company_near = $company->as_array();
        }
      }
      $this->model_school->update_hot($id, 'hit');
      $view = View::factory('school/school_detail');
      $data->image = json_decode($data->image);
      $view->bind_global('school', $data);
      $this->view($view);
    }
    else {
      throw Kohana_HTTP_Exception_404();
    }
  }

} // End Home
