<?php
include('db_connection.php');

$mode = $_POST['mode'];
$num = $_POST['challanNumber'];

if (empty($mode)) {
  echo "Select Number Type written in Challan Number.";
} else {
  if ($mode === "challan") {
    $query = "SELECT * FROM `challan_detail` WHERE `challan_number`='$num'";
  } else if ($mode === "rc") {
    $query = "SELECT * FROM `challan_detail` WHERE `registration_number`='$num'";
  } else if ($mode === "license") {
    $query = "SELECT * FROM `challan_detail` WHERE `license_number`='$num'";
  }
  $res = mysqli_query($con, $query);
  if (mysqli_num_rows($res) == 1) {
    $arr1 = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $arr2 = fetch_owner_data($arr1['aadhaar_number']);
    $arr1 = array_merge($arr1, $arr2);
    $rules_arr[] = array_map('intval', explode(',', $arr1['violated_rules']));
    $arr3 = rules_show_array($rules_arr[0]);
    $main_arr = array([$arr1], $arr3);
    echo json_encode($main_arr);
  } else {
    echo "More than one challan have issued with same number!!";
  }
}

function rules_show_array($rules_arr)
{
  require('db_connection.php');
  $arr = array();
  foreach ($rules_arr as $rules_id) {
    $id = (int)$rules_id;
    $sql = "SELECT * FROM `traffic_rules_detail` WHERE `id`=$id";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) === 1)
      $arr[] = mysqli_fetch_array($res, MYSQLI_ASSOC);
  }
  return $arr;
}

function fetch_owner_data($adhar)
{
  require('db_connection.php');
  $sql1 = "SELECT * FROM `owner_detail` WHERE `aadhaar_number`={$adhar}";
  $sql2 = "SELECT `chassis_number` FROM `vehicle_detail` WHERE `aadhaar_number`={$adhar}";
  $r1 = mysqli_query($con, $sql1);
  $r2 = mysqli_query($con, $sql2);

  $temp = mysqli_fetch_array($r1, MYSQLI_ASSOC);
  $name = $temp['full_name'];
  $address = $temp['house_no'] . ', ' . $temp['street'] . ', ' . $temp['post_office'] . ', ' . $temp['city'] . ', ' . $temp['district'] . ', ' . $temp['pincode'];

  $r2 = mysqli_query($con, $sql2);
  $temp = mysqli_fetch_array($r2, MYSQLI_ASSOC);
  $chassis = $temp['chassis_number'];

  $arr = array('name' => $name, 'address' => $address, 'chassis_number' => $chassis);
  return $arr;
}
