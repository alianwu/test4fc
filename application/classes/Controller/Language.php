<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Language extends Controller {

  public function action_index()
  {
    echo  'Language: '. I18n::lang(). ' etc. username：'.__('username');
  }

}