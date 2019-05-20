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

    $useremailll = "";

    if ($user_id2 != "") {

        $sql2 = 'select a.email,a.username from ' . USER_MASTER . '  a where a.id=' . $user_id2;

        $query22 = $mysql->query($sql2);

        $rowCount2 = $mysql->rowCount($query22);

        if ($rowCount2 != 0) {

            $rowsss = $mysql->fetchArray($query22);

            $row223 = $rowsss[0];

            $useremailll = $row223['email'];

            //  $usernameee = $row223['username'];
        }
    } else {
        echo 'No Email Found';
        exit;
    }


    $item_per_page = CONFIG_ORDER_PAGE_SIZE;
    //get total number of records from database for pagination
    $sql2 = 'select b.id,b.mail_subject as subject,b.mail_body as content,b.time_stamp date_time from ' . EMAIL_QUEUE . ' b 

where b.mail_to=' . $mysql->quote($useremailll) . '

order by b.time_stamp desc';

    $query2 = $mysql->query($sql2);

    //$results = $mysqli->query("SELECT COUNT(*) FROM paginate");
    $total_rows2 = $mysql->rowCount($query2); //hold total records in variable
    //break records into pages
    $total_pages = ceil($total_rows2 / $item_per_page);

    //get starting position to fetch the records
    $page_position = (($page_number - 1) * $item_per_page);


    //Limit our results within a specified range. 

    $sql = 'select b.id,b.mail_subject as subject,b.mail_body as content,b.time_stamp date_time from ' . EMAIL_QUEUE . '  b 

where b.mail_to=' . $mysql->quote($useremailll) . '

order by b.time_stamp desc LIMIT ' . $page_position . ',' . $item_per_page;



    $query = $mysql->query($sql);

    if ($mysql->rowCount($query) > 0) {
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>

                    <th>#</th>

                    <th style="width: 500px">Subject</th>

                    <th style="width: 200px">Date</th>

                    <th>Action</th>

                </tr>
            </thead>
            <tbody>

                <?php
                $rows = $mysql->fetchArray($query);

                foreach ($rows as $row) {


                    echo '<tr>';

                    echo '<td>' . $row['id'] . '</td>';

                    echo '<td>' . $row['subject'] . '</td>';





//                        $dtReplyDateTime = new DateTime($row['date_time'], new DateTimeZone($admin->timezone()));
//                        $dtReplyDateTime->setTimezone(new DateTimeZone($member->timezone()));
//                        $dtReplyDateTime = $dtReplyDateTime->format('d-M-Y H:i');
                    // time zone logic



                    $sql = 'select a.timezone from ' . TIMEZONE_MASTER . ' as a where a.is_default=1';

                    $query = $mysql->query($sql);

                    $rowCount = $mysql->rowCount($query);

                    if ($rowCount != 0) {

                        $rows = $mysql->fetchArray($query);

                        $row11 = $rows[0];

                        $dftimezonewebsite = $row11['timezone'];
                    }



                    //get defaul timezone of admin

                    $sql = 'select am.*,tz.timezone from ' . ADMIN_MASTER . ' as am

                                                                    inner join ' . TIMEZONE_MASTER . ' as tz

                                                                    on am.timezone_id=tz.id

                                                                    where am.id=' . $admin->getUserId();

                    $query = $mysql->query($sql);

                    $rowCount = $mysql->rowCount($query);

                    if ($rowCount != 0) {

                        $rows = $mysql->fetchArray($query);

                        $row22 = $rows[0];

                        $dftimezoneadmin = $row22['timezone'];
                    }

                    $date = new DateTime($row['date_time'], new DateTimeZone($dftimezonewebsite));

                    $date->setTimezone(new DateTimeZone($dftimezoneadmin));

                    $finaldate = $date->format('d-M-Y H:i');

                    echo '<td>' . $finaldate . '</td>';
                    ?>





                <td>



                    <input type="button" class="btn btn-info" value="Show Detail" onclick="popUp('mail_history_detail.html?e_id=<?php echo $row['id']; ?>&u_id=<?php echo $user_id2; ?>');" />

                </td>

                <?php
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

