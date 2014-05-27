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
        or self::type($file, array('jpg', 'png', 'jpeg', 'gif')) == FALSE) {
      return FALSE ;
    }
    return TRUE;
  }

  public static function save_get_path($file)
  {
    if (is_array($file['name'])) {
      foreach($file['name'] as $k => $v) {
        $tmp = array(
          'name'=> $file['name'][$k],
          'type'=> $file['type'][$k],
          'tmp_name'=> $file['tmp_name'][$k],
        );
        $ret[] = self::_save_get_path($tmp);
      }
      return $ret;
    } 
    return self::_save_get_path($file);
  }
  
  public static function _save_get_path($file)
  {
    if (empty($file['name'])) {
      return NULL;
    }
    $cache = Cache::instance()->get('core');
    $dir =  Arr::path($cache, 'config.core.upload_dir');
    $static_url =  Arr::path($cache, 'config.core.static_url');
    $safe_dir = $dir .date("Y/m") . DIRECTORY_SEPARATOR;;
    $real_dir = $safe_dir;
    if (is_dir($real_dir) == FALSE) {
      mkdir($real_dir, 0755, TRUE);
    }
    $dirname = $real_dir;
		$pi = pathinfo($file['name']);
		$name = $pi['filename'];
		$extension = strtolower($pi['extension']);
    $filename = $name.'_'.uniqid().'.'.$extension;
    //$filename = $file['name'].'_'.uniqid().'.'.File::ext_by_mime($file['type']);
    $fullpath = self::save($file, $filename, $dirname);
    if ($fullpath) {
      return $static_url.$safe_dir.basename($fullpath);
    }
    return NULL;
  }

}
