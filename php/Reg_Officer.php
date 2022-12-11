<?php
include('db_connection.php');
session_start();

$adhar = trim($_POST['aadhar']);
$uid = trim($_POST['uidNo']);
$user = trim($_POST['userName']);
$dob = trim($_POST['birth']);
$pass = trim($_POST['pass']);
$verify = trim($_POST['acChoice']);

if ("$verify" == "permitNo") {
  $pmNo = trim($_POST['permit']);
} else {
  $pmNo = '';
}

$dob = strtotime($dob);
// temp img files for transfering images into target folder
$tempProf = $_FILES["imgInpt"]["tmp_name"];
$tempSign = $_FILES["signImgInpt"]["tmp_name"];
// target folder where images will be transfer
$targetFolder = "F:/Installation/XAMP/htdocs/E-Challan/Database_Uploaded/Officers_data/";
// img files with target folder to upload path in database
$profFile = $targetFolder . basename($_FILES["imgInpt"]["name"]);
$signFile = $targetFolder . basename($_FILES["signImgInpt"]["name"]);
// img extension fetched from img file which used in uploading files in database for file extension
$ImgFT1 = pathinfo($profFile, PATHINFO_EXTENSION);
$ImgFT2 = pathinfo($signFile, PATHINFO_EXTENSION);
// main img data which store in database
$profImg = basename($_FILES["imgInpt"]["name"]);
$signImg = basename($_FILES["signImgInpt"]["name"]);

$query1 = "SELECT date_of_birth FROM owner_detail WHERE aadhaar_number='$adhar'";
try {
  $result = mysqli_query($con, $query1);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $count = mysqli_num_rows($result);
  $dob2 = strtotime($row['date_of_birth']);

  if ($dob2 == $dob && $count == 1) {
    $query2 = "INSERT INTO officer_detail(identification_number,profile_photo,signature_photo,aadhaar_number,permitted_by,permit_number,username,password,status) 
    VALUES ('$uid','$profImg','$signImg',$adhar,'$verify','$pmNo','$user','$pass','PENDING')";
    $res = mysqli_query($con, $query2);
    if (move_uploaded_file($tempProf, $profFile) && move_uploaded_file($tempSign, $signFile)) {
      echo "The file: " . basename($_FILES["imgInpt"]["name"]) . " and " . basename($_FILES["signImgInpt"]["name"]) . " has been uploaded Successfully.";
    } else {
      echo "Failed to upload image";
    }
  } else {
    echo "Person not Found in Database, Require Data in owner_info about the Person!";
  }
} catch (Exception $e) {
  die("Exception: " . $e);
}
session_destroy();
// }
