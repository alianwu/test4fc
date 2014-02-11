<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Sigin extends Controller_Accounts_Sigin {
  
  public $view = 'manager';
  public $model = 'User';
  public $redirect_url = 'manager_home';
  public $success = 'manager_sigin/success';

} 
