<?php

class Attendance_Model_Checkin extends Zend_Db_Table_Abstract
{
  protected $_name = 'tbl_emploginlog';
  protected $_primary = 'id';

  //protected $_currentRow = null;

  public function addCheckinEntry($data) {
    $this->insert($data);
    $id = $this->getAdapter()->lastInsertId('tbl_emploginlog');
    return $id;
  }

  public function addSaveEntry($curdatetime,$curdate,$employeeId,$latitude_co,$longitude_co,$checkoutaddress) {
    $data = array(
      'checkouttime'  => $curdatetime,
      'latitude_co'   => $latitude_co,
      'longitude_co'  => $longitude_co,
      'checkoutaddress' => $checkoutaddress

    );
    $userWhere = array('employeeId=?' => $employeeId ,'date=?' => $curdate);

    $this->update($data, $userWhere);
    return 'update';
  }
  public function entryValidation($employeeId){

    $db = Zend_Db_Table::getDefaultAdapter();
    $query = "SELECT count(date) AS Tickets FROM `tbl_emploginlog` WHERE employeeId='$employeeId' AND Date(date)=CURRENT_DATE()";
    $result = $db->query($query)->fetchAll();
    return $result;
   }


    public function getlastcheckinentry($employeeId){

      $db = Zend_Db_Table::getDefaultAdapter();
      $query = "SELECT date,checkintime FROM tbl_emploginlog  WHERE employeeId='$employeeId' AND DATE(date)=CURDATE() ORDER by checkintime  ";

      $result = $db->query($query)->fetchAll();
      return $result;
    }

    public function getlastcheckoutentry($employeeId){

      $db = Zend_Db_Table::getDefaultAdapter();
      $query = "SELECT count(date) as noofcheckouts,checkouttime FROM tbl_emploginlog  WHERE employeeId='$employeeId' AND DATE(date)=CURDATE() ORDER by checkouttime ";

      $result = $db->query($query)->fetchAll();
      return $result;
    }

    public function getAllentry($employeeId){

      $db = Zend_Db_Table::getDefaultAdapter();
      $query = "SELECT * from tbl_emploginlog WHERE employeeId='$employeeId' order by date DESC";

      $result = $db->query($query)->fetchAll();
      return $result;
    }


}
