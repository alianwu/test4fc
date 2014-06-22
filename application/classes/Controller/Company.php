<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Company extends Controller_Template {

  protected $model_company;

  public function before()
  {
    parent::before();
    $this->model_company = Model::factory('Company');
  }

  public function action_index()
  {
    $type = Arr::get($_GET, 'type', 'company_near');
    $view =  View::factory('company/company');
    $view->set_global('type', $type);
    $this->view($view);
  }


  public function action_detail()
  {
    $id = (int) $this->request->param('id'); 
    if ($this->manager) {
      $data = $this->model_company->get_one($id, TRUE);
    }
    else {
      $data = $this->model_company->get_one_front($id);
    }
    if ($id 
      && $data) {
      if ($data->city_area_shop) {
        $where = array(
            'page' => 1,
            'shop' => $data->city_area_shop);
        $house = Model::factory('House')->get_list_front($this->city_id, $where);
        if ($house) {
          $data->house_near = $house->as_array();
        }
        $school = Model::factory('School')->get_list_front($this->city_id, $where);
        if ($school) {
          $data->school_near = $school->as_array();
        }
      }
      $this->model_company->update_hot($id, 'hit');
      $view = View::factory('company/company_detail');
      $data->image = json_decode($data->image);
      $view->bind_global('company', $data);
      $this->view($view);
    }
    else {
      throw Kohana_HTTP_Exception_404();
    }
  }

} // End Home
