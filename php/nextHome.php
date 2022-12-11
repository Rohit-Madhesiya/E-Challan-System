<?php
include("db_connection.php");
session_start();

$query0 = "SELECT * FROM `traffic_rules_detail`";
$result = mysqli_query($con, $query0);
$optRule = "<select name='rules' id='rules' class='rules'>
      <option value='none' disabled selected><pre> ----------------------------Traffic Rules------------------------------Penalty-------------------------------------------</pre></option>
      ";
while ($row = mysqli_fetch_array($result)) :;
  $id = $row['id'];
  $offence = $row['offence'];
  $fine = $row['penalty'];
  $optRule .= "<option value='$id'>$offence&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Rs.($fine)</option><br><br><br><br><br>";
endwhile;
$optRule .= "</select>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/home.css">
  <link rel="stylesheet" href="../css/nextHome.css">
  <link rel="stylesheet" href="../css/nextHomePopup.css">
  <link rel="stylesheet" href="../css/index.css">

  <title>Officer Home</title>
</head>

<body>

  <header>
    <div class="logo-nav">
      <img src="../icon/logo.png" alt="logo">
    </div>
    <div class="headName-nav">
      <h3>ई-चालान प्रणाली</h3>
      <h3>E-CHALLAN SYSTEM</h3>
    </div>
    <div class="iconImg-nav">
      <img src="../icon/NHAI-Black.png" alt="logo img">
    </div>
    <nav>
      <div class="contain-nav">
        <div class="home-nav" onclick="homeJump()">
          <img src="../mini-icon/680-6800118_home-icon-png-transparent.png" alt="home">
          <h4>HOME</h4>
        </div>
        <div class="account-nav">
          <img src="../mini-icon/admin-icon2.png" alt="account">
          <h4>ACCOUNT</h4>
          <div class="ac-container-nav">
            <div class="login-nav" onclick="homeJump()">
              <img src="../mini-icon/iconmonstr-log-out-4-240.png" alt="login">
              <h4>OFFICER SIGN IN</h4>
            </div>
            <div class="create-nav" onclick="reg_officerJump2()">
              <img src="../mini-icon/iconmonstr-id-card-22-240.png" alt="cr ac img">
              <h4>APPLY FOR OFFICER ACCOUNT</h4>
            </div>
            <div class="create-nav" onclick="homeJump()">
              <img src="../mini-icon/iconmonstr-id-card-22-240.png" alt="admin icon img">
              <h4>ADMIN LOGIN</h4>
            </div>
          </div>
        </div>
        <div class="rules-nav" onclick="rulesJump2() ">
          <img src="../mini-icon/Mediamodifier-Design-Template (2).png" alt="rules img">
          <h4>TRAFFIC RULES</h4>
        </div>
        <div class="about-nav" onclick="">
          <img src="../mini-icon/question_mark.svg" alt="help img">
          <h4>HELP</h4>
        </div>
        <div class="about-nav" onclick="">
          <img src="../mini-icon/about-256.png" alt="about img">
          <h4>ABOUT US</h4>
        </div>
      </div>
    </nav>
  </header>

  <div class="idDiv" style="display: flex;">
    <div class="logoDiv">
      <?php
      try {
        $adhar = isset($_SESSION['officer_adhaar']) ? $_SESSION['officer_adhaar'] : "";
        $query1 = "SELECT `full_name` FROM `owner_detail` WHERE `aadhaar_number`=$adhar";
        $query2 = "SELECT `identification_number`, `profile_photo` FROM `officer_detail` WHERE `aadhaar_number`=$adhar";

        $res1 = mysqli_query($con, $query1);
        $res2 = mysqli_query($con, $query2);
        if ($res1)
          $row1 = mysqli_fetch_array($res1, MYSQLI_ASSOC);
        if ($res2)
          $row2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
        $officer_id = isset($row2['identification_number']) ? $row2['identification_number'] : "Unknown";
        $img = '../Database_Uploaded/Officers_data/';
        $img .= isset($row2['profile_photo']) ? $row2['profile_photo'] : "";

        $officerName = isset($row1['full_name']) ? $row1['full_name'] : 'Unknown';
        $_SESSION['officer_name'] = $officerName;
      ?>
        <img src=<?php echo $img; ?> alt="Profile Photo">
    </div>
    <div class="userName">
      <h3>
      <?php
        echo "&nbsp $officerName</h3><h4 id='off_id'>ID Number:&nbsp;$officer_id</h4>";
      } catch (Exception $e) {
        echo "$e" . "<br>";
      }
      ?>

    </div>
    <div class="off-nav">
      <div class="off-home">
        <img src="../icon/nextHome-icons/iconmonstr-home-2-240.png" alt="home">
        <i>Home</i>
      </div>
      <div class="off-home" id="off_profile">
        <img src="../icon/nextHome-icons/iconmonstr-id-card-10-240.png" alt="profile">
        <i>Profile</i>
      </div>
      <div class="off-home" id='verifyPop' onclick="togglePopup1()">
        <img src="../icon/nextHome-icons/iconmonstr-note-19-240.png" alt="verify">
        <i>Verification</i>
      </div>
      <div class="off-home" id="issued_challan">
        <img src="../icon/nextHome-icons/iconmonstr-note-31-240.png" alt="issued">
        <i>Issued Challan</i>
        <span class="badge" id="iss_ch_badge"></span>
      </div>

    </div>
    <div class="off-msg off-home" id="mssg_notify">
      <img src="../icon/nextHome-icons/iconmonstr-email-1-240.png" alt="msg">
      <span class="badge" id="msg_badge"></span>
    </div>
    <div class="logout">
      <a onclick="homeJump()">Logout &#10150;</a>
    </div>
  </div>

  <div class="mainPageDiv">
    <div class="mainPage">
      <div class="mainPageFront">
        <div class="mainDiv-main">
          <div class="fineDiv">
            <div class="fineHead">
              <h2 class="fineHead">Create New Fine</h2>
            </div>
            <br>
            <form action="" method="post" id="challanForm" class="fineForm" enctype="multipart/form-data">
              <div class="imgDiv">
                <img src="../icon/camera.png" alt="" class="cameraImg" id="camImg">
                <img src="../icon/nextHome-icons/images.png" alt="Upload Image" class="vehImg" id="vehImg">
                <input type="file" name="fineVeh" id="inptImg" class="inptImg" required>
              </div>

              <div class="filDiv">
                <?php
                echo "<input type='hidden' id='officer_id' name='officer_id' value='$officer_id'>";
                ?>
                <div class="numDiv">
                  <input type="radio" name="number" id="rc_number" value="RC" onclick="select(1)">
                  <label for="rc_number">Registration Number</label>
                  <input type="radio" name="number" id="chasis_number" value="Chassis" onclick="select(2)">
                  <label for="chasis_number">Vehicle Chassis Number</label>
                  <input type="radio" name="number" id="license_number" value="License" onclick="select(3)">
                  <label for="license_number">Driver License Number</label>
                </div>
                <div class="secFillDiv">
                  <div class="vehNumDiv">
                    <input type="text" name="vehInpt" class="Inpt" placeholder=" " required>
                    <label for="vehLbl" class="vehLbl" id="vehLbl">Available Number</label>
                  </div>
                  <div class="addDiv">
                    <input type="text" name="addInpt" class="Inpt" placeholder=" " required>
                    <label for="addLbl" class="addLbl">Place's Address(Where Traffic Rules Violated)</label>
                  </div>
                  <div class="dateDiv">
                    <input type="datetime-local" name="dateInpt" id="dateInpt" class="dateInpt" required>
                    <label for="dateLbl" class="addLbl">Date and Time</label>
                  </div>
                  <div class="reasonDiv">
                    <?php echo  $optRule; ?>
                    <input type="button" name="check" value="Apply" class="checkBtn" id="checkBtn">
                  </div>
                  <div class="penaltyDiv">
                    <input type="number" name="penaltyInpt" class="Inpt" id="penaltyInpt" placeholder=" " required readonly onfocus="this.blur()">
                    <label for="penaltyLbl" class="penaltyLbl">Total Penalty (Rs.)</label>
                  </div>
                  <div class="btn">
                    <input type="submit" value="Submit" class="btnDone" name="submit">
                  </div>
                  <div id="err2"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="secDiv">
            <hr class="line">
            <div class="tableDiv">
              <table class="fineTable">
                <thead>
                  <tr>
                    <th>Section</th>
                    <th>Offence</th>
                    <th>Penalty</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="ruleBody"></tbody>

              </table>
            </div>
          </div>

        </div>
      </div>

      <div class="mainPageBack">
        <div class="offProfileContainer">
          <div class="offP1">
            <div class="offPImg">
              <img src="../icon/nextHome-icons/profile_nav/default_profile.svg" alt="" id="offPImg">
              <img src="../icon/nextHome-icons/profile_nav/33f60aa9-4b99-4060-ae2c-6fe0cf70917d.svg" alt="" id="offPSign">
            </div>
            <div class="offPName">
              <h4 id="offPId">ID Number: &nbsp;</h4>
              <h5 id="offPUser">Username: &nbsp;</h5>
              <h5 id="offPName">Name: &nbsp;</h5>
              <h5 id="offPDob">Date of Birth: &nbsp;</h5>
              <h5 id="offPGen">Gender: &nbsp;</h5>
              <h5 id="offPEmail">Email: &nbsp;</h5>
              <h5 id="offPMobile">Mobile No.: &nbsp;</h5>
            </div>
          </div>
          <hr>
          <div class="offP2">
            <div class="offPuser">
              <button id="offPadminSend" onclick="togglePopup3Front()">Send Message to Admin</button>
              <button id="offChangePass" onclick="togglePopup3Front()">Change Password</button>
            </div>
            <h5 id="offPLastSeen">Last Activity: &nbsp;</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="issuedChallanDiv">
    <table class="issuedChTable" id="issuedChTable">
      <thead>
        <tr>
          <th>S. NO.</th>
          <th>Challan No.</th>
          <th>Date & Time</th>
          <th>Penalty</th>
        </tr>
      </thead>
      <tbody id="issuedChBody">
      </tbody>
    </table>
    <div class="issuedChallanDwnldBtn">
      <button class="pdfBtn" id="IssuedChPdfBtn">
        <img src="../icon/challanPayment/pdf.png" alt="pdf">
        Download</button>
    </div>
  </div>

  <div class="msg-container msgDiv" id="mssg_container">
    <table class="offMsgTable">
      <thead style="text-align: center;">
        <tr>
          <th>MESSAGE</th>
          <th>DATE AND TIME</th>
        </tr>
      </thead>
      <tbody id="offMsgTbody">
        <tr>
          <td colspan='2' style='text-align:center;'>EMPTY</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Verification Panel -->
  <div class="popup" id="popup1">
    <div class="popupOverlay" onclick="togglePopup1()"></div>
    <div class="content">
      <div class="closebtn" onclick="togglePopup1()">&times;</div>
      <div class="popDiv">
        <div class="main-container">
          <div class="nav-list">
            <div id="list-opt1">
              <button id="btn-1" class=" li-btn">License Details</button>
            </div>
            <div id="list-opt2">
              <button id="btn-2" class=" li-btn">Registration Certificate</button>
            </div>
            <div id="list-opt3">
              <button id="btn-3" class=" li-btn">Vehicle Insurance</button>
            </div>
            <div id="list-opt4">
              <button id="btn-4" class=" li-btn">PUC Certificate</button>
            </div>
          </div>
          <div class="pages-div">
            <div class="page page-default">
              <div class="page-front">
                <input type="text" name="licInpt" id="licInpt" placeholder=" " required>
                <label for="licNumLbl">Driving License Number</label>
                <input type="submit" id="licSubmit" value="SEARCH">
              </div>
              <div class="page-back">
                <div class="lic-heading">
                  <h4> Details of Driving License: &nbsp;</h4>
                  <h4 id="lic-head" class="inptTxt"> UNDEFINED</h4>
                </div>
                <hr>
                <div class="lic-status lic-heading">
                  <h4>Current Status: &nbsp;</h4>
                  <h4 id="lic-status" class="inptTxt">UNDEFINED</h4>
                </div>
                <hr>
                <div class="lic-holder-data">
                  <div class="lic-holder-d1">
                    <div class="lic-holder-name">
                      <h6>Holder's Name:</h6>
                      <h6 id="lic-name" class="inptTxt2"> UNDEFINED</h6>
                    </div>
                    <div class="lic-holder-name">
                      <h6>Date of Birth:</h6>
                      <h6 id="lic-dob" class="inptTxt2">UNDEFINED</h6>
                    </div>
                    <div class="lic-holder-name">
                      <h6>Address:</h6>
                      <h6 id="lic-add" class="inptTxt2">UNDEFINED</h6>
                    </div>
                    <div class="lic-holder-isdate">
                      <h6> Issue Date:</h6>
                      <h6 id="lic-iss-date" class="inptTxt2">UNDEFINED</h6>
                    </div>
                    <div class="lic-holder-isdate">
                      <h6> Validity Date:</h6>
                      <h6 id="lic-val-date" class="inptTxt2">UNDEFINED</h6>
                    </div>
                  </div>
                  <div class="lic-holder-d2 holder-img" id="lic-profile">
                  </div>
                  <div class="lic-holder-d3">

                    <div class="lic-holder-isdate">
                      <h6> Issuing Office:</h6>
                      <h6 id="lic-iss-off" class="inptTxt2">UNDEFINED</h6>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="lic-val-detail">
                  <h4 class="lic-heading lic-val-head">Validity Details</h4>
                  <table class="val-detail-table">
                    <tr>
                      <th>MODE</th>
                      <th>FROM</th>
                      <th>TO</th>
                      <th>PERIOD</th>
                    </tr>
                    <tr>
                      <td>Non-Transport</td>
                      <td id="nt-from" class="inptTxt2">NA</td>
                      <td id="nt-to" class="inptTxt2">NA</td>
                      <td id="nt-period" class="inptTxt2">NA</td>
                    </tr>
                    <tr>
                      <td>Transport</td>
                      <td id="t-from" class="inptTxt2">NA</td>
                      <td id="t-to" class="inptTxt2">NA</td>
                      <td id="t-period" class="inptTxt2">NA</td>
                    </tr>
                  </table>
                </div>
                <hr>
                <div class="lic-cov-detail">
                  <h4 class="lic-heading lic-val-head">Class of Vehicle Details</h4>
                  <table>
                    <tr>
                      <th>CLASS OF VEHICLE</th>
                      <th>ISSUE DATE</th>
                    </tr>
                    <tbody id="lic-cov-body">
                      <tr>
                        <td class="inptTxt2">NA</td>
                        <td class="inptTxt2">NA</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="page">
              <div class="page-front">
                <input type="text" name="rcInpt" id="rcInpt" placeholder=" " required>
                <label for="rcLbl">Vehicle RC Number</label>
                <input type="submit" id="regSubmit" value="SEARCH">
              </div>
              <div class="page-back">
                <div class="rc-heading">
                  <h4>Certificate of Registration</h4>
                </div>
                <hr>
                <div class="rc-detail1">
                  <div class="rc-reg-num ">
                    <h4>Registration Number: &nbsp;</h4>
                    <h4 id="rc-reg-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-reg-date rc-reg-num">
                    <h4>Registration Date: &nbsp;</h4>
                    <h4 id="rc-reg-date" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-dealer rc-reg-num">
                    <h4>Dealer's Name and Address: &nbsp;</h4>
                    <h4 id="rc-dealer" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
                <hr>

                <div class="rc-owner rc-detail1">
                  <div class="rc-owner-name rc-reg-num">
                    <h4>Owner Name: &nbsp;</h4>
                    <h4 id="rc-owner-name" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-owner-add rc-reg-num">
                    <h4>Permanent Address: &nbsp;</h4>
                    <h4 id="rc-owner-add" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
                <hr>
                <div class="rc-heading">
                  <h4>Detailed Description</h4>
                </div>
                <hr>
                <div class="rc-detail-desc rc-detail1">
                  <div class="rc-man-year rc-reg-num">
                    <h4>Manufacture Year: &nbsp;</h4>
                    <h4 id="rc-man-year" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-make-name rc-reg-num">
                    <h4>Maker's Name: &nbsp;</h4>
                    <h4 id="rc-make-name" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-chassis-num rc-reg-num">
                    <h4>Chassis Number: &nbsp;</h4>
                    <h4 id="rc-chas-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-engine-num rc-reg-num">
                    <h4>Engine Number: &nbsp;</h4>
                    <h4 id="rc-engine-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-engine-type rc-reg-num">
                    <h4>Engine Type: &nbsp;</h4>
                    <h4 id="rc-engine-type" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-model rc-reg-num">
                    <h4>Model: &nbsp;</h4>
                    <h4 id="rc-model" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-tob rc-reg-num">
                    <h4>Type Of Body: &nbsp;</h4>
                    <h4 id="rc-tob" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-color rc-reg-num">
                    <h4>Color: &nbsp;</h4>
                    <h4 id="rc-color" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-type rc-reg-num">
                    <h4>Type: &nbsp;</h4>
                    <h4 id="rc-type" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-fuel rc-reg-num">
                    <h4>Fuel Type: &nbsp;</h4>
                    <h4 id="rc-fuel" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-seat rc-reg-num">
                    <h4>Seat Capacity: &nbsp;</h4>
                    <h4 id="rc-seat" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
                <!-- </div> -->
              </div>
            </div>
            <div class="page">
              <div class="page-front">
                <!-- <h4>Insurance Details</h4> -->
                <input type="text" name="insInpt" id="insInpt" placeholder=" " required>
                <label for="insLbl">Insurance Number/RC Number</label>
                <input type="submit" id="insSubmit" value="SEARCH">
              </div>
              <div class="page-back">
                <div class="rc-heading">
                  <h4>Certificate of Vehicle Insurance</h4>
                </div>
                <hr>
                <div class="rc-detail1">
                  <div class="rc-reg-num ">
                    <h4>Insurance Policy Number: &nbsp;</h4>
                    <h4 id="ins-policy-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-dealer rc-reg-num">
                    <h4>Insurance Period: &nbsp;</h4>
                    <h4 id="ins-period" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-reg-date rc-reg-num">
                    <h4>Start Date: &nbsp;</h4>
                    <h4 id="ins-start-date" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-reg-date rc-reg-num">
                    <h4>End Date: &nbsp;</h4>
                    <h4 id="ins-end-date" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
                <hr>

                <div class="rc-owner rc-detail1">
                  <div class="rc-owner-name rc-reg-num">
                    <h4>Insured Person Name: &nbsp;</h4>
                    <h4 id="ins-person-name" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-owner-add rc-reg-num">
                    <h4>Insured Person Date of Birth: &nbsp;</h4>
                    <h4 id="ins-person-dob" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-owner-add rc-reg-num">
                    <h4>Insured Person Address: &nbsp;</h4>
                    <h4 id="ins-person-add" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <!-- </div> -->
                </div>
                <hr>
                <div class="rc-heading">
                  <h4>Insurance Company Description</h4>
                </div>
                <!-- <hr> -->
                <!-- <div class="rc-detail1"> -->
                <div class="rc-detail-desc rc-detail1">
                  <div class="rc-cov rc-reg-num">
                    <h4>Office Name: &nbsp;</h4>
                    <h4 id="ins-off-name" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-man-year rc-reg-num">
                    <h4>Office Address: &nbsp;</h4>
                    <h4 id="ins-off-add" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-make-name rc-reg-num">
                    <h4>Office Phone Number: &nbsp;</h4>
                    <h4 id="ins-off-phone" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-chassis-num rc-reg-num">
                    <h4>Office E-mail: &nbsp;</h4>
                    <h4 id="ins-off-email" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
                <hr>
                <div class="rc-heading">
                  <h4>Insured Vehicle Description</h4>
                </div>
                <div class="rc-detail-desc rc-detail1">
                  <div class="rc-engine-num rc-reg-num">
                    <h4>Vehicle Registration Number: &nbsp;</h4>
                    <h4 id="ins-rc-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-man-year rc-reg-num">
                    <h4>Manufacture Year: &nbsp;</h4>
                    <h4 id="ins-man-year" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-chassis-num rc-reg-num">
                    <h4>Chassis Number: &nbsp;</h4>
                    <h4 id="ins-chas-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-engine-num rc-reg-num">
                    <h4>Engine Number: &nbsp;</h4>
                    <h4 id="ins-engine-num" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-reg-num">
                    <h4>Make: &nbsp;</h4>
                    <h4 id="ins-make" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-model rc-reg-num">
                    <h4>Model: &nbsp;</h4>
                    <h4 id="ins-model" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
                <hr>
                <div class="rc-detail-desc rc-detail1">
                  <div class="rc-reg-num">
                    <h4>Net Premium Value: &nbsp;</h4>
                    <h4 id="ins-net-value" class="inptTxt">UNDEFINED</h4>
                  </div>
                  <div class="rc-reg-num">
                    <h4>Insured Declared Value: &nbsp;</h4>
                    <h4 id="ins-declared-value" class="inptTxt">UNDEFINED</h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="page">
              <div class="page-front">
                <input type="text" name="pucInpt" id="pucInpt" placeholder=" " required>
                <label for="">PUCC Number/RC Number</label>
                <input type="submit" id="pucSubmit" value="SEARCH">
              </div>
              <div class="page-back">
                <div class="rc-heading">
                  <h4>Certificate of Pollution Under Control</h4>
                </div>
                <hr>
                <div class="rc-heading">
                  <h4>Certificate Number: &nbsp;</h4>
                  <h4 id="puc-cert-num" class="inptTxt">UP61AU3136</h4>
                </div>
                <div class="rc-detail1">
                  <div class="rc-reg-num ">
                    <h4>Vehicle Registration Number: &nbsp;</h4>
                    <h4 id="puc-reg-num" class="inptTxt">UP61AU3136</h4>
                  </div>
                  <div class="rc-chassis-num rc-reg-num">
                    <h4>Chassis Number: &nbsp;</h4>
                    <h4 id="puc-chas-num" class="inptTxt">FSF454DFD3G4J7</h4>
                  </div>
                  <div class="rc-engine-num rc-reg-num">
                    <h4>Engine Number: &nbsp;</h4>
                    <h4 id="puc-engine-num" class="inptTxt">FG45D1JU096</h4>
                  </div>
                  <div class="rc-reg-date rc-reg-num">
                    <h4>Text Date and Time: &nbsp;</h4>
                    <h4 id="puc-date-time" class="inptTxt">12-07-2000</h4>
                  </div>
                  <div class="rc-reg-date rc-reg-num">
                    <h4>Certificate Validity Period: &nbsp;</h4>
                    <h4 id="puc-period" class="inptTxt">1 YEARS</h4>
                  </div>
                  <div class="rc-reg-date rc-reg-num">
                    <h4>Expiry Date: &nbsp;</h4>
                    <h4 id="puc-expiry-date" class="inptTxt">12-07-2001</h4>
                  </div>
                </div>
                <hr>

                <div class="rc-owner rc-detail1">
                  <div class="rc-owner-name rc-reg-num">
                    <h4>Test Centre Name: &nbsp;</h4>
                    <h4 id="puc-centre-name" class="inptTxt">pollution centre </h4>
                  </div>
                  <div class="rc-owner-add rc-reg-num">
                    <h4>Test Centre Address: &nbsp;</h4>
                    <h4 id="puc-centre-add" class="inptTxt">GZP</h4>
                  </div>
                </div>
                <hr>
                <div class="rc-heading">
                  <h4>Vehicle Emmission</h4>
                </div>
                <div class="rc-detail-desc rc-detail1">
                  <table class="puc-table">
                    <tr>
                      <th>TYPE</th>
                      <th>VALUE</th>
                    </tr>
                    <tr>
                      <td>CO</td>
                      <td class="puc-co">NULL</td>
                    </tr>
                    <tr>
                      <td>HC</td>
                      <td class="puc-hc">NULL</td>
                    </tr>
                    <tr>
                      <td>CO2</td>
                      <td class="puc-co2">NULL</td>
                    </tr>
                    <tr>
                      <td>O2</td>
                      <td class="puc-o2">NULL</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div id="err2"></div>
        </div>
      </div>
    </div>
  </div>


  <div class="popup" id="popup2">
    <div class="popupOverlay" onclick="togglePopup2()"></div>
    <div class="content">
      <div class="closebtn" onclick="togglePopup2()">&times;</div>
      <div class="popDiv">
      </div>
    </div>
  </div>
  <!-- Profile Message and Pass Popup Box -->
  <div class="popup" id="popup3">
    <div class="popupOverlay" onclick="togglePopup3Front()"></div>
    <div class="content">
      <div class="closebtn" onclick="togglePopup3Front()">&times;</div>
      <div class="popDiv mainPageDiv">
        <div class="adminMsgPop mainPage" id="adminMsgPop">
          <div class="adminPopFront mainPagefront">
            <form action="#" name="AdminMsgForm" id="AdminMsgForm" method="post">
              <label for="fromIdAdmin" class="fromId" id="fromId">FROM: &nbsp; <?php echo $officer_id; ?></label> <br>
              <label for="toIdAdmin" class="toId">TO: &nbsp; ADMIN</label><br>
              <label for="msgLblAdmin">MESSAGE: &nbsp;</label>
              <input type="text" name="msgAdminInput" id="msgAdminInput" placeholder="Type Your Message Here"><br>
              <input type="submit" value="SEND">
            </form>
          </div>
          <div class="adminPopBack mainPageBack">
            <form action="#" name="passChangeForm" id="passChangeForm" method="post">
              <label for="current_pass">Enter Current Password</label>
              <input type="password" name="curr_pass" id="curr_pass" required><br>
              <label for="new_pass">Enter New Password</label>
              <input type="password" name="new_pass" id="new_pass" required><br>
              <label for="confirm_pass">Confirm Password</label>
              <input type="password" name="confirm_pass" id="confirm_pass" required><br>
              <input type="submit" value="CHANGE" name="change_btn">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


</body>
<script type="text/javascript" src="../js/main.js"></script>
<script src="../js/jQuery.js"></script>
<script src="../jasonday-printThis-v1.15.0-24-g23be1f8/jasonday-printThis-23be1f8/printThis.js"></script>
<script type="text/javascript" src="../js/nextHome.js"></script>
<?php
session_destroy();
?>

</html>