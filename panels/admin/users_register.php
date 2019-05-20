<?php defined("_VALID_ACCESS") or die("Restricted Access"); ?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<h4>
			<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('New Activation Request')); ?> 
        	
        </h4>
        <div class="table-responsive">
        	<table class="table table-hover table-striped">
            	<thead>
                	<tr>
                    	<th>#</th>
                        <th width="15%"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_name')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></th>
                        <th width="200"></th>
                    </tr>
                </thead>
                <tbody>
                	<?php
						$sql = 'select * from ' . USER_REGISTER_MASTER . ' order by id desc';
						$query = $mysql->query($sql);
						$strReturn = "";
						$i = 1;
						if($mysql->rowCount($query) > 0){
							$rows = $mysql->fetchArray($query);
							foreach($rows as $row){
					?>
                    <tr>
                    	<th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $mysql->prints($row['username']); ?></td>
                        <td><?php echo $mysql->prints($mysql->prints($row['first_name'])) . ' ' . $mysql->prints($mysql->prints($row['last_name'])); ?></td>
                        <td><?php echo $mysql->prints($mysql->prints($row['email'])); ?></td>
                        <td class="text-right">
                        	<form action="<?php echo CONFIG_PATH_SITE_ADMIN?>users_register_reject.do" method="post">
								<input type="hidden" name="id" value="<?php echo $row['id'];?>" />
								<input type="hidden" name="email" value="<?php echo $row['email'];?>" />
								<input type="hidden" name="username" value="<?php echo $row['username'];?>" />
								<div class="btn-group">
									<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_add.html?id=<?php echo $row['id']; ?>" class="btn btn-default btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_accept')); ?></a>
									<button type="submit" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_Reject')); ?></button>
								</div>
							</form>
                        </td>
                   	</tr>
                    <?php
							}
						}else{
					?>
                    	<tr>
                        	<td colspan="7" class="no_record">
								<?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')); ?>
                            </td>
                        </tr>
                    <?php
						}
					?>
                </tbody>
            </table>
        </div>
    </div>
</div>