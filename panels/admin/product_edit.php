<?php
defined("_VALID_ACCESS") or die("Restricted Access");
//$validator->formSetAdmin('suppliers_edit_54964566hh2');

$id = $request->GetInt('id');
$sql = 'select * from ' . Product . ' a where a.id=' . $mysql->getInt($id);
$query = $mysql->query($sql);
$rowCount = $mysql->rowCount($query);
if ($rowCount == 0) {
    header("location:" . CONFIG_PATH_SITE_ADMIN . "engineer.html?reply=" . urlencode('reply_invalid_login'));
    exit();
}
$rows = $mysql->fetchArray($query);
$row = $rows[0];
?>
<?php
$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_missing_data':
        $msg = 'Something  Is Missing';
        break;
}
include("_message.php");
?>
<div class="row">
    <div class="col-lg-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'ECOM'); ?></li>
            <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>product.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_manage_products')); ?></a></li>
            <li class="active"><?php echo $admin->wordTrans($admin->getUserLang(),'Product'); ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <section class="panel">
            <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Prodcut_details')); ?></div>
            <div class="panel-body">
                <div class="tab-content">	
                    <div id="tabs-1" class="tab-pane active">
                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>product_edit_process.do" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="panel-body">

                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Category')); ?></label>
                                            <select name="cat" class="form-control" id="cat">
                                                <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_category')); ?></option>
                                                <?php
                                                $sql_timezone = 'select * from ' . Category . ' order by name';
                                                $query_timezone = $mysql->query($sql_timezone);
                                                $rows_timezone = $mysql->fetchArray($query_timezone);
                                                foreach ($rows_timezone as $row_timezone) {
                                                    echo '<option ' . (($row_timezone['id'] == $row['category_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['name']) . '</option>';
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
                                                    echo '<option ' . (($row_timezone['id'] == $row['vendor_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['name']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_name')); ?></label>
                                            <input name="name" type="text"  class="form-control" id="name" value="<?php echo $mysql->prints($row['name']) ?>" />
                                            <input name="id" type="hidden" class="textbox_fix" id="id" value="<?php echo $row['id'] ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_description')); ?></label>
                                            <input name="desc" type="text" class="form-control" id="desc" value="<?php echo $mysql->prints($row['description']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_style_number')); ?></label>
                                            <input name="s_number" type="text" class="form-control" id="s_number" value="<?php echo $mysql->prints($row['style_number']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_part_number')); ?></label>
                                            <input name="p_number" type="text" class="form-control" id="p_number" value="<?php echo $mysql->prints($row['part_number']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_cost')); ?></label>
                                            <input name="cost" type="text" class="form-control" id="cost" value="<?php echo $mysql->prints($row['cost']) ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_default_price')); ?></label>
                                            <input name="def_price" type="text" class="form-control" id="def_price" value="<?php echo $mysql->prints($row['def_price']) ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_minimum_price')); ?></label>
                                            <input name="min_price" type="text" class="form-control" id="min_price" value="<?php echo $mysql->prints($row['min_price']) ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Picture')); ?></label>
                                            <input type="file" name="myimage" >

                                        </div>
                                          <div class="form-group">
                                                
                                            <img src='<?php 
                                            $pathto=CONFIG_PATH_PANEL_ADMIN.'img/product-list/'.$row['img'];
                                            echo $pathto; ?>' height="100" width="130">

                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_warrenty')); ?></label>
                                            <input name="warrenty" type="checkbox" class="" id="warrenty" value="1" <?php echo (($row['warrenty'] == '1') ? 'checked="checked"' : ''); ?>/>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_status')); ?></label>
                                            <label class="checkbox-inline"><input type="radio" name="status" value="1" <?php echo (($row['status'] == '1') ? 'checked="checked"' : ''); ?> > <i style="color:#006600"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_active')); ?></i></label>
                                            <label class="checkbox-inline"><input type="radio" name="status" value="0" <?php echo (($row['status'] == '0') ? 'checked="checked"' : ''); ?> > <i><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_inactive')); ?></i></label>
                                        </div>


                                    </div> <!-- / panel-body -->
                                    <div class="panel-footer">
                                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update')); ?>" class="btn btn-success"/>
                                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>product.html" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(),'Cancel'); ?></a>

                                    </div> <!-- / panel-footer -->

                                </div> <!-- / col-lg-6 -->
                            </div> <!-- / row -->


                        </form>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>
