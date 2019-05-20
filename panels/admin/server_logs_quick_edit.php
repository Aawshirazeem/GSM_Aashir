<?php
defined("_VALID_ACCESS") or die("Restricted Access");
?>


<div class="row m-b-20">

	<div class="col-xs-12">

    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">

            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>

            <li class="slideInDown wow animated active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_server_log_manager')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_quick_edit_server_log')); ?></li>

        </ol>

    </div>

</div>
<h4>Tools Quick Edit</h4>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs_quick_edit_process.do" method="post">
    <table class="table table-bordered table-hover">

        <tr>
           
            <th  width="300px">Group Name</th>
            <th>Tool Name</th>
            <th width="200px">Delivery Time</th>

        </tr>
        <?php

        $sql='select a.id,a.server_log_name,a.delivery_time,a.group_id ,b.group_name

from  ' . SERVER_LOG_MASTER . ' a

inner join  ' . SERVER_LOG_GROUP_MASTER . ' b

on a.group_id=b.id order by a.group_id';
      //  echo $sql;
        $query = $mysql->query($sql);

        $strReturn = "";

        if ($mysql->rowCount($query) > 0) {

            $rows = $mysql->fetchArray($query);

            $i = 0;

            $groupName = "";

            foreach ($rows as $row) {

                $i++;
                
                    if ($groupName != $row['group_name']) {

                        $groupName = $row['group_name'];

              echo ' <input type="hidden" name="idss2[]" value="' . $mysql->prints($row['group_id']) . '"> ';
                        echo '<tr><th colspan=""><input type="text" name="grp_name_' . $mysql->prints($row['group_id']) . '" value="' .$groupName . '" class="form-control"></th></tr>';
     
                    }
                    
                    
                echo '<tr>';
                
                echo ' <input type="hidden" name="idss[]" value="' . $mysql->prints($row['id']) . '"> ';
                echo '<td colspan="1"></td>';
                echo '<td><input type="text" name="tool_name_' . $mysql->prints($row['id']) . '" value="' . $mysql->prints($row['server_log_name']) . '" class="form-control"></td>';
                echo '<td><input type="text" name="tool_time_' . $mysql->prints($row['id']) . '" value="' . $mysql->prints($row['delivery_time']) . '" class="form-control"></td>';


                // echo '<td>' . $mysql->prints($row['tool_name']) . '</td>';
                //echo '<td>' . $mysql->prints($row['delivery_time']) . '</td>';
                echo '</tr>';
            }
        }
        ?>




    </table>
    <input type="submit" value="Update" class="btn btn-success">
</form>