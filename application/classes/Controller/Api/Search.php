<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Search extends Controller_Api {

  public function action_index()
  {
    $data = NULL;
    $query = Arr::extract($_GET, array('page', 'output', 'city_area', 'type', 'ctype', 'price', 'underground'));
    $page = max((int)$query['page'], 1);
    switch($query['type']){
      case '1':
        $data = $this->model_house->get_search_front($query, $page);
        break;
      default:
    }


    $this->body['error'] = 0;
    $this->body['data'] = $data;
  
  }

} // End API
