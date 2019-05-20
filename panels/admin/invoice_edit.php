<?php

//echo "here";



/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 * 

 */

   $get_invoice_det='select * from '.INVOICE_EDIT;

                $inv_detail=$mysql->query($get_invoice_det);

                $inv_detail = $mysql->fetchArray($inv_detail);

                $logo=$inv_detail[0]["logo"];

                $textarea=$inv_detail[0]["detail"].$inv_detail[0]["detail2"].$inv_detail[0]["detail3"].$inv_detail[0]["detail4"];

?>





<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),'CMS'); ?></li>           

             <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Invoice_Edit')); ?></li>

        </ol>

    </div>

</div>



<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>invoice_edit_proccess.html" method="post" enctype="multipart/form-data" >

    <div class="row">

        <div class="col-md-8">

            <div class="">

                <h4 class="m-b-20"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Invoice_Edit')); ?></h4>



                <div class="form-group">

                    <label>  <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Upload_Invoice_Logo')); ?> </label>

                    <br/>

                    <img src="<?php echo CONFIG_PATH_SITE  ?>images/<?php echo $inv_detail[0]["logo"]; ?>" width="220px">

      

                    <input type="file" name="file" />

                </div>







                <div class="form-group">

                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enter_Company_Detail')); ?> </label>

                    <textarea rows="4" style="width:100%" name="detail"><?php echo $textarea; ?></textarea>

                </div>



                <div class="form-group">



                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_save')); ?>" name="submit" class="btn btn-success btn-sm" />

                </div>







            </div></div></div>

</form>

