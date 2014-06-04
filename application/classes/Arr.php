<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Array helper.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Arr extends Kohana_Arr {

	public static function config(array $array)
  {
    $values = array();
    foreach($array as $v) {
      $values[$v['group']][$v['name']] =  $v['value'];
    }
    return $values;
  }

}

