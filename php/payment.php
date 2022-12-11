<?php
include('db_connection.php');

$amount = $_POST['fineAmt'];
$account_num = trim($_POST['accNum']);
$pass = $_POST['password'];
$challanNum = $_POST['chNumber'];

$sql = "SELECT `balance` FROM `bank_detail` WHERE `account_number`=$account_num AND `password`='$pass'";

$res = mysqli_query($con, $sql);

if (mysqli_num_rows($res) == 1) {
  $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
  $bal = $arr['balance'];
  if ($bal < $amount) {
    echo "Unsufficient Balance";
  } else {
    $sqlCheck = "SELECT `paid` FROM `challan_detail` WHERE  `challan_number`='$challanNum'";
    $res = mysqli_query($con, $sqlCheck);
    $arr = mysqli_fetch_array($res, MYSQLI_ASSOC);
    if ($arr['paid'] === "UNPAID") {
      $bal -= $amount;
      $sql = "UPDATE `bank_detail` SET `balance`=$bal WHERE `account_number`=$account_num AND`password`='$pass'";
      $res = mysqli_query($con, $sql);
      if ($res) {
        $query = "UPDATE `challan_detail` SET `paid`='PAID' WHERE `challan_number`='$challanNum'";
        if (mysqli_query($con, $query) == 1) {
          echo "Success!";
          exit();
        }
      } else {
        echo "Payment Failed!";
      }
    } else {
      echo "Already Paid!";
    }
  }
}
