<?php defined('SYSPATH') or die('No direct script access.');

class Controller_House_Faq extends Controller_Faq {
  

  public function before()
  {
    parent::before();
    $this->type = 1;
  }

} // End House
