<?php defined('SYSPATH') OR die('No direct script access.');

class Upload extends  Kohana_Upload {

  public static function check($file)
  {
    $name = $file['name'];
    if(is_array($name)) {
      foreach($name as $k=>$v) {
        $tmp = array(
            'name' => $v,
            'type' => $file['type'][$k],
            'tmp_name' => $file['tmp_name'][$k],
            'error' => $file['error'][$k],
            'size' => $file['size'][$k],
          );
          if (!self::_check($tmp)) {
            return FALSE;
          }
      }
    }
    return self::_check($file);
  }

  public static function _check($file) 
  {
    if (self::valid($file) == FALSE 
        or self::type($file, array('jpg', 'png', 'gif')) == FALSE) {
      return FALSE ;
    }
    return TRUE;
  }
}
