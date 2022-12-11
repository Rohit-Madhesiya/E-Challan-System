<?php
include('db_connection.php');
$data = stripslashes(file_get_contents("php://input"));
$data = json_decode($data, true);

$chNum = $data['chNum'];
$subject = $data['sub'];
$message = $data['msg'];

$query = "INSERT INTO `user_msg_detail`(`challan_no`, `subject`, `message`) VALUES ('$chNum','$subject','$message')";
$res = mysqli_query($con, $query);
echo $res;

exit();
