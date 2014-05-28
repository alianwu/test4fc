<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Sigin extends Controller_Accounts_Sigin {
  
  public $redirect_url = 'sigin';
  public $success = 'sigin/success';
   
} // End Sigin
