<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Faq extends Controller_Template {
  
  public $template = 'template';

  public function before()
  {
    parent::before();
    $this->model_house = Model::factory('House');
    $this->model = Model::factory('House_Faq');
    $this->model_detail = Model::factory('House_Faq_Detail');
  }

  private function _action_list($type='hot')
  {
    $hid = $this->request->param('id');
    $house = $this->model_house->get_one_front($hid, 1);
    if ($house == NULL) {
      throw new Kohana_HTTP_Exception_404();
    }
    switch ($type) {
      case 'hot':
        $faq = $this->model->get_list_hot_front($hid, 1);
        break;
      case 'latest':
        $faq = $this->model->get_list_front($hid, 1);
        break;
      default:
    }

    $view_list =  View::factory('house/faq_list');
    $view_list->bind_global('faq', $faq);
    $view_list->bind_global('house', $house);
    
    if ($this->auto_render == TRUE) {
      $view =  View::factory('house/faq');
      $view->container = $view_list;
      $this->template->container = $view;
    }
    else {
      $this->response->body($view_list); 
    }
  }

  public function action_index()
  {
    $this->_action_list('hot');
  }
  public function action_latest()
  {
    $this->_action_list('latest');
  }

  public function action_detail()
  {
    $faqid = $this->request->param('id');
    $faq = $this->model->get_one_front($faqid);
    $faqd = $this->model_detail->get_list_front($faqid, 1);
    if ($faq == NULL) {
      throw new Kohana_HTTP_Exception_404();
    }
    $view =  View::factory('house/faq_detail');
    $view->bind_global('faq', $faq);
    $view->bind_global('faqd', $faqd);
    $this->template->container = $view;
  }

} // End House
