<?php

$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'challan_db';

try {
  $con = mysqli_connect($server, $user, $pass, $db);
  if (mysqli_connect_errno()) {
    die("Connection Failed: " . mysqli_connect_error());
  }
} catch (Exception $e) {
  echo "Exception Occured: " . $e;
}
