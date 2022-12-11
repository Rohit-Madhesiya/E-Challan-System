<?php
include('db_connection.php');
$query1 = "SELECT COUNT(*)AS 'total' FROM `challan_detail` WHERE `paid`='UNPAID'";
$query2 = "SELECT COUNT(*)AS 'total' FROM `challan_detail` WHERE `paid`='PAID'";
$query3 = "SELECT SUM(`penalties`)AS 'total' FROM `challan_detail` WHERE `paid`='UNPAID'";
$query4 = "SELECT SUM(`penalties`)AS 'total' FROM `challan_detail` WHERE `paid`='PAID'";

$sql = mysqli_query($con, $query1);
$sql = mysqli_fetch_array($sql, MYSQLI_ASSOC);
$arr = array('t1' => $sql['total']);
$sql = mysqli_query($con, $query2);
$sql = mysqli_fetch_array($sql, MYSQLI_ASSOC);
$arr = array_merge($arr, array('t2' => $sql['total']));
$sql = mysqli_query($con, $query3);
$sql = mysqli_fetch_array($sql, MYSQLI_ASSOC);
$arr = array_merge($arr, array('t3' => $sql['total']));
$sql = mysqli_query($con, $query4);
$sql = mysqli_fetch_array($sql, MYSQLI_ASSOC);
$arr = array_merge($arr, array('t4' => $sql['total']));

echo json_encode($arr);
