<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Sigup extends Controller_Accounts_Sigup {
  
  public $view = 'manager';
  public $model = 'User';
  public $redirect_url = 'manager_sigin';
  public $success = 'manager_sigup/success';

} 
