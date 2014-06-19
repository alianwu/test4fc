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
      $company = $this->model_company->get_one($id, TRUE);
    }
    else {
      $company = $this->model_company->get_one_front($id);
    }
    if ($id 
      && $company) {
      $this->model_company->update_hot($id, 'hit');
      $view = View::factory('company/company_detail');
      $company->image = json_decode($company->image);
      $view->bind_global('company', $company);
      $this->view($view);
    }
    else {
      throw Kohana_HTTP_Exception_404();
    }
  }

} // End Home
