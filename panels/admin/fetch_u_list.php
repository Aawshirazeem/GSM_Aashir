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
    $filter =$_POST['filter'];
    $item_per_page = CONFIG_ORDER_PAGE_SIZE;
    //get total number of records from database for pagination
    $r_user_qry = '';

    $u_user_qry = '';

    $all_user_qry = '';



    $r_user_qry = ' select a.id,a.username,a.email from nxt_user_master a ';

    $u_user_qry = ' select b.id,b.username,b.email from nxt_user_register_master b ';



    if ($filter == 'R') {



        $sql = $r_user_qry;
    } else if ($filter == 'U') {

        $sql = $u_user_qry;
    } else {

        $sql = 'select a.id,a.username,a.email 

							from (

							

							' . $r_user_qry . '

							

							UNION ALL

							

							' . $u_user_qry . '

							

							) a 

							order by a.username';
    }


    $query = $mysql->query($sql);

    //$results = $mysqli->query("SELECT COUNT(*) FROM paginate");
    $total_rows = $mysql->rowCount($query); //hold total records in variable
    //break records into pages
    $total_pages = ceil($total_rows / $item_per_page);

    //get starting position to fetch the records
    $page_position = (($page_number - 1) * $item_per_page);


    //Limit our results within a specified range. 
    $sql = $sql . ' LIMIT ' . $page_position . ',' . $item_per_page;

    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        ?>
        <table class="MT5 table table-striped table-hover panel">

            <tr>

                <th width="16"></th>

                <th>Username</th>

                <th colspan="3"></th>



            </tr>
            <tbody>

                <?php
                $rows = $mysql->fetchArray($query);

                foreach ($rows as $row) {

                    echo '<tr id="tr' . $i . '">';

                    echo '<td id="td_check_' . $i . '">

											<label class="c-input c-checkbox"><input type="checkbox" class="subSelectEmailItems"  name="ids" value="' . $i . '" /><span class="c-indicator c-indicator-success"></span></label>

											<input type="hidden" name="email_' . $i . '" value="' . $row['email'] . '" />

										</td>';

                    echo '<td>' . $row['username'] . '</td>';

                    echo '<td>' . $row['email'] . '</td>';

                    echo '<td id="td_email_' . $i . '">...</td>';

                    echo '</tr>';

                    $i++;
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

