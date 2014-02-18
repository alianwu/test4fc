<?php defined('SYSPATH') OR die('No direct script access.');

class Pretty {

  public static function postgrs_array($str = NULL)
  {
    $ret = array();
    if (empty($str) || $str == '{}') {
      return $ret;
    }
    $ret = explode(',', mb_substr($str, 1, -1));
    return $ret;
  }

}
