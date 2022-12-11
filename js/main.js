window.addEventListener('load', () => {
    const preload = document.querySelector('.loader');
    setTimeout(() => { preload.classList.add('loader_finish') }, 100);
});
// MOVING PAGES FROM ONE TO ANOTHER

function challanPayment() {
    window.location.href = "./html/challanPayment.html";
}

function adminChallan() {
    window.location.replace("html/adminChallan.html");
}

function adminChallanJump() {
    window.location.replace("./adminChallan.html");
}

function newChallan() {
    window.location.href = "/html/newChallan.html";
}

function pendingChallan() {
    window.location.href = "/html/pendingChallan.html";
}

function nextHome() {
    window.location.href = "/php/nextHome.php";
}

function afterLogOut() {
    window.location.href = "./home.html";
}

function homeJump() {
    window.location.href = "../home.html";
}

function reg_officer() {
    window.location.href = "./html/Register_officer.html";
}

function reg_officerJump() {
    window.location.href = "./Register_officer.html";
}

function reg_officerJump2() {
    window.location.href = "../html/Register_officer.html";
}

function rules() {
    window.location.href = "./html/rules.html";
}

function rulesJump() {
    window.location.href = "./rules.html";
}

function rulesJump2() {
    window.location.replace('../html/rules.html');
}


//POPUP OVERLAY SIGN IN 
function togglePopup() {
    document.getElementById("popup").classList.toggle("active");
}

function togglePopup2() {
    document.getElementById("popup2").classList.toggle("active");
}


// Jquery for login verification and all
$(document).ready(function() {
    $.ajax({
        url: "./php/revenue_show.php",
        method: "GET",
        dataType: 'json',
        success: function(data) {
            $('.tNum1').text(data['t1']);
            $('.tNum2').text(data['t2']);
            $('.tNum3').text('Rs. ' + data['t3']);
            $('.tNum4').text('Rs. ' + data['t4']);
        }
    });
    $('#loginForm').on('submit', (function(e) {
        e.preventDefault();
        user = $('.userId1').val();
        pass = $('.pswd1').val();
        mydata = { 'type': 1, 'userName': user, 'password': pass };
        $.ajax({
            url: "./php/signIn_verify.php",
            method: "POST",
            data: JSON.stringify(mydata),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#err').fadeOut();
            },
            success: function(data) {
                if (data == "success") {
                    console.log(data);
                    setTimeout(() => {
                        window.location.href = './php/nextHome.php'
                    }, 500);
                } else {
                    $('#err').html(data).fadeIn();
                }
            }

        });
    }));
    $('#adminLoginForm').on('submit', function(e) {
        e.preventDefault();
        user = $('.userId2').val();
        pass = $('.pswd2').val();
        mydata = { 'type': 2, 'userName': user, 'password': pass };
        $.ajax({
            url: "./php/signIn_verify.php",
            method: "POST",
            data: JSON.stringify(mydata),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#err').fadeOut();
            },
            success: function(data) {
                if (data == "success") {
                    console.log(data);
                    setTimeout(() => {
                        window.location.href = './html/adminChallan.html'
                    }, 500);
                } else {
                    $('.err2').html(data).fadeIn();
                }
            }
        });
    });
});