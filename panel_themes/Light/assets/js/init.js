var config_path_site = "/";
var config_current_time = "";
var IMEIs = "";

function setPaths(sitePath, currentTime)
{
	
	config_path_site = sitePath;
	config_current_time = currentTime;
}


$(document).ready(function () 
{
	loadSettings();
});



function loadSettings()
{
	$(".showDialogBox").click(function()
	{
        $("#" + $(this).attr('id') + "Panel").modal();
	});
	
	
	
	$( ".toggle" ).click(
		function() {
			$(".links:not(#" + $(this).attr('id') + "Box)").slideUp(500, 'easeInOutCirc');
			var id = $(this).attr('id');
			$("#" + id + "Box").slideToggle(500, 'easeInOutCirc');
		}
	);
	
	$( ".togglePending" ).click(
		function() {
			$(".pendingItems:not(#pending" + $(this).attr('id') + ")").slideUp(500, 'easeInOutCirc');
			var id = $(this).attr('id');
			$("#pending" + id + "").slideToggle(500, 'easeInOutCirc');
		}
	);

	
	$('.prompt').click(function (e) {
		var msg = $(this).attr('title');
		if(msg == "")
		{
			msg = "Are you sure you want to continue!";
		}
		var r=confirm(msg);
		if (r==true)
		{
			
		}
		else
		{
			e.preventDefault();
		} 
		
		
	});
	
	$('.promptOnSubmit').submit(function (e) {
		var msg = $(this).attr('title');
		if(msg == "")
		{
			msg = "Are you sure you want to continue!";
		}
		var r=confirm(msg);
		if (r==true)
		{
			
		}
		else
		{
			e.preventDefault();
		} 
	});
	
	$( "button, input:submit, a", ".butSkin" ).button();
	$( "#check" ).button();
	
	$( "a.demo" ).click(function() { return false; });
	
	
	
	$('.dashboard_stats .showDialogBox .headerPanel i').hover(
		function (){
			var box = $(this).parent().parent().parent().find('#' + $(this).attr('id') + 'Box');
			if(box.find('.percentage').length)
			{
				var pieValue = box.find('.percentage').attr('data-percent');
				box.find('.percentage').data('easyPieChart').update(0);
				box.find('.percentage').data('easyPieChart').update(pieValue);
			}
			box.fadeIn();
		},
		function (){
			$(this).parent().parent().parent().find('#' + $(this).attr('id') + 'Box').fadeOut();
		}
	);	
	
	$(".focusMe").focus();
	$(".focusMe").trigger('focus');
	
	if ( $('#messagebox').length )
	{
		$(function() {
			$( "#messagebox" ).dialog({
				modal: true,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		});
	}
	
	$(".selectAllBoxes").change(
		function() {
			$(".subSelect" + $(this).attr("id")).attr("checked",$(this).is(':checked'));
		}
	);
	
	$(".toggleOnCheck").change(
			function() {
				if($(this).is(':checked'))
				{
					$("#" + $(this).attr("id") + 'Hide').show();					
				}
				else
				{
					$("#" + $(this).attr("id") + 'Hide').hide();
				}
			}
		);

	
	$(".selectAllBoxesLink").click(function(e) {
			e.preventDefault();
			$(".subSelect" + $(this).attr("value")).prop("checked",true).trigger("change");
		}
	);
	$(".unselectAllBoxesLink").click(function(e) {
			e.preventDefault();
			$(".subSelect" + $(this).attr("value")).prop("checked",false).trigger("change");
		}
	);
	
}