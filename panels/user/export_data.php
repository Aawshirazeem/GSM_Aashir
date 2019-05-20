<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2><?php $lang->prints('lbl_Export_Data'); ?></h2>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>imei_download.do" enctype="multipart/form-data" method="post">
    <div class="col-lg-6 col-lg-offset-3">
	
	
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Export_Data')); ?></div>
	
	<div class="panel-body">
	
	
	
		<div class="col-lg-2 col-xs-4">
			<div class="form-group">
				<label>IMEI</label>
				<input type="checkbox" name="imei" value="1">
			</div>
		</div>		
		<div class="col-lg-2 col-xs-4">	
			<div class="form-group">	
				<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Code')); ?></label>
				<input type="checkbox" name="ucode" value="1">
			</div>
		</div>	
		<div class="col-lg-8 col-xs-4">	
			<div class="form-group">	
				<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Service')); ?></label>
				<input type="checkbox" name="servicename" value="1">
			</div>
		</div>	
        <div class="form-group">
            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Service_Type')); ?></label>
            <select name="stype" class="form-control" id="stype" onchange="getServices();">
                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Select_Service_Type')); ?></option>
                <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),'IMEI'); ?></option>
                <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(),'FILE'); ?></option>
                <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(),'Server'); ?></option>                                            
            </select>
        </div>

        <div class="form-group" id="services">

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-lg-6" data-date-format="yyyy-mm-dd">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_From_Date')); ?></label>
    <!--										<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>-->
                    <input class="datepicker form-control" id="fdt" name="from_date" data-date-format="yyyy-mm-dd" value="<?php echo date("Y-m-d"); ?>">
                </div>
                <div class="col-lg-6" data-date-format="yyyy-mm-dd">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_To_Date')); ?></label>
    <!--										<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>-->
                    <input class="datepicker form-control" id="fdt" name="to_date" data-date-format="yyyy-mm-dd" value="<?php echo date("Y-m-d"); ?>">
                </div>
            </div>
        </div>
        <!--         <div class="form-group">
                    <label>TXT</label>
                    <input type="radio" name="filetype" value="txt">
                    <label>CSV</label>
                    <input type="radio" name="filetype" value="csv">
                    <label>PDF</label>
                    <input type="radio" name="filetype" value="pdf">
                </div>-->
        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Export_Data')); ?>" class="btn btn-primary" />

    </div>
	</div>
	</div>
	</div>
</form>  

<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/css/datepicker.css" />
<script type="text/javascript" src="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">

                $(document).ready(function ()
                {

                    $('.datepicker').datepicker({
                        startDate: '-3d'
                    });
                    $('#from_date').datepicker({format: 'yyyy-mm-dd'});
                    $('#to_date').datepicker({format: 'yyyy-mm-dd'});
                });







</script>

<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>
<script>
                setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
                function getServices()
                {
                    var stype = $('#stype option:selected').val();
                    //  alert(stype);exit;
                    // var userid = '<?php echo $userid; ?>';
                    if (stype != "") {
                        $.ajax({
                            type: "POST",
                            url: config_path_site_member + "_ajax_get_services.do",
                            //data:,
                            data: "&type=" + stype,
                            success: function (msg) {
                                //     alert(msg);
                                //$('.conversation-list').append(msg);

                                $('#services').html(msg);

                                //$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
                                //$('.progress-bar-sync').css("width", '25%');
                                //apiSync(2, id)


                            }
                        });
                    }
                    else
                    {
                        $('#services').html("");
                    }
                }

</script>