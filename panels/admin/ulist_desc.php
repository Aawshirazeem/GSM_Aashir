<?php
defined("_VALID_ACCESS") or die("Restricted Access");
//$group_id = $request->getInt('group_id');
//$id = $request->GetInt('id');
?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>mass_email.html"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_mass_email')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_users_email_list')); ?></li>


        </ol>
    </div>
</div>

<div class="row m-b-20">
    <div class="col-md-12">
        <h4 class="m-b-20">
            <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_users_email_list')); ?>
                                                                   <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'mass_email.html'; ?> " class="btn btn-danger btn-sm pull-right"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_Back_Mass_mail')) ?></a>

            <a href="<?php echo CONFIG_PATH_SITE_ADMIN . 'ulist_desc_add.html';?> " class="btn btn-success btn-sm pull-right"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_user_email')) ?></a>

        </h4>
        <hr>
        <table class="table table-striped table-hover">
            <tr>
                <th width="16">#</th>
                <th width="16"></th>
<!--                <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Name')); ?> </th>-->
                <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_Email')); ?> </th>

                <th width="250"></th>
            </tr>
            <?php
            $sql = 'select * from nxt_ulistdetail2';
            $query = $mysql->query($sql);
            $i = 0;
            if ($mysql->rowCount($query) > 0) {
                $rows = $mysql->fetchArray($query);
                $i = 0;
                foreach ($rows as $row) {


                    $i++;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $graphics->status($row['status']) . '</td>';
                 //   echo '<td class="">' . $row['name'] . '</td>';
                    echo '<td class="text-center">' . $row['email'] . '</td>';
                    echo '<td class="text-right">';
                    echo '
								<div class="btn-group">
                                                                 
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'ulist_desc_edit.html?id=' . $row['id'] . '" class="btn btn-success btn-sm"> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_edit')) . '</a>
                                                                     
                                                                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'ulist_desc_del.html?id=' . $row['id'] . '" class="btn btn-danger btn-sm"> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_delete')) . '</a>

								</div>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
            }
            ?>
        </table>
    </div>
</div>



