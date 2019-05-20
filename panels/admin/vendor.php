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

<?php
$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_success':
        $msg = 'New Vendor Added';
        break;
    case 'reply_edit_success':
        $msg = 'Vendor Edit Successfully';
        break;
        break;
    case 'reply_name_duplicate':
        $msg = 'Name Already Exist';
        break;
   
}
include("_message.php");
?>
<ul class="breadcrumb">
    <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
    <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'ECOM'); ?></li>
    <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Manage Vendors'); ?></li>
</ul>

<div class="col-sm-10">
    <div class="panel">
        <div class="panel-heading">
            <?php echo $admin->wordTrans($admin->getUserLang(),'List of Vendors'); ?>
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>vendor_add.html" class="btn btn-xs btn-danger pull-right"><?php echo $admin->wordTrans($admin->getUserLang(),'Add Vendor'); ?></a>
        </div>
        <table class=" table table-striped table-hover">
            <tr>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Status'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Name'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Email'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Contact Person'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Phone'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Address'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Notes'); ?></th>
                <th><?php echo $admin->wordTrans($admin->getUserLang(),'Edit'); ?></th>

            </tr>
            <?php
            $sql = 'select * from ' . Vendor . ' order by id';
            $result = $mysql->getResult($sql);
            if ($result['COUNT']) {
                foreach ($result['RESULT'] as $row) {
                    echo '<tr>';
                    echo '<td width="16">' . $graphics->status($row['status']) . '</td>';
                    echo '<td>' . $mysql->prints($row['name']) . '</td>';
                    echo '<td>' . $mysql->prints($row['email']) . '</td>';
                    echo '<td>' . $mysql->prints($row['c_person']) . '</td>';
                    echo '<td>' . $mysql->prints($row['c_number']) . '</td>';
                    echo '<td>' . $mysql->prints($row['address']) . '</td>';
                    echo '<td>' . $mysql->prints($row['notes']) . '</td>';
                    echo '<td class="" width="">
								<a href="' . CONFIG_PATH_SITE_ADMIN . 'vendor_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Edit</a>
						  </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
            }
            ?>
        </table>
    </div>

    <script>
        $(document).ready(function ()
        {
            $(".various").fancybox({
                maxWidth: 900,
                maxHeight: 400,
                fitToView: false,
                width: '90%',
                height: '90%',
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                'afterClose': function () {
                    window.location.reload();
                }
            });
        });
    </script>
