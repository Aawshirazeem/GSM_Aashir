<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('config_settting_15821611');



 $get_invoice_det='select * from '.INVOICE_EDIT;

                $inv_detail=$mysql->query($get_invoice_det);

                $inv_detail = $mysql->fetchArray($inv_detail);

                $logo=$inv_detail[0]["logo"];

                $textarea=$inv_detail[0]["detail"].$inv_detail[0]["detail2"].$inv_detail[0]["detail3"].$inv_detail[0]["detail4"];



$sql_2 = 'select a.value,a.field,a.value_int finfo from '.CONFIG_MASTER.' a

where a.field in ("USER_NOTES","ADMIN_NOTES","TER_CON")

order by a.id';

//echo $sql_2;

$query_2 = $mysql->query($sql_2);

$rows_2 = $mysql->fetchArray($query_2);



$a_notes=$rows_2[1]['value'];

$u_notes=$rows_2[0]['value'];

$ter_con_of=$rows_2[2]['finfo'];

$ter_con=$rows_2[2]['value'];

//echo $a_notes.$u_notes;











$sql_timezone2 = 'select a.is_tabbed,(select now()) as tz from nxt_admin_master a where a.id='.$admin->getUserId();

//echo $sql_timezone2;exit

$query_timezone2 = $mysql->query($sql_timezone2);

$rows_timezone2 = $mysql->fetchArray($query_timezone2);



$timst=$rows_timezone2[0]['tz'];

$multi_tab=$rows_timezone2[0]['is_tabbed'];





$sql_timezone1 = 'select * from ' . SMTP_CONFIG;

$query_timezone1 = $mysql->query($sql_timezone1);

$rows_timezone1 = $mysql->fetchArray($query_timezone1);

//echo '<pre>';

//var_dump($rows_timezone1);exit;

//echo $rows_timezone1[0]["show_price"];exit;

$old_url="admin";
if ($rows_timezone1[0]['old_url'] !="") {
    $old_url=  trim($rows_timezone1[0]['old_url'] );
}

$chat_code=$rows_timezone1[0]['chat_code'];
if ($rows_timezone1[0]['is_custom'] == 1) {

    // get all custome filds

    $sql2 = 'select * from nxt_custom_fields a where a.s_type=3 and a.s_id=3';

    $result = $mysql->getResult($sql2);

    $query = $mysql->query($sql2);

	$rowCount2 = $mysql->rowCount($query);

    //$query2 = $mysql->query($sql2);

    //$c_rows = $mysql->fetchArray($query2);

}

?>
<div class="row m-b-20">
  <div class="col-lg-12">
    <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
      <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
      <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
      <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_general_configuration')); ?></li>
    </ol>
  </div>
</div>
<?php

if (!is_writable(CONFIG_PATH_SITE_ABSOLUTE . "config/_settings.php")) {

    echo '<div class="alert alert-warning"><i class="fa fa-warning"></i> Configuration file is not writable</div>';

}

?>
<div class="row">
  <div class="col-xs-12 col-lg-12">
    <div class="bs-nav-tabs nav-tabs-warning">
      <ul class="nav nav-tabs nav-animated-border-from-left">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" data-target="#nav-tabs-0-1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_general')); ?></a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Sign_Up')); ?></a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-3"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Place_Order')); ?></a> </li>
              <li class="nav-item"> <a class="nav-link" data-toggle="tab" data-target="#nav-tabs-0-4"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Company_Details')); ?></a> </li>

      </ul>
      <br>
      <label><?php echo $admin->wordTrans($admin->getUserLang(),'<b>Note:</b> Your Database Server Time is'); ?> <b><?php echo $timst;?></b><?php echo $admin->wordTrans($admin->getUserLang(),'. Set the Hosting Time Zone according to that'); ?></label>
      <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>config_settings_2_process.do" method="post" enctype="multipart/form-data">
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-1">
            <div class="p-t-20">
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_domain_name')); ?> </label>
                    <input type="text" class="form-control" name="CONFIG_DOMAIN" id="CONFIG_DOMAIN" value="<?php echo CONFIG_DOMAIN ?>" />
                  </div>
                  <div class="col-sm-4">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_title')); ?> </label>
                    <input type="text" class="form-control" name="CONFIG_SITE_TITLE" id="CONFIG_SITE_TITLE" value="<?php echo CONFIG_SITE_TITLE ?>" />
                  </div>
                  <div class="col-sm-4">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_name')); ?> </label>
                    <input type="text" class="form-control" name="CONFIG_SITE_NAME" id="CONFIG_SITE_NAME" value="<?php echo CONFIG_SITE_NAME ?>" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-sm-4">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_hosting_time_zone')); ?></label>
                    <select name="timezone" class="form-control" id="timezone">
                      <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
                      <?php

                                            $sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';

                                            $query_timezone = $mysql->query($sql_timezone);

                                            $rows_timezone = $mysql->fetchArray($query_timezone);

                                            foreach ($rows_timezone as $row_timezone) {

                                                echo '<option ' . (($row_timezone['is_default']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';

                                            }

											//

                                            ?>
                    </select>
                  </div>
                  <div class="col-sm-4">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Order_History_Page_Size')); ?>
                      <?php //$lang->prints('lbl_currency'); ?>
                    </label>
                    <input name="CONFIG_ORDER_PAGE_SIZE" type="text" class="form-control checkUserName required" data-msg-required="Please enter order history page size" id="smtp_port" value="<?php echo (defined('CONFIG_ORDER_PAGE_SIZE') ? CONFIG_ORDER_PAGE_SIZE : ''); ?>" required />
                  </div>
				  <div class="col-sm-4">
					  <label><?php echo $admin->wordTrans($admin->getUserLang(),'Google Translation API Key'); ?></label>
					  <input type="text" name="CONFIG_TRANS_GOOGLE_KEY" class="form-control CONFIG_TRANS_GOOGLE_KEY" id="CONFIG_TRANS_GOOGLE_KEY" value="<?php echo CONFIG_TRANS_GOOGLE_KEY;?>"/>
                  </div>
<!--                  <div class="col-sm-4">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_administrator_email')); ?> </label>
                    <input type="text" class="form-control" name="CONFIG_EMAIL_ADMIN" id="CONFIG_EMAIL_ADMIN" value="<?php echo CONFIG_EMAIL_ADMIN ?>" />
                  </div>-->
                  
                  <!--<div class="col-sm-4">

                                            <label><?php //$lang->prints('lbl_notification_email'); ?> </label>

                                            <input type="text" class="form-control" name="CONFIG_EMAIL" id="CONFIG_EMAIL" value="<?php //echo CONFIG_EMAIL ?>" />

                                    </div>--> 
                  
                  <!--<div class="col-sm-4">

                                            <label><?php //$lang->prints('lbl_contact_email');?> </label>

                                            <input type="text" class="form-control" name="CONFIG_EMAIL_CONTACT" id="CONFIG_EMAIL_CONTACT" value="<?php echo CONFIG_EMAIL_CONTACT ?>" />

                                    </div>--> 
                  
                </div>
              </div>
         
              <div class="row">
                <div class="col-sm-4">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Wrong_Password_Count')); ?></label>
                  <input name="CONFIG_WRONG_PASSWORD_COUNTER" type="text" class="form-control" data-msg-required="Please enter wrong password counter" id="" value="<?php echo (defined('CONFIG_WRONG_PASSWORD_COUNTER') ? CONFIG_WRONG_PASSWORD_COUNTER : ''); ?>" required />
                </div>
                  <div class="col-sm-4">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Admin_URL')); ?></label>
                  <input name="a_url" type="text" class="form-control" data-msg-required="Please enter the admin URL" id="" value="<?php echo $old_url;?>" required />
                  <input name="old_a_url" type="hidden"  value="<?php echo $old_url;?>" />

                  </div>
				  <div class="col-sm-4">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),'Default Language'); ?></label>
                  <select name="CONFIG_DEFAULT_LANG" class="form-control CONFIG_DEFAULT_LANG" id="CONFIG_DEFAULT_LANG">
                  	<?php
					$sqlLangList = 'select * from ' . LANG_MASTER;
					$queryLangList = $mysql->query($sqlLangList);
					$rowsLangList = $mysql->fetchArray($queryLangList);
					foreach ($rowsLangList as $rowLangList) {
						$selected = '';
						if(CONFIG_DEFAULT_LANG != "" && CONFIG_DEFAULT_LANG == $rowLangList['language_code']){
							$selected = 'selected';
						}
					?>
                    	<option value="<?php echo $rowLangList['language_code']; ?>" <?php echo $selected; ?>><?php echo $mysql->prints($rowLangList['language']); ?></option>
                    <?php
					}
					?>
                  </select>
                </div>
               
              </div>
              <br/>
              <br/>
              <div class="row">                 
					<div class="col-sm-4">
					  <div class="animated-switch">
						<input id="switch-show_price" name="show_price" type="checkbox" <?php echo ($rows_timezone1[0]['show_price'] == '1') ? 'checked="checked"' : ''; ?>>
						<label for="switch-show_price" class="label-success"></label>
					  </div>
					  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Show_Prices')); ?></label>
					  <?php /*?><input type="checkbox" name="show_price" id="onlinestatus" <?php echo ($rows_timezone1[0]['show_price'] == '1') ? 'checked="checked"' : ''; ?> data-plugin="switchery" data-color="#A0D269" data-size="large"/><?php */?>
					 </div>
					 
					<div class="col-sm-4">
					 <div class="animated-switch">
						<input id="switch-tab" name="switch-tab" <?PHP if($multi_tab){ echo 'checked';} ?> type="checkbox" class="">
						<label for="switch-tab" class="label-success chkadj"></label>
					  </div>
					  <label><?php echo $admin->wordTrans($admin->getUserLang(),'Enable Multi tabs feature'); ?></label>
					</div>
					<input type="hidden" name="mtaboldval" value="<?php echo $multi_tab;?>"/>
              </div>
              
              <!--<div class="form-group">

                                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success" />   </form>

                                </div>--> 
              
            </div>
          </div>
                       
              <div role="tabpanel" class="tab-pane" id="nav-tabs-0-4">
			  
		  <div class="row">
                <div class="form-group">

                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enter_Company_Detail')); ?> </label><br>

                    <textarea rows="4" cols="70" name="detail"><?php echo $textarea; ?></textarea>

                </div>
		   </div>
			  <hr>	  
          <div class="row">
                <div class="col-md-3">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Facebook_Link')); ?> </label>
                  <input type="text" class="form-control" name="CONFIG_FB_LINK" id="CONFIG_FB_LINK" value="<?php echo (defined('CONFIG_FB_LINK') ? CONFIG_FB_LINK : ''); ?>" />
                </div>
                <div class="col-md-3">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Twitter_Link')); ?> </label>
                  <input type="text" class="form-control" name="CONFIG_TW_LINK" id="CONFIG_TW_LINK" value="<?php echo (defined('CONFIG_TW_LINK') ? CONFIG_TW_LINK : ''); ?>" />
                </div>
                <div class="col-md-3">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Google_+_Link')); ?></label>
                  <input type="text" class="form-control" name="CONFIG_GP_LINK" id="CONFIG_FB_LINK" value="<?php echo (defined('CONFIG_GP_LINK') ? CONFIG_GP_LINK : ''); ?>" />
                </div>
                <div class="col-md-3">
                  <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Phone_Number')); ?> </label>
                  <input type="text" class="form-control" name="CONFIG_PH_NO" id="CONFIG_PH_NO" value="<?php echo (defined('CONFIG_PH_NO') ? CONFIG_PH_NO : ''); ?>" />
                </div>
              </div>
			                
              </div>
          <div role="tabpanel" class="tab-pane" id="nav-tabs-0-3">		  
			  <div class="col-md-4">	
				<label><?php echo $admin->wordTrans($admin->getUserLang(),'Admin Notes On/Off'); ?></label>
				<div class="animated-switch">
				  <input id="switch-admin_notes" name="switch-admin_notes" <?PHP if($a_notes){ echo 'checked';} ?> type="checkbox" class="">
				  <label for="switch-admin_notes" class="label-success chkadj"></label>
				</div>
			  </div>	
			  <div class="col-md-4">	
				<label><?php echo $admin->wordTrans($admin->getUserLang(),'User Notes On/Off'); ?></label>
				<div class="animated-switch">
				  <input id="switch-user_notes" name="switch-user_notes" <?PHP if($u_notes){ echo 'checked';} ?> type="checkbox" class="">
				  <label for="switch-user_notes" class="label-success chkadj"></label>
				</div>
			  </div>			
          </div>
          <div role="tabpanel" class="tab-pane" id="nav-tabs-0-2">
            <div class="btn-group btn-group-sm pull-right">
              <?php

                $sql = 'select value_int as val from ' . CONFIG_MASTER . ' WHERE field=\'AUTO_REGISTRATION\'';

                $query = $mysql->query($sql);

                if($mysql->rowCount($query) > 0){

                    $rows = $mysql->fetchArray($query);

                    $enabled = $rows[0]['val'];

                }else{

                    $sql = 'insert into ' . CONFIG_MASTER . '(field, value_int) values("AUTO_REGISTRATION", 0)';

                    $mysql->query($sql);

                    $enabled = 0;

                }        



               

            ?>
            </div>
            <div class="p-t-20 table-responsive">
              <table class="table table-bordered" id="kaka">
                <tr>
                  <th>S.No</th>
                  <th>Field Type</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Field Option</th>
                  <th>Validation</th>
                  <th>Required</th>
                  <th>Action</th>
                </tr>
                <?php

                                if ($rowCount2 == 0) {

                                    ?>
                <tr>
                  <td style="width: 40px" id="tblr1c0">1</td>
                  <td id="tblr1c1"><select class="form-control"  name="f_type[]" style="width: 100px" onchange="setfop(this);">
                      <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),'Text'); ?></option>
                      <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(),'Drop Down'); ?></option>
                      <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(),'Checkbox'); ?></option>
                    </select></td>
                  <td id="tblr1c2"><input type="text" name="f_name[]"  class="form-control"/></td>
                  <td id="tblr1c3"><input type="text" name="f_desc[]"  class="form-control"/></td>
                  <td id="tblr1c4"><input id="tblr1c4fop" type="text" name="f_opt[]" title="Add Options Separated with commas"  class="form-control" readonly/></td>
                  <td id="tblr1c5"><input id="tblr1c5fvalid" type="text" name="f_valid[]"  class="form-control"/></td>
                  <td id="tblr1c6"><label class="c-input c-checkbox">
                      <input type="checkbox"   name="f_req1[]"  class="" onclick="updatecheck(this);"/>
                      <input id="chk_1" name="f_req2[]" type="hidden" value="0">
                      <span class="c-indicator c-indicator-success"></span></label></td>
                  <td id="tblr1c7" class="col-md-1"><input type="button" value="+" class="btn btn-success " onclick="addnewrow()"></td>
                </tr>
                <?php

                                } else {

                                    ?>
                
                <!--                                     <tr>

                                        <td style="width: 40px" id="tblr1c0">1</td>

                                        <td id="tblr1c1">

                                            <select class="form-control"  name="f_type[]" style="width: 100px" onchange="setfop(this);">

                                                <option value="1">Text</option>

                                                <option value="2">Drop Down</option>

                                                <option value="3">Checkbox</option>

                                            </select>

                                        </td>

                                        <td id="tblr1c2"><input type="text" name="f_name[]"  class="form-control"/></td>

                                        <td id="tblr1c3"><input type="text" name="f_desc[]"  class="form-control"/></td>

                                        <td id="tblr1c4"><input id="tblr1c4fop" type="text" name="f_opt[]"  class="form-control" disabled="disabled"/></td>

                                        <td id="tblr1c5"><input id="tblr1c5fvalid" type="text" name="f_valid[]"  class="form-control"/></td>

                                        <td id="tblr1c6"><input type="checkbox"   name="f_req1[]"  class="" onclick="updatecheck(this);"/><input id="chk_1" name="f_req2[]" type="hidden" value="0"></td>

                                        <td id="tblr1c7" class="col-md-1"><input type="button" value="+" class="btn btn-success " onclick="addnewrow()"></td>

                                    </tr>-->
                
                <?php

                                    if ($result['COUNT']) {

                                        $i = 1;

                                        foreach ($result['RESULT'] as $row2) {

                                            echo '<tr>';

                                            echo ' <td style="width: 40px" id="tblr' . $i . 'c0">' . $i . '</td>';

                                            // echo '<td>' . $mysql->prints($row2['name']) . '</td>';

                                            echo ' <td id="tblr' . $i . 'c1">

                                            <select class="form-control"  name="f_type[]" style="width: 100px" onchange="setfop(this);">

                                                <option ' . (($row2['f_type'] == 1) ? 'selected="selected"' : '') . ' value="1">Text</option>

                                                <option ' . (($row2['f_type'] == 2) ? 'selected="selected"' : '') . ' value="2">Drop Down</option>

                                                <option ' . (($row2['f_type'] == 3) ? 'selected="selected"' : '') . ' value="3">Checkbox</option>

                                            </select>

                                        </td>';

                                            echo ' <td id="tblr' . $i . 'c2"><input type="text" name="f_name[]" value="' . $row2['f_name'] . '"  class="form-control"/></td>';

                                            echo ' <td id="tblr' . $i . 'c3"><input type="text" name="f_desc[]" value="' . $row2['f_desc'] . '"  class="form-control"/></td>';

                                            echo ' <td id="tblr' . $i . 'c4"><input id="tblr' . $i . 'c4fop" type="text" name="f_opt[]" title="Add Options Separated with commas"  value="' . $row2['f_opt'] . '" class="form-control" ' . (($row2['f_type'] == 1 || $row2['f_type'] == 3 ) ? 'readonly=""' : '') . '/></td>';

                                            echo ' <td id="tblr' . $i . 'c5"><input id="tblr' . $i . 'c5fvalid" type="text" name="f_valid[]" value="' . $row2['f_valid'] . '" class="form-control" ' . (($row2['f_type'] == 3 || $row2['f_type'] == 2) ? 'readonly=""' : '') . '/></td>';

                                            echo ' <td id="tblr' . $i . 'c6"><label class="c-input c-checkbox"><input type="checkbox"  name="f_req1[]"  class="" ' . (($row2['f_req'] == 1) ? 'checked="checked"' : '') . ' onclick="updatecheck(this);"/><input id="chk_' . $i . '" name="f_req2[]" type="hidden" value="' . $row2['f_req'] . '"><span class="c-indicator c-indicator-success"></span></label></td>';

                                            if ($i == 1) {

                                                echo '<td id="tblr' . $i . 'c7" class="col-md-1"><input type="button" value="+" class="btn btn-success " onclick="addnewrow()"></td>';

                                            } else {

                                                echo '<td id=tblr' . $i . 'c8><input type="button" value="-" class="btn btn-danger" onclick="deleteRow(this)"></td>';

                                            }

                                            echo '</tr>';

                                            $i++;

                                        }

                                    }

                                }

                                ?>
              </table>
              <label><?php echo $admin->wordTrans($admin->getUserLang(),"Note: If you want to disable custom field option then delete all rows and remove the 'NAME' of first One"); ?></label>
            </div>
            <hr style="border: double">
               <div class="col-md-4">
              <label><?php echo $admin->wordTrans($admin->getUserLang(),'User Auto Activation'); ?></label>
              <div class="animated-switch">
                <input id="switch-auto_reg" name="switch-auto_reg" <?PHP if($enabled){ echo 'checked';} ?> type="checkbox" class="">
                <label for="switch-auto_reg" class="label-success"></label>
                <br>
              </div>
            </div>
            <div class="col-md-4">
              <label><?php echo $admin->wordTrans($admin->getUserLang(),'Terms & Conditions'); ?></label>
              <div class="animated-switch">
                <input id="switch-ter_con" name="switch-ter_con" <?PHP if($ter_con_of){ echo 'checked';} ?> type="checkbox" class="" onchange="change(this);">
                <label for="switch-ter_con" class="label-success"></label>
              </div>
            </div>
         
			
			<div class="col-md-4">
				<div id="termcon"> <?php echo $admin->wordTrans($admin->getUserLang(),'Enter URL (like www.google.com)'); ?>
                  <input type="text" name="termcond" class="form-control"  value="<?php echo $ter_con;?>">
                </div>
			</div>
	
          </div>
        </div>
        <div class="col-xs-12" style="margin-top: 20px">
          <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success" />
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.js"></script> 
<script type="text/javascript">var myJQ = $.noConflict(true);</script> 
<script type="text/javascript">





 var apiaccess =<?php echo $ter_con_of; ?>;

//alert(apiaccess);

    if (apiaccess == 0)

        $("#termcon").hide();



    function makeclone()



    {



        var clonex = document.getElementById("mainaccount").cloneNode(true);



        myJQ("#kaka tr:last td:nth-child(3)").append(clonex);







    }



    function addnewrow()



    {







        //var mainncounter = 1;



        var rowCount = $('#kaka tr').length;



        var count = myJQ("#kaka tr:last td:nth-child(1)").text();



        //count=count+1;



        var mainncounter = parseInt(count);



        mainncounter = mainncounter + 1;



        if (rowCount <= 5)



        {



            myJQ("#kaka").append(



                    "<tr>" +



                    "<td id=tblr" + mainncounter + "c0>" + rowCount + "</td>" +



                    " <td id=tblr" + mainncounter + "c1><select class='form-control'  name='f_type[]' style='width: 100px' onchange='setfop(this);'><option value='1'>Text</option> <option value='2'>Drop Down</option> <option value='3'>Checkbox</option></select></td>" +



                    "<td id=tblr" + mainncounter + "c2><input type='text' name='f_name[]'  class='form-control'/></td>" +



                    //  "  <td><select name='accountlist[]' class='form-control'><option value='Account One'>Account One</option><option value='Account Two'>Account Two</option></select></td> " +



                    //  "<td><select name='acc_id[]'><option value='1'>Excess UK</option><option value='2'>Excess EUR</option><option value='3'>test account new one</option></select></td>" +



                    " <td id=tblr" + mainncounter + "c3><input type='text' name='f_desc[]' class='form-control'/></td>" +



                    "  <td id=tblr" + mainncounter + "c4><input id='tblr" + mainncounter + "c4fop' type='text' name='f_opt[]' title='Add Options Separated with commas' readonly='readonly' class='form-control'/></td>" +



                    //  "<td>"+fname+"</td>" +



                    //   "<td> <input type='text' name='fc[]'class='form-control'/></td>" +



                    //    "<td> <input type='text' name='rate[]'class='form-control'/> </td>" +



                    "  <td id=tblr" + mainncounter + "c5><input type='text' id='tblr" + mainncounter + "c5fvalid' name='f_valid[]'  class='form-control'/></td>" +



                    //     "  <td id=tblr" + mainncounter + "c6><input type='text' name='rate[]' id=tblr" + mainncounter + "c6tb class='form-control' disabled='true' /><input type='hidden' name='min[]' id='tblr"+mainncounter+"c6tbhidmin'/><input type='hidden' name='max[]' id='tblr"+mainncounter+"c6tbhidmax'/></td>" +



                    // "  <td id=tblr" + mainncounter + "c6><input type='text' value='0' name='rate[]' onblur='sumuplc(this);' onkeypress='return isNumberKey(event)'  id=tblr" + mainncounter + "c6tb class='' readonly='true' /><span class='glyphicon glyphicon-arrow-up' style='color:green;font-size: 9px' id='tblr" + mainncounter + "c6tbhidmin'></span><span  id='tblr" + mainncounter + "c6tbhidmax' class='glyphicon glyphicon-arrow-down' style='color:red;font-size: 9px'></span> <input type='hidden' name='rule[]' id='tblr" + mainncounter + "c6tbhidrule'/></td>" +







                    "<td id=tblr" + mainncounter + "c7><label class='c-input c-checkbox'> <input type='checkbox' onclick='updatecheck(this);' name='f_req1[]'/><input id='chk_" + mainncounter + "' name='f_req2[]' type='hidden' value='0'><span class='c-indicator c-indicator-success'></span> </label></td>" +



                    "<td id=tblr" + mainncounter + "c8><input type='button' value='-' class='btn btn-danger' onclick='deleteRow(this)'></td>" +



                    "</tr>");



            //    oko();



            // alert(count);



            makeclone();



        }



        else



        {



            alert("Sorry you cant add more fields");



        }



    }



    function deleteRow(r) {



        //var i = r.parentNode.parentNode.rowIndex;



        //document.getElementById("kaka").deleteRow(i);



        myJQ("#kaka tr:last").remove();



    }



    function updatecheck(r)



    {



        //$(r).attr('checked');



        //alert(r.is(':checked'));



        //alert(r.val);



        var $row = myJQ(r).closest("tr");      // Finds the closest row <tr> 



        var $tds = $row.find("td:nth-child(1)"); // Finds the 2nd <td> element



        //alert($tds.text());



        var rownumber = parseInt($tds.text());



        if (myJQ(r).is(':checked'))



        {



            //  alert("checked");



            myJQ("#chk_" + rownumber).val(1);



        }



        else



        {



            // alert("un-checked");



            myJQ("#chk_" + rownumber).val(0);



        }



        // alert(myJQ(r).val());











    }



    function setfop(r)



    {



        var temp = myJQ(r).val();



        // alert(temp);



        var $row = myJQ(r).closest("tr");      // Finds the closest row <tr> 



        var $tds = $row.find("td:nth-child(1)"); // Finds the 2nd <td> element



        //alert($tds.text());



        var rownumber = parseInt($tds.text());



        //alert(rownumber);



        if (temp == "2")



        {







            if (myJQ("#tblr" + rownumber + "c4fop").prop("readonly"))



            {



                //alert("disable");



                myJQ("#tblr" + rownumber + "c4fop").removeAttr('readonly');



                myJQ("#tblr" + rownumber + "c5fvalid").attr('readonly', 'readonly');



                //$("input").removeAttr('disabled');



            }







        }







        else if (temp == "3")



        {



            myJQ("#tblr" + rownumber + "c5fvalid").attr('readonly', 'readonly');



            myJQ("#tblr" + rownumber + "c4fop").attr('readonly', 'readonly');



        }



        else



        {



            //alert("enble");



            // $("input").attr('disabled','disabled');



            if (myJQ("#tblr" + rownumber + "c5fvalid").prop("readonly"))



            {



                myJQ("#tblr" + rownumber + "c4fop").attr('readonly', 'readonly');



                myJQ("#tblr" + rownumber + "c5fvalid").removeAttr('readonly');



            }



        }



        //alert('set op');



    }

    

    

    

    function change(p) {



        if ($(p).prop("checked") == true) {



            $("#termcon").show();

        } else {



            $("#termcon").hide();

        }

    }



</script>