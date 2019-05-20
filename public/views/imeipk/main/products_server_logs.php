

<div class="container gsmColl">
    <h3 class="wow fadeInDown" data-wow-delay="0.2s">Server Log Service</h3><br>
    <div class="clearfix">
        <div class="col-md-5 col-sm-5 pad-lft0">

            <form id="live-search" action="" class="styled" method="post">
                <fieldset>
                    <input type="text" class="input-large form-control" id="filter" value="Search Service" />
                    <span id="filter-count"></span>
                </fieldset>
            </form>
        </div>

        <div class="exp-collap pull-right">
            [  <a href="#" class="btn btn-info openall">Expand All</a> <a href="#" class="btn btn-default closeall">Collapse All</a> ]
        </div>
    </div><br><br>
    <div class="panel-group" id="accordion">
        <?php
        $i = 0;
        $groupName = '';
        $mysql = new mysql();

        $price = "select show_price from `nxt_smtp_config`";
        $p_result = $mysql->query($price);
        $p_result = $p_result->fetch_assoc();
        $price = $p_result ["show_price"];


//get defual currency
        $cur = 'select id from ' . CURRENCY_MASTER . ' where is_default=1';
        $cur = $mysql->query($cur);
        $cur = $cur->fetch_assoc();
        $cur = $cur["id"];

        $sqlgrp = 'select * from ' . SERVER_LOG_GROUP_MASTER . ' a

order by a.sort_order';

        $query = $mysql->query($sqlgrp);
        $rowsgrp = $mysql->fetchArray($query);

        foreach ($rowsgrp as $grp) {
            $temp_g_id = $grp['id'];

            $sql = 'select
										slm.*,
										slgm.group_name,
										if(' . $price . ' =1, slad.amount,"0") as amount,
										cm.prefix, cm.suffix
									from ' . SERVER_LOG_MASTER . ' slm
									left join ' . SERVER_LOG_GROUP_MASTER . ' slgm on(slm.group_id = slgm.id)
									left join ' . CURRENCY_MASTER . ' cm on(cm.id = ' . $cur . ')
									left join ' . SERVER_LOG_AMOUNT_DETAILS . ' slad on(slad.log_id=slm.id and slad.currency_id = ' . $cur . ')
									where slgm.id=' . $temp_g_id . ' order by slm.group_id, slm.server_log_name';
            // echo $sql;
            $query = $mysql->query($sql);
            $rows = $mysql->fetchArray($query);
            if ($mysql->rowCount($query) > 0) {



                echo ' <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#' . $grp['id'] . '">
                       <b>' . $grp['group_name'] . '</b> <i class="fa fa-chevron-down pull-right"></i>
                    </a>
                </h4>
            </div><div id="' . $grp['id'] . '" class="panel-collapse collapse">
                <div class="panel-body">';



                //  echo $grp['group_name'];
                echo '<table class="table table-bordered table-hover">
    <tr class="commentlist">
         <th width="100">#ID</th>
        <th>Tool</th>
        <th width="100">Credits</th>
        <th width="200">Delivery Time</th>
    </tr>';

                foreach ($rows as $pro) {


                    echo '<tr class="commentlist">';
                    echo '<td>' . $pro['id'] . '</td>';
                    echo '<td class="searchme"><b>' . $pro['server_log_name'] . '</b></td>';
                    if ($price == 1)
                        echo '<td class="text_right">' . $objCredits->printCredits($pro['amount'], $pro['prefix'], $pro['suffix']) . ' </td>';
                    else
                        echo '<td class="text_right">**</td>';
                    echo '<td class="text_right">' . $pro['delivery_time'] . '</td>';
                    //echo '<td class="text-center">' . (($pro['status'] == '1') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') . '</td>';
                    echo '</tr>';
                }
                echo '</table>     </div>
            </div>
        </div>';
            }
        }
        ?>	



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            var jq = $.noConflict();
            jq('.closeall').click(function () {
                jq('.panel-collapse.in')
                        .collapse('hide');
            });
            jq('.openall').click(function () {
                jq('.panel-collapse:not(".in")')
                        .collapse('show');
            });
        </script>	
        <script>
            jq(document).ready(function () {


                jq("#filter").focus(function () {
                    jq(this).val('');
                });
                jq("#filter").keyup(function () {
                    jq('.panel-collapse:not(".in")')
                            .collapse('show');
                    // Retrieve the input field text and reset the count to zero
                    var filter = jq(this).val(), count = 0;

                    // Loop through the comment list
                    jq(".searchme").each(function () {

                        // If the list item does not contain the text phrase fade it out
                        if (jq(this).text().search(new RegExp(filter, "i")) < 0) {
                            jq(this).closest('tr').fadeOut();

                            // Show the list item if the phrase matches and increase the count by 1
                        } else {
                            jq(this).closest('tr').show();
                            count++;
                        }
                    });
                });
            });</script>
    </div>	
</div>			