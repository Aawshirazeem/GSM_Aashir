<?php
defined("_VALID_ACCESS") or die("Restricted Access");
//echo "here";
//exit;
$id = $request->GetInt('id');
$type=$request->GetInt('type');
if($type==1){
	$sql = 'select
      a.`txn_id`,
      um.`username`,
      um.email,
      a.`date_time`,
       a.`date_time_paid`,
       b.`date_time` as p_date,
       c.`prefix`,
       b.`amount` as paid,
       b.`credits` as total,b.`gateway_id`,
       b.`remarks`
      from nxt_invoice_master as a
      left join nxt_invoice_log as b
      on a.id=b.`inv_id`
      left join `nxt_currency_master` as c
      on a.`currency_id`=c.`id`
      left join `nxt_user_master` um on (a.user_id = um.id)
where a.`paid_status`=1 and inv_id='.$id;
}else if($type==0){
	$sql = 'select
      a.`txn_id`,
      um.`username`,
      um.email,
      a.`date_time`,
       a.`date_time_paid`,
       b.`date_time` as p_date,
       c.`prefix`,
       b.`amount` as paid,
       b.`credits` as total,b.`gateway_id`,
       b.`remarks`
      from nxt_invoice_master as a
      left join nxt_invoice_log as b
      on a.id=b.`inv_id`
      left join `nxt_currency_master` as c
      on a.`currency_id`=c.`id`
      left join `nxt_user_master` um on (a.user_id = um.id)
where a.`paid_status`=0 and inv_id='.$id; 
}
//echo $sql;
$query = $mysql->query($sql);
$get_invoice_det='select * from '.INVOICE_EDIT;
$inv_detail=$mysql->query($get_invoice_det);
$inv_detail = $mysql->fetchArray($inv_detail);
// echo '<pre>';
// var_dump($inv_detail);exit;
//echo CONFIG_PATH_PANEL_ABSOLUTE;
// exit;
?>
<link rel="stylesheet" href="<?php echo  CONFIG_PATH_PANEL_ADMIN ?>/assets/spinner/throbber.css" type="text/css">
<?php /*?><link rel="stylesheet" href="<?php echo  CONFIG_PATH_PANEL_ADMIN ?>/assets/css/invoice.css" type="text/css"><?php */?>
<div class="row m-b-20">
	<?php /*?><header class="clearfix">
    	<div id="logo">
        	<img src="<?php echo CONFIG_PATH_SITE ?>images/<?php echo $inv_detail[0]["logo"]; ?>">
        </div>
        <div id="company">
        	<h2 class="name"><?php echo $inv_detail[0]["detail"]; ?></h2>
            <div><?php echo $inv_detail[0]["detail2"]; ?></div>
            <div><?php echo $inv_detail[0]["detail3"]; ?></div>
            <div><?php echo $inv_detail[0]["detail4"]; ?></div>
        </div>
    </header><?php */?>
    <?php
	if($mysql->rowCount($query) > 0){
		$rows = $mysql->fetchArray($query);
	?>
    <main>
<!--      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
      </div>-->
    </main>
    
    <style>
    	.invoice-title h2, .invoice-title h3 {
			display: inline-block;
		}
    </style>
    
    <div style="position:absolute;bottom:327px;right:434px;opacity:0.5; z-index:11;">
        	<img src="<?php echo CONFIG_PATH_PANEL ?>/assets_1/<?php if($type==0){ echo  'unpaid.png';}else {echo  'paid.png';}?>">
        </div>
    
    <div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2><?php echo $admin->wordTrans($admin->getUserLang(),'Invoice'); ?></h2><h3 class="pull-right"><?php echo $admin->wordTrans($admin->getUserLang(),'Order'); ?> # <?php echo $id; ?></h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong><?php echo $admin->wordTrans($admin->getUserLang(),'Billed To'); ?>:</strong><br>
    					<?php echo $rows[0]["username"] ?><br>
    					<?php echo $rows[0]["email"] ?><br>
    					<?php echo $admin->wordTrans($admin->getUserLang(),'Total Credits'); ?>: <b><?php echo $rows[0]["total"].' '.$rows[0]["prefix"] ?></b>
    				</address>
    			</div>
                <div class="col-xs-6 text-right">
    				<address>
    					<strong><?php echo $admin->wordTrans($admin->getUserLang(),'Order Date'); ?>:</strong><br>
    					<?php echo $rows[0]["date_time"] ?><br>
                        <strong><?php echo $admin->wordTrans($admin->getUserLang(),'Paid Date'); ?>:</strong><br>
    					<?php echo $rows[0]["date_time_paid"] ?><br><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row m-b-40">
    	<div class="col-md-12">
    				<h4><?php echo $admin->wordTrans($admin->getUserLang(),'Order Summary'); ?></h4>
    			
    				<div class="table-responsive">
    					<table class="table table-bordered">
    						<thead>
                                <tr>
        							<td><strong>#</strong></td>
        							<td class="text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(),'Description'); ?></strong></td>
        							<td class="text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(),'Date'); ?></strong></td>
        							<td class="text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(),'Currency'); ?></strong></td>
                                    <td class="text-right"><strong><?php echo $admin->wordTrans($admin->getUserLang(),'Amount'); ?></strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<?php
								$i=1;
								$amount=0;
								foreach ($rows as $row){
									// var_dump($rows);exit;
									if($row["remarks"]=="0" ||$row["remarks"]=="1"){
								?>
                                <tr>
    								<td><?php echo $i; ?></td>
    								<td class="text-center"><?php echo $row["gateway_id"]; ?></td>
    								<td class="text-center"><?php echo $row["p_date"]; ?></td>
                                    <td class="text-center"><?php echo $row["prefix"]; ?></td>
    								<td class="text-right"><?php echo $row["paid"]; ?></td>
    							</tr>
                                <?php
									}else{
								?>
                                <tr>
    								<td><?php echo $i; ?></td>
    								<td class="text-center"><?php echo $row["gateway_id"]; ?></td>
    								<td class="text-center"><?php echo $row["p_date"]; ?></td>
                                    <td class="text-center"><?php echo $row["prefix"]; ?></td>
    								<td class="text-right"><?php echo $row["paid"]; ?></td>
    							</tr>
                                <?php
									$amount += $row["paid"];
									}
									//  $amount  =$amounts - 
									$i++;
								}
								?>
    							<tr>
    								<td class="no-line" colspan="3"></td>
    								<td class="no-line text-center"><strong><?php echo $admin->wordTrans($admin->getUserLang(),'Received'); ?></strong></td>
    								<td class="no-line text-right"><?php if($type==1){ echo $rows[0]["total"]; }else {echo $amount;}?></td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    	</div>
    </div>
    <div class="row">
    	<div class="col-md-12">
        	<?php
			if($type==0){
			?>
				<h4><?php echo $admin->wordTrans($admin->getUserLang(),'Remaining Balance'); ?> : <b style="color:red;"><?php echo ($rows[0]["total"] - $amount).$rows[0]["prefix"]; ?></b> </h4>
			<?php
			}else{
			?>
				<h2><?php echo $admin->wordTrans($admin->getUserLang(),'Thank you!'); ?></h2>
			<?php
			}
			?>
        </div>
    </div>
</div>

<?php
	}
	?>
    
</div>
<?php echo $pCode;?>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.3.js" ></script>
<?php $url1 = CONFIG_PATH_ADMIN . 'user_credit_edit_proccess.php' ?>
<script>
function edit(){
	$("#amnt").css("background-color", "green");
    $("#amnt").prop("readonly", false);
}
function edit_proccess(r){
	//var trid = $("#r1").
	//alert(r);
	var amount=$("#amnt_inv_vo"+r).val();
	var credit=$("#credit_inv_vo"+r).val();
	var gateway=$("#gt"+r).val();
	//  alert(gateway);
	// var id=
	amount= parseInt(amount);
	if(amount >0){
		$("#refresh_inv_vo"+r).attr('class', 'throbber-loader');
		$.ajax({
			url: '<?php echo $url1; ?>',
			data: {id:r, amnt:amount,cr:credit,gt:gateway,type:0},
			success: function (data) {
				$("#refresh_inv_vo"+r).attr('class', 'glyphicon glyphicon-refresh');
                $("#amnt_inv_vo"+r).val('');
                $("#gt"+r).val('');             

                $("#lbl"+r).text(data)
				//$("#key").val(data);
				//$("#btn_reg").val('Regenerate API Key');
                //window.location = "users_credit_invoices.html?status=0";
				//change_log_id();
           }
		});
	}
}

function edit_proccess_accept(r){
	//var trid = $("#r1").
	// alert(r);
	var amount=$("#lbl"+r).text();
	var credit=$("#credit_inv_vo"+r).val();
	var gateway=$("#gt"+r).val();
	//alert(gateway);
	//var id=
	amount= parseInt(amount);
	if(amount >0){
		//$("#refresh_inv_vo"+r).attr('class', 'throbber-loader');
		$.ajax({
			url: '<?php echo $url1; ?>',
			data: {id:r, amnt:amount,cr:credit,gt:gateway,type:1},
			success: function (data) {
				window.location = "users_credit_invoices.html?status=0";
			}
		});
	}
}
</script>