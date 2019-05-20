<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$id = $request->getInt('id');
?>
<div class="row m-b-20">
	<div class="col-xs-12">
    	<ol class="breadcrumb icon-home icon-angle-double-right animation-delay-slow">
            <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>dashboard.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_dashboard')); ?></a></li>
            <li class="slideInDown wow animated"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_services')); ?></li>
                        <li class="slideInDown wow animated"><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_manager')); ?></a></li>

            <li class="slideInDown wow animated active"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_file_service_arrange')); ?></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel">
            <div class="panel-heading">
                <?php echo  $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_file_service_sort')); ?>

            </div>
            <div class="panel-body">
                <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file_arrange_process.do" method="post">
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

                            $sql = "select * from nxt_file_service_master";
                            $sql = 'select * from ' . FILE_SERVICE_MASTER . ' a

order by a.sort_order,a.service_name
';
                            $query = $mysql->query($sql);
                            $strReturn = "";
                            if ($mysql->rowCount($query) > 0) {
                                $rows = $mysql->fetchArray($query);
                                $i = 0;
                                $groupName = "";
                                foreach ($rows as $row) {
                                    $i++;

                                    echo '<li class="dd-item" data-id="' . $mysql->prints($row['id']) . '"><div class="dd-handle">' . $mysql->prints($row['service_name']) . '</div></li>';
                                }
                            } else {
                                echo $graphics->messagebox_warning($lang->get('com_no_record_found'));
                            }
                            ?>
                        </ol>
                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_file.html" class="btn btn-danger">Cancel</a>
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

