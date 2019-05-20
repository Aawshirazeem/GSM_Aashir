<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$mysql = new mysql();



$username = $request->GetStr("username");

$email = $request->GetStr("email");



$displaySearch = false;

if ($username != '' or $email != '') {

    $displaySearch = true;

}

?>

<div class="row">

	<div class="col-xs-10">
    	<h4 class="m-b-20">

        	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Site_Admins')); ?>

            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>admin_add.html" class="btn btn-sm btn-danger pull-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Add_Admin')); ?></a>

        </h4>
<div class="table-responsive">
        <table class="table table-striped table-hover panel">   

            <tr>

                <td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_status')); ?></td>

                <td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></td>

                <td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_first_name')); ?></td>

                <td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Last_Name')); ?></td>

                <td><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Nick')); ?></td>

                 <td class="text-right"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></td>

            </tr>

            <?php

            $sql = 'select * from ' . ADMIN_MASTER . ' order by username';

            $result = $mysql->getResult($sql);

            if ($result['COUNT']) {

                foreach ($result['RESULT'] as $row) {

                    echo '<tr>';

                    echo '<td width="16">' . $graphics->status($row['status']) . '</td>';

                     echo '<td>' . $mysql->prints($row['username']) . '</td>';

                    echo '<td>' . $mysql->prints($row['fname']) . '</td>';

                    echo '<td>' . $mysql->prints($row['lname']) . '</td>';

                     echo '<td>' . $mysql->prints($row['nname']) . '</td>';

                    echo '<td class="text-right" width="150px">

                        <a href="' . CONFIG_PATH_SITE_ADMIN . 'admin_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">'. $admin->wordTrans($admin->getUserLang(),$lang->get('com_edit')).'</a>

                  </td>';

                    echo '</tr>';

                }

            } else {

                echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

            }

            ?>

        </table>

    </div>

    </div>	

</div>