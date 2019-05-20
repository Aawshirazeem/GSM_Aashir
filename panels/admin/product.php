<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$mysql = new mysql();
//print_r($_POST);
//var_dump($_POST);
//$temp='';
if (($_POST['ven'] != '') && ($_POST['cat'] != '')) {
    $temp = '  where v.id=' . $_POST['ven'] . ' and c.id=' . $_POST['cat'];
} else if ($_POST['ven'] != '') {

    $temp = $temp . ' where v.id=' . $_POST['ven'];


    //$temp=$temp.'  and c.id='.$_POST['cat'];
} else if ($_POST['cat'] != '')
    $temp = $temp . ' where c.id=' . $_POST['cat'];
else
{
    
}    
 
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
        $msg = 'Product Table Updated';
        break;
    case 'reply_edit_success':
        $msg = 'Product Edit Successfully';
        break;
        break;
    case 'reply_name_duplicate':
        $msg = 'Name Already Exist';
        break;
    case 'reply_missing_data':
        $msg = 'Cant Edit Product Data missing';
        break;
}
include("_message.php");
?>
<ul class="breadcrumb">
    <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
    <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'ECOM'); ?></li>
    <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Manage Products'); ?></li>
</ul>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    <fieldset>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Category')); ?></label>
                            <select name="cat" class="form-control" id="cat">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_category')); ?></option>
<?php
$sql_timezone = 'select * from ' . Category . ' order by name';
$query_timezone = $mysql->query($sql_timezone);
$rows_timezone = $mysql->fetchArray($query_timezone);
foreach ($rows_timezone as $row_timezone) {
    echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['name']) . '</option>';
}
?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_vendor')); ?></label>
                            <select name="ven" class="form-control" id="ven">
                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_vendor')); ?></option>
<?php
$sql_timezone = 'select * from ' . Vendor . ' order by name';
$query_timezone = $mysql->query($sql_timezone);
$rows_timezone = $mysql->fetchArray($query_timezone);
foreach ($rows_timezone as $row_timezone) {
    echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['name']) . '</option>';
}
?>
                            </select>
                        </div>
                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?>" class="btn btn-success" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            List of Products
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>product_add.html" class="btn btn-xs btn-danger pull-right"><i class="fa fa-plus"></i><?php echo $admin->wordTrans($admin->getUserLang(),'Add Product'); ?></a>
            <a href="#searchPanel" data-toggle="modal" class="btn btn-xs btn-danger pull-right"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> </a>
        </div>
        <table class=" table table-striped table-hover">
            <tr>
                <th>Status</th>
                <th>Category</th>
                <th>Vendor</th>
                <th>Product</th>
                <th>Description</th>
                <th>Cost</th>
                <th>IMG</th>
                <th>Edit</th>

            </tr>
<?php
$sql = 'select a.*,v.name vendor,c.name category 
                        from ' . Product . ' a
                        inner join ' . Vendor . ' v
                        on v.id=a.vendor_id
                        inner join ' . Category . ' c
                            on c.id=a.category_id';

if ($temp != '') {
    $sql = $sql . $temp;
}
//echo $sql;

$result = $mysql->getResult($sql);
if ($result['COUNT']) {
    foreach ($result['RESULT'] as $row) {
        $pathto = CONFIG_PATH_PANEL_ADMIN . 'img/product-list/' . $row['img'];
        echo '<tr>';
        echo '<td width="16">' . $graphics->status($row['status']) . '</td>';
        echo '<td>' . $mysql->prints($row['category']) . '</td>';
        echo '<td>' . $mysql->prints($row['vendor']) . '</td>';
        echo '<td>' . $mysql->prints($row['name']) . '</td>';
        echo '<td>' . $mysql->prints($row['description']) . '</td>';
        echo '<td>' . $mysql->prints($row['cost']) . '</td>';
        echo '<td> <img src=' . $pathto . ' height="100" width="130"></td>';



        echo '<td class="" width="">
								<a href="' . CONFIG_PATH_SITE_ADMIN . 'product_edit.html?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Edit</a>
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
