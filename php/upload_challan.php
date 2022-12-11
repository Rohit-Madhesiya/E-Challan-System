<?php
include('db_connection.php');
session_start();
// This function will return a random
// string of specified length
function challan_number()
{
  // String of all alphanumeric character
  $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  // Shuffle the $str_result and returns substring
  // of specified length
  return substr(str_shuffle($str_result), 0, 6);
}

$targetFolder = "F:/Installation/XAMP/htdocs/E-Challan/Database_Uploaded/Challan_data/";
$img = $_FILES["fineVeh"]["name"];
$tempImg = $_FILES["fineVeh"]["tmp_name"];

$imgFile = $targetFolder . basename($img);
$imgFT1 = pathinfo($imgFile, PATHINFO_EXTENSION);
$imgName = basename($img);
if (isset($_POST['number']))
  $SelectedNum = $_POST['number'];
else
  $SelectedNum = "";
// $SelectedNum = isset($_POST['number']) ? $_POST['number'] : "";
$num = strtoupper($_POST['vehInpt']);
$add = strtoupper($_POST['addInpt']);
$date = $_POST['dateInpt'];
$officer_id = $_POST['officer_id'];
$penalty = $_POST['penaltyInpt'];
$license = "";
$adhaar = "";
$challanNo = challan_number();

switch ($SelectedNum) {

  case "RC":
    // include_once('db_connection.php');
    $query = "SELECT `aadhaar_number` FROM `vehicle_detail` WHERE `registration_number`='{$num}'";
    $res = mysqli_query($con, $query);
    $row = mysqli_num_rows($res);
    if ($row == 0) {
      echo "Invalid RC Number";
      exit();
    } else {
      $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
      $adhaar = $row['aadhaar_number'];

      $query = "SELECT `license_number` FROM `owner_detail` WHERE `aadhaar_number`={$adhaar}";
      $res = mysqli_query($con, $query);
      $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
      $license = $row['license_number'];
    }
    break;
  case "Chassis":
    // include_once('db_connection.php');
    $query = "SELECT `aadhaar_number`, `registration_number` FROM `vehicle_detail` WHERE `chassis_number`='{$num}'";
    $res = mysqli_query($con, $query);
    $row = mysqli_num_rows($res);
    if ($row == 0) {
      echo "Invalid Chassis Number";
      exit();
    } else {
      $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
      $adhaar = $row['aadhaar_number'];
      $num = $row['registration_number'];

      $query = "SELECT `license_number` FROM `owner_detail` WHERE `aadhaar_number`={$adhaar}";
      $res = mysqli_query($con, $query);
      $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
      $license = $row['license_number'];
    }
    break;
  case "License":
    // include_once('db_connection.php');
    $query = "SELECT `aadhaar_number` FROM `owner_detail` WHERE `license_number`='{$num}'";
    $res = mysqli_query($con, $query);
    $row = mysqli_num_rows($res);
    if ($row == 0) {
      echo "Invalid Driving License Number";
      exit();
    } else {
      $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
      $adhaar = $row['aadhaar_number'];
      $license = $num;

      $query = "SELECT `registration_number` FROM `vehicle_detail` WHERE `aadhaar_number`={$adhaar}";
      $res = mysqli_query($con, $query);
      $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
      $num = $row['registration_number'];
    }
    break;
}

$query = "SELECT `rule_id` FROM `temp_rules`";
$res = mysqli_query($con, $query);
$row = mysqli_num_rows($res);
if ($row == 0) {
  echo "Traffic Violated Rules Not Selected";
  exit();
} else {
  $main_arr = array();
  while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
    $main_arr[] = $arr['rule_id'];
  }
  $rule_str = implode(',', $main_arr);
}
$sql = "INSERT INTO `challan_detail`(`challan_number`, `picture`, `registration_number`, `license_number`, `aadhaar_number`, `officer_id_number`,
   `penalties`, `violated_rules`, `place`, `date_time`, `paid`) VALUES ('$challanNo','$imgName',
   '$num','$license',$adhaar,'$officer_id',$penalty,'$rule_str','$add','$date','UNPAID')";

if (mysqli_query($con, $sql) && move_uploaded_file($tempImg, $imgFile)) {
  echo "1";
} else if ($license == "") {
  echo "Invalid Number Found";
} else {
  echo "Something Went Wrong from the server";
}

session_destroy();
