var btn_1 = document.querySelector('#btn-1');
var btn_2 = document.querySelector('#btn-2');
var btn_3 = document.querySelector('#btn-3');
var btn_5 = document.querySelector('#btn-5');
var btn_6 = document.querySelector('#btn-6');
var sub_page1 = document.querySelector('.msg_subpage1');
var sub_page2 = document.querySelector('.msg_subpage2');
var sub_btn1 = document.querySelector('#msg_inbox');
var sub_btn2 = document.querySelector('#msg_complaint');
var list_btn = document.getElementsByClassName('list-btn');
var page = document.getElementsByClassName("page");

window.onload = function() {
    pageHide();
    list_btn[0].classList.add("list_btn_focus");
    page[0].style.display = "block";
}

function pageHide() {
    for (i = 0; i < page.length; i++) {
        page[i].style.display = 'none';
        if (list_btn[i].classList.contains("list_btn_focus"))
            list_btn[i].classList.remove("list_btn_focus");
        document.querySelector('.searchResultDiv').style.display = 'none';
    }
}

function togglePopup1() {
    document.getElementById("popup1").classList.toggle("active");
}

function togglePopup2() {
    document.getElementById("popup2").classList.toggle("active");
}
btn_1.addEventListener('click', function() {
    pageHide();
    list_btn[0].classList.add("list_btn_focus");
    page[0].style.display = "block";
});
btn_2.addEventListener('click', function() {
    pageHide();
    list_btn[1].classList.add("list_btn_focus");
    page[1].style.display = 'block';
});
btn_3.addEventListener('click', function() {
    pageHide();
    list_btn[3].classList.add("list_btn_focus");
    page[2].style.display = 'block';
    sub_page1.classList.add('subpage_focus');
    sub_page2.classList.remove('subpage_focus');
    sub_btn1.classList.add('msg_headName_focus');
    sub_btn2.classList.remove('msg_headName_focus');
});
btn_5.addEventListener('click', function() {
    pageHide();
    list_btn[4].classList.add("list_btn_focus");
    page[3].style.display = 'block';
});
btn_6.addEventListener('click', function() {
    pageHide();
    list_btn[2].classList.add("list_btn_focus");
    page[4].style.display = 'block';
});
sub_btn1.addEventListener('click', function() {
    sub_btn1.classList.add('msg_headName_focus');
    sub_btn2.classList.remove('msg_headName_focus');
    if (!sub_page1.classList.contains('subpage_focus'))
        sub_page1.classList.add('subpage_focus');
    if (sub_page2.classList.contains('subpage_focus'))
        sub_page2.classList.remove('subpage_focus');
});
sub_btn2.addEventListener('click', function() {
    sub_btn2.classList.add('msg_headName_focus');
    sub_btn1.classList.remove('msg_headName_focus');
    if (!sub_page2.classList.contains('subpage_focus'))
        sub_page2.classList.add('subpage_focus');
    if (sub_page1.classList.contains('subpage_focus'))
        sub_page1.classList.remove('subpage_focus');
});

$(document).ready(function() {
    // TODO: get data from messages and show in popup dialog
    $(document).on('click', '#msg_tbody_row', function() {

        $('.msg_name_id_store').html('FROM: &nbsp; ' + $('.msg_name', this).html() + '(' + $('.msg_id', this).html() + ')');
        $('.msg_date_store').html('DATE AND TIME: &nbsp; ' + $('.msg_date', this).html());
        $('.msg_sub_store').html("");
        $('.msg_msg_store').html($('.msg_msg', this).html());
        console.log('Working-1');
        $('.msg_popup').addClass('active');
    });
    $(document).on('click', '.msg_overlay', function() {
        $('.msg_popup').removeClass('active');
    });
    $(document).on('click', '#complaint_tbody_row', function() {
        $('.msg_name_id_store').html('FROM: &nbsp;' + $('.cmplt_id', this).html());
        $('.msg_date_store').html('DATE AND TIME: &nbsp; ' + $('.cmplt_date', this).html());
        $('.msg_sub_store').html('SUBJECT: &nbsp;' + $('.cmplt_sub', this).html());
        $('.msg_msg_store').html($('.cmplt_msg', this).html());
        $('.msg_popup').addClass('active');
    });
    $(document).on('click', '#off_mssg_btn', function() {
        let name = $(this).attr('off_name');
        let id = $(this).attr('off_id');
        $('#toId').text('TO: ' + name + '(' + id + ')');
        $('#errDiv').text("");
        $('#msgAdminInput').val('');
        $('#popup1').toggleClass('active');
    });


    function showCpmltMsg() {
        mydata = { flag: '9' };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('#cmplt_body').html(data);
            }
        });

    }

    function ShowOfficerList() {
        mydata = { 'flag': '1' };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('#off_list_body').html(data);
            }
        }).done(checkStat);
    }

    function checkStat() {
        $('.off_stat').each(function() {
            stat = $(this).html();
            if (stat === "ACTIVE") {
                $(this).css('color', 'green');
            } else {
                $(this).css('color', 'red');
            }
        });
    }

    function showVerification() {
        mydata = { 'flag': '2' };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('#verify_tbody').html(data);
            }
        });
    }

    function showMessage() {
        mydata = { 'flag': '6' };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('#msg_tbody').html(data);
            }
        });
    }

    function genRandomPermitNum() {
        var text = "";
        var possible = "0123456789ABCEFGHIJKMNOPRS";
        for (var i = 0; i < 10; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        $('#permitInpt').val(text);
    }

    function showAvPemit() {
        mydata = { flag: '8' };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('.avPermitTbody').html(data);
            }
        });
    }

    ShowOfficerList();
    showVerification();
    showMessage();
    genRandomPermitNum();
    showAvPemit();
    showCpmltMsg();

    $(document).on('click', '#off_action_block', function() {
        console.log($(this).attr('off_id'));
        id = $(this).attr('off_id');
        mydata = { flag: '3', off_id: id };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                console.log(data);
                ShowOfficerList();
            }
        });
    });
    $(document).on('click', '#off_action_active', function() {
        console.log($(this).attr('off_id'));
        id = $(this).attr('off_id');
        mydata = { flag: '4', off_id: id };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                console.log(data);
                ShowOfficerList();
            }
        });
    });
    $(document).on('click', '#off_action_delete', function() {
        console.log($(this).attr('off_id'));
        id = $(this).attr('off_id');
        mydata = { flag: '5', off_id: id };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                console.log(data);
                ShowOfficerList();
            }
        });
    });
    $(document).on('click', '#request_decline_btn', function() {
        console.log($(this).attr('off_id'));
        id = $(this).attr('off_id');
        mydata = { flag: '5', off_id: id };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                console.log(data);
                ShowOfficerList();
                showVerification();
            }
        });
    });
    $(document).on('click', '#request_approve_btn', function() {
        console.log($(this).attr('off_id'));
        id = $(this).attr('off_id');
        mydata = { flag: '4', off_id: id };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                console.log(data);
                ShowOfficerList();
                showVerification();
            }
        });
    });
    $('#sendMsgToOfficer').on('click', function(e) {
        e.preventDefault();
        let off_id = $('#toId').html();
        off_id = off_id.substr(10);
        let msg = document.getElementById('msgAdminInput').value;
        mydata = { 'flag': '10', 'off_id': off_id, 'msg': msg };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "json",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    console.log("Success");
                    $('#errDiv').text('Message sent successfully.');
                    $('#errDiv').css('color', 'green');
                } else {
                    console.log("Something Went Wrong from the server!!");
                    $('#errDiv').text('Something Went Wrong!!');
                    $('#errDiv').css('color', 'red');
                }
            }
        });
    });

    $(document).on('click', '#off_activity_btn', function() {
        let id = $(this).attr('off_id');
        let mydata = { 'flag': '11', 'off_id': id };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                $('#activityTbody').html(data);
            }

        });
        $('#popup2').toggleClass('active');
    });

    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "../php/show_challan.php",
            method: "POST",
            dataType: "json",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data) {
                    x1 = data[0];
                    x2 = data[1];
                    console.log(data);
                } else {
                    x1 = "";
                    x2 = "";
                    console.log("Something Wrong!, jQuery");
                    $('.err').css('display', 'block');
                }

                $('#chNum').text("CHALLAN NO.:    " + x1[0].challan_number);
                $('#issDate').text("ISSUE DATE AND TIME:    " + x1[0].date_time);
                $('#place').text("PLACE:    " + x1[0].place);
                $('#vehNumber').text("REGISTRATION NO.:    " + x1[0].registration_number);
                $('#chasNum').text("CHASSIS NO.:    " + x1[0].chassis_number);
                $('#fName').text("OWNER NAME:    " + x1[0].name);
                $('#licenNum').text("OWNER LICENSE NO.:    " + x1[0].license_number);
                $('#add').text("ADDRESS:    " + x1[0].address);
                // Image Transferring to show
                targetFolder = "../Database_Uploaded/Challan_data/" + x1[0].picture;
                $('.detail2').css({ 'background-image': "url('" + targetFolder + "')" });
                $('#totalAmt').text("AMOUNT:    (Rs.)    " + x1[0].penalties);
                $('#status').html("<h4 id='paidStatus'>" + (x1[0].paid).toUpperCase() + "</h4>");

                // Table Data Show
                tableData = "";
                for (i = 0; i < x2.length; i++) {
                    tableData += "<tr><td>" + x2[i].section + "</td><td>" + x2[i].offence + "</td><td>" + x2[i].penalty + "</tr>";
                }
                $('#tBody').html(tableData);

                $('#challan_number').val(x1[0].challan_number);
                $('#amountPay').val(x1[0].penalties);

                var d = new Date();

                $('#recptNo').text("Receipt No.:    " + d.getFullYear() + (d.getMonth() + 1) + d.getDate() + d.getHours() + d.getMilliseconds());
                $('#genDate').text("Generated Date:    " + d.getDate() + "-" + (d.getMonth() + 1) + "-" + d.getFullYear());

                if ($('#paidStatus').text() == "UNPAID") {
                    $('#paidStatus').css('color', 'red');
                } else {
                    $('#paidStatus').css('color', 'green');
                }
                $('.searchResultDiv').css('display', 'block');
                $('#searchForm')[0].reset();
            }
        });
    });
    $('#permitGenForm').on('submit', function(e) {
        e.preventDefault();
        num = $('#permitInpt').val();
        offNum = $('#officerInpt').val();
        mydata = { flag: '7', pm_num: num, off_id: offNum };
        $.ajax({
            url: "../php/adminChallan.php",
            method: "POST",
            dataType: "html",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data == "Error") {
                    console.log(data);
                } else {

                    $('#avPermitTbody').html(data);
                    // $('#avPermitTbody').css('display', 'block');
                    showAvPemit();
                    genRandomPermitNum();
                }
            }
        });
    });



});