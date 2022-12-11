//CHALLANPAYMENT SELECTION TEXT
function SelectedText(num) {
    var txt = 0;
    var inpt = document.getElementsByClassName("inptCheck");
    if (num == '1') {
        txt = "CHALLAN NUMBER";
        // inpt[0].setAttribute("minlength", '6');
    } else if (num == '2') {
        txt = "VEHICLE NUMBER";
        // inpt[0].setAttribute("minlength", '10');
    } else {
        txt = "DL NUMBER";
        // inpt[0].setAttribute("minlength", '15');
    }

    inpt[0].placeholder = "ENTER " + txt;
}

function togglePopup1() {
    document.getElementById("popup1").classList.toggle("active");
}

$(document).ready(function() {

    $('#flipLbl').css('display', 'none');
    $('.err').css('display', 'none');
    $('.midDiv').css('display', 'none');
    $('.pdfDiv').css('display', 'none');

    let ChallanNum = "";
    $('#firstForm').on('submit', function(e) {
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
                ChallanNum = x1[0].challan_number;
                $('#chNum').text("CHALLAN NO.:    " + x1[0].challan_number);
                $('#issDate').text("ISSUE DATE AND TIME:    " + x1[0].date_time);
                $('#place').text("PLACE:    " + x1[0].place);
                $('#vehNumber').text("REGISTRATION NO.:    " + x1[0].registration_number);
                $('#chasNum').text("CHASSIS NO.:    " + x1[0].chassis_number);
                $('#fName').text("OWNER NAME:    " + x1[0].name);
                $('#licenNum').text("OWNER LICENSE NO.:    " + x1[0].license_number);
                $('#add').text("ADDRESS:    " + x1[0].address);
                // Image Transferring to show
                targetFolder = "/E-Challan/Database_Uploaded/Challan_data/" + x1[0].picture;
                $('.detail2').css({ 'content': "url('" + targetFolder + "')" });
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
                    $('#flipLbl').css('display', 'block');
                    $('#paidStatus').css('color', 'red');
                } else {
                    $('#paidStatus').css('color', 'green');
                    $('.pdfDiv').css('display', 'block');
                }

                $('.midDiv').css('display', 'block');
            }
        });
    });


    // Payment Form Submission
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "../php/payment.php",
            method: "POST",
            dataType: "json",
            data: new FormData(this),
            contentType: false,
            cache: false,
            async: false,
            processData: false,
            success: function(data) {
                    if (data == "Success!") {
                        $('.pdfDiv').css('display', 'block');
                        console.log("Success!");
                    } else {
                        console.log(data);
                    }


                    $('#paidStatus').text("PAID");
                    $('#paidStatus').css('color', 'green');
                    $('#flipLbl').css('display', 'none');
                    $('#paymentForm')[0].reset();
                }
                // complete: function(data) {
                //     if (data == "Success!") {
                //     }
                // }
        });
    });

    $('#pdfBtn').on('click', function() {
        console.log('Processing...');
        $('#secDivReceipt').printThis({

            printDelay: 2000,
            importCSS: false,
            importStyle: true,
            loadCSS: ['E-Challan/css/challanPayment.css', 'E-Challan/css/home.css'],
            debug: true,
            canvas: true,
            base: true,
            formValues: true,
            doctypeString: true
        });
    });

    $('#cmpltBtn').on('click', function() {
        let chNum = ChallanNum;
        let sub = document.getElementById('cmpltSub').value;
        let msg = document.getElementById('cmpltMsg').value;
        if (sub == '' || msg == '') {
            $('#errContent').text('Input Field Cannot be Empty');
            $('#errContent').css('color', 'red');
        }
        mydata = { 'chNum': chNum, 'sub': sub, 'msg': msg };
        $.ajax({
            url: "../php/complaint_upload.php",
            method: "POST",
            data: JSON.stringify(mydata),
            success: function(data) {
                if (data) {
                    $('#errContent').text('Complaint Successfully Sent.');
                    $('#errContent').css('color', 'green');
                } else {
                    $('#errContent').text('Invalid Challan Number');
                    $('#errContent').css('color', 'red');
                }
            }
        });
    });
});