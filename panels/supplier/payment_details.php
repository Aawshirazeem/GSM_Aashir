<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");
$supplier->checkLogin();
$supplier->reject();
?>

<div class="row">
	<div class="col-lg-12">
    	<div class="panel panel-color">
        	<div class="panel-heading bg-default-700 p-10 color-white m-b-10">
            	<h3 class="panel-title m-0"><?php echo $admin->wordTrans($admin->getUserLang(),'Payment Details'); ?></h3>
            </div>
            <div class="panel-body">
            	<table class="table table-striped table-hover">
                	<tr>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),'Sno'); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_date')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_amount')); ?></th>
                        <th><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_comments')); ?></th>
                    </tr>
                    <?php
                    $sql = 'select *, DATE_FORMAT(date_time, "%d %b, %Y") as date_time from ' . SUPPLIER_PAYMENT . ' where supplier_id=' . $supplier->getUserId() . ' order by id desc';
                    $query = $mysql->query($sql);
                    $i = 1;
                    $summ = 0;
                    if ($mysql->rowCount($query) > 0) {
                        $rows = $mysql->fetchArray($query);
                        foreach ($rows as $row) {
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . $mysql->prints($row['date_time']) . '</td>';
                            echo '<td>' . $mysql->prints($row['credits_paid']) . '</td>';
                            echo '<td>' . $mysql->prints($row['comments']) . '</td>';
                            echo '</tr>';
                            $sum = $sum + $row['credits_paid'];
                            $i++;
                        }
                        echo '<tr>';

                        echo '<td colspan="4"></td>';
                        echo '</tr>';
                        echo '<tr>';

                        echo '<td>'.$admin->wordTrans($admin->getUserLang(),'Total').':</td><td></td><td colspan="3">' . $sum . '</td>';
                        echo '</tr>';
                    } else {
                        echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
                    }
                    ?>
                </table>
            </div>

        </div>

    </div>

</div>
