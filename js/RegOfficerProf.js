window.onload = function () {
  profDiv = document.getElementById("profileDiv");
  profImg = document.getElementById("profileImg");
  imgInpt = document.getElementById("imgInpt");
  profHint = document.getElementById("profUploadHint");
  imgInpt.addEventListener("change", officerImgShow);

  signDiv = document.getElementById("signDiv");
  signImg = document.getElementById("signImg");
  signImgInpt = document.getElementById("signImgInpt");
  signHint = document.getElementById("signUploadHint");
  signImgInpt.addEventListener("change", officerSignShow);

  selector = document.querySelector(".acChoice");
  permit = document.querySelector(".permitNo");
  selector.addEventListener("change", check);


}
// Officer Profile Photo and Signature uploaded, now showing in Page
function officerImgShow() {
  const file = this.files[0];
  profHint.style.display = "none";
  if (file) {
    reader = new FileReader();
    reader.addEventListener("load", function () {
      profImg.setAttribute("src", this.result);
    });
    reader.readAsDataURL(file);
  }
}
function officerSignShow() {
  const file = this.files[0];
  signHint.style.display = "none";
  if (file) {
    reader = new FileReader();
    reader.addEventListener("load", function () {
      signImg.setAttribute("src", this.result);
    });
    reader.readAsDataURL(file);
  }
}

function check() {
  val = selector.options[selector.selectedIndex].value;
  if (val == "permitNo") {
    permit.style.display = "block";
  } else {
    permit.style.display = "none";
  }
}


// AJAX TO Store Data

$(document).ready(function () {
  img = document.getElementById("imgInpt");
  sign = document.getElementById("signImgInpt");
  adhar = document.getElementById("aadhar");
  off_id = document.getElementById("uidNo");
  user = document.getElementById("userName");
  Pass = document.getElementById("pass");
  dob = document.getElementById("birth");
  choice = document.getElementById("acChoice");

  // Ajax Request for Insert Data to officer Database
  $("#officer_form").on('submit', (function (e) {
    e.preventDefault();
    console.log("Button Clicked");
    $.ajax({
      url: "../php/Reg_Officer.php",
      method: "POST",
      // data: JSON.stringify(mydata),
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $('#err').fadeOut();
      },
      success: function (data) {
        if (data == 'invalid') {
          $('#err').html("Invalid File!").fadeIn();
        } else {
          $('#err').html("Success").fadeIn();
          $('#officer_form')[0].reset();
        }
      }
    });

  }));


});