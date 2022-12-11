<?php
include('db_connection.php');
session_start();

$data = stripslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$type = $data['type'];
$id = trim($data['userName']);
$pass = trim($data['password']);

switch ($type) {
  case 1:
    $query = "SELECT `aadhaar_number`,`identification_number` FROM officer_detail WHERE (identification_number='$id' OR username='$id') AND password='$pass' AND status='ACTIVE'";

    try {
      $result = mysqli_query($con, $query);
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);
      if ($count == 1) {
        $status = "active";
        $adhar = $row['aadhaar_number'];
        $officer_id = $row['identification_number'];
        $query2 = "INSERT INTO `officer_activity`(`officer_id`, `status`) VALUES ('$officer_id','$status')";

        if ($con->query($query2) === TRUE) {
          echo "success";
          $_SESSION['officer_adhaar'] = $adhar;
          $_SESSION['officer_id'] = $officer_id;
        } else {
          echo "Something Wrong";
        }
      } else {
        echo "Login Failed, Invalid ID number or Password!";
      }
    } catch (Exception $e) {
      echo "Exception: " . $e;
    }
    exit();
    break;
  case 2:
    $query = "SELECT * FROM `admin_details` WHERE `username`='$id' AND `password`='$pass'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
      echo "success";
      $_SESSION['admin_username'] = $id;
    } else {
      echo "Login Failed, Invalid ID number or Password!";
    }
    exit();
    break;
}
