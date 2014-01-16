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
abstract class Controller_Test_Template extends Controller {

  /**
   * @var  View  page template
   */
  public $template = 'test/template';

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
    
    $this->auto_render = ! (bool) Arr::get($_SERVER, 'HTTP_X_PJAX', FALSE);
    
    if ($this->auto_render === TRUE)
    {
      // Load the template
      $this->template = View::factory($this->template);
    }
  }

  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    if ($this->auto_render === TRUE)
    {
      $this->response->body($this->template->render());
    }

    parent::after();
  }

}
