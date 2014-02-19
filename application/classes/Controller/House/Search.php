<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Search extends Controller_Template {
  
  public $template = 'template-search';

  public function before()
  {
    parent::before();
    $this->model = Model::factory('House_New');
  }

  public function action_index()
  {
    $this->template->container = View::factory('house/search');
  }

  public function action_result()
  {
    $data = NULL;
    $query = Arr::extract($_GET, array('page', 'type', 'ctype', 'price', 'underground'));
    switch($type){
      case 1:
        $page = max((int)$query['page'], 1);
        $data = $this->model->get_search($query, $page);
        break;
      default:
        
    }
    $view =  View::factory('house/search_result');
    $view->bind_global('result', $data);
    $this->template->container = $view;
  }

} // End House
