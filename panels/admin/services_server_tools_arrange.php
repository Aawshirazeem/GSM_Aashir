<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->getInt('id');
?>
<ul class="breadcrumb">
    <li><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><i class="fa fa-home"></i> <?php $lang->prints('lbl_dashboard'); ?></a></li>
    <li class="active"><?php $lang->prints('lbl_services'); ?></li>
    <li class="active"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html"><?php $lang->prints('lbl_server_log_manager'); ?></a></li>
    <li class="active"><?php $lang->prints('lbl_server_log_sort'); ?></li>
</ul>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel">
            <div class="panel-heading">
                <?php $lang->prints('lbl_server_log_sort'); ?>

            </div>
            <div class="panel-body">
                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_server_tools_arrange_process.do" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <div class="dd nestable_list" data-output="nestable_list_1_output">
                        <textarea id="nestable_list_1_output" name="list" class="hidden col-lg-12 form-control"></textarea>
                        <ol class="dd-list">
                            <?php
                            $sql = 'select itm.*,icm.countries_name as CountryName,
													igm.id as gid, igm.group_name, igm.status as groupStatus
											from ' . SERVER_LOG_MASTER . ' itm
											
											left join ' . COUNTRY_MASTER . ' icm on (itm.country_id = icm.id)
											left join ' . SERVER_LOG_GROUP_MASTER . ' igm on(itm.group_id = igm.id)
											where itm.group_id=' . $id . '
											order by itm.sort_order, igm.group_name, itm.server_log_name';
                            //    echo $sql;
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
                                        //echo '<tr><td colspan="9"><h2>' . (($row['groupStatus'] == '1') ? $mysql->prints($groupName) : '<del>' . $mysql->prints($groupName) . '</del>') . '<a href="' . CONFIG_PATH_SITE_ADMIN . 'services_imei_group_edit.html?id=' . $row['gid'] . '"> <i class="icon-pencil"></i></a></h2></td></tr>';
                                    }
                                    echo '<li class="dd-item" data-id="' . $mysql->prints($row['id']) . '"><div class="dd-handle">' . $mysql->prints($row['server_log_name']) . '</div></li>';
                                }
                            } else {
                                echo $graphics->messagebox_warning($lang->get('com_no_record_found'));
                            }
                            ?>
                        </ol>
                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>server_logs.html" class="btn btn-danger">Cancel</a>
                        <input type="submit" value="submit" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo CONFIG_PATH_ASSETS; ?>nestable/jquery.nestable.css" />
<script src="<?php echo CONFIG_PATH_ASSETS; ?>nestable/jquery.nestable.js" type="text/javascript"></script>
<script class="include" type="text/javascript" src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.scrollTo.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>js/jquery.nicescroll.js" type="text/javascript"></script>

<script>
    var Nestable = function () {

        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target),
                    output = list.data('output');
            console.log(output);
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        // activate Nestable for list 1
        $('.nestable_list').nestable({
            group: 1
        }).on('change', updateOutput);

        // output initial serialised data
        updateOutput($('.nestable_list').data('output', $('#' + $('.nestable_list').data('output'))));

    }();
</script>

