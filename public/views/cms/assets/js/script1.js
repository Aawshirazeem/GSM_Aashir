// JavaScript Document

$('document').ready(function ()
{
    /* validation */
    $("#signupform").validate({
        rules:
                {
                    username: {
                        required: true
                    },
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    country_id: {
                        required: true
                    },
                    password_new: {
                        required: true
                    },
                    password_confim: {
                        required: true,
                        equalTo: '#password_new'
                    },
                    email: {
                        required: true,
                        email: true
                    },
                },
        messages:
                {
                    username: "please enter user name",
                    phone: "please enter phone number",
                    city: "please enter city",
                    first_name: "please enter first name",
                    last_name: "please enter last name",
                    country_id: "please select country",
                    password_new: {
                        required: "please provide a password"
                    },
                    email: "please enter a valid email address",
                    cpassword: {
                        required: "please retype your password",
                        equalTo: "password doesn't match !"
                    }
                },
        submitHandler: submitForm
    });
    /* validation */

    /* form submit */
    function submitForm()
    {
        var data = $("#signupform").serialize();

        $.ajax({
            type: 'POST',
            url: '../user/register_process.do',
            data: data,
            beforeSend: function ()
            {
               // $("#error").fadeOut();
                $("#btn-submit").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; sending ...');
            },
            success: function (data)
            {

                if (data == "invalid_verification_code")
                {
//                    $("#error").fadeIn(1000, function () {
//
//
//                        $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry invalid verification code !</div>');
//                        $("html, body").animate({scrollTop: 0}, "slow");
//
//
//                    });

                    $("#error").html('<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Reply Info</h4></div><div class="modal-body" id="getCode" style="">Sorry invalid verification code !</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>');
                    $("#getCodeModal").modal('show');
                }
                if (data == "duplicate_email_username")
                {
//                    $("#error").fadeIn(1000, function () {
//
//
//                        $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry email and username already taken !</div>');
//                        $("html, body").animate({scrollTop: 0}, "slow");
//
//
//                    });
                    $("#error").html('<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Reply Info</h4></div><div class="modal-body" id="getCode" style="">Sorry email and username already taken !</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>');
                    $("#getCodeModal").modal('show');
                }
                if (data == "duplicate_username")
                {
//                    $("#error").fadeIn(1000, function () {
//
//
//                        $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry username already taken !</div>');
//                        $("html, body").animate({scrollTop: 0}, "slow");
//
//
//                    });
                    $("#error").html('<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Reply Info</h4></div><div class="modal-body" id="getCode" style="">Sorry username already taken !</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>');
                    $("#getCodeModal").modal('show');
                }
                if (data == "duplicate_email")
                {
//                    $("#error").fadeIn(1000, function () {
//
//
//                        $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Sorry email already taken !</div>');
//                        $("html, body").animate({scrollTop: 0}, "slow");
//
//
//                    });

                    $("#error").html('<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Reply Info</h4></div><div class="modal-body" id="getCode" style="">Sorry email already taken !</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>');
                    $("#getCodeModal").modal('show');
                }
                if (data == "thanks1")
                {
//                    $("#error").fadeIn(1000, function () {
//
//
//                        $("#error").html('<div class="alert alert-success"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; Thanks You are registered successfully!<br>An Activation main mail has been sent to you.Please check your email to activate your account instantly</div>');
//                        $("html, body").animate({scrollTop: 0}, "slow");
//                        document.getElementById("signupform").reset();
//
//
//                    });

                    $("#error").html('<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Reply Info</h4></div><div class="modal-body" id="getCode" style="">Thanks You are registered successfully!<br>An Activation main mail has been sent to you.Please check your email to activate your account instantly</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:window.location=\'login.html\'">Close</button></div></div></div></div>');
                    $("#getCodeModal").modal('show');
                }
                if (data == "thanks")
                {
//                    $("#error").fadeIn(1000, function () {
//
//
//                        $("#error").html('<div class="alert alert-success"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp;Your Registration request has been submitted successfully.<br>Please Contact With Admin for your account activation.</div>');
//                        $("html, body").animate({scrollTop: 0}, "slow");
//                        document.getElementById("signupform").reset();
//
//
//                    });

                    $("#error").html('<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Reply Info</h4></div><div class="modal-body" id="getCode" style="">Your Registration request has been submitted successfully.<br>Please Contact With Admin for your account activation.</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:window.location=\'login.html\'">Close</button></div></div></div></div>');
                    $("#getCodeModal").modal('show');
                }
                refreshCaptcha();
            }
        });
        return false;
    }
    /* form submit */

    function refreshCaptcha()
    {
        var img = document.images['captchaimg'];
        img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
    }


});