	$(document).ready(function(){
		function generate() {
			var imei = $('input#imei').val();
			imei = remove_not_digits(imei);
			$('#imei').val(imei);
			$('.imeicd').html(gen_cd(imei));
			$('.imeicdtext').val(gen_cd(imei));
		}
		
	    function remove_not_digits(imei) {
	        return imei.replace(/[^0-9]/, '0');
	    }

		function gen_cd(imei) {

		    var step2 = 0;
		    var step2a = 0;
		    var step2b = 0;
		    var step3 = 0;

		    // add zero's till the length is 14
		    for(var i=imei.length; i < 14; i++)
		    imei = imei + "0";

		    for(var i=1; i<14; i=i+2) {
		    var step1 = (imei.charAt(i))*2 + "0";
		    // add the individual digits of the numbers calculates in step 1
		    step2a = step2a + parseInt(step1.charAt(0)) + parseInt(step1.charAt(1));
		    }

		    // add together all the digits on an even position
		    for(var i=0;i<14;i=i+2)
		    step2b = step2b + parseInt(imei.charAt(i));

		    step2 = step2a + step2b;

		    // if the last digit of step2 is zero then the Luhn digit is zero
		    if ( step2 % 10 == 0) step3 = 0;
		    // otherwise find the nearest higher number ending with a zero
		    else
		    step3 = 10 - step2 % 10;

		    return step3;
		}
	    
		$( document ).on( "keyup", "#imei", function() {
			generate();
		});
	});