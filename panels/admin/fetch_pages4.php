<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}


if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    // include("config.inc.php");  //include config file
    //Get page number from Ajax POST
    if (isset($_POST["page"])) {
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
        if ((!is_numeric($page_number)) && ($page_number < 0 )) {
            die('Invalid page number!');
        } //incase of invalid page number
    } else {
        $page_number = 1; //if there's no page number, set it to 1
    }
    $user_id2 = $request->PostInt('user_id');


    $item_per_page = 10;
    //get total number of records from database for pagination

    $sql2 = 'select im.*,um.username, cm.prefix, gm.gateway from nxt_invoice_master im 
left join nxt_user_master um on (im.user_id = um.id) 
left join nxt_currency_master cm on (im.currency_id = cm.id) 
left join nxt_gateway_master gm on (im.gateway_id = gm.id)
where im.status=0 and im.paid_status in (0,2,3,1)
and um.id=' . $user_id2 . '
order by im.id DESC';

    $query2 = $mysql->query($sql2);

    //$results = $mysqli->query("SELECT COUNT(*) FROM paginate");
    $total_rows2 = $mysql->rowCount($query2); //hold total records in variable
    //break records into pages
    $total_pages = ceil($total_rows2 / $item_per_page);

    //get starting position to fetch the records
    $page_position = (($page_number - 1) * $item_per_page);


    //Limit our results within a specified range. 

    $sql = 'select im.*,um.username, cm.prefix, gm.gateway from nxt_invoice_master im 
left join nxt_user_master um on (im.user_id = um.id) 
left join nxt_currency_master cm on (im.currency_id = cm.id) 
left join nxt_gateway_master gm on (im.gateway_id = gm.id)
where im.status=0 and im.paid_status in (0,2,3,1)
and um.id=' . $user_id2 . '
order by im.id DESC LIMIT ' . $page_position . ',' . $item_per_page;

    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="100"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_amount')); ?></th>
                    <th style="width: 300px">Description / Note</th>
					<th style="width: 100px">Update</th>
                    <th style="width: 200px">Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $rows = $mysql->fetchArray($query);

                foreach ($rows as $row) {


                    echo '<tr>';

                    echo '<td>' . $row['id'].'<br>'; 
                    switch ($row['paid_status']) {

                        case '0':

                            echo '<span class="label label-info">' . $admin->wordTrans($admin->getUserLang(), 'Unpaid') . '</span>';

                            break;

                        case '1':

                            echo '<span class="label label-success">' . $admin->wordTrans($admin->getUserLang(), 'Paid') . '</span>';

                            break;

                        case '2':

                            echo '<span class="label label-danger">' . $admin->wordTrans($admin->getUserLang(), 'Rejected') . '</span>';

                            break;
                    }
                    echo '</td>';

                    //  echo '<td>' . $row['username'] . '</td>';
                    //  echo '<td>' . $row['credits'] . '</td>';
                    echo '<td>' . $objCredits->printCredits($row['credits'], $row['prefix'], $row['suffix']) . '</td>';
                    //echo '<td>' . $row['amount'] . '</td>';
                    if ($row['paid_status'] == 0) {
                    echo '<td>';
                    echo '<div><input  id=gt'.$row["id"].' name="gateway" placeholder="Admin Note" class="form-control" style="float:left; width:110px; padding-left:3px; padding-right:0;">';
                    echo '<lable id="lbl'.$row["id"].'">'.$row["amount"].'</lable> - <input name="amount" id="amnt_inv_vo'.$row["id"].'" maxlength="6" size="6"  value="" class="form-control" style="display:inline-block; width:100px" />';
                    echo '</td>';
					
					echo '<td>';
					echo '<a href="javascript:void(0)" id="refresh_inv_vo'.$row["id"].'" class="zmdi zmdi-refresh zmdi-hc-2x" onclick="edit_proccess('.$row["id"].')"></a>';
					echo '</td>';
                    }
                    else
                    {
                        echo '<td></td>';
						echo '<td></td>';
                    }

//                        $dtReplyDateTime = new DateTime($row['date_time'], new DateTimeZone($admin->timezone()));
//                        $dtReplyDateTime->setTimezone(new DateTimeZone($member->timezone()));
//                        $dtReplyDateTime = $dtReplyDateTime->format('d-M-Y H:i');
                    // time zone logic
                    $finaldate = $admin->datecalculate($row['date_time']);
                    $finaldate2 = "";
                    if ($row['date_time_paid'] != "" && $row['date_time_paid'] != "0000-00-00 00:00:00")
                        $finaldate2 = $admin->datecalculate($row['date_time_paid']);
                    echo '<td>' . $finaldate . '<br>' . $finaldate2 . '</td>';
                    ?>




                    <?php
                    if ($row['paid_status'] != 2) {


                        if ($row['paid_status'] == 0) {
                            echo '<td><div class="btn-group" id="somediv'.$row["id"].'"><a href="#" onclick="edit_proccess_accept('.$row["id"].')" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_Accept')) . '</a><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_unpaid_reject_process.html?id=' . $row['id'] . '&userr='.$user_id2.'" class="btn btn-default btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_Reject')) . '</a><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=' . $row['paid_status'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_View')) . '</a> </td>';
                        } else {
                            echo '<td>';
                            echo '<div class="btn-group"><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_credit_invoices_detail.html?id=' . $row['id'] . '&type=' . $row['paid_status'] . '" class="btn btn-primary btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_View')) . '</a><a href="' . CONFIG_PATH_SITE_ADMIN . 'users_inv_mark_unpaid.html?id=' . $row['id'] . '" class="btn btn-danger btn-sm">' . $admin->wordTrans($admin->getUserLang(), $lang->get('lbl_mark_unpaid')) . '</a> </td>';
                        }
                        ?>
                        <?php
                        echo '</tr>';
                    }
                }

                echo '</tbody></table>';
                echo '<div align="center">';
                /* We call the pagination function here to generate Pagination link for us. 
                  As you can see I have passed several parameters to the function. */
                echo paginate_function($item_per_page, $page_number, $total_rows, $total_pages);
                echo '</div>';


                exit;
            } else {
                echo 'No Order Found';
            }
            exit;
        }
################ pagination function #########################################
################ pagination function #########################################

        function paginate_function($item_per_page, $current_page, $total_records, $total_pages) {
            $pagination = '';
            if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
                $pagination .= '<ul class="pagination">';

                $right_links = $current_page + 3;
                $previous = $current_page - 1; //previous link 
                $next = $current_page + 1; //next link
                $first_link = true; //boolean var to decide our first link

                if ($current_page > 1) {
                    $previous_link = ($previous == 0) ? 1 : $previous;
                    $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
                    $pagination .= '<li><a href="#" data-page="' . $previous_link . '" title="Previous">&lt;</a></li>'; //previous link
                    for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
                        if ($i > 0) {
                            $pagination .= '<li><a href="#" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
                        }
                    }
                    $first_link = false; //set first link to false
                }

                if ($first_link) { //if current active page is first link
                    $pagination .= '<li class="first active">' . $current_page . '</li>';
                } elseif ($current_page == $total_pages) { //if it's the last active link
                    $pagination .= '<li class="last active">' . $current_page . '</li>';
                } else { //regular current link
                    $pagination .= '<li class="active">' . $current_page . '</li>';
                }

                for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
                    if ($i <= $total_pages) {
                        $pagination .= '<li><a href="#" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($current_page < $total_pages) {
                    $next_link = ($i > $total_pages) ? $total_pages : $i;
                    $pagination .= '<li><a href="#" data-page="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
                    $pagination .= '<li class="last"><a href="#" data-page="' . $total_pages . '" title="Last">&raquo;</a></li>'; //last link
                }

                $pagination .= '</ul>';
            }
            return $pagination; //return pagination links
        }
        ?>

