<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Vote extends Controller_Manager_Template {

  public function before()
  {
    parent::before();
    $this->model = Model::factory('Vote');
    $this->redirect('manager_home/404');
  }

  public function action_index()
  {
    $page = Arr::get($_GET, 'page', 1);
    $vote = $this->model->get_list(NULL, $page);
    $category = $this->model_category->get_list_pretty();

    $view = View::factory('manager/vote/vote_index');
    $view->bind('vote', $vote);
    $view->bind('category', $category);

    $this->view($view);
  }

  public function action_editor()
  { 
    $id = Arr::get($_GET, 'id', 0);
    if($id) {
      $data = $this->model->get_one($id);
      if ($data) {
        $_POST = $data;
      }
    }
    $view = View::factory('manager/vote/vote_editor');
    $this->view($view);
  }

  public function action_save()
  {
  }

  public function action_api()
  {
  }

} 
