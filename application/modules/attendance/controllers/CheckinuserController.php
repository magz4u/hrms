
<?php

//define("apikey",  "AIzaSyC8BOrqc0ASmawZIxr42yVUJ-NiGbJ2YUI");
class Attendance_CheckinuserController extends Zend_Controller_Action

{
	private $options;
	private $tbl_emploginlog;
	private $userlog_model;
	/**
	* Init.
	*
	* @see Zend_Controller_Action::init()
	*/
	public

	function init()
	{
		$this->_options = $this->getInvokeArg('bootstrap')->getOptions();
	}

	 

	/* checkin Action */
	function checkinAction()
	{

		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$loginId = $auth->getStorage()->read()->id;
			$loginuserRole = $auth->getStorage()->read()->emprole;
			$loginuserGroup = $auth->getStorage()->read()->group_id;
			$loginempId = $auth->getStorage()->read()->employeeId;
			$loginuserName = $auth->getStorage()->read()->userfullname;
			$loginuserEmail = $auth->getStorage()->read()->emailaddress;

			$data['emprole'] = $loginuserRole;
			$data['group_id'] = $loginuserGroup;
			$data['employeeId'] = $loginempId;
			$data['userfullname'] = $loginuserName;
			$data['emailaddress'] = $loginuserEmail;

			$view = Zend_Layout::getMvcInstance()->getView();
			$checkForm = new Attendance_Form_checkin();
			$this->view->form = $checkForm;

			$this->view->hideCheckout = false;

		  $checkinObj1 = new Attendance_Model_Checkin();

      $all=$checkinObj1->getAllentry($loginempId);

			//$checkInAddresObj = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=12.932390,77.693684&sensor=true"));
			//print_r($checkInAddresObj->results[0]->formatted_address);
			//print_r($data['userfullname']);
			$valid = $checkinObj1->entryValidation($loginempId);
			$this->view->noofcheckin = $valid[0]['Tickets'];
			if($this->view->noofcheckin == 0) {
				$this->view->hideCheckout = true;
			}

		  $lastcheckin=$checkinObj1->getlastcheckinentry($loginempId);
		  $this->view->messagelck ="You have checked in Today @  " .$lastcheckin[0]['checkintime'];

		  $lastcheckout=$checkinObj1->getlastcheckoutentry($loginempId);
			if(isset($lastcheckout[0]['checkouttime'])) {
				$this->view->noofcheckouts = $lastcheckout[0]['noofcheckouts'];
				$this->view->hideCheckout = true;
				$this->view->messagelco ="You have checked out Today @" .$lastcheckout[0]['checkouttime'];
			}
	}
}
	/* handle Checkin form Submit */
	function handlecheckinAction() {
		if ($this->getRequest()->getPost()) {
			$auth = Zend_Auth::getInstance();
			if ($auth->hasIdentity()) {
				$loginId = $auth->getStorage()->read()->id;
				$loginuserRole = $auth->getStorage()->read()->emprole;
				$loginuserGroup = $auth->getStorage()->read()->group_id;
				$loginempId = $auth->getStorage()->read()->employeeId;
				$loginuserName = $auth->getStorage()->read()->userfullname;
				$loginuserEmail = $auth->getStorage()->read()->emailaddress;

				$data['emprole'] = $loginuserRole;
				$data['group_id'] = $loginuserGroup;
				$data['employeeId'] = $loginempId;
				$data['userfullname'] = $loginuserName;
				$data['emailaddress'] = $loginuserEmail;

				$attendanceForm = new Attendance_Form_checkin();
				$curdate = new Zend_Date();
				$date = new Zend_Db_Expr('NOW()');
				$curdate->setTimezone('Asia/Kolkata');
				$this->view->showCheckout = true;
				$data['date']=$date; // current date

				$checkinObj = new Attendance_Model_Checkin();
				if ($this->_request->getParam('checkin') == 'Check IN') {
					// checkin flow is here
					$data['latitude'] = $this->_request->getParam('latitude');
					$data['longitude'] = $this->_request->getParam('longitude');
					//Capture geo API location address
					$checkInAddressObj = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$data['latitude'].",".$data['longitude']."&sensor=true"));
					//$checkInAddresObj->results[0]->formatted_address;
					//print_r($checkInAddresObj->results[0]->formatted_address);
					$data['checkinaddress'] = $checkInAddressObj->results[0]->formatted_address;
					$data['checkintime'] = $curdate->toString('HH:mm:ss');
				  $inserted = $checkinObj->addCheckinEntry($data);
					if ($inserted ) {
						$this->view->form = $attendanceForm;
						$this->view->message = "You have checked in Today @   ".$data['checkintime'];
					}
				} else if ($this->_request->getParam('checkout') == 'Check Out') {
					// checkout flow is here
					$data['latitude_co'] = $this->_request->getParam('latitude_co');
					$data['longitude_co'] = $this->_request->getParam('longitude_co');
					//Capture geo API location address
				  $checkOutAddressObj = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$data['latitude_co'].",".$data['longitude_co']."&sensor=true"));
				  $data['checkoutaddress'] = $checkOutAddressObj->results[0]->formatted_address;
          //print_r($checkOutAddressObj->results[0]->formatted_address);
					$updated = $checkinObj->addSaveEntry($curdate->toString('YYYY-MM-dd HH:mm:ss'),$curdate->toString('YYYY-MM-dd'),$loginempId,$data['latitude_co'],$data['longitude_co'],$data['checkoutaddress']);
					if ($updated == 'update') {
						$this->view->form = $attendanceForm;
						$this->view->showCheckout = false;
						$this->view->message = "You have checked out Time @   ".    $curdate->toString('YYYY-MM-dd HH:mm:ss');
          // $this->_helper->redirector('checkinuser','checkin');
					$this->_helper->redirector($url, array('attendance'));
					} else {
						//$this->view->message = "Problem updating checkout time.";
					}
				} else {
					$attendanceForm = new Attendance_Form_checkin();
					$this->view->form = $attendanceForm;
					$this->view->message = 'Somewhere ';
				}
			}
		} else {
			$this->view->message = 'Not valid login. Please login.';
		}
	}

}
