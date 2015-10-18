<?php

/**
 * AddressApplication.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AddressApplication extends Application
{
  protected $login_action = array('account', 'signin');

  public function getRootDir()
  {
    return dirname(__FILE__);
  }

  protected function registerRoutes()
  {
    return array(
      '/'
      => array('controller' => 'address', 'action' => 'index'),
      '/address/post'
      => array('controller' => 'address', 'action' => 'post'),
      '/address/register'
      => array('controller' => 'address', 'action' => 'register'),
      '/address/update/:id'
      => array('controller' => 'address', 'action' => 'update'),
      '/address/delete/:id'
      => array('controller' => 'address', 'action' => 'delete'),

      '/user/:user_name'
      => array('controller' => 'address', 'action' => 'user'),
      '/user/:user_name/address/:id'
      => array('controller' => 'address', 'action' => 'show'),

      '/account'
      => array('controller' => 'account', 'action' => 'index'),
      '/account/:action'
      => array('controller' => 'account'),

    );
  }

  protected function configure()
  {
    $this->db_manager->connect('master', array(
      'dsn'      => 'mysql:dbname=myapp;host=localhost',
      'user'     => 'myappuser',
      'password' => 'myapppass',
    ));
  }
}
