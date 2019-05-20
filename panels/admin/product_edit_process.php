<?php

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$admin->checkLogin();
$admin->reject();
$validator->formValidateAdmin('suppliers_add_549883whh2');
//echo '<pre>';
//var_dump($_POST);
////$warrenty=$request->PostInt('warrenty');
////echo $warrenty;
//exit;
$id = $request->PostInt('id');
$cat = $request->PostStr('cat');
$ven = $request->PostStr('ven');
$name = $request->PostStr('name');
$desc = $request->PostStr('desc');
$s_number = $request->PostStr('s_number');
$p_number = $request->PostStr('p_number');
$cost = $request->PostStr('cost');
$def_price = $request->PostStr('def_price');
$min_price = $request->PostInt('min_price');
$status = $request->PostInt('status');
$warrenty = $request->PostInt('warrenty');
$imagetmp=addslashes (file_get_contents($_FILES['myimage']['tmp_name']));
if (($name == "") || ($cat == "") || ($ven == "")) {
    //echo 'here';exit;
    header("location:" . CONFIG_PATH_SITE_ADMIN . "product.html?reply=" . urlencode('reply_missing_data'));
    exit();
}

//
//$sql = 'select name from ' . Category . ' where name=' . $mysql->quote($name);
//$query = $mysql->query($sql);
//if ($mysql->rowCount($query) > 0) {
//    header("location:" . CONFIG_PATH_SITE_ADMIN . "category_add.html?reply=" . urlencode('reply_name_duplicate'));
//    exit();
//}


$sql = 'insert into ' . Product . '
			(category_id,vendor_id,name,description,style_number,part_number,warrenty,cost,def_price,min_price,status)
			values(
			' . $mysql->quote($cat) . ',
                            	' . $mysql->quote($ven) . ',
                                    ' . $mysql->quote($name) . ',
                                        ' . $mysql->quote($desc) . ',
                                            ' . $mysql->quote($s_number) . ',
                                                ' . $mysql->quote($p_number) . ',
                                    	' . $mysql->quote($warrenty) . ',
                                            	' . $mysql->quote($cost) . ',
                                                    	' . $mysql->quote($def_price) . ',
                                                            	' . $mysql->quote($min_price) . ',
			' . $mysql->getInt($status) . ')';
//echo $sql;exit;
$sql = 'update ' . Product . '
			set 
			category_id = ' . $mysql->quote($cat) . ',
                        vendor_id = ' . $mysql->quote($ven) . ',
                         name = ' . $mysql->quote($name) . ',
                              description = ' . $mysql->quote($desc) . ',
                                   style_number = ' . $mysql->quote($s_number) . ',
                                        part_number = ' . $mysql->quote($p_number) . ',
                                               warrenty = ' . $mysql->quote($warrenty) . ',
                                                      cost = ' . $mysql->quote($cost) . ',
                                                             def_price = ' . $mysql->quote($def_price) . ',
                                                                 min_price = ' . $mysql->quote($min_price) . ',
                                                                 
			status = ' . $mysql->getInt($status) . '
			where id = ' . $mysql->getInt($id);
$mysql->query($sql);
//$id = $mysql->insert_id();
$pro_img_name=$id.'_'.$_FILES["myimage"]["name"];
$pathto='C:/xampp/htdocs'.CONFIG_PATH_PANEL_ADMIN.'img/product-list/'.$pro_img_name;
$a=move_uploaded_file($_FILES["myimage"]["tmp_name"],$pathto);
$sql='update ' . Product . ' set img= ' . $mysql->quote($pro_img_name) . '  where id = ' . $mysql->getInt($id);
$mysql->query($sql);
header("location:" . CONFIG_PATH_SITE_ADMIN . "product.html?reply=" . urlencode('reply_success'));
?>