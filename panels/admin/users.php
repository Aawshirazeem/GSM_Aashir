<?php
defined("_VALID_ACCESS") or die("Restricted Access");
?>
<!--<ul class="breadcrumb">
    <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> Dashboard</a></li>
    <li class="active">Users</li>
</ul>-->


<!--<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>
                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html" method="get" name="frm_customer_search" id="frm_customer_search" role="form">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?></label>
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $username; ?>" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_username')); ?>" />
                    </div>
                    <button type="submit" value="" name="search" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>-->


<!--
<div class="mail-option">

    <div class="btn-group">
        <div class="btn-group">
            <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html?<?php echo $getString; ?>" class="btn mini"><?php $lang->prints('lbl_all_users'); ?></a>
        </div>
         USER GROUP 
        <div class="btn-group hidden-phone">
            <a class="btn mini blue" href="#" data-toggle="dropdown"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_user_groups')); ?><i class="fa fa-angle-down "></i></a>
            <ul class="dropdown-menu">
<?php
$sqlGroup = 'select * from ' . USER_GROUP_MASTER . ' where status=1';
$groups = $mysql->getResult($sqlGroup);
foreach ($groups['RESULT'] as $group) {
    ?>
                                                                                                        <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users.html?group_id=<?php echo $group['id']; ?>"><?php echo $group['group_name']; ?></a></li>
    <?php
}
?>
                                                        <li role="presentation" class="divider"></li>
                                                        <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_group.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->get('com_setting')); ?></a></li>
                                                    </ul>
                                                </div>
                                                 USER GROUP END 
                                                <a href="#searchPanel" data-toggle="modal" class="btn btn-warning">	<i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_search')); ?> </a>
<?php
if (trim($username) != '') {
    echo '<a href="' . CONFIG_PATH_SITE_ADMIN . 'users.html" class="btn btn-info"><i class="fa fa-undo"></i></a>';
}
?>
                                            </div>
                                        </div>-->
<input  type="hidden" name="firstC" value="<?php echo $firstC ?>" />
<input  type="hidden" name="offset" value="<?php echo $offset ?>" />
<input  type="hidden" name="limit" value="<?php echo $limit ?>" />
<?php

$newsql = 'select um.id , um.img,um.`status`, um.username,um.email,um.credits, crm.prefix, crm.suffix
from ' . USER_MASTER . '  um 
 
inner join ' . CURRENCY_MASTER . '  crm on (um.currency_id = crm.id) 
order by um.id


';
$new_query_users = $mysql->query($newsql);


$i = $offset + 1;
if ($mysql->rowCount($new_query_users) > 0) {
    $rows = $mysql->fetchArray($new_query_users);

?>
<div class="row m-b-20">
    <div class="col-xs-12">
        <h5><?php echo $admin->wordTrans($admin->getUserLang(),'Manage Users'); ?></h5>
    </div>
</div>
<div class="row m-b-20">
    <div class="col-xs-12">
        <div class="row">
            <!--                    <div class="col-sm-8">
                            <form role="form">
                                <div class="form-group contact-search m-b-30">
                                    <input type="text" id="search2" class="form-control" placeholder="Search...">
                                </div>  form-group 
                            </form>
                        </div>-->
            <div class="col-sm-4">
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>users_add.html" class="btn btn-default btn-sm waves-effect waves-light m-b-30"><i class="fa fa-plus"></i>     <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_add_new_user')); ?></a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mails m-0 table table-actions-bar" id="ok">
                <thead>
                    <tr>
                        <th style="min-width: 5px;"></th>
                        <th style="min-width: 5px;"></th>
                        <th style="min-width: 10px;"></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_email')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?></th>
                        <th style="min-width: 90px;"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rows as $row) {
                        if ($row['currency_id'] != 0) {
                            $prefix = $row['prefix'];
                            $suffix = $row['suffix'];
                        } else {
                            $prefix = $prefixD;
                            $suffix = $suffixD;
                        }

                        // echo '<tr class="' . (($row['status'] != 1) ? 'danger' : '') . '">';
                        echo '<td width="5">' . $row['id'] . '</td>';
                        echo '<td>' . $graphics->status($row['status']) . '</td>';
                        if ($row['img'] != '') {


                            echo '<td width="16"><img src="' . CONFIG_PATH_SITE . 'images/' . $row['img'] . '" alt="contact-img" title="contact-img" class="img-circle thumb-sm" /></td>';
                        } else {
                            echo '<td width="16"><img src="' . CONFIG_PATH_PANEL . 'assets/images/users/avatar-2.jpg" class="img-circle thumb-sm" alt="user"></td>';
                        }

                        echo '<td>' . $mysql->prints($row['username']) . '<br /></td>';
                        echo '<td>' . $mysql->prints($row['email']) . '<br /></td>';
                        //    echo '<td class="text-center">' . (($row['api_access'] == '1') ? $row['api_key'] : '') . '</td>';
//                                echo '<td class="text-center">';
//                                if ($row['countries_iso_code_2'] != '') {
//                                    echo '<img src="' . CONFIG_PATH_SITE . 'images/flags/' . strtolower($row['countries_iso_code_2']) . '.png" class="tooltips" data-placement="top" data-toggle="tooltip" data-original-title="' . $row['countries_name'] . '" />';
//                                }
//                                echo '</td>';
                        
                        $usercreditss=$row['credits'];
                        if($usercreditss >= 0)
                        
                        
                        echo '<td width="100"><span class="m-r-10 label label-pill label-primary label-lg">' . $objCredits->printCredits($usercreditss, $row['prefix'], $row['suffix']) . '</span></td>';
                        else
                        echo '<td width="100"><span class="m-r-10 label label-pill label-danger label-lg">' . $objCredits->printCredits($usercreditss, $row['prefix'], $row['suffix']) . '</span></td>';

//                                echo '<td width="300" class="text-right">
//							<div class="btn-group">
//								<a class="btn btn-primary btn-sm various" data-fancybox-type="iframe" title="Update users" href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit.html?id=' . $row['id'] . '&' . $getString . '">' . $lang->get('com_setting') . '</a>
//								<div class="btn-group text-left">
//									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
//										' . $lang->get('com_options') . '
//										<span class="caret"></span>
//									</button>
//									<ul class="dropdown-menu">
//										<li><a href="' . CONFIG_PATH_SITE_ADMIN . 'credits_invoice.html?id=' . $row['id'] . '" class="various" data-fancybox-type="iframe" >' . $lang->get('com_invoice') . '</a></li>
//										<li><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_reset_key.do?id=' . $row['id'] . '&key=' . urlencode($row['api_key']) . '&email=' . urlencode($row['email']) . '&username=' . urlencode($row['username']) . '">' . $lang->get('lbl_reset_api_key') . '</a></li>
//										<li><a href="' . CONFIG_PATH_SITE_ADMIN . 'credits_history.html?id=' . $row['id'] . '" class="various" data-fancybox-type="iframe" >' . $lang->get('com_transections') . '</a></li>
//									</ul>
//								</div>
//								<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credits.html?id=' . $row['id'] . '&' . $getString . '" class="btn btn-default btn-sm" title=""  >' . $lang->get('com_credits') . '</a>
//								' . (($row['login_key'] != '') ? (' <a href="' . CONFIG_PATH_SITE_USER . 'login_key_process.do?key=' . $row['login_key'] . '" class="btn btn-default btn-sm" title="" target="_blank">' . $lang->get('com_login') . '</a>') : '') . '
//							</div>	
//						  </td>';
                        echo ' <td>
                                                    	<a href="' . CONFIG_PATH_SITE_ADMIN . 'users_edit.html?id=' . $row['id'] . '&' . $getString . '" class="table-action-btn"><i class="fa fa-pencil"></i></a>
                                                    
                                                    </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php } else
                echo 'No Record Found';
                ?>
        </div>
    </div>
</div>



<div align="right">
    <form action="" method="post" name="page_form">
        <input type="hidden" name="req_type" value="P" />

        <select name="dlist_page_size">
            <option value="0">--<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_page_size')); ?>--</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="250">250</option>
            <option value="500">500</option>
            <option value="1000">1000</option>
        </select>&nbsp;
        <input type="number" placeholder="Custom Page Size" name="page_size" value="" />&nbsp;<input class="btn btn-success formSubmit" type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),'Set'); ?>"  />
    </form>
</div>
<?php echo $pCode; ?>
