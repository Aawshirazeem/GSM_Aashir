<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('user_credits_52134757d2');
	
    
	$id = $request->GetInt('id');
	
	
	
	$sql ='select id, username, email from ' . USER_MASTER . ' where id=' . $mysql->getInt($id);				
	$userResult = $mysql->getResult($sql);
	$user = $userResult['RESULT'][0];
	

	$DisclaimerText = 'Yes I want to delete ' . $user['username'];
	
?>

	<div class="row">
		<div class="col-lg-12">
			<ul class="breadcrumb">
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'Dashboard'); ?></a></li>
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html"> <?php echo $admin->wordTrans($admin->getUserLang(),'Users'); ?></a></li>
				<li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit.html?id=<?php echo $id;?>"><?php echo $user['username']?></a></li>
				<li class="active"> <?php echo $admin->wordTrans($admin->getUserLang(),'Delete User'); ?></li>
			</ul>
		</div>
	</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_delete_process.do" method="post">


  
  <div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-danger">
				<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_transfer_credits')); ?></div>
				<div class="panel-body">
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
						<input type="text" readonly class="form-control" value="<?php echo $user['username']?>" />
						<input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $user['id']?>" />
					</div>
					<div class="form-group">
						<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email')); ?></label>
						<input type="text" readonly class="form-control" value="<?php echo $user['email']; ?>" />
					</div>
					<div class="alert alert-danger">
						<h3><?php echo $DisclaimerText; ?></h3>
						<input type="hidden" name="text_compate" value="<?php echo $DisclaimerText; ?>" />
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_type_above_message_here')); ?> <i class="fa fa-level-up"></i></label>
							<div class="input-group m-bot15">
								<input type="text" name="disclaimer" class="form-control checkMyValue" data-value="<?php echo $DisclaimerText; ?>" value="" required />
								<span class="input-group-addon"><i class="fa fa-times"></i></span>
							</div>
							
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-3"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_edit.html?id=<?php echo $id;?>" class="btn btn-default btn-block"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a></div>
							<div class="col-sm-9"><button type="submit" class="btn btn-danger btn-block"><i class="icon-ok-sign"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_delete_user')); ?></button></div>
						</div>
					</div>
					<span class="text-warning"><i class="fa fa-warning"></i> <?php echo $admin->wordTrans($admin->getUserLang(),'This process will not delete orders related to above user.'); ?></i>
				</div> <!-- / panel-body -->
			</div> <!-- / panel -->
		</div> <!-- / col-lg-6 -->
	</div> <!-- / row -->
	
   
  </form>


	<script>
		$(document).ready(function () 
		{
			$('.checkMyValue').keyup(function(){
				var myRealValue = $(this).val();
				var myValue = $(this).data('value');
				console.log(myRealValue);
				if(myRealValue == myValue){
					$(this).next('.input-group-addon').html('<i class="fa fa-check"></i>')
				} else {
					$(this).next('.input-group-addon').html('<i class="fa fa-times"></i>')
				}
			});
		});
	</script>
