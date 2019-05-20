var config_path_site = "/";
var config_path_site_member = "/";

$(document).ready(function () 
{
	loadSettingsPanel();
});

function setPathsMember($site, $member)
{
	config_path_site = $site;
	config_path_site_member = $member;
}

$(document).ready(function () {
	$(".bodyMainLoader").hide();
	$("body").addClass('bodyAfter');
	$(".bodyMain").show();

});

function loadSettingsPanel()
{
	fileService();
	serverLog();
	prepaidLog();
	imeiTools();
	
	creditPurchase();
	

    $('.chosen-select').chosen();
	
	


	
	$(".checkUserName").focus(
		function () {
			$("#usernameAvail").fadeOut();
			$("#usernameNotAvail").fadeOut();
		}
	);

	$(".checkUserName").blur(
		function () {
				var tempUsername = $(this).val().trim();
				$("#usernameAvail").hide();
				$("#usernameNotAvail").hide();
				if(tempUsername != "")
				{
					$("#usernameWait").show();
					randNum = Math.random()*1000;
					$.ajax({
						type: "GET",
						url: config_path_site_member + "_ajax_check_username.do",
						data: "username=" + tempUsername + '&rand=' + randNum,
						error: function(){
							alert("Can't complete process at present please try after some time!</div>");
							$("#usernameWait").hide();
						},
						success: function(msg){
							if(msg == "Avail")
							{
								$("#usernameAvail").fadeIn();
							}
							else
							{
								$("#usernameNotAvail").fadeIn();
							}
							$("#usernameWait").hide();
					}
				});
			}
		
		}
	);
	
	
	

	
	
	$(".checkEmail").focus(
		function () {
			$("#emailAvail").fadeOut();
			$("#emailNotAvail").fadeOut();
		}
	);

	$(".checkEmail").blur(
		function () {
				var tempEmail = $(this).val().trim();
				$("#emailAvail").hide();
				$("#emailNotAvail").hide();
				if(tempEmail != "")
				{
					$("#emailWait").show();
					randNum = Math.random()*1000;
					$.ajax({
						type: "GET",
						url: config_path_site_member + "_ajax_check_email.do",
						data: "email=" + tempEmail + '&rand=' + randNum,
						error: function(){
							alert("Can't complete process at present please try after some time!</div>");
							$("#emailWait").hide();
						},
						success: function(msg){
							if(msg == "Avail")
							{
								$("#emailAvail").fadeIn();
							}
							else
							{
								$("#emailNotAvail").fadeIn();
							}
							$("#emailWait").hide();
					}
				});
			}
		
		}
	);

	$(".creditsTransferType").change(
		function () {
			if($(this).val() == '1')
			{
				$("#crAdd").val("");
				$("#crAdd").focus();
				$("#crAdd").removeAttr("disabled")
				$("#crRevoke").val("0");
				$("#crRevoke").attr("disabled","disabled")
			}
			else
			{
				$("#crAdd").val("0");
				$("#crAdd").attr("disabled","disabled")
				$("#crRevoke").val("");
				$("#crRevoke").focus();
				$("#crRevoke").removeAttr("disabled")
			}
		}
	);



}


function creditPurchase()
{
	if ( $('#creditList').length )
	{
		$('#creditList').change(
			function()
			{
			    $("#credits").val($(this).val());
			    $("#credits").focus();
			    creditCheck();
			}
		);
	}
	
	
	if ( $('.creditsTransferType').length )
	{
		$('.creditsTransferType').click(
			function()
			{
				creditCheck();
			}
		);
	}

	if ( $('#credits').length )
	{
		$('#credits').change(
			function()
			{
				creditCheck();
			}
		);
	}

}

function creditCheck()
{
	var pgID = $(".creditsTransferType:checked").val();
	var prefix = $("#prefix").val();
	var suffix = $("#suffix").val();
	var defaultPrefix = $("#defaultPrefix").val();
	var defaultSuffix = $("#defaultSuffix").val();
	var prefix_default = $("#prefix_default").val();
	var suffix_default = $("#suffix_default").val();
	var min = parseInt($("#min" + pgID).val());
	var max = parseInt($("#max" + pgID).val());
	
	var rate = $("#rate").val();
	var credits = parseInt($("#credits").val());
	var outOfRange = 0;
	$("#lblOutOfRange").hide();
	if((min > 0 || max > 0) && credits > 0)
	{
		$("#trRange").show();
		$("#valRange").html(min + " to " + max);
		if((min == 0 && credits > max) && outOfRange == 0)
		{
			outOfRange = 1
		}
		if((max == 0 && credits < min) && outOfRange == 0)
		{
			outOfRange = 2
		}
		if((credits > max || credits < min) && outOfRange == 0)
		{
			outOfRange = 3
		}
	}
	else
	{
		$("#trRange").hide();
		$("#valRange").html('-');
	}
	if(outOfRange > 0)
	{
		$("#valRate").html("-");
		$("#valCr").html("-");
		$("#valPer").html("-");
		$("#valCharges").html("-");
		$("#valToPay").html("-");
		$("#lblOutOfRange").show();
		alert("Out of range");
		return;
	}
	
	if(credits == "")
	{
		credits = 0;
	}
        
	var per = 0;
	$("#tblCredits:hidden").fadeIn();
	per = $("input[name=charges" + pgID + "]").val();
	rate=1;
	var ratePer = new Number(rate);
       	credits = credits / rate;
	// alert(credits);
	var charges = parseFloat(credits*per/100)
        //alert(charges);
	var total = credits + charges;
//	alert(total);
	$("#valRate").html(prefix + "1" + suffix + " = " + defaultPrefix + ratePer.toPrecision(3) + defaultSuffix);
	$("#valCr").html(prefix + " " + credits.toPrecision(3) + defaultSuffix);
	$("#valPer").html(per + "%");
	$("#valCharges").html(prefix + " " + charges.toPrecision(3) + defaultSuffix);
	$("#valToPay").html(prefix + " " + total.toPrecision(3) + defaultSuffix);
}


function fileService()
{
	if ( $('#file_service').length )
	{
		$('#file_service').change(
			function()
			{
			    fileServiceDetails();
			}
		);
	}
}

function fileServiceDetails()
{
	$("#loadIndTool").show();
	randNum = Math.random()*1000;
	$.ajax({
		type: "GET",
		url: config_path_site_member + "_ajax_load_file_service_details.do",
		data: "temp=" + randNum + '&file_service=' + $("#file_service").val(),
		error: function(){
			$("#toolDetails").html('<div class="warning">Can\'t complete process at present please try after some time!</div>');
			$("#loadIndTool").hide();
		},
		success: function(msg){
			$("#toolDetails").hide();
			$("#toolDetails").html(msg);
			$("#toolDetails").fadeIn();
			$("#loadIndTool").fadeOut();
		}
	});

}





function imeiTools()
{
	if ( $('#tool').length )
	{
		$('#tool').change(
			function()
			{
			    imeiToolDetails();
			    imeiLoadNetwork();
			    imeiLoadModel();
			}
		);
	}
}
function imeiToolDetails()
{
     //setPathsMember('','/impkk/user/');
	$('#loadIndTool').show();
        $("#toolDetails").hide();
	//$("#toolDetails").html('<div style="text-align:center;width:100%;font-size:16px;line-height:16px;"><br /><br /><img src="/images/progress.gif" /> Please wait...</div>');
	randNum = Math.random()*1000;
	$.ajax({
	   type: "GET",
	   url: config_path_site_member + "_ajax_load_tool_details.do",
	   data: "temp=" + randNum + '&tool=' + $("#tool").val(),
	   error: function(){
		 $("#toolDetails").html('<div class="warning">Can\'t complete process at present please try after some time!</div>');
		 $("#loadIndTool").hide();
	   },
	   success: function(msg){
	   
	   	$("#toolDetails").hide();
		   $("#toolDetails").html(msg);
		   $("#toolDetails").fadeIn();
		   imeiLoadNetwork();
		   imeiLoadModel();
		   $("#loadIndTool").fadeOut();
	   }
	 });
	//ajax End
}

function imeiLoadNetwork()
{
	$('#brand').change(
		function()
		{
			$("#loadIndBrand").show();
			$.getJSON(config_path_site_member + "_ajax_brand_list.do",{id: $(this).val()}, function(j){
				var options = '';
				for (var i = 0; i < j.length; i++) {
					options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
				}
				$("#model").html(options);
				$('#model option:first').attr('selected', 'selected');
				$("#loadIndBrand").fadeOut();
			})
		}
	);
}


function imeiLoadModel()
{
	$('#country').change(
		function()
		{
			$("#loadIndCountry").show();
			$.getJSON(config_path_site_member + "_ajax_network_list.do",{id: $(this).val()}, function(j){
				var options = '';
				for (var i = 0; i < j.length; i++) {
					options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
				}
				$("#network").html(options);
				$('#network option:first').attr('selected', 'selected');
				$("#loadIndCountry").fadeOut();
			});
		}
	);
}


function serverLog()
{
	if ( $('#server_log').length )
	{
		$('#server_log').change(
			function()
			{
			    serverLogDetails();
			}
		);
	}
}

function serverLogDetails()
{
	$("#loadIndTool").show();
       // setPathsMember('','/impkk/user/');
	randNum = Math.random()*1000;
	$.ajax({
		type: "GET",
		url: config_path_site_member + "_ajax_load_server_log_details.do",
		data: "temp=" + randNum + '&server_log=' + $("#server_log").val(),
		error: function(){
			$("#toolDetails").html('<div class="warning">Can\'t complete process at present please try after some time!</div>');
			$("#loadIndTool").hide();
		},
		success: function(msg){
			$("#toolDetails").hide();
			$("#toolDetails").html(msg);
			$("#toolDetails").fadeIn();
			$("#loadIndTool").fadeOut();
		}
	});

}


function prepaidLog()
{
	if ( $('#prepaid_log').length )
	{
		$('#prepaid_log').change(
			function()
			{
			    prepaidLogDetails();
			}
		);
	}
}

function prepaidLogDetails()
{
	$("#loadIndTool").show();
	randNum = Math.random()*1000;
	$.ajax({
		type: "GET",
		url: config_path_site_member + "_ajax_load_prepaid_log_details.do",
		data: "temp=" + randNum + '&prepaid_log=' + $("#prepaid_log").val(),
		error: function(){
			$("#toolDetails").html('<div class="warning">Can\'t complete process at present please try after some time!</div>');
			$("#loadIndTool").hide();
		},
		success: function(msg){
			$("#toolDetails").hide();
			$("#toolDetails").html(msg);
			$("#toolDetails").fadeIn();
			$("#loadIndTool").fadeOut();
		}
	});

}






