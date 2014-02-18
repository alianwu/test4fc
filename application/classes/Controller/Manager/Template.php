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
abstract class Controller_Manager_Template extends Controller_Template {

  /**
   * @var  View  page template
   */
  public $template = 'manager/template';

  /**
   * Loads the template [View] object.
   */
  public function before()
  {
    parent::before();
    $this->user = Session::instance()->get('manager.user', NULL);
    if ($this->user === NULL) {
      $this->redirect('manager_sigin');
    }
    $this->pagination = Kohana::$config->load('pagination.manager');
  }


  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    parent::after();
  }

}
