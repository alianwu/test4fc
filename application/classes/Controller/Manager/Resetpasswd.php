<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manager_Resetpasswd extends Controller_Accounts_Resetpassport {
  
  public $view = 'manager';
  public $model = 'User';
  public $success = 'manager_ressetpasswd/success';

} 
