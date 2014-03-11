<?php defined('SYSPATH') OR die('No direct script access.');

class Pretty {

  public static function postgrs_array($str = NULL, $index=NULL)
  {
    $ret = array();
    if (empty($str) || $str == '{}') {
    }
    elseif (is_array($str)) {
    }
    else {
      $ret = str_getcsv(trim($str, '{}'));
    }

    if ($index === NULL) {
      return $ret;
    }
    else {
      return isset($ret[$index])?$ret[$index]:NULL;
    }
  }

  public static function article_category($id)
  {
    $cache_category = Cache::instance()->get('article_category', FALSE);
    if (isset($cache_category[$id])) {
      return $cache_category[$id];
    }

    $cache_category = Model::factory('Article_Category')->get_list_pretty();
    Cache::instance()->set('article_category', $cache_category);

    return isset($cache_category[$id])?$cache_category[$id]:'未知'; 
  }

  public static function article_tag($tag, $html=true)
  {
    $tag = self::postgrs_array($tag);
    if (empty($tag)) {
      return '';
    }
    $tag_str = '';
    $cache_tag = Cache::instance()->get('article_tag', FALSE);
    if (empty($cache_tag)) {
      $cache_tag = Model::factory('Article_Tag')->get_list_pretty();
      Cache::instance()->set('article_tag', $cache_tag);
    }
    foreach($tag as $v) {
      if(isset($cache_tag[$v])) {
        $tag_str .= $html?HTML::anchor('article_home/tag/'.$v, $cache_tag[$v]):$cache_tag[$v].' ';

      }
    }
    return $tag_str;
  }
  
  public static function formatted_input($data, $split = " ")
  {
    $ret = '';
    if (empty($data) || trim($data) == '') {
      return $ret;
    }
    $arr = self::postgrs_array($data);      
    foreach($arr as $v) {
      $ret .= $v.$split;
    }
    return $ret;
  }

  public static function article_pre_next($aid, $category)
  {
    $article = Model::factory('Article');
    $pre =  $article->get_pre_one($aid, $category);
    $next = $article->get_next_one($aid, $category);
    return array(0=>$pre, 1=>$next);
  }

  public static function article_relation($relation)
  {
    $article = self::postgrs_array($relation);
    return false;
  }

}
