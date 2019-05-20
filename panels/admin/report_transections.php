<?php

defined("_VALID_ACCESS") or die("Restricted Access");



$search_user_id = $request->GetInt('search_user_id');

$c_type = $request->GetInt('c_type');





$paging = new paging();

$log_type=$_GET['type'];

$offset = $request->getInt('offset');

$from_date = $request->getstr("from_date");

$to_date = $request->getstr("to_date");

$i = $offset;

$i++;

$limit = 100;

$qLimit = " limit $offset,$limit";

$extraURL = '&search_user_id=' . $search_user_id . '&from_date=' . $from_date . '&to_date=' . $to_date;





$clearSearch = false;

$qType = "where ctm.trans_type=6   ";

if ($search_user_id != 0) {

    if ($c_type != 0) {



        if ($c_type == 2) {

            $qType .= (($qType == '') ? ' where ' : ' and ') . ' ctm.user_id2 = ' . $search_user_id;

            $clearSearch = true;

        } else {

            $qType .= (($qType == '') ? ' where ' : ' and ') . ' ctm.user_id = ' . $search_user_id;

            $clearSearch = true;

        }

    } else {

        $qType .= (($qType == '') ? ' where ' : ' and ') . ' (ctm.user_id = ' . $search_user_id.' or  ctm.user_id2 = ' . $search_user_id.')';

        $clearSearch = true;

    }

}

if ($c_type != 0) {



    if ($c_type == 2) {

        $qType .= (($qType == '') ? ' where ' : ' and ') . ' ctm.info = "Credits Revoked by Admin"';

        $clearSearch = true;

    } elseif ($c_type == 1) {

        $qType .= (($qType == '') ? ' where ' : ' and ') . ' ctm.info ="Credits Added by Admin"';

        $clearSearch = true;

    } else {

        $qType .= (($qType == '') ? ' where ' : ' and ') . ' ctm.info ="Credits Added by [Auto Pay]"';

        $clearSearch = true;

    }

}

?>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="searchPanel" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"><i class="fa fa-remove"></i></button>

                <h4 class="modal-title"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?></h4>

            </div>

            <div class="modal-body">

                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html" method="get" name="frm_customer_edit_login" id="frm_customer_edit_login" class="formSkin">

                    <div class="form-group">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </label>

                        <select name="search_user_id" class="form-control">

                            <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all_users')); ?> </option>

<?php

$sql_usr = 'select id, username from ' . USER_MASTER . ' order by username';

$query_usr = $mysql->query($sql_usr);

$rows_usr = $mysql->fetchArray($query_usr);

foreach ($rows_usr as $row_usr) {

    echo '<option ' . (($row_usr["id"] == $search_user_id) ? 'selected="selected"' : '') . ' value="' . $row_usr['id'] . '">' . $mysql->prints($row_usr['username']) . '</option>';

}

?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_type')); ?> </label>

                        <select name="c_type" class="form-control">

                            <option value="0"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_all')); ?> </option>

                            <option value="1"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Added')); ?> </option>

                            <option value="2"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Revoked')); ?> </option>

                            <option value="3"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Auto_Added')); ?> </option>



                        </select>

                    </div>

                    <div class="form-group">

                        <div class="row">

                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_from')); ?></label>

<!--										<input type="text" id="fdt" name="from_date" value="<?php echo $from_date; ?>" class="form-control dpd1"/>-->

                                <input class="datepicker" id="fdt" name="from_date" data-date-format="mm/dd/yyyy" value="<?php echo $from_date; ?>">

                            </div>

                            <div class="col-sm-6" data-date-format="dd-mm-yyyy">

                                <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_to')); ?></label>

<!--										<input type="text" id="ldt" name="to_date" value="<?php echo $to_date; ?>" class="form-control dpd2"/>-->

                                <input class="datepicker" id="fdt" name="to_date" data-date-format="mm/dd/yyyy" value="<?php echo $to_date; ?>">

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?>" class="btn btn-success"/>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>







<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Reports')); ?></a></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Transaction')); ?></li>

        </ol>

    </div>

</div>





<?php

if ($search_user_id != 0) {
	$msg = $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_click_here_reset_the_search_options'));
    echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_transections.html">' .$msg. '</a>');

}



if ($from_date != '' && $to_date != '') {

    $from_date_search = date('Ymd', strtotime($from_date));

    $to_date_search = date('Ymd', strtotime($to_date));

    $qType .= (($qType == '') ? ' where ' : ' and ') . ' date(ctm.date_time) between ' . $from_date_search . ' and ' . $to_date_search;

    $clearSearch == true;
	$msg = $admin->wordTrans($admin->getUserLang(),$lang->get('lbl_click_here_reset_the_search_options'));
    echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_transections.html">' .$msg  . '</a>');

}

if ($c_type != 0) {
	$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_click_here_reset_the_search_options'));
    echo $graphics->messageBox('<a href="' . CONFIG_PATH_SITE_ADMIN . 'report_transections.html">' . $msg. '</a>');

}

//$qType = ($qType == '') ? '' : ' where ' . $qType;

$mysql->query('SET SQL_BIG_SELECTS=1');

if($log_type==1)

{

    $sql='select im.date_time,im.id invoice_id,im.txn_id, 

                 um.username as username,im.credits, iml.gateway_id 

                 from `nxt_invoice_master` as im 

                 left join `nxt_invoice_log` as iml on(iml.`inv_id`=im.id) 

                 left join nxt_user_master um on (im.user_id=um.id)

                 where im.`gateway_id` !=0 order by im.id DESC ';

    

}

else

{

 $sql = 'select

				ctm.id, ctm.date_time, ctm.credits, ctm.views, 

				um.username as username1, 

				um2.username as username2,

				date(ctm.date_time) as dt,

				im.id invoice_id,ctm.info

				from ' . CREDIT_TRANSECTION_MASTER . ' ctm

				left join ' . INVOICE_MASTER . ' im on (im.txn_id=ctm.id)

				left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)

				left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)

				' . $qType . '    and ctm.info!="Account Created" 

				order by ctm.id DESC';   

}    



//echo $sql;

$query = $mysql->query($sql . $qLimit);



$pCode = '';

if ($mysql->rowCount($query) > 0) {

    $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'report_transections.html', $offset, $limit, $extraURL);

    $transs = $mysql->fetchArray($query);

    //echo "<pre/>";

    //var_dump($transs);exit;

    ?>

    <div class="row">

        

                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html" class="btn btn-default btn-sm waves-effect waves-light m-b-30"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Manual_Transaction_Log')); ?> </a>

                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>report_transections.html?type=1" class="btn btn-primary btn-sm waves-effect waves-light m-b-30"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Auto_Transaction_Log')); ?> </a>

                       

                    

        <div class="col-lg-12">

             

            <section class="">

              

                <h4 class="panel-heading m-b-20">

   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Transection')); ?>

                    <div class="btn-group btn-group-sm pull-right">

                        <a href="#searchPanel" data-toggle="modal" class="btn btn-warning"><i class="fa fa-search"></i> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_search')); ?> </a>

    <?php if ($clearSearch == true) { ?><a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>report_transections.html" data-toggle="modal" class="btn btn-danger"><i class="fa fa-undo"></i></a> <?php } ?>

                    </div>

                </h4>
				
			<div class="">	

                <table class="table table-striped table-hover">

                    <?php

                    if($log_type ==1)

                     {

                        ?>

                    

                         <tr>

                      

                        <th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?> </th>

                        <th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice')); ?> </th>

                      

                               <th><?php echo $admin->wordTrans($admin->getUserLang(),'Transaction ID'); ?></th>

                     

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Info')); ?> </th>

                    </tr>

                    <?php

                     foreach ($transs as $trans) {

                        ?>

                        <tr>

                           

                            <td><?php echo $finaldate = $admin->datecalculate($row['date_time']);?></td>

                            <td><?php echo ($trans['invoice_id'] != 0) ? ('INV #' . str_pad($trans['invoice_id'], 4, '0', 0)) : ''; ?></td>

                       

                               <td><?php echo $trans['txn_id'] ?> </td>

                      

                            <td><?php echo $trans['username'];

                     ?></td>

                            <td class=""><?php echo $trans['credits']; ?> <?php $lang->prints('lbl_cr'); ?> </td>

                            <td><?php echo $trans['gateway_id']; ?></td>

                        </tr>

        <?php

    }

                     }

                    else {

                        ?>

                    

                        <tr>

                     

                        <th width="10"></th>

                        <th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_date')); ?> </th>

                        <th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_invoice')); ?> </th>

                     

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_username')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_credits')); ?> </th>

                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Info')); ?> </th>

                    </tr>

                      <?php  

                       foreach ($transs as $trans) {

                        ?>

                        <tr>

                           

                            <td><?php if ($trans['username2'] != '') {

                        echo '<i class="fa fa-angle-left"></i>';

                    } ?></td>

                            <td><?php  echo $finaldate = $admin->datecalculate($row['date_time']); ?></td>

                            <td><?php echo ($trans['invoice_id'] != 0) ? ('INV #' . str_pad($trans['invoice_id'], 4, '0', 0)) : ''; ?></td>

                     

                            <td><?php echo $trans['username1'];

                    echo $trans['username2']; ?></td>

                            <td class=""><?php echo $trans['credits']; ?> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_cr')); ?> </td>

                            <td><?php echo $trans['info']; ?></td>

                        </tr>

        <?php

    }

                    }

                     ?>

                    

                    <?php

                   

    echo '</table>';

    echo '<div class="row m-t-20">
            <div class="col-md-6">
                <div class="TA_C navigation" id="paging">
                    '.$pCode.'
                </div>
            </div>
            <div class="col-md-6">
                
            </div>
        </div>';

} else {
	$msg=$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_no_record_found'));
    echo $graphics->messagebox_warning($msg);

}

?>

        </section>

    </div>
	
	</div>

</div>



<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/css/datepicker.css" />

<script type="text/javascript" src="<?php echo CONFIG_PATH_EXTRA; ?>bootstrap-datepicker/js/bootstrap-datepicker.js"></script>



<script>

    $(document).ready(function ()

    {



        $('.datepicker').datepicker({

            startDate: '-3d'

        });

        $('#from_date').datepicker({format: 'yyyy-mm-dd'});

        $('#to_date').datepicker({format: 'yyyy-mm-dd'});

    });

</script>

