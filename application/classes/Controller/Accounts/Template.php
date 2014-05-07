<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Controller_Accounts_Template extends Controller {

  public $view = 'default';
  public $model = 'Accounts_Core';
  public $redirect_url = 'http%3A%2F%2F([a-z_\.]+)?puhua.co%2F';
  public $success = 'success';
  
  public $template = 'accounts/template';
  public $auto_render = TRUE;
  
  protected $accounts;
  protected $accounts_ip;
  protected $accounts_ip_num;
  protected $accounts_ip_num_maxfail = 3;

  public function before()
  {
    parent::before();
    
    $accounts = Kohana::$config->load('accounts');

    if (isset($accounts->{$this->view})) {
      $this->accounts = array_merge($accounts->{$this->view}, $accounts->default);
    }
    else {
      $this->accounts = $this->default;
    }
    
    if (isset($this->accounts['maxfail'])) {
      $this->accounts_ip_num_maxfail = $this->accounts['maxfail'];
    }

    $this->accounts_ip = 'accounts_'. Request::$client_ip;
    $this->accounts_ip_num = Cache::instance()->get($this->accounts_ip, 0);

    $this->model = Model::factory($this->model);
    $this->model->set_view($this->view);

    if ($this->auto_render === TRUE)
    {
      // Load the template
      $this->template = View::factory($this->view . DIRECTORY_SEPARATOR  . $this->template);
      $this->template->bind_global('accounts', $this->accounts);
    }
  }

  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    if ($this->auto_render === TRUE)
    {
      $this->template->bind_global('view', $this->view);
      $this->response->body($this->template->render());
    }

    parent::after();
  }

}
