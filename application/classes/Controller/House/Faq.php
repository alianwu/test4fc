<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Faq extends Controller_Template {
  
  public $template = 'template-house';

  public function before()
  {
    parent::before();
    $this->model_house = Model::factory('House_New');
    $this->model_faq = Model::factory('House_Faq');
    $this->model_faq_detail = Model::factory('House_Faq_Detail');
  }

  public function action_index()
  {
    $hid = $this->request->param('id');
    $house = $this->model_house->get_house_one_front($hid, 1);
    if ($house == FALSE) {
      throw new Kohana_HTTP_Exception_404();
    }
    $faq = $this->model_faq->get_faq_front($hid, 1);
    $view =  View::factory('house/faq_index');
    $view->bind_global('house', $house);
    $view->bind_global('faq', $faq);
    $this->template->container = $view;
  }

  public function action_detail()
  {
    $faqid = $this->request->param('id');
    $faq = $this->model_faq->get_faq_one_front($faqid);
    $faqd = $this->model_faq_detail->get_faq_front($faqid, 1);
    if ($faq == FALSE) {
      throw new Kohana_HTTP_Exception_404();
    }
    $view =  View::factory('house/faq_detail');
    $view->bind_global('faq', $faq);
    $view->bind_global('faqd', $faqd);
    $this->template->container = $view;
  }

} // End House
