<?php

class Attendance_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initAppAutoload() {

		$auth= Zend_Auth::getInstance();
		$storage = $auth->getStorage()->read();
	}

	/** URL Masking */
	public function _initRoute()
	{
		$router = Zend_Controller_Front::getInstance()->getRouter();

		$route = new Zend_Controller_Router_Route('attendance', array(
  		'module' => 'attendance',
  		'controller' => 'checkinuser',
  		'action' => 'checkin'
		));

    $checkinRoute = new Zend_Controller_Router_Route('attendance/checkin', array(
      'module' => 'attendance',
      'controller' => 'checkinuser',
      'action' => 'handlecheckin'
    ));



		$router->addRoute('attendance', $route);
    $router->addRoute('checkin', $checkinRoute);

	}
}
