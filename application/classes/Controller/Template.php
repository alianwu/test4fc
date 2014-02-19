<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract controller class for automatic templating.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Controller_Template extends Controller {

  /**
   * @var  View  page template
   */
  public $template = 'template';

  public $core = NULL;
  public $setting = NULL;
  public $user = NULL;
  public $city_pretty = NULL;
  public $city_cache = NULL;
  public $city_area = NULL;
  public $city_id = 0;
  public $city_lng = '116.404';
  public $city_lat = '39.915';
  public $pagination = NULL;
  public $token = '';
  public $alert = '';

  /**
   * @var  boolean  auto render template
   **/
  public $auto_render = TRUE;
  
  /**
   * Loads the template [View] object.
   */
  public function before()
  {
    parent::before();

    $http_x_pjax =  (bool) Arr::get($_SERVER, 'HTTP_X_PJAX', FALSE);
    if ($http_x_pjax) {
      $this->auto_render = FALSE;
    }

    $this->model_city = Model::factory('City');
    $this->city_pretty = $this->model_city->get_city_pretty();

    $this->user = $this->get_user('member.user');
    $this->city_id = $this->get_city_id();
    $this->city_area = $this->model_city->get_city_pretty($this->city_id);
    $this->city_cache = $this->model_city->get_city_cache();
    $this->core = Kohana::$config->load('core');
    $this->setting = Kohana::$config->load('setting');
    $this->token = Security::token();
    $this->pagination = Kohana::$config->load('pagination.default');

    if ($this->auto_render === TRUE)
    {
      // Load the template
      $this->template = View::factory($this->template);
      $this->template->bind_global('core', $this->core);
      $this->template->bind_global('setting', $this->setting);
      $this->template->bind_global('user', $this->user);
      $this->template->bind_global('city_pretty', $this->city_pretty);
      $this->template->bind_global('city_cache', $this->city_cache);
      $this->template->bind_global('city_area', $this->city_area);
    }
  }

  public function check_user()
  {
   return $this->user !== NULL; 
  }

  public function get_user($session_name)
  {
    $user = Session::instance()->get($session_name, NULL);
    return $user;
  }

  public function get_city_id()
  {
    $city_id = (int) Cookie::get('city_id');
    if(isset($this->city_pretty[$city_id])) {
      return $city_id;
    }
    return 1;
  }

  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    if ($this->request->method() == 'POST') {
      $this->token = Security::token( TRUE );
    }

    if ($this->auto_render === TRUE) {
      $this->template->set_global('city_id', $this->city_id);
      $this->template->set_global('city_lng', $this->city_lng);
      $this->template->set_global('city_lat', $this->city_lat);
      $this->template->set_global('token', $this->token);
      $this->template->set_global('alert', $this->alert);
      $this->template->set_global('pagination', $this->pagination);
      $this->response->body($this->template->render());
    }

    parent::after();
  }

}
