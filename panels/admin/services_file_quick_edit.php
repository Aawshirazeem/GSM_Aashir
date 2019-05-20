<?php
defined("_VALID_ACCESS") or die("Restricted Access");
?>


<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_tool_manager')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_quick_edit_file_service_tools')); ?></li>

        </ol>

    </div>

</div>
<h4><?php echo $admin->wordTrans($admin->getUserLang(),'Tools Quick Edit'); ?></h4>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_quick_edit_process.do" method="post">
    <table class="table table-bordered table-hover">

        <tr>
          
            <th>Tool Name</th>
            <th width="200px">Delivery Time</th>

        </tr>
        <?php
        $sql='select a.id,a.service_name,a.delivery_time from ' . FILE_SERVICE_MASTER . ' a';
       // echo $sql;
        $query = $mysql->query($sql);

        $strReturn = "";

        if ($mysql->rowCount($query) > 0) {

            $rows = $mysql->fetchArray($query);

            foreach ($rows as $row) {

                
                    
                echo '<tr>';
                
                echo ' <input type="hidden" name="idss[]" value="' . $mysql->prints($row['id']) . '"> ';
                echo '<td><input type="text" name="tool_name_' . $mysql->prints($row['id']) . '" value="' . $mysql->prints($row['service_name']) . '" class="form-control"></td>';
                echo '<td><input type="text" name="tool_time_' . $mysql->prints($row['id']) . '" value="' . $mysql->prints($row['delivery_time']) . '" class="form-control"></td>';
                echo '</tr>';
            }
        }
        ?>




    </table>
    <input type="submit" value="Update" class="btn btn-success">
</form>