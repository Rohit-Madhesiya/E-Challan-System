<?php
include("db_connection.php");
ignore_user_abort(true);

$data = stripslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

switch ($data['flag']) {
  case '2':
    delJump:
    $query = "DELETE FROM `temp_rules` WHERE 1";
    mysqli_query($con, $query);
    break;
  case '1':
    $id = $data['id'];
    $query = "INSERT INTO `temp_rules`( `rule_id`) VALUES ($id)";
    $res = mysqli_query($con, $query);
    break;
  case '3':
    $query = "SELECT `rule_id` FROM `temp_rules`";
    $res = mysqli_query($con, $query);
    $main_arr = array();
    while ($arr = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
      $id = $arr['rule_id'];
      $sql = "SELECT * FROM `traffic_rules_detail` WHERE `id`=$id";
      $res2 = mysqli_query($con, $sql);
      $main_arr[] = mysqli_fetch_array($res2, MYSQLI_ASSOC);
    }
    echo json_encode($main_arr);
    break;
  case '4':
    $id = $data['rules_id'];
    $query = "DELETE FROM `temp_rules` WHERE `rule_id`={$id}";
    $res = mysqli_query($con, $query);
    if ($res)
      echo "Item Deleted!";
    else
      echo "Not Working";
    break;
  case '5':
    $off_id = $data['officer_id'];
    $status = 'inactive';
    $query2 = "INSERT INTO `officer_activity`(`officer_id`, `status`) VALUES ('$off_id','$status')";
    $res = mysqli_query($con, $query2);
    if ($res) {
      goto delJump;
    } else
      echo "Not Done";
    break;
  default:
    echo "Something Wrong!!";
}
