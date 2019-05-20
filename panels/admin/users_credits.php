<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('user_credits_52134757d2');
	
    
	$id = $request->GetInt('id');
	$rid = $request->GetInt('rid');
	$irid = $request->GetInt('irid');
	$uid = $request->GetInt('uid');
	$amount = $request->GetStr('amount');
	$gateway_id = $request->GetStr('gateway_id');
	$currency_id = $request->GetStr('currency_id');
        
	if($request->GetStr('credits')>0)
	{
		$credits = number_format($request->GetStr('credits'),2);
                
	}
	else
	{
		$credits = 0;
	}
       // echo 'here';
       // var_dump($credits);
        //exit;
	$firstC = $request->GetStr('firstC');
    $limit = $request->GetInt('limit');
    $offset = $request->GetInt('offset');
    $username = $request->GetStr('username');
	
	
	if($rid>0)
	{
		$sql_in = 'update '.INVOICE_REQUEST	.' set status=1 where id='.$rid;
		//$mysql->query($sql_in);
		
	}
	
	$getString = "";
	if($firstC != '')
	{
		$getString .= '&firstC='. $firstC;
	}
	if($limit != 0)
	{
		$getString .= '&limit='. $limit;
	}
	if($offset != 0)
	{
		$getString .= '&offset='. $offset;
	}
	if($username != '')
	{
		$getString .= '&username='. $username;
	}
	
	$getString = trim($getString, '&');
	
	
	
/*	$sql ='select * from ' . USER_MASTER . ' 
					where id=' . $mysql->getInt($id); */
	$sql ='select
					um.*,im.amount,
					cm.prefix, cm.suffix, cm.rate
				from ' . USER_MASTER . ' um
				left join '.INVOICE_MASTER.' im on (im.user_id=um.id)
				left join ' . CURRENCY_MASTER . ' cm on (um.currency_id = cm.id)
				where um.id=' . $mysql->getInt($id);				
	$query = $mysql->query($sql);
       
	$rowCount = $mysql->rowCount($query);
	
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_ADMIN . "users.html?&reply=" . urlencode('reply_invalid_id'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	// echo '<pre>';
       // var_dump($row);exit;
	if($amount>0)
	{
		$amount1 = $amount;
	}
	else
	{
		$amount1 = $row['amount'];
		$currency_id = $row['currency_id'];
	}
	
	$prefix = $suffix = '';
	if($row['currency_id'] != 0)
	{
		$prefix = $row['prefix'];
		$suffix = $row['suffix'];
		$rate = $row['rate'];
	}
	else
	{
		$sql_curr ='select * from ' . CURRENCY_MASTER . ' where `default`=1';
		$query_curr = $mysql->query($sql_curr);
		$rows_curr = $mysql->fetchArray($query_curr);
		$prefix = $rows_curr[0]['prefix'];
		$suffix = $rows_curr[0]['suffix'];
		$rate = $rows_curr[0]['rate'];
	}
?>
<style>
	label {
  font-weight: 500;
}
</style>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),'Users'); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),'Add Credits'); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_creidts_process.do" method="post">
	<input  type="hidden" name="email" id="name" value="<?php echo $row['email']?>" />
    <input  type="hidden" name="firstC"  value="<?php echo $firstC?>" />
    <input  type="hidden" name="offset" value="<?php echo $offset?>" />
    <input  type="hidden" name="limit" value="<?php echo $limit?>" />
    <input  type="hidden" name="username" value="<?php echo $username?>" />
  	<input  type="hidden" name="user_id" value="<?php echo $id?>" />	
  	<input  type="hidden" name="credits" value="<?php echo $credits?>" />	
  	<input  type="hidden" name="invoice_request_id" value="<?php echo $rid?>" />	
  	<input  type="hidden" name="irid" value="<?php echo $irid?>" />	
  	<input  type="hidden" name="uid" value="<?php echo $uid?>" />	
  	<input  type="hidden" name="amount" value="<?php echo $amount1?>" />	
  	<input  type="hidden" name="gateway_id" value="<?php echo $gateway_id?>" />	
  	<input  type="hidden" name="currency_id" value="<?php echo $currency_id?>" />
    
    <div class="row m-b-20">
    	<div class="col-md-12">
        	<h4 class="m-b-20">
            	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_transfer_credits')); ?>
            </h4>
            <div class="form-group col-md-4">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
                <input  type="hidden" name="username" class="textbox_fix" id="name" value="<?php echo $row['username']?>" />
                <input type="text" readonly class="form-control" value="<?php echo $row['username']?>" />
                <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id']?>" />
            </div>
            <div class="form-group col-md-2">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_credits')); ?></label>
               	<input type="text" name="total_credits" readonly class="form-control" value="<?php echo number_format($row['credits'],2); ?>" />
            </div>
            <div class="col-md-6">
                <label class="c-input c-radio">
                	<input type="radio" name="creditType" value="1" checked="checked">
                    <span class="c-indicator c-indicator-success"></span>
                    <span class="c-input-text color-success"> <i class="fa fa-plus-circle"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_add')); ?> </span>
                </label>
                
                <label class="c-input c-radio">
                	<input type="radio" name="creditType" value="0">
                    <span class="c-indicator c-indicator-danger"></span>
                    <span class="c-input-text color-danger"> <i class="fa fa-minus-circle"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_revoke')); ?> </span>
                </label>
                <input type="hidden" name="rate" value="<?php echo $rate;?>" />
                
                <div class="form-group has-success" id="crAddDiv">
                    <div class="input-group">
                        <?php echo '<span class="input-group-addon">' . $prefix . '</span>'?>
                        <input type="text" name="crAdd" id="crAdd" class="form-control calAmount" placeholder="Credits" <?php echo (($credits!=0) ? ('value="' . $credits . '"') : '')?> />
                        <?php echo (($suffix != '') ? ('<span class="input-group-addon">' . $suffix . '</span>') : '')?>
                    </div>
                    <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_you_want_to_allot_or_revoke_from_the_above_account')); ?>.</p>
                </div>
<!--						<div class="form-group hiddefn" id="crRevokeDiv">
                    <div class="input-group">
                        <?php echo '<span class="input-group-addon">' . $prefix . '</span>'?>
                        <input type="text" name="crRevoke" id="crRevoke" class="form-control" placeholder="Credits" />
                        <?php echo (($suffix != '') ? ('<span class="input-group-addon">' . $suffix . '</span>') : '')?>
                    </div>
                    <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_you_want_to_withdraw_from_above_account')); ?></p>
                </div>-->
                
            </div>
			<br style="clear:both">
            <div class="form-group col-md-12">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_note')); ?></label>
                <input type="text" name="admin_note" class="form-control" value="" />
                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_note_for_further_refrence')); ?></p>
            </div>
            <div class="form-group col-md-12" id="pss">
            	<label class="c-input c-checkbox">
                	<input type="checkbox" name="paid_status" id="" value="" checked="checked" />
                    <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_paid')); ?> </span>
                    <span class="c-indicator c-indicator-success"></span>                    
                </label>
            </div>
            <div class="form-group col-md-12">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>
                <button type="submit" class="btn btn-success btn-sm"><i class="icon-ok-sign"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_account')); ?></button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
			$("input[name=creditType]:radio").change(function () {

				//alert('Changed');
                                 var myRadio = $('input[name=creditType]');
    var checkedValue = myRadio.filter(':checked').val();
    if(checkedValue==0){
        
                        $('#pss').hide();
                    }
                    else
                    {
                         $('#pss').show();
                    }
			})           
 });

    var myRadio = $('input[name=creditType]');
    var checkedValue = myRadio.filter(':checked').val();
    //alert(checkedValue);
    //$('#crRevokeDiv').hide();
    </script>