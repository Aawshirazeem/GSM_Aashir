<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetUser('user_credit_64569j766428');
       
        $user_id=$member->getUserId();
	$id = $request->GetInt('id');
	$reply=$request->GetStr('reply');
	$sql ='select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($id).' and reseller_id='.$user_id;
	$query = $mysql->query($sql);
	$rowCount = $mysql->rowCount($query);
	
	if($rowCount == 0)
	{
		header("location:" . CONFIG_PATH_SITE_USER . "users.html?reply=" . urlencode('reply_invalid_login'));
		exit();
	}
	$rows = $mysql->fetchArray($query);
	$row = $rows[0];
	
?>
<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Dashboard')); ?></a></li>
                                <li><a href="<?php echo CONFIG_PATH_SITE_USER; ?>users.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Users')); ?></a></li>
				<li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Transfer_Credits')); ?></li>
				
			</ul>
		</div>
	</div>

  <form action="<?php echo CONFIG_PATH_SITE_USER; ?>user_creidts_process.do" method="post" name="frm_customers_edit" id="frm_customers_edit" class="formSkin">
	
		<div class="col-lg-6 col-lg-offset-3">
                    <div class="row">
            <div>
    <p style="color: red;">  <b><?php echo $reply; ?></b></p>
</div>
			<div class="panel">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Transfer_Credits')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?></label>
						<input type="text" readonly class="form-control" value="<?php echo $row['username']?>" />
						<input name="username" type="hidden" id="name" value="<?php echo $row['username']?>" />
						<input name="id" type="hidden" id="id" value="<?php echo $row['id']?>" />
						<input name="email" type="hidden" id="id" value="<?php echo $row['email']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_credits')); ?></label>
						<input type="text" readonly class="form-control" value="<?php echo $row['credits']?>" />
					</div>
					<div class="form-group has-success">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_add')); ?>
						<input type="radio" name="creditType" class="creditsTransferType" value="1" checked="checked" /></label>
						<input name="crAdd" type="text" class="form-control text-success" id="crAdd" value="0" />
						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_you_want_to_allot_to_above_account')); ?></p>
					</div>
					<div class="form-group has-error">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_revoke')); ?>
						<input type="radio" name="creditType" class="creditsTransferType" value="0" /></label>
						<input name="crRevoke" type="text" class="form-control" id="crRevoke" value="0"  />
						<p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits_you_want_to_withdraw_from_above_account')); ?></p>
					</div>
					<div class="form-group">
						
                                            <input type="submit" class="btn btn-success" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_account')); ?>" />
					</div>
				</div>
			</div>
		</div>
	</div>
  </form>
