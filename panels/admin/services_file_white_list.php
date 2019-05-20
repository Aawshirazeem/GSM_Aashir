<?php
defined("_VALID_ACCESS") or die("Restricted Access");

$validator->formSetAdmin('user_edit_789971255d2');


$s_id = $request->getInt('s_id');
$sql = 'select * from ' . FILE_EXTENSIONS . ' where s_id=' . $s_id;

$query = $mysql->query($sql);

$i = 1;
?>

<div class="">



    <h4 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_white_list_file_manager')); ?></h4>



    <div class="btn-group btn-group-sm m-b-20" style="">

		
        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_white_list_add.html?s_id=<?php echo $s_id; ?>" class="btn btn-success"> <i class="fa fa-plus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_extension')); ?></a>

        


    </div>

</div>

<div class="card-box">

	<div class="table-responsive">

    <table class="MT5 table table-striped table-hover panel">

        <tr>

            <th width="16"></th>

            <th width="16"></th>

            <th><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_file_extensions')); ?></th>

            <th width=""></th>

        </tr>

<?php
if ($mysql->rowCount($query) > 0) {

    $rows = $mysql->fetchArray($query);

    foreach ($rows as $row) {

        echo '<tr>';

        echo '<td>' . $i++ . '</td>';

        echo '<td>' . $graphics->status($row['status']) . '</td>';

        echo '<td>' . $row['file_ext'] . '</td>';

        echo '<td class="text-right">
<div class="btn-group">
<a href="' . CONFIG_PATH_SITE_ADMIN . 'ext_delete.html?id=' . $row['id'] . '&s_id=' . $s_id . '" class="btn btn-danger btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_delete')) . '</a>
						<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_file_white_list_edit.html?id=' . $row['id'] . '&s_id=' . $s_id . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_setting')) . '</a>';

        echo '</div></td>';
        echo '</tr>';
    }
} else {

    echo '<tr><td colspan="6" class="no_record">' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_no_record_found')) . '</td></tr>';
}
?>

    </table>
	
	</div>
	<a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"> << <i class="icon-minus"></i> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_back')); ?></a>

	
</div>