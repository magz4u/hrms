<?php

/**
 * Checkin form
 */
class Attendance_Form_checkin extends Zend_Form
{

  public function init() {
    $this->setMethod("post");
    $this->setAttrib('id','checkin');
    $this->setAttrib('action',BASE_URL.'attendance/checkin');

    $checkInAction = new Zend_Form_Element_Submit('submit');
    $checkInAction->setAttrib('name','checkin');
    $checkInAction->setAttrib('id','checkinbtn');
    $checkInAction->setLabel('Check IN');

    $checkOutAction = new Zend_Form_Element_Submit('button');
    $checkOutAction->setAttrib('name','checkout');
    $checkOutAction->setAttrib('id','checkoutbtn');
    $checkOutAction->setLabel('Check Out');

    $this->addElements(array($checkInAction,$checkOutAction));

  }
}
