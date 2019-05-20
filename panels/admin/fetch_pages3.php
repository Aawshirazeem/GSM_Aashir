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
    $user_id = $request->PostInt('user_id');
    $item_per_page = CONFIG_ORDER_PAGE_SIZE;
    //get total number of records from database for pagination
    $qType="";
    
    $sql='select ctm.* , um.username as username1, um2.username as username2
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
					where   ' . $qType . ' (user_id=' .$user_id . ' or user_id2=' . $user_id . ')
                                          
					order by ctm.id DESC';
    
    $query = $mysql->query($sql);

    //$results = $mysqli->query("SELECT COUNT(*) FROM paginate");
    $total_rows = $mysql->rowCount($query); //hold total records in variable
    //break records into pages
    $total_pages = ceil($total_rows / $item_per_page);

    //get starting position to fetch the records
    $page_position = (($page_number - 1) * $item_per_page);
$tempvar="IMEI Order";

    //Limit our results within a specified range. 
    $sql='select ctm.* , um.username as username1, um2.username as username2,oim.imei
					from ' . CREDIT_TRANSECTION_MASTER . ' ctm
					left join ' . USER_MASTER . ' um on (ctm.user_id=um.id)
					left join ' . USER_MASTER . ' um2 on (ctm.user_id2=um2.id)
                                             left join ' . ORDER_IMEI_MASTER . ' oim
                                        on ctm.order_id_imei=oim.id and ctm.info="IMEI Order"
					where   ' . $qType . ' (ctm.user_id=' .$user_id . ' or ctm.user_id2=' . $user_id . ')
                                          
					order by ctm.id DESC LIMIT ' . $page_position . ' , ' . $item_per_page;
								
//
    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        ?>
<table class="table table-bordered" style="table-layout:fixed;
width:100%;
word-wrap:break-word;">
            <thead>
                <tr>
                    <th width="60">Trans#</th>
                    <th width="60">Order#</th>
                    <th>Transaction Info</th>
                    <th>Note</th>
                    <th style="text-align:center">IMEI</th>
                    <th>Date&Time</th>
                    <th width="25" style="text-align:center"></th>
                    <th width="100" style="text-align:center">Credits</th>
                    <th style="text-align:center">Net</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $rows = $mysql->fetchArray($query);

                foreach ($rows as $row) {

                        //  $i++;
        echo '<tr>';
        echo '<td align="center">' . $row['id'] . '<br /></td>';
        echo '<td align="center">';
        echo ($row['order_id_imei'] != '0') ? $row['order_id_imei'] : '';
        
        echo ($row['order_id_file'] != '0') ? $row['order_id_file'] : '';
        echo ($row['order_id_server'] != '0') ? $row['order_id_server'] : '';
        echo '</td>';
      
        echo '<td><b>' . $row['info'] . '</b><br />';
        switch ($row['trans_type']) {
            case 6:
                echo (($user_id == $row['user_id']) ? $row['username2'] : $row['username1']);
                break;
        }
        echo '</td>';
           echo '<td><b>';
         echo ($row['admin_note'] != '') ? $row['admin_note'] : $row['user_note'];     echo '</b></td>';
        echo '</td>';
           echo '<td style="text-align:center"><b>';
         echo ($row['imei'] != '') ? $row['imei'] : '';     echo '</b></td>';

         $finaldate2 = $member->datecalculate($row['date_time']);
        echo '<td>' . $finaldate2. '</td>';
        //echo '<td align="center"><u>' . (($member->getUserID() == $row['user_id']) ? $row['credits_acc'] : $row['credits_acc_2']) . '</u></td>';
        //echo '<td align="center">'. (($member->getUserID() == $row['user_id']) ? $row['credits_acc_process'] : $row['credits_acc_process_2']) . '</td>';
        //echo '<td align="center">' . (($member->getUserID() == $row['user_id']) ? $row['credits_acc_used'] : $row['credits_acc_used_2']) . '</td>';

        echo '<td align="center">';
        switch ($row['trans_type']) {
            case 0:
                echo '<i class="fa fa-plus-circle text-success"></i>';
                break;
            case 1:
                echo '<i class="fa fa-plus-circle text-success"></i>';
                break;
            case 2:
                echo '<i class="fa fa-minus-circle text-danger"></i>';
                break;
            case 3:
                echo '<i class="fa fa-minus-circle text-danger"></i>';
                break;
            case 6:
                if ($user_id == $row['user_id']) {
                    echo '<i class="fa fa-arrow-up text-success"></i>';
                } else {
                    echo '<i class="fa fa-arrow-down text-danger"></i>';
                }
                break;
        }
        echo '</td>';

        echo '<td align="center">' . round($row['credits'],2) . '</td>';
        echo '<td align="center">
								<b>' . (($user_id == $row['user_id']) ? round($row['credits_after'],2) : round($row['credits_after_2'],2)) . '</b>
							</td>';
        echo '</tr>';
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

