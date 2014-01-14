<?php defined('SYSPATH') OR die('No direct script access.');
abstract class Controller_Common extends Controller {

  public $template = 'accounts/template';
  public $auto_render = TRUE;
  
  protected $accounts_ip;
  protected $accounts_ip_num;

  public function before()
  {
    parent::before();
    
    $this->accounts_ip = 'accounts-'. Request::$client_ip;
    $this->accounts_ip_num = Session::instance()->get($this->accounts_ip, 0);

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
