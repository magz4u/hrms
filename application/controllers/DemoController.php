<?php

class DemoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$geocoder = new Geocode_Geocoder("google");
		$coordinates = new Geocode_Coordinates();
		
		$coordinates->setCoordinates(array('lat'=>38.286488,'lng'=>-107.578125));
		$geocoder->setCoordinates($coordinates);
		$address = $geocoder->retrieveAddress();
		
		$this->view->address = $address->getUnformattedAddress();
		$this->view->street = $address->get("route");
		$this->view->city = $address->get("sublocality");
		$this->view->state = $address->get("administrative_area_level_1");
		$this->view->county = $address->get("administrative_area_level_2");
		$this->view->country = $address->get("country");
		
		$this->view->longitude = $coordinates->getLongitude();
		$this->view->latitude = $coordinates->getLatitude();
		
		$address2 = new Geocode_Address();
		$address2->set("street", "park street and otis dr");
		$address2->set("city", "alameda");
		$address2->set("state", "california");
		$address2->set("country", "usa");
		
		$geocoder->setAddress($address2);
		$coordinates2 = $geocoder->retrieveCoordinates();
		
		$this->view->address2 = $address2->getFullAddress();
	    	$this->view->latitude2 = $coordinates2->getLatitude();
		$this->view->longitude2 = $coordinates2->getLongitude();
	}
}