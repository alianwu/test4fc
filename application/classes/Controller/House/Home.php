<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Home extends Controller_Template {
  
  public $template = 'template-house';

  public function before()
  {
    parent::before();
    $this->model_house = Model::factory('House_New');
  }

  public function action_index()
  {
    $house = $this->model_house->get_house_front($this->city_id, 1);
    $view =  View::factory('house/index');
    $view->bind_global('house', $house);
    $this->template->container = $view;
  }

  public function action_near()
  {
    $view =  View::factory('house/near');
    $this->template->container = $view;
  }

  public function action_detail()
  {
    $hid = (int) $this->request->param('id');
    $data = $this->model_house->get_house_one_front($hid);
    if ($data == NULL) {
      throw new Kohana_HTTP_Exception_404();
    }
    $view =  View::factory('house/detail');
    $view->bind_global('house', $data);
    $this->template->container = $view;
  }

} // End House
