<?php
/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */

if (!defined("_VALID_ACCESS")) {

    define("_VALID_ACCESS", 1);

    require_once("../../_init.php");
}



$mysql = new mysql();

// var_dump($_GET);
//  echo $_GET['inv'];
// exit;
// if($_GET['inv']=="false")
// {





if (isset($_GET['val'])) {

    $data = "'%" . $_GET['val'] . "%'";



    $sql = 'SELECT * FROM ' . USER_MASTER . ' WHERE username like ' . $data . ' or email like ' . $data;

    //  echo  $sql;
    // exit;

    $sql = $mysql->query($sql);

    $rows = $mysql->fetchArray($sql);
}

//if(empty($rows)) {
//    echo "<div class='scr_result'><table width='100%' cellspacing='3' cellpadding='0' border='0'>";
//    echo "<tr>";
//        echo "<td colspan='4'>There were not records</td>";
//    echo "</tr>";
//    echo "<div class='scr_result'></table>";
//} 



if (count($rows) > 0) {
    ?>

    <div class='dropdown open'>

        <a class=""></a>

        <div class="dropdown-menu dropdown-menu-scale">

            <a class="dropdown-item"><h4>Search Result <span class="pull-right btnRemoveSearch"><i class="fa fa-times"></i></span></h4></a>

            <?php
            foreach ($rows as $row) {
                ?>

                <a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>users_edit.html?id=<?php echo $row['id']; ?>" class="dropdown-item">

                    <h6 class="label label-info label-lg"><?php echo $row['username']; ?></h6>

                    <?php echo $row['email']; ?>

                </a>

                <?php
            }
            ?>

        </div>

    </div>

    <?php
} else {



//      echo "<div class='scr_result'><table width='100%' cellspacing='3' cellpadding='0' border='0'>";
//    echo "<tr>";
//        echo "<td colspan='4'>There were not records</td>";
//    echo "</tr>";
//    echo "<div class='scr_result'></table>";  

    if (isset($_GET['val'])) {

        // $data = "'%".$_GET['val']."%'";

        $data = "'%" . $_GET['val'] . "%'";



        $sql = "SELECT a.id,a.`status`,a.imei FROM " . ORDER_IMEI_MASTER . " a WHERE a.imei like ". $data;

        //  echo  $sql;
        // exit;

        $sql = $mysql->query($sql);

        $rows = $mysql->fetchArray($sql);
    }

    $type = '';

    $tool_id = '';

    if (count($rows) > 0) {

        echo "<div class='dropdown open'><a class=''></a><div class='dropdown-menu dropdown-menu-scale' style='width: 450px;'> <a class='dropdown-item'><h4>Search Result <span class='pull-right btnRemoveSearch'><i class='fa fa-times'></i></span></h4></a><div><table style='height: 125px' class='table table-hover table-responsive'>";

        echo '<tr><th>IMEI</th><th style="width:150px">Status</th><th>Action</th></tr>';

        foreach ($rows as $row) {

            $type = '';

            $tool_id = '';

            if ($row['status'] == 0) {

                $type = 'Pending';

                $tool_id = $row['tool_id'];
            } else if ($row['status'] == 1) {

                $type = 'locked';

                $tool_id = $row['tool_id'];
            } else if ($row['status'] == 2) {

                $type = 'avail';

                $tool_id = $row['tool_id'];
            } else if ($row['status'] == 3) {

                $type = 'rejected';

                $tool_id = $row['tool_id'];
            }

            echo "<tr class='scr_result'>";
            ?>

            <td>
<?php echo $row['imei']; ?>



            </td>


            <?php
            switch ($row['status']) {
                case 0:
                    echo '<td><span class="text-default"><i class="fa fa-spinner fa-pulse text-default"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_New_Order')) . '</span></td>';
                    break;
                case 1:
                    echo '<td><span class="text-primary"><i class="fa fa-lock text-primary"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_In-Process')) . '</span></td>';
                    break;
                case 2:
                    echo '<td><span class="text-success"><i class="fa fa-circle text-success"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_Completed')) . '</span></td>';
                    break;
                case 3:
                    echo '<td><span class="text-danger"><i class="fa fa-circle text-danger"></i> ' . $admin->wordTrans($admin->getUserLang(), $lang->get('com_rejected')) . '</span></td>';
                    break;
            }

            //   echo "<td>" . $row['status'] . "</td>";
            echo "<td>"; 
            

            //  echo "<td>" .$row['status']."</td>";

            echo '<a class="btn btn-default" data-fancybox-type="iframe" href="' . CONFIG_PATH_SITE_ADMIN . 'order_imei_detail.html?id=' . urlencode($row['id']) . (($type != '' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString)) . '" ><i class="fa fa-arrow-right"></i></a><a class="btn btn-danger" data-fancybox-type="iframe" href="' . CONFIG_PATH_SITE_ADMIN . 'order_q_edit.html?status='.urlencode($row['status']).'&id=' . urlencode($row['id']) . (($type != '' ) ? ('&type=' . $type . '&' . $pString) : ( '&' . $pString)) . '" ><i class="fa fa-pencil-square-o"></i></a>';



echo "</td>";



            echo "</tr>";
        }

        echo "</table></div></div></div>";
    } else {

        //  echo 'not imei';
        // exit;

        if (isset($_GET['val'])) {

            $data = "'%" . $_GET['val'] . "%'";

            //  $data = $_GET['val'];
//    $sql = 'SELECT a.id,a.`tool_name`,c.prefix as Currency,b.`amount_purchase` as PurchasePrice,b.`amount` as SalePrice 
//FROM `nxt_imei_tool_master` as a
//inner join `nxt_imei_tool_amount_details`  as b
//on a.`id`=b.`tool_id`
//inner join `nxt_currency_master` as c
//on b.`currency_id`=c.`id`
//WHERE a.tool_name like '.$data;

            $sql = 'SELECT a.id,a.`tool_name`,a.visible

FROM `nxt_imei_tool_master` as a



WHERE a.tool_name like ' . $data;

            //echo  $sql;
            // exit;

            $sql = $mysql->query($sql);

            $rows = $mysql->fetchArray($sql);
        }

        if (count($rows) > 0) {
            ?>



            <div class='dropdown open'>

                <a class=""></a>


                <div class="dropdown-menu dropdown-menu-scale">

                    <a class="dropdown-item"><h4>Search Result <span class="pull-right btnRemoveSearch"><i class="fa fa-times"></i></span></h4></a>

                    <?php
                    foreach ($rows as $row) {
                        
                        if($row['visible']==1)
                        $ser_status='<span><i class="fa fa-check text-success"></i></span>';
                                else
                        $ser_status='<span><i class="fa fa-times text-success"></i></span>';
                        ?>


                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN ?>services_imei_tools_edit.html?id=<?php echo $row['id']; ?>" class="dropdown-item">



                            <?php echo  $ser_status.'  '.trim($row['tool_name']); ?>

                        </a>


                        <?php
// echo "<td>".$row['prefix']."</td>";
// echo "<td>".$row['amount_purchase']."</td>";
//            echo "<td>".$row['amount']."</td>";
                        //echo "</tr>";
                    }
                    ?> </div>
            </div>


            <?php
            // echo "</table>";
        } else {
            ?>
            <div class="search-result seach-resut-box" style="display: block;">
                <div class="dropdown open">

                    <a class=""></a>

                    <div class="dropdown-menu dropdown-menu-scale">

                        <a class="dropdown-item"><h4>Search Result <span class="pull-right btnRemoveSearch"><i class="fa fa-times"></i></span></h4></a>


                        <a href="#" class="dropdown-item">

                            <h6 class="label label-info label-lg">No Data Found....</h6>

                        </a>


                    </div>

                </div>




            </div>
            <?php
        }
    }
}

// }
//        else
//        {
//            
//        if (isset($_GET['val'])) {
//   // $data = "'%".$_GET['val']."%'";
//      $data = "'".$_GET['val']."'";
//    
//    $sql = "select a.id,a.amount,a.date_time,a.`paid_status`  from
//nxt_invoice_master as a where a.id=".$data;
//  // echo  $sql;
//   // exit;
//    $sql = $mysql->query($sql);
//    $rows = $mysql->fetchArray($sql);
//    }
//    if(count($rows) > 0)
//    {
//      echo "<table width='100%' cellspacing='3' cellpadding='0' border='1'>";
//      echo "<tr><th>Invoice#</th><th>Amount</th><th>Invoice Date</th><th>Status</th></tr>";
//    foreach ($rows as $row) {
//         $type='';
//
//        
//        echo "<tr class='scr_result'>";
//           
//         
//echo "<td>".$row['amount']."</td>";
// echo "<td>".$row['date_time']."</td>";
// echo "<td>".(($row['paid_status']==1) ? 'Paid':'Unpaid')."</td>";
//           
//     
//         
//            echo "</tr>";
//    
//    }
//    echo "</table>";  
//          
//    }
//        
//    }
?>



