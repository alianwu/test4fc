<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract controller class for automatic templating.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Controller_Manager_Template extends Controller_Template {

  /**
   * @var  View  page template
   */
  public $template = 'manager/template';

  public $model_log;

  /**
   * Loads the template [View] object.
   */
  public function before()
  {
    parent::before();
    $this->user = $this->manager;
    if ($this->user === NULL ) {
      $auth = Cookie::get('auth');
      $user = json_decode($auth, TRUE);
      if ($user) {
        $this->user = $user;
      }
    }
    if( $this->user === NULL or $this->user['ip'] <> Request::$client_ip) {
      if ($this->pjax == TRUE) {
        echo '页面已失效';
        exit;
      }
      else {
        $this->redirect('manager_sigin');
      }
    }
    $this->pagination = Kohana::$config->load('pagination.manager');
  }

  public function action_404()
  {
    $view = View::factory('manager/404');
    $this->view($view);
  }

  public function action_display()
  {
    $id = (int) Arr::get($_GET, 'id', 0);
    if ($id) {
      $ret = $this->model->update_hot($id, 'display');
      $this->result($ret?TRUE:FALSE);
    }
    $this->action_index();
  }

  public function action_api()
  {
    $this->model_log = Model::factory('Log');

    $post = Validation::factory($_POST)
      ->rules('act', array(
          array('not_empty'),
          array('in_array', array(':value', array('delete', 'hot', 'unhot'))),
        ))
      ->rules('id', array(
          array('not_empty')
        ));
    $action = Arr::get($_POST, 'act', '');
    if ($action == 'delete') {
      $post->rules('desc', array(
          array('not_empty'),
          array('max_length', array(':value', 100)),
          array('min_length', array(':value', 5)),
        ))
        ->rules('lindex', array(
            array('not_empty'),
            array('digit')
          ));
    }
    if ($check = $post->check()) {
      $data = $post->data();
      if($action == 'delete') {
        $ret = $this->model->delete($data['id']);
        $log = array(
           'uid' => $this->user['id'],
           'author' => $this->user['name'],
           'ip' => Request::$client_ip,
           'lindex' => $data['lindex'],
           'created' => 'now()',
           'controller' => $this->request->controller(),
           'log' => $data['desc'].' -- '.(implode(',', $data['id'])),
           'action' => 0,
          );
        $this->model_log->save($log);
        $this->result($ret?0:1);
      }
    }
    else {
      $error = $post->errors('manager/api');
      $this->result(FALSE, NULL, array('error'=>$error));
    }
    return $check;
  }

  /**
   * Assigns the template [View] as the request response.
   */
  public function after()
  {
    parent::after();
  }

}
