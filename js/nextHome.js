// Here window.onload is using because js is not loading into the DOM and caught type error
window.onload = function() {
    camImg = document.getElementById("camImg");
    vehImg = document.getElementById("vehImg");
    inptImg = document.getElementById("inptImg");
    inptImg.addEventListener("change", payImgShow);
    fakeform = document.getElementById("fakeForm");
}

function payImgShow() {
    const file = this.files[0];
    if (file) {
        reader = new FileReader();
        camImg.style.display = "none";
        reader.addEventListener("load", function() {
            vehImg.setAttribute("src", this.result);
        });
        reader.readAsDataURL(file);
    }
}

function select(n) {
    // txtChange.addEventListener("change", select);
    if (n == 1) {
        txt = "Registration Number";
    } else if (n == 2) {
        txt = "Vehicle Chassis Number";
    } else if (n == 3) {
        txt = "License Number";
    } else {
        txt = "Vehicle Number"
    }
    document.getElementById('vehLbl').textContent = txt;
}

function togglePopup1() {
    document.getElementById("popup1").classList.toggle("active");
}

function togglePopup2() {
    document.getElementById("popup2").classList.toggle("active");
}

function togglePopup3Front() {
    document.getElementById("popup3").classList.toggle("active");
    document.getElementById("adminMsgPop").classList.remove("rotateMainPage");
}

function togglePopup3Back() {
    togglePopup3Front;
    document.getElementById("adminMsgPop").classList.add("rotateMainPage");
}

// Verification Panel Script
var btn_1 = document.querySelector('#btn-1');
var btn_2 = document.querySelector('#btn-2');
var btn_3 = document.querySelector('#btn-3');
var btn_4 = document.querySelector('#btn-4');
var page = document.getElementsByClassName("page");
var page_back = document.getElementsByClassName("page-back");

function pageHide() {
    for (i = 0; i < page.length; i++) {
        page[i].style.display = 'none';
        page_back[i].style.display = 'none';
    }
}

function remove_btn_class() {
    btn_1.classList.remove('li-btn-after');
    btn_2.classList.remove('li-btn-after');
    btn_3.classList.remove('li-btn-after');
    btn_4.classList.remove('li-btn-after');
}

btn_1.addEventListener('click', function() {
    remove_btn_class();
    pageHide();
    page[0].style.display = "block";
    btn_1.classList.add('li-btn-after');

});
btn_2.addEventListener('click', function() {
    remove_btn_class();
    pageHide();
    page[1].style.display = 'block';
    btn_2.classList.add('li-btn-after');
});
btn_3.addEventListener('click', function() {
    remove_btn_class();
    pageHide();
    page[2].style.display = 'block';
    btn_3.classList.add('li-btn-after');
});
btn_4.addEventListener('click', function() {
    remove_btn_class();
    pageHide();
    page[3].style.display = 'block';
    btn_4.classList.add('li-btn-after');
});

$(document).ready(function() {
    $('.issuedChallanDiv').hide();
    $('.msgDiv').hide();
    $('#err2').hide();
    $('#issued_challan').on('click', function(e) {
        e.stopPropagation();
        $('.issuedChallanDiv').toggle();
    });

    $('#mssg_notify').on('click', function(e) {
        e.stopPropagation();
        let off_id = $('#off_id').text();
        myData = { 'flag': '9', 'off_id': off_id };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(myData),
            success: function(data) {
                $('#offMsgTbody').html(data);
            }
        });

        $('.msgDiv').toggle();
    });

    issuedChallan();
    profileData();

    $(document).on('click', function() {
        $('.issuedChallanDiv').hide();
        $('.msgDiv').hide();
    });

    $('#off_profile').on('click', function() {
        $('.mainPage').toggleClass('rotateMainPage');
    });
    $('#offChangePass').on('click', function() {
        $('.adminMsgPop').toggleClass('rotateMainPage');
    });

    function profileData() {
        let off_id = $('#off_id').text();
        mydata = { 'flag': '6', 'num': off_id };
        srcFolder = '../Database_Uploaded/Officers_data/';
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                x = data;
                $('#offPImg').attr('src', srcFolder + x['profile_photo']);
                $('#offPSign').attr('src', srcFolder + x['signature_photo']);
                $('#offPId').append(x['officer_id']);
                $('#offPUser').append(x['username']);
                $('#offPName').append(x['full_name']);
                $('#offPDob').append(x['date_of_birth']);
                $('#offPGen').append(x['gender']);
                $('#offPEmail').append(x['email']);
                $('#offPMobile').append(x['mobile']);
                $('#offPLastSeen').append(x['date_time']);
            }
        });
    }

    function issuedChallan() {
        let off_id = $('#off_id').text();
        mydata = { 'flag': '5', 'num': off_id };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('#iss_ch_badge').text(data['srNum']);
                $('#issuedChBody').html(data['output']);
            }
        });

    }

    function deleteTable() {
        mydata = { flag: '2' };
        $.ajax({
            url: "../php/clear_temp_rule.php",
            method: "POST",
            data: JSON.stringify(mydata),
            success: function(data) {
                showTable();
            }
        });
    }

    function showTable() {
        mydata = { flag: '3' };
        output = "";
        count = 0;
        $.ajax({
            url: "../php/clear_temp_rule.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    x = data;
                } else {
                    x = "";
                    $('#err').text("Something Went Wrong!");
                }
                for (i = 0; i < x.length; i++) {
                    count += parseInt(x[i].penalty);
                    output += " <tr><td>" + x[i].section + "</td><td>" + x[i].offence + "</td>";
                    output += "<td>Rs. " + x[i].penalty + "</td><td><button type='button' class='remBtn' rules_id='" + x[i].id + "'>REMOVE</button></td></tr > ";
                }
                $("#ruleBody").html(output);
                $('#penaltyInpt').val(count);
            }
        });
    }
    $("#checkBtn").on('click', function() {
        rule_id = document.getElementById('rules').value;
        mydata = { flag: '1', id: rule_id };
        $.ajax({
            url: "../php/clear_temp_rule.php",
            method: "POST",
            data: JSON.stringify(mydata),
            success: function(data) {
                showTable();
                console.log('Working: ' + data);
            }
        });
    });
    $("#ruleBody").on('click', ".remBtn", function() {
        let id = $(this).attr('rules_id');
        mydata = { flag: '4', rules_id: id };
        $.ajax({
            url: "../php/clear_temp_rule.php",
            method: "POST",
            data: JSON.stringify(mydata),
            success: function(data) {
                showTable();
                console.log(data);
            }
        });

    });

    // function convertFormToJSON(form) {
    //     return $(form)
    //         .serializeArray()
    //         .reduce(function(json, { name, value }) {
    //             json[name] = value;
    //             return json;
    //         }, {});
    // }
    // officer message transfer to DB
    $('#AdminMsgForm').on('submit', function(e) {
        e.preventDefault();
        let off_id = $('#off_id').text();
        let msg = document.getElementById('msgAdminInput').value;
        if (msg == '' || off_id == '') {
            console.log('Input Field cannot be Empty.');
            return false;
        }
        let myData = { 'flag': 7, 'off_id': off_id, 'msg': msg };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(myData),
            beforeSend: function() {
                console.log("processing....");
            },
            success: function(data) {
                console.log("Success: " + data);
            }
        });
    });
    // officer password change request
    $('#passChangeForm').on('submit', function(e) {
        e.preventDefault();
        let off_id = $('#off_id').text();
        let curr_pass = document.getElementById('curr_pass').value;
        let new_pass = document.getElementById('new_pass').value;
        let myData = { 'flag': 8, 'off_id': off_id, 'curr_pass': curr_pass, 'new_pass': new_pass };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(myData),
            beforeSend: function() {
                console.log("processing....");
            },
            success: function(data) {
                console.log(data);
            }
        });
    });

    // Ajax Request for Insert Data to officer Database
    $("#challanForm").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: "../php/upload_challan.php",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $('#err2').fadeOut();
            },
            success: function(data) {
                console.log(data);
                deleteTable();
                if (data == 1) {
                    $('#err2').html("Challan has been issued.").fadeIn();
                    $('#err2').css('color', 'green');
                    setTimeout($('#err2').fadeOut(), 5000);
                } else {
                    $('#err2').html(data).fadeIn();
                    $('#err2').css('color', 'red');
                }
                $("#challanForm")[0].reset();
            },
            complete: function() {
                // console.log("Calling Function");
                issuedChallan();
            }
        });

    }));

    $('#licSubmit').on('click', function() {
        let num = document.getElementById('licInpt').value;
        mydata = { 'flag': '1', 'number': num };
        let cov_output = "";
        let tr_flag = false;
        let tr_check = true;
        let ntr_check = true;
        let ntr_flag = false;

        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    x1 = data[0];
                    x2 = data[1];
                    x3 = data[2][0]['vehicle_class'];
                } else {
                    $('#err2').show();
                    $('#err2').text("Something Went Wrong!");
                }
                $('#lic-head').text(x1['license_number']);
                $('#lic-name').text(x1['name']);
                $('#lic-dob').text(x1['date_of_birth']);
                $('#lic-add').text(x2['address']);
                $('#lic-iss-date').text(x1['issue_date']);
                $('#lic-val-date').text(x1['validity_date']);
                $('#lic-iss-off').text(x1['issuing_office']);
                currDate = new Date();
                valDate = new Date(x1['validity_date']);
                if (valDate.getFullYear() - currDate.getFullYear() > 1) {
                    $('#lic-status').text("ACTIVE");
                } else {
                    $('#lic-status').text("EXPIRE");
                }
                img = "../Database_Uploaded/license_data/" + x1['photo'];
                $('#lic-profile').css('background-image', 'url(' + img + ')');
                for (i = 0; i < x3.length; i++) {
                    cov_output += " <tr> <td class = 'inptTxt2'> " + x3[i] + "</td> <td class = 'inptTxt2'> " + x1['issue_date'] + " </td> </tr> ";
                    if (tr_check) {
                        if (x3[i] == "LMV-TR" || x3[i] == "HPMV" || x3[i] == "MGV" || x3[i] == "HGMV") {
                            tr_flag = true;
                            tr_check = false;
                        }
                    }
                    if (ntr_check) {
                        if (x3[i] == "MCWG" || x3[i] == "LMV" || x3[i] == "LMV-NT" || x3[i] == "MCWOG") {
                            ntr_flag = true;
                            ntr_check = false;
                        }
                    }
                }
                $('#lic-cov-body').html(cov_output);
                page_back[0].style.display = 'block';
            },
            complete: function() {
                if ($('#lic-status').text() == "ACTIVE") {
                    $('#lic-status').css('color', 'green');
                } else {
                    $('#lic-status').css('color', 'red');
                }

                if (ntr_flag) {
                    $('#nt-from').text(x1['issue_date']);
                    $('#nt-to').text(x1['validity_date']);
                    $('#nt-period').text(x1['period']);
                }
                if (tr_flag) {
                    $('#t-from').text(x1['issue_date']);
                    $('#t-to').text(x1['validity_date']);
                    $('#t-period').text(x1['period']);
                }
            }
        });
    });
    $('#regSubmit').on('click', function() {
        let num = document.getElementById('rcInpt').value;
        mydata = { 'flag': '2', 'number': num };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    x = data;
                } else {
                    $('#err2').show();
                    $('#err2').text("Something Went Wrong!");
                }
                $('#rc-reg-num').text(x['registration_number']);
                $('#rc-reg-date').text(x['registration_date']);
                $('#rc-dealer').text(x['dealer_name_address']);
                $('#rc-owner-name').text(x['name']);
                $('#rc-owner-add').text(x['address']);
                $('#rc-man-year').text(x['manufacture_year']);
                $('#rc-make-name').text(x['make']);
                $('#rc-chas-num').text(x['chassis_number']);
                $('#rc-engine-num').text(x['engine_number']);
                $('#rc-engine-type').text(x['engine_type']);
                $('#rc-model').text(x['model']);
                $('#rc-tob').text(x['type_of_body']);
                $('#rc-color').text(x['colour']);
                $('#rc-type').text(x['type']);
                $('#rc-fuel').text(x['fuel']);
                $('#rc-seat').text(x['seat_capacity']);
                page_back[1].style.display = 'block';
            }
        });
    });
    $('#insSubmit').on('click', function() {
        let num = document.getElementById('insInpt').value;
        mydata = { 'flag': '3', 'number': num };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    x = data;
                } else {
                    $('#err2').show();
                    $('#err2').text("Something Went Wrong!");
                }
                $('#ins-policy-num').text(x['policy_number']);
                $('#ins-period').text(x['insurance_period']);
                $('#ins-start-date').text(x['start_date']);
                $('#ins-end-date').text(x['end_date']);
                $('#ins-person-name').text(x['name']);
                $('#ins-person-dob').text(x['date_of_birth']);
                $('#ins-person-add').text(x['address']);
                $('#ins-off-name').text(x['office_name']);
                $('#ins-off-add').text(x['office_address']);
                $('#ins-off-phone').text(x['office_phone_number']);
                $('#ins-off-email').text(x['office_email']);
                $('#ins-rc-num').text(x['vehicle_registration_number']);
                $('#ins-man-year').text(x['manufacture_year']);
                $('#ins-chas-num').text(x['chassis_number']);
                $('#ins-engine-num').text(x['engine_number']);
                $('#ins-make').text(x['make']);
                $('#ins-model').text(x['model']);
                $('#ins-net-value').text(x['net_premium_value']);
                $('#ins-declared-value').text(x['insured_declared_value']);

                page_back[2].style.display = 'block';
            }
        });
    });
    $('#pucSubmit').on('click', function() {
        let num = document.getElementById('pucInpt').value;
        mydata = { 'flag': '4', 'number': num };
        $.ajax({
            url: "../php/show_verification.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    x = data;
                } else {
                    $('#err2').show();
                    $('#err2').text("Something Went Wrong!");
                }
                $('#puc-cert-num').text(x['certificate_number']);
                $('#puc-reg-num').text(x['vehicle_registration_number']);
                $('#puc-chas-num').text(x['chassis_number']);
                $('#puc-engine-num').text(x['engine_number']);
                $('#puc-date-time').text(x['test_date_time']);
                $('#puc-period').text(x['validity_period']);
                $('#puc-expiry-date').text(x['expiry_date']);
                $('#puc-centre-name').text(x['centre_name']);
                $('#puc-centre-add').text(x['centre_address']);
                $('.puc-co').text(x['vehicle_emmission'][0]);
                $('.puc-hc').text(x['vehicle_emmission'][1]);
                $('.puc-co2').text(x['vehicle_emmission'][2]);
                $('.puc-o2').text(x['vehicle_emmission'][3]);
                page_back[3].style.display = 'block';
            },
            complete: function() {
                expDate = new Date(x['expiry_date']);
                curDate = new Date();
                if (expDate.getFullYear() + parseInt(x['validity_period']) < curDate.getFullYear()) {
                    $('#puc-expiry-date').css('color', 'red');
                } else {
                    $('#puc-expiry-date').css('color', 'blue');
                }
            }
        });
    });

    // Runs when Window Closed to clear the temp_rule table from database so that in next use it will be empty!
    $(window).on('unload', function() {
        off_id = document.getElementById('officer_id').value;
        mydata = { flag: '5', officer_id: off_id };
        navigator.sendBeacon("../php/clear_temp_rule.php", JSON.stringify(mydata));
    });

    // HTML TO PDF DOWNLOADING
    $('#IssuedChPdfBtn').on('click', function() {
        console.log("Processing...");
        $('#issuedChTable').printThis({
            importCSS: false,
            loadCSS: 'E-Challan/css/nextHome.css',
        });
    });
});

window.addEventListener('beforeunload', ev => {
    ev.returnValue = "Are you sure?";
});