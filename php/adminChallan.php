<?php
include('db_connection.php');

$data = stripslashes(file_get_contents("php://input"));
$data = json_decode($data, true);


switch ($data['flag']) {
  case '1':
    $output = "";
    $flag = false;
    $res = getTableData("SELECT `identification_number`, `aadhaar_number`, `username`, `status` FROM `officer_detail` WHERE `status`='ACTIVE' OR `status`='BLOCK'");
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $adhar = $arr['aadhaar_number'];
      $arr2 = getTableData("SELECT `full_name`, `email` FROM `owner_detail` WHERE `aadhaar_number`=$adhar");
      $arr2 = mysqli_fetch_array($arr2, MYSQLI_ASSOC);
      $id = $arr['identification_number'];
      $arr3 = getTableData("SELECT `date_time` FROM `officer_activity` WHERE `officer_id`='$id' AND `status`='active' ORDER BY `id` DESC LIMIT 1");
      $arr3 = mysqli_fetch_array($arr3, MYSQLI_ASSOC);
      if ($arr3 == null) {
        $arr3['date_time'] = "NULL";
      }
      $output .= "<tr class='list-tbody-row'><td>{$id}</td><td>{$arr2['full_name']}</td>
      <td>{$arr['username']}</td><td>{$arr2['email']}</td><td>{$arr3['date_time']}</td>
      <td id='off_list_stat' class='off_stat'>{$arr['status']}</td>
      <td><div class='off_action_div'> <button id='off_action_btn'>ACTION</button>
      <div class='off_action_dropdown'><button id='off_action_block' class='block_btn' off_id='{$id}'>BLOCK</button>
      <button id='off_action_active' off_id='{$id}'>ACTIVATE</button>
      <button id='off_action_delete' off_id='{$id}'>DELETE</button></div></div></td>
      <td><button id='off_activity_btn' off_id='{$id}'>ACIVITY</button></td>
      <td><button id='off_mssg_btn' off_id='{$id}' off_name={$arr2['full_name']}>MESSAGE</button></td>
      </tr>
      ";
      $flag = true;
    }
    if ($flag) {
      echo ($output);
    } else {
      echo "<tr class='list-tbody-row'><td colspan='9'>EMPTY</td></tr>";
    }
    break;
  case '2':

    $output = "";
    $flag = false;
    $res = getTableData("SELECT `identification_number`, `profile_photo`, `signature_photo`, `aadhaar_number`, `username` FROM `officer_detail` WHERE `status`='PENDING'");

    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $adhar = $arr['aadhaar_number'];
      $arr2 = getTableData("SELECT `full_name`,`mobile`, `email`,`gender` FROM `owner_detail` WHERE `aadhaar_number`=$adhar");
      $arr2 = mysqli_fetch_array($arr2, MYSQLI_ASSOC);
      $profImg = '../Database_Uploaded/Officers_data/' . $arr['profile_photo'];
      $signImg = '../Database_Uploaded/Officers_data/' . $arr['signature_photo'];
      $id = $arr['identification_number'];
      $name = $arr2['full_name'];
      $mobile = $arr2['mobile'];
      $email = $arr2['email'];
      $gen = $arr2['gender'];
      $user = $arr['username'];
      $output .=
        "
      <tr class='list-tbody-row'>
      <td>
          <div class='off_list_img'>
              <img src='$profImg' alt='' id='off_list_img1'>
              <img src='$signImg' alt='' id='off_list_img2'>
          </div>
      </td>
      <td>$id</td>
      <td>$name</td>
      <td>$user</td>
      <td>$gen</td>
      <td>$mobile/$email</td>
      <td><button id='request_approve_btn' off_id=$id>APPROVE</button></td>
      <td><button id='request_decline_btn' off_id=$id>DECLINE</button></td></tr>";
      $flag = true;
    }

    if ($flag) {
      echo ($output);
    } else {
      echo "<tr class='list-tbody-row'><td colspan='7'>EMPTY</td></tr>";
    }
    break;
  case '3':
    $id = $data['off_id'];
    $flag = false;

    $sql = "UPDATE `officer_detail` SET `status`='BLOCK' WHERE `identification_number`='$id'";
    $res = mysqli_query($con, $sql);
    if ($res) {
      $flag = true;
    }


    if ($flag) {
      echo json_encode("$id has Blocked");
    } else {
      echo "Something Wrong!!";
    }
    break;
  case '4':
    $id = $data['off_id'];
    $flag = false;

    if (getTableData("UPDATE `officer_detail` SET `status`='ACTIVE' WHERE `identification_number`='$id'")) {
      $flag = true;
    }
    if ($flag) {
      echo json_encode("$id has Activated");
    } else {
      echo "Something Wrong!!";
    }
    break;
  case '5':
    $id = $data['off_id'];
    $flag = false;

    if (getTableData("DELETE FROM `officer_detail` WHERE `identification_number`='$id'")) {
      $flag = true;
    }
    if ($flag) {
      echo json_encode("$id has Activated");
    } else {
      echo "Something Wrong!!";
    }
    break;
  case '6':
    $output = "";
    $flag = false;
    $res = getTableData("SELECT * FROM `officer_msg_detail` WHERE `msg_to`='ADMIN' ORDER BY `date_time`");
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $id = $arr['officer_id'];
      $res2 = getTableData("SELECT `full_name` FROM `owner_detail` WHERE `aadhaar_number`=(SELECT `aadhaar_number` FROM `officer_detail` WHERE `identification_number`='$id')");
      $arr2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
      $name = $arr2['full_name'];
      $msg = $arr['msg'];
      $date = $arr['date_time'];
      $output .= "
      <tr class='list-tbody-row mssg-tbody-row' id='msg_tbody_row'>
      <td class='msg_id'>$id</td>
      <td class='msg_name'>$name</td>
      <td id='msg_row' class='msg_msg'>$msg
      </td>
      <td class='msg_date'>$date</td>
  </tr>";
      $flag = true;
    }

    if ($flag) {
      echo ($output);
    } else {
      echo "<tr class='list-tbody-row mssg-tbody-row' id='msg_tbody_row'><td colspan='5'>EMPTY</td></tr>";
    }
    break;
  case '7':
    $num = $data['pm_num'];
    $id = $data['off_id'];
    $check = getTableData("SELECT * FROM `permit_number_detail` WHERE `officer_id`='$id'");
    $check = mysqli_num_rows($check);
    if ($check >= 1) {
      echo "Permit Number Already Issued For ID No.: $id";
    } else {
      $res = getTableData("INSERT INTO `permit_number_detail`(`permit_number`, `officer_id`, `status`) VALUES ('$num','$id','ACTIVE')");
      if ($res) {
        echo "Succes!";
      } else {
        echo "Error$res";
      }
    }
    break;
  case '8':
    $res = getTableData("SELECT * FROM `permit_number_detail`");
    $output = "";
    $flag = false;
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $sr = $arr['id'];
      $permit = $arr['permit_number'];
      $offId = $arr['officer_id'];
      $stat = $arr['status'];

      $output .= "<tr><td>$sr</td><td>$permit</td>
      <td>$offId</td><td>$stat</td></tr>";

      $flag = true;
    }
    if ($flag) {
      echo $output;
    } else {
      echo "<tr>
      <td colspan='4'>EMPTY</td></tr>";
    }
    break;
  case '9':
    $res = getTableData("SELECT * FROM `user_msg_detail`");
    $output = "";
    $flag = false;
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $ch = $arr['challan_no'];
      $sub = $arr['subject'];
      $msg = $arr['message'];
      $date = $arr['date_time'];

      $output .= "<tr class='list-tbody-row mssg-tbody-row' id='complaint_tbody_row'>
      <td class='cmplt_id'>$ch</td>
      <td id='msg_row' class='cmplt_sub'>$sub</td>
      <td id='msg_row' class='cmplt_msg'>$msg</td>
      <td class='cmplt_date'>$date</td>
  </tr>";

      $flag = true;
    }
    if ($flag) {
      echo $output;
    } else {
      echo "<tr>
      <td colspan='4'>EMPTY</td></tr>";
    }

    break;
  case '10':
    $off_id = $data['off_id'];
    $msg = $data['msg'];
    $query = "INSERT INTO `officer_msg_detail`(`officer_id`, `msg_from`, `msg_to`, `msg`) VALUES ('$off_id','ADMIN','$off_id','$msg')";
    $res = mysqli_query($con, $query);
    echo "$res";
    break;
  case '11':
    $off_id = $data['off_id'];
    $query = "SELECT `id`, `status`, `date_time` FROM `officer_activity` WHERE `officer_id`='$off_id' ORDER BY `date_time` DESC LIMIT 10";
    $res = mysqli_query($con, $query);
    // $res = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $output = "";
    $flag = 0;
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $flag = 1;
      $id = $arr['id'];
      $stat = strToUpper($arr['status']);
      $datetime = $arr['date_time'];
      $output .= "<tr><td>{$id}</td><td>{$stat}</td><td>$datetime</td></tr>";
    }
    if ($flag)
      echo $output;
    else
      echo "<tr><td colspan='3' style='text-align: center;'>EMPTY</td></tr>";
    break;
  default:
    echo "Something Wrong!!";
}

function getTableData($query)
{
  include('db_connection.php');
  $res = mysqli_query($con, $query);
  return $res;
}
