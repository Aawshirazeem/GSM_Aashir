//var config_path_site_admin = "/impkk/admin/";
var mailStarted = 0;
function setPathsAdmin(sitePath)
{
	config_path_site_admin = sitePath;
	loadSettingsPanel();

	$('.popovers').popover();
	//$('.popovers').popover('show')

}



$(document).ready(function () {
//'/impkk/admin/');
	$('.search').blur(function(e) {
		 setTimeout($('.searchBox').slideUp('fast'), 1600);
	});
	$('.search').focus(function(e) {
		$('.searchBox').html('<li><a href="#"><i class="fa fa-level-up fa-flip-horizontal"></i> Search here</a></li>');
		 $('.searchBox').slideDown('fast');
	});
	$('.search').keyup(function(e) {
		clearTimeout($.data(this, 'timer'));
		$('.search').popover('hide');
		if (e.keyCode == 13)
			search(true);
		else
			$(this).data('timer', setTimeout(search, 500));
			
	});
	function search(force) {
		
		$('.searchBox').html('<li><a href="#"><i class="fa fa-refresh fa-spin"></i> Searching...</a></li>');

		var existingString = $(".search").val();
	    if (!force && existingString.length < 3){
	    	$('.searchBox').slideUp('fast');
	    	return; //wasn't enter, not > 2 char
	    } 
	    $('.searchBox').slideDown('fast');

		randNum = Math.random()*1000;
		$.ajax({
			type: "GET",
			url: config_path_site_admin + "_ajax_search.do",
			data: 'rand=' + randNum + "&search=" + existingString,
			error: function(){
				alert("Can't complete process at present please try after some time!");
			},
			success: function(msg){
				$('.searchBox').html(msg);
				$('.searchBox').slideDown('fast');
			}
		});
	}

	/*
	$( "#city" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				//url: "http://ws.geonames.org/searchJSON",
				url: config_path_site_admin + "_json_search.do",
				dataType: "jsonp",
				data: {
					featureClass: "P",
					style: "full",
					maxRows: 12,
					name_startsWith: request.term
				},
				error: function(){
					alert(config_path_site_admin + "_json_search.do" + "Can't complete process at present please try after some time!");
				},
				success: function( data ) {
					response( $.map( data.geonames, function( item ) {
					return {
						label: item.countryName,
						value: item.name
					}
					}));
				}
			});
		},
		
		minLength: 2,
		
		select: function( event, ui ) {
			window.location.href = ui.item.value;
			//alert("asdf");
			log( ui.item ?
			"Selected: " + ui.item.label :
			"Nothing selected, input was " + this.value);
		},
		
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
	*/
	
	
	$(".bodyMainLoader").hide();
	$("body").addClass('bodyAfter');
	$(".bodyMain").show();
	$(".delParent").click(function(){
		$(this).parent().parent().hide();
	});
	
	

	$('.autoCheckAll').change(function(){
		$('.autoCheckMe').prop("checked",$(this).is(":checked"));
		updateDownload();
	});
	
	$('.autoCheckMe').change(function(){
		updateDownload();
	});

	function updateDownload(){
		var imei = '';
		$('#downloadFile').hide();
		$('.autoCheckMe:checked').each(function(){
			imei += $(this).data('imei') + "\n";
			$('#downloadFile').show();
		});
		$('#downloadContent').text(imei);
	}

	$( ".autoFillText" ).keyup(
		function() {
			var id = $(this).attr('id');
			$("." + id + "Fill").val($(this).val());
		}
	);

	
});

function apiSync(type, id)
{
	//setPathsAdmin('/impkk/admin/');
	randNum = Math.random()*1000;
	switch(type)
	{
		case 1:
			$('#load_head').html("Checking connectivitiy...");
			$('#load_details').append("<p>Checking connectivitiy...</p>");
			$.ajax({
				type: "GET",
				url: config_path_site_admin + "_ajax_api_connect.do",
				data: 'rand=' + randNum + "&id=" + id,
				error: function(){
					alert("Can't complete process at present please try after some time!");
				},
				success: function(msg){
					$('#load_details').append(msg);
					$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
					$('.progress-bar-sync').css("width", '25%');
					apiSync(2, id)
				}
			});
			break;
		case 2:
			$('#load_head').html("Synchronizing unlocking tools...");
			$('#load_details').append("<p>Synchronizing unlocking tools...</p>");
			$.ajax({
				type: "GET",
				url: config_path_site_admin + "_ajax_api_sync_tools.do",
				data: 'rand=' + randNum + "&id=" + id,
				error: function(){
					alert("Can't complete process at present please try after some time!");
				},
				success: function(msg){
					$('#load_details').append(msg);
					$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
					$('.progress-bar-sync').css("width", '50%');
					apiSync(3, id)
				}
			});
			break;
		case 3:
			$('#load_head').html("Synchronizing brands...");
			$('#load_details').append("<p>Synchronizing brands...</p>");
			$.ajax({
				type: "GET",
				url: config_path_site_admin + "_ajax_api_sync_brands.do",
				data: 'rand=' + randNum + "&id=" + id,
				error: function(){
					alert("Can't complete process at present please try after some time!");
				},
				success: function(msg){
					$('#load_details').append(msg);
					$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
					$('.progress-bar-sync').css("width", '75%');
					apiSync(4, id)
				}
			});
			break;
		case 4:
			$('#load_head').html("Synchronizing country/network...");
			$('#load_details').append("<p>Synchronizing country/network...</p>");
			$.ajax({
				type: "GET",
				url: config_path_site_admin + "_ajax_api_sync_country.do",
				data: 'rand=' + randNum + "&id=" + id,
				error: function(){
					alert("Can't complete process at present please try after some time!");
				},
				success: function(msg){
					$('#load_details').append(msg);
					$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
					$('#load_head_main').html('<i class="fa fa-check text-success"></i> <span id="load_head" class="text-success">Synchronization done!</span>');
					$('#btnCancel').hide();
					$('#btnBack').show();
					$('.progress-bar-sync').css("width", '100%');
					$('.progress').toggleClass('active');
					$('.progress').toggleClass('progress-striped');
				}
			});
			break;
	}
}




function startPassword()
{
	/*if($('#tblResetAllPasswords').length == 0)
	{
		return;
	}
	*/
	$('#startResetAllPasswords').hide();
	var ids = '';
	max = (arr.length > 5) ? 5 : arr.length;
	for(i=0;i<max;i++)
	{
		ids += '&ids[]=' + arr[i];
		$("#td_pass_" + arr[i]).html($("#waitSample").html());
	}
	randNum = Math.random()*1000;
	$.ajax({
		type: "GET",
		url: config_path_site_admin + "_ajax_users_reset_password.do",
		data: 'rand=' + randNum + ids,
		error: function(){
			alert("Can't complete process at present please try after some time!");
		},
		success: function(msg){
			for(i=0;i<max;i++)
			{
				$("#td_pass_" + arr[i]).html("Done.");
			}
			if(arr.length > 5)
			{
				arr = arr.splice(5);
				startPassword();
			}
		}
	});
}


function startEmails()
{

	var subject = $("input:text[name=subject]").val();
	var body = CKEDITOR.instances['editor1'].getData();
	if(subject == "")
	{
		alert("Please enter subject");
		return;
	}
	if(body == "")
	{
		alert("Can't send blank email. Please enter some text to email!");
		return;
	}
	if($("input:checkbox[name=ids]:checked").length == 0 && mailStarted == 0)
	{
		alert("Please select user(s) to mail this content.");
		return;
	}
	mailStarted = 1;
	
	if($('#startSendEmails').length == 0){ return; }
	
	$('#startSendEmails').slideUp();
	$('#startSendEmailsWait').slideDown();
	
	var ids = '';
	var emails = '';
	var arr = new Array();
	
	$("input:checkbox[name=ids]:checked").each(function()
	{
		arr.push($(this).val())
	});
	//alert(arr);
        //return;
	max = arr.length;
        //alert(max);
	if(max == 0)
	{
		return;
                alert('max is 0');
	}
	for(i=0;i<max;i++)
	{
		ids += '&ids[]=' + arr[i];
		emails = '&emails[]=' + $("input:hidden[name=email_" + arr[i] + "]").val();
		$("#td_check_" + arr[i]).html('');
		$("#td_email_" + arr[i]).html('Sending...');
                $.ajax({
		type: "POST",
		url: config_path_site_admin + "_ajax_send_email.do?"+'&subject=' + subject + '&body=' + escape(body)  +emails,
		data: '&subject=' + subject + '&body=' + escape(body) + emails+ '&IDD='+i,
		error: function(){
			alert("Can't complete process at present please try after some time!");
		},
		success: function(msg){
                    //alert(msg);
        
				$("#td_email_" + arr[msg]).html("Done.");
        
			//startEmails();
		}
	});
	}
       // alert(emails);
	//var randNum = Math.random()*1000;
//	$.ajax({
//		type: "POST",
//		url: config_path_site_admin + "_ajax_send_email.do?"+'&subject=' + subject + '&body=' + escape(body)  +emails,
//		data: '&subject=' + subject + '&body=' + escape(body) + emails,
//		error: function(){
//			alert("Can't complete process at present please try after some time!");
//		},
//		success: function(msg){
//			for(i=0;i<max;i++)
//			{
//				$("#td_email_" + arr[i]).html("Done.");
//			}
//			//startEmails();
//		}
//	});
}


function loadSettingsPanel()
{	
	// Clear icons on enter username
	$(".checkUserName").focus(
		function () {
			$("#usernameAvail").fadeOut();
			$("#usernameNotAvail").fadeOut();
		}
	);
	
	// Show icons when we leave username textbox
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
						url: config_path_site_admin + "_ajax_check_username.do",
						data: "username=" + tempUsername + '&rand=' + randNum,
						error: function(){
							alert("Can't complete process at present please try after some time!");
							$("#usernameWait").hide();
						},
						success: function(msg){
							if(msg == "Avail")
							{
								$("#usernameAvail").fadeIn();
								$(".formSubmit").show();
							}
							else
							{
								$("#usernameNotAvail").fadeIn();
								$(".formSubmit").hide();
							}
							$("#usernameWait").hide();
						}
					});
			}
		
		}
	);
	
	
	$(".checkUserNameSupplier").focus(
		function () {
			$("#usernameAvailSupplier").fadeOut();
			$("#usernameNotAvailSupplier").fadeOut();
		}
	);
	$(".checkUserNameSupplier").blur(
		function () {
                 //   setPathsAdmin('/impkk/admin/');
				var tempUsername = $(this).val().trim();
				$("#usernameAvailSupplier").hide();
				$("#usernameNotAvailSupplier").hide();
				if(tempUsername != "")
				{
					$("#usernameWaitSupplier").show();
					randNum = Math.random()*1000;
					$.ajax({
						type: "GET",
						url: config_path_site_admin + "_ajax_check_username_supplier.do",
						data: "username=" + tempUsername + '&rand=' + randNum,
						error: function(){
							alert("Can't complete process at present please try after some time!");
							$("#usernameWaitSupplier").hide();
						},
						success: function(msg){
							if(msg == "Avail")
							{
								$("#usernameAvailSupplier").fadeIn();
								$(".formSubmit").show();
							}
							else
							{
								$("#usernameNotAvailSupplier").fadeIn();
								$(".formSubmit").hide();
							}
							$("#usernameWaitSupplier").hide();
					}
				});
			}
		
		}
	);
	
	
	// Clear icons when we enter email textbox
	$(".checkEmail").focus(
		function () {
			$("#emailAvail").fadeOut();
			$("#emailNotAvail").fadeOut();
		}
	);
	$(".checkEmailSupplier").focus(
		function () {
			$("#emailAvailSupplier").fadeOut();
			$("#emailNotAvailSupplier").fadeOut();
		}
	);
	
	// Check email in database and show appropriate icons
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
						url: config_path_site_admin + "_ajax_check_email.do",
						data: "email=" + tempEmail + '&rand=' + randNum,
						error: function(){
							alert("Can't complete process at present please try after some time!");
							$("#emailWait").hide();
						},
						success: function(msg){
							if(msg == "Avail")
							{
								$("#emailAvail").fadeIn();
								$(".formSubmit").show();
							}
							else
							{
								$("#emailNotAvail").fadeIn();
								$(".formSubmit").hide();
							}
							$("#emailWait").hide();
					}
				});
			}
		
		}
	);
	$(".checkEmailSupplier").blur(
		function () {
				var tempEmail = $(this).val().trim();
                                
				$("#emailAvailSupplier").hide();
				$("#emailNotAvailSupplier").hide();
				if(tempEmail != "")
				{
                                   // setPathsAdmin('/impkk/admin/');
					$("#emailWaitSupplier").show();
					randNum = Math.random()*1000;
					$.ajax({
						type: "GET",
						url: config_path_site_admin + "_ajax_check_email_supplier.do",
						data: "email=" + tempEmail + '&rand=' + randNum,
						error: function(){
							alert("Can't complete process at present please try after some time!");
							$("#emailWaitSupplier").hide();
						},
						success: function(msg){
							if(msg == "Avail")
							{
								$("#emailAvailSupplier").fadeIn();
							}
							else
							{
								$("#emailNotAvailSupplier").fadeIn();
							}
							$("#emailWaitSupplier").hide();
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
				$("#crAddDiv").show();
				$("#crRevokeDiv").hide();
				$("#crAdd").focus();
			}
			else
			{
				$("#crAdd").val("0");
				$("#crAddDiv").hide();
				$("#crRevokeDiv").show();
				$("#crRevoke").focus();
			}
		}
	);

	$("#api_id").change(
		function () {
			var tempApiID = $(this).val().trim();
                       var calltype= $("#calltype").val();
                       //alert(calltype);
			if(tempApiID != "")
			{
				$("#ApiIdWait").show();
				randNum = Math.random()*1000;
				$.ajax({
					type: "GET",
					url: config_path_site_admin + "_ajax_load_api_details.do",
					data: "api_id=" + tempApiID + '&rand=' + randNum +'&type='+calltype,
					error: function(){
						alert("Can't complete process at present please try after some time!");
						$("#ApiIdWait").hide();
					},
					success: function(msg){
						$('#apiIdDetails').html(msg);
						$("#ApiIdWait").hide();
					}
				});
			}
		}
	);
	
	$("#clearAllItems").change(function(){
		if($(this).val() == 1)
		{
			$('#butAll').show();
		}
		else
		{
			$('#butAll').hide();
		}
	});
	
	
	
	

}

function imeiOrders()
{	
	/*****************************************
				IMEI orders
	******************************************/
	// variable to hold request
	var request;
	var firstReqiest = true;
	var tTotalRequest = 0;
	var tRequestPending = 0;
	var type = '';
	// bind to the submit event of our form
	$("#frmAjaxOrderExtra").submit(function(event){
		event.preventDefault();
		
		type = $('input[name="reqeustType"]').val();
		switch(type)
		{
			case 'pending':
				if(firstReqiest == true)
				{
					$('#loadingPanel').show();
					$('#frmAjaxOrder').hide();
					$('.lock-to-top').hide();
					tTotalRequest = $('input[name="locked[]"]:checked').length;
					tRequestPending = tTotalRequest;
					firstReqiest = false;
				}
				
				//Save Data to temp IMEI textarea
				var i =0;
				$("#tempFields").html('');
				$('input[name="locked[]"]:checked').each(function(){
					if(i < 100)
					{
						$(this).prop("checked",false);
						var chkLock = '<input type="checkbox" checked="checked" name="locked[]" value="' + $(this).val() + '" />' + $(this).val();
						$("#tempFields").append(chkLock);
						$("#tempDownloadIMEIS").append('<input type="hidden" name="Ids[]" value="' + $(this).val() + '" />');
						tRequestPending--;
					}
					i++;
				});
				break;
			case 'locked':
				if(firstReqiest == true)
				{
					$('#loadingPanel').show();
					$('#frmAjaxOrder').hide();
					$('.lock-to-top').hide();
					tTotalRequest = $('input.txtCode').filter(function() { return this.value; }).length;
					tTotalRequest += $('input.subSelectUnavail:checked').length;
					
					tRequestPending = tTotalRequest;
					firstReqiest = false;
				}
				
				
				//Save Data to temp IMEI textarea
				var i =0;
				$("#tempFields").html('');
				$('input.txtCode').filter(function() { return this.value; }).each(function(){
					if(i < 100)
					{
						$(this).prop("checked",false);
						var tempName = $(this).attr("name");
						var tempId = $(this).attr("id");
						var tempVal = $(this).val();
						var itemId = tempName.substring(tempName.lastIndexOf('_') + 1);
						var chkLock = '<input type="hidden" name="' + tempName + '" id="' + tempId + '" value="' + tempVal + '" />';
						if($("#inpro_"+ itemId).is(':checked'))
                                                chkLock += '<input type="hidden" name="inproc_' + itemId + '" value="' + itemId + '" />';
                                                chkLock += '<input type="hidden" name="Ids[]" value="' + itemId + '" />';
						$("#tempFields").append(chkLock);
						$("#tempDownloadIMEIS").append('<input type="hidden" name="Ids[]" value="' + itemId + '" />');
						
						$(this).val('');
						tRequestPending--;
					}
					i++;
				});
				$('input.subSelectUnavail:checked').each(function(){
					if(i < 100)
					{
						$(this).prop("checked",false);
						var tempName = $(this).attr("name");
						var tempId = $(this).attr("id");
						var tempVal = $(this).val();
						var itemId = tempName.substring(tempName.lastIndexOf('_') + 1);
						var chkLock = '<input type="checkbox" name="unavailable_' + itemId + '" id="check' + itemId + '" checked="checked">';
						var tempRemarks = $('input[name="un_remarks_' + itemId + '"]').val();
						chkLock += '<input type="text" name="un_remarks_' + itemId + '" id="check' + itemId + 'Hide" value="' + tempRemarks + '">';
						chkLock += '<input type="hidden" name="Ids[]" value="' + itemId + '" />';
						$("#tempFields").append(chkLock);
						$("#tempDownloadIMEIS").append('<input type="hidden" name="Ids[]" value="' + itemId + '" />');
						$(this).val('');
						tRequestPending--;
					}
					i++;
				});
				break;
		}
		
		
		var per = ((tTotalRequest - tRequestPending)/tTotalRequest) * 100;
		
		$("#pBarSubmit").attr("value", per);
		$("#pBarSubmit").show();
		$('#btn-group-top').hide();
		// abort any pending request
		if (request) {
			request.abort();
		}
		// setup some local variables
		var $form = $(this);
		// let's select and cache all the fields
		var $inputs = $form.find("input, select, button, textarea");
		// serialize the data in the form
		var serializedData = $form.serialize();

		// let's disable the inputs for the duration of the ajax request
                 //   setPathsAdmin('/impkk/admin/');
		// fire off the request to /form.php
		request = $.ajax({
			url: config_path_site_admin + "order_imei_" + type + "_process_ajax.do",
			type: "post",
			data: serializedData
		});

		// callback handler that will be called on success
		request.done(function (response, textStatus, jqXHR){
			// log a message to the console
			try {
				var obj = jQuery.parseJSON(response);
				if(obj.result == 'Done')
				{
					
				}
				else
				{
					switch(obj.result)
					{
						case 'reply_imei_miss':
							$("#h1Error").show();
							$("#h1ErrorText").text("Please enter some IMEIs!");
							break;
						case 'reply_insuff_credits':
							$("#h1Error").show();
							$("#panelButtonsCredits").show();
							$("#h1ErrorText").text("You don't have sufficient credits to continue!");
							break;
					}
					$("#h1Wait").hide();
					$("#pBarSubmit").hide();
					return false
				}
			} catch(err){
				alert(err + response);
			}
			if($('#tempFields').html() != '')
			{
				$("#frmAjaxOrderExtra").submit();
			}
			else
			{
				$("#h1Done").show();
				$("#panelButtons").show();
				$("#h1Wait").hide();
				$("#pBarSubmit").hide();
			}
			return false
		});

		// callback handler that will be called on failure
		request.fail(function (jqXHR, textStatus, errorThrown){
			// log the error to the console
			alert("there is some error!" + jqXHR + textStatus + errorThrown);
			return false;
		});

		// callback handler that will be called regardless
		// if the request failed or succeeded
		request.always(function () {
			// reenable the inputs
			//$inputs.prop("disabled", false);
		});

		// prevent default posting of form
		event.preventDefault();
	});
}


function imeiDownload()
{	
	$('.loadIMEIs').click(function(){
		showIMEIs();
	});
	function showIMEIs()
	{
		$('#imeiPlain').html($('#imeiPlain2').html());
		if(!$("input:checkbox[name=toggleID]").is(':checked'))
		{
			$('#imeiPlain > .tplId').remove();
		}
		if(!$("input:checkbox[name=toggleDate]").is(':checked'))
		{
			$('#imeiPlain > .tplDetails > .tplDate').remove();
		}
		if(!$("input:checkbox[name=toggleServices]").is(':checked'))
		{
			$('#imeiPlain > .tplDetails > .tplServices').remove();
		}
		if(!$("input:checkbox[name=toggleDate]").is(':checked') && !$("input:checkbox[name=toggleServices]").is(':checked'))
		{
			$('#imeiPlain > .tplDetails').remove();
		}
		$('#imei').val($('#imeiPlain').text());
		$('#imei2').val($('#imeiPlain').text());
	}
	showIMEIs();
}





