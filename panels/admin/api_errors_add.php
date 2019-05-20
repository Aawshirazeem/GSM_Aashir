<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->GetInt('id');
?>

<div class="row m-b-20">
    <div class="col-xs-12">
        <ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_settings')); ?></li>
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html"><i class="fa fa-book"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_API_Custom')); ?></a></li>
            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_Customized_API_error')); ?></li>
        </ol>
    </div>
</div>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_errors_add_process.do" method="post">
    <div class="row">
        <div class="col-md-6">
            <h4 class="m-b-20">
                <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_New_API_error')); ?>
            </h4>
            <input type="hidden" name="api_id" value="<?php echo $id;?>"/>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_error')); ?></label>
                <input name="api_error" type="text" class="form-control required" id="api_error" value="" />

            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_reply')); ?> </label>
                <input name="reply" type="text" class="form-control required" id="reply" value="" />
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?> </label>
                <input name="is_action" type="checkbox" class="" id="is_action" value="" />
            </div>

            <div class="form-group">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_custom.html" class="btn btn-danger btn-sm"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cancel')); ?></a>
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12">
        <h4 class="m-b-20">
            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_API_Errors')); ?>
<!--            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>api_errors_add.html" class="btn btn-danger btn-sm pull-right"> <i class="fa fa-plus"></i> <?php echo  $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_customized_api_errors')) ?></a>-->

        </h4>
        <table class="table table-striped table-hover">
            <tr>
                <th width="16">#</th>
                <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_api_error')); ?> </th>
                <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_api_error_desc')); ?> </th>
                 <th class="text-center"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Action')); ?> </th>
                <th></th>
            </tr>
            <?php
            $sql = 'select * from nxt_api_errors where api_id='.$id;
            $query = $mysql->query($sql);
            if ($mysql->rowCount($query) > 0) {
                $rows = $mysql->fetchArray($query);
                $i = 0;
                foreach ($rows as $row) {
                     $tmpaction="NO";
                    if($row['action']==1)
                        $tmpaction="YES";
                    $i++;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td class="text-center">' . $row['reason'] . '</td>';
                    echo '<td class="text-center">' . $row['reply'] . '</td>';
                    echo '<td class="text-center">' . $tmpaction. '</td>';
                    //echo '<td></td>';
                    echo '<td>';


                    echo '	<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_errors_edit.html?id=' . $row['id'] . '&api_id='.$id.'" class="btn btn-primary btn-sm">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_edit')) . '</a>
									<a href="' . CONFIG_PATH_SITE_ADMIN . 'api_errors_delete.html?id=' . $row['id'] . '&api_id='.$id.'" class="btn btn-danger btn-sm">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_delete')) . '</a>';

                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="no_record">' .  $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
            }
            ?>
        </table>
    </div>
</div>