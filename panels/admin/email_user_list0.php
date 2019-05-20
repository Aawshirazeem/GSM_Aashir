<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	$validator->formSetAdmin('email_user_list_78454349971255d2');
	
	$limit = $request->getInt('limit');		
	$offset = $request->getInt('offset');
	if($limit==0)
	{
		$limit = 20;
	}
?>

		<div id="startSendEmailsWait" class="TA_C hidden">
			<?php echo $graphics->messageBox("Sending emails...");?>
		</div>
		
		
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel">
					<div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_email_to_users')); ?></div>
					<div class="panel-body">

						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),'Mail subject'); ?></label>
							<input type="text" name="subject" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label><?php echo $admin->wordTrans($admin->getUserLang(),'Mail Body'); ?></label>
							<div class="clearfix"></div>
							<textarea id="editor1" name="editor1" class="ckeditor"></textarea>
							<div class="clearfix"></div>
						</div>
					</div> <!-- / panel-body -->
					<div id="startSendEmails" class="panel-footer TA_C">
						<a href="javascript:startEmails();" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_send')); ?></a>
					</div> <!-- / panel-footer -->
				</div> <!-- / panel -->
			</div> <!-- / col-lg-6 -->
		</div> <!-- / row -->
		
		
		
		<table class="MT5 table table-striped table-hover panel">
			<tr>
			  <th width="16"></th>
			  <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
			  <th></th>
			  <th width="100"></th>
			</tr>
			<?php
				$sql = 'select * from ' . USER_MASTER . ' order by username';
				$query = $mysql->query($sql);
				if($mysql->rowCount($query) > 0)
				{
						$rows=$mysql->fetchArray($query);
						foreach($rows as $row)
						{
							echo '<tr id="tr' . $row['id'] . '">';
								echo '<td id="td_check_' . $row['id'] . '">
											<input type="checkbox" class="subSelectEmailItems"  name="ids" value="' . $row['id'] . '" />
											<input type="hidden" name="email_' . $row['id'] . '" value="' . $row['email'] . '" />
										</td>';
								echo '<td>' . $row['username'] . '</td>';
								echo '<td>' . $row['email'] . '</td>';
								echo '<td id="td_email_' . $row['id'] . '">...</td>';
							echo '</tr>';
						}
				}
				else
				{
					echo '<tr><td colspan="6" class="no_record">'.$admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')).'</td></tr>';
				}
			?>
		</table>
		<div class="FL PT5 PB5 PL5 text_11 text_black">
			<i class="fa fa-level-up fa-flip-horizontal"></i>
			<a href="#" value="EmailItems" class="selectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),'Check All'); ?></a> / 
			<a href="#" value="EmailItems" class="unselectAllBoxesLink"><?php echo $admin->wordTrans($admin->getUserLang(),'Uncheck All'); ?></a>
		</div>
		
<script type="text/javascript" src="<?php echo CONFIG_PATH_ASSETS; ?>ckeditor/ckeditor.js"></script>
