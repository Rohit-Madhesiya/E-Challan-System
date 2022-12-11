<?php
include('db_connection.php');

$data = stripslashes(file_get_contents("php://input"));
$data = json_decode($data, true);
$outputTable = "";
switch ($data['flag']) {
  case '1':
    $lic_num = $data['number'];
    if (!empty($lic_num)) {
      $sql = "SELECT * FROM `license_detail` WHERE `license_number`='$lic_num'";
      $res = mysqli_query($con, $sql);
      if (mysqli_num_rows($res) == 1) {
        $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $main_arr = array();
        $main_arr[] = array('license_number' => $arr['license_number'], 'name' => $arr['full_name'], 'photo' => $arr['profile_photo'], 'issue_date' => $arr['issue_date'], 'validity_date' => $arr['validity_date'], 'date_of_birth' => $arr['date_of_birth'], 'issuing_office' => $arr['issuing_office'], 'period' => $arr['period']);

        $add = $arr['house_no'] . ", " . $arr['area'] . ", " . $arr['post_office'] . ", " . $arr['city'] . ", " . $arr['district'] . ", " . $arr['state'] . ", " . $arr['pincode'];
        $add = array('address' => $add);
        $main_arr[] = $add;

        $temp[] = array('vehicle_class' => explode(',', $arr['vehicle_class_allowed']));
        $main_arr[] = $temp;
        echo json_encode($main_arr);
      } else {
        echo "Invalid License Number!";
      }
    } else {
      echo "Number not Found!";
    }
    break;
  case '2':
    $rc_num = $data['number'];
    if (!empty($rc_num)) {
      $sql = "SELECT * FROM `vehicle_detail` WHERE `registration_number`='$rc_num'";
      $res = mysqli_query($con, $sql);
      if (mysqli_num_rows($res) == 1) {
        $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $adhar = (int) $arr['aadhaar_number'];
        $query = "SELECT `full_name`, `house_no`, `street`, `landmark`, `area`, `post_office`, `city`, `district`, `state`, `pincode` FROM `owner_detail` WHERE `aadhaar_number`={$adhar}";
        $res2 = mysqli_query($con, $query);
        if (mysqli_num_rows($res2) == 1) {
          $res2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
          $name = array('name' => $res2['full_name']);
          $addres = array('address' => $res2['house_no'] . ", " . $res2['street'] . ", " . $res2['landmark'] . ", " . $res2['area'] . ", " . $res2['post_office'] . ", " . $res2['city'] . ", " . $res2['district'] . ", " . $res2['state'] . ", " . $res2['pincode']);
          // $name = array('name' => $res2['full_name']);
          $main_arr = array_merge($arr, $name, $addres);
          echo json_encode($main_arr);
        } else {
          echo "Something Wrong with the Owner!";
        }
      } else {
        echo "Something Went Wrong!";
      }
    } else {
      echo "Number Not Found!";
    }
    break;
  case '3':
    $rc_num = $data['number'];
    if (!empty($rc_num)) {
      $sql = "SELECT * FROM `vehicle_insurance_detail` WHERE `policy_number`='$rc_num' OR `vehicle_registration_number`='$rc_num'";
      $res = mysqli_query($con, $sql);
      if (mysqli_num_rows($res) == 1) {
        $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $adhar = $arr['insured_person_aadhaar'];
        $vehicle_rc = $arr['vehicle_registration_number'];
        $query1 = "SELECT `full_name`, `date_of_birth`, `house_no`, `street`, `landmark`, `area`, `post_office`, `city`, `district`, `state`, `pincode` FROM `owner_detail` WHERE `aadhaar_number`={$adhar}";
        $query2 = "SELECT `chassis_number`, `engine_number`, `manufacture_year`, `make`, `model`, `registration_date` FROM `vehicle_detail` WHERE `registration_number`='$vehicle_rc'";
        $res1 = mysqli_query($con, $query1);
        $res2 = mysqli_query($con, $query2);
        $arr1 = mysqli_fetch_array($res1, MYSQLI_ASSOC);
        $arr2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
        $addres = array('address' => $arr1['house_no'] . ", " . $arr1['street'] . ", " . $arr1['landmark'] . ", " . $arr1['area'] . ", " . $arr1['post_office'] . ", " . $arr1['city'] . ", " . $arr1['district'] . ", " . $arr1['state'] . ", " . $arr1['pincode']);
        $arr1 = array('name' => $arr1['full_name'], 'date_of_birth' => $arr1['date_of_birth']);
        $main_arr = array_merge($arr, $arr1, $addres, $arr2);
        echo json_encode($main_arr);
      } else {
        echo "Something Went Wrong!";
      }
    } else {
      echo "Number Not Found!";
    }
    break;
  case '4':
    $rc_num = $data['number'];
    if (!empty($rc_num)) {
      $sql = "SELECT * FROM `puc_detail` WHERE `certificate_number`='$rc_num' OR `vehicle_registration_number`='$rc_num'";
      $res = mysqli_query($con, $sql);
      if (mysqli_num_rows($res) == 1) {
        $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $veh_emm = array('vehicle_emmission' => explode(',', $arr['vehicle_emmission']));
        $veh_rc = $arr['vehicle_registration_number'];
        $sql2 = "SELECT `chassis_number`, `engine_number` FROM `vehicle_detail` WHERE `registration_number`='$veh_rc'";
        $res2 = mysqli_query($con, $sql2);
        $arr2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
        $main_arr = array();
        $main_arr = array_merge($arr, $arr2, $veh_emm);
        echo json_encode($main_arr);
      } else {
        echo "Something Went Wrong!";
      }
    } else {
      echo "Number Not Found!";
    }
    break;
  case '5':
    $officer_id = $data['num'];
    $officer_id = substr($officer_id, 12);
    $sql_query = "SELECT `challan_number`,`penalties`, `date_time` FROM `challan_detail` WHERE `officer_id_number`='$officer_id' ORDER BY `date_time`";
    $result = mysqli_query($con, $sql_query);
    $outputTable = "";
    if (mysqli_num_rows($result) > 0) {
      $srNum = 0;
      while ($arr = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $srNum++;
        $outputTable .= "<tr><td>" . $srNum . "</td><td>" . $arr['challan_number'] . "</td><td>" . $arr['date_time'] . "</td><td>Rs.   " . $arr['penalties'] . "</tr>";
      }
    } else {
      $outputTable = "<tr><td colspan='4'>EMPTY</td></tr>";
    }
    $main_arr = array('output' => $outputTable, 'srNum' => $srNum);
    echo json_encode($main_arr);
    break;
  case '6':
    $officer_id = $data['num'];
    $officer_id = substr($officer_id, 12);
    $sql = "SELECT `profile_photo`, `signature_photo`, `aadhaar_number`, `username` FROM `officer_detail` WHERE `identification_number`='$officer_id'";
    $res = mysqli_query($con, $sql);
    $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $adhar = $arr['aadhaar_number'];
    unset($arr['aadhaar_number']);
    $sql2 = "SELECT `full_name`, `date_of_birth`, `gender`, `mobile`, `email` FROM `owner_detail` WHERE `aadhaar_number`={$adhar}";
    $res2 = mysqli_query($con, $sql2);
    $arr2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);

    $sql3 = "SELECT `date_time` FROM `officer_activity` WHERE `officer_id`='$officer_id' AND `status`='inactive' ORDER BY `id` DESC LIMIT 1";
    $res3 = mysqli_query($con, $sql3);
    $arr3 = mysqli_fetch_array($res3, MYSQLI_ASSOC);
    $temp_arr = array_merge($arr2, $arr, $arr3, array('officer_id' => $officer_id));
    echo json_encode($temp_arr);
    break;
    //  Officer's msg transfer to admin
  case '7':
    $off_id = $data['off_id'];
    $off_id = substr($off_id, 12);
    $msg = $data['msg'];
    $query1 = "SELECT `full_name` FROM `owner_detail` WHERE `aadhaar_number`=(SELECT `aadhaar_number` FROM `officer_detail` WHERE `identification_number`='$off_id')";
    $res = mysqli_query($con, $query1);
    $row = mysqli_num_rows($res);
    if ($row == 0) {
      echo "Invalid Officer ID";
      exit();
    }
    $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $off_name = $arr['full_name'];
    $msg_query = "INSERT INTO `officer_msg_detail`(`officer_id`, `msg_from`, `msg_to`, `msg`) VALUES ('$off_id','$off_name','ADMIN','$msg')";
    $res = mysqli_query($con, $msg_query);
    if ($res) {
      echo "Message Sent Successfully..";
    } else {
      echo "Something went wrong!!!!";
    }
    break;
  case '8':
    // officer's password changing
    $off_id = $data['off_id'];
    $off_id = substr($off_id, 12);
    $curr_pass = $data['curr_pass'];
    $new_pass = $data['new_pass'];
    $query = "SELECT `password` FROM `officer_detail` WHERE `identification_number`='$off_id'";
    $res = mysqli_query($con, $query);
    $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
    if ($arr['password'] == $curr_pass) {
      $query = "UPDATE `officer_detail` SET `password`='$new_pass' WHERE `identification_number`='$off_id'";
      $res = mysqli_query($con, $query);
      if ($res) {
        echo "Success...";
      } else {
        echo "Something went wrong!!";
      }
    } else {
      echo "Wrong Password";
    }
    break;
  case '9':
    $off_id = $data['off_id'];
    $off_id = substr($off_id, 12);
    echo $off_id;
    $query = "SELECT `msg`, `date_time` FROM `officer_msg_detail` WHERE `msg_to`='$off_id' ORDER BY `date_time`";
    $res = mysqli_query($con, $query);
    $output = "";
    $flag = 0;
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $flag = 1;
      $msg = $arr['msg'];
      $dateT = $arr['date_time'];
      $output .= "<tr><td>{$msg}</td><td>{$dateT}</td></tr>";
    }
    if ($flag) {
      echo $output;
    } else {
      echo "<tr><td colspan='2' style='text-align:center;'>EMPTY</td></tr>";
    }
    break;
  default:
    echo "Something Wrong in jQuery!";
}
exit();
