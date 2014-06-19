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
      $school = $this->model_school->get_one($id, TRUE);
    }
    else {
      $school = $this->model_school->get_one_front($id);
    }
    if ($id 
      && $school) {
      $this->model_school->update_hot($id, 'hit');
      $view = View::factory('school/school_detail');
      $school->image = json_decode($school->image);
      $view->bind_global('school', $school);
      $this->view($view);
    }
    else {
      throw Kohana_HTTP_Exception_404();
    }
  }

} // End Home
