<?php
	defined("_VALID_ACCESS") or die("Restricted Access");
	
?>
<div class="bg_gray_light">
	<div class="container">
		<h3 class="wow fadeInDown" data-wow-delay="0.2s">Our Services</h3>
		<div class="thumb-pad9 wow fadeInUp" data-wow-delay="0.1s">
		<section class="panel tab">
			<header class="panel-heading tab-bg-dark-navy-blue">
				<ul class="nav nav-tabs nav-justified ">
					<li class="active"><a data-toggle="tab" href="#imei"><?php $lang->prints('tbl_imei_service_status');?></a></li>
					<li><a data-toggle="tab" href="#file"><?php $lang->prints('tbl_file_service_status');?></a></li>
					<li><a data-toggle="tab" href="#serverlog"><?php $lang->prints('tbl_server_log_status');?></a></li>
					<li><a data-toggle="tab" href="#prepaidlog"><?php $lang->prints('tbl_prepaid_log_status');?></a></li>
				</ul>
			</header>
			<div class="panel-body">
				<div class="tab-content tasi-tab">
					<div id="imei" class="tab-pane active">
						<?php include("products_imei.php"); ?>
					</div>
					<div id="file" class="tab-pane">
						<?php include("products_file.php"); ?>
					</div>
					<div id="serverlog" class="tab-pane">
						<?php include("products_server_logs.php"); ?>
					</div>
					<div id="prepaidlog" class="tab-pane">
						<?php include("products_prepaid_logs.php"); ?>
					</div>
				</div>
			</div>
		</section>
	</div>
	</div>
</div>