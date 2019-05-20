<?php
//defined("_VALID_ACCESS") or die("Restricted Access");
if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
$mysql = new mysql();
$userid = $request->GetStr("id");
$userid = urldecode($userid);
// get all the chat history new and old all data
//$sql = "select * from dummy a where a.entry_type='admin' and a.user_id=".$userid;
?>
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="panel col-lg-12">
            <div class="panel-heading">
                List of Agents

            </div>


            <table class=" table table-striped table-hover">
                <tr>

                    <th>Name</th>

                    <th style="text-align:">Action</th>

                </tr>
                <?php
                // $sql = 'select * from ' . Vendor . ' order by id';
                $sql = "select a.id,a.nname,a.online,(select count(b.a_id) countms from " . Chat_Box . " b 
                where b.isview=0
                    and b.entry_type='user' 
                        and b.admin_id=a.id
                                            and b.user_id=" . $member->getUserId() . ") msgcount from " . ADMIN_MASTER . " a where a.`status`=1";
               // echo $sql;exit;
                $result = $mysql->getResult($sql);
                //  $i=0;
                if ($result['COUNT']) {

                    foreach ($result['RESULT'] as $row) {
                        //       $i++;
                        echo '<tr>';
                        //        echo '<td width="10">' . $i . '</td>';
                        echo '<td>' . $graphics->status($row['online']) . '  ' . $mysql->prints($row['nname']) . '</td>';

                        echo '<td class="" width=""><a href="http://localhost:8081/impkk/shop/chat_3.html?adminid=' . $row['id'] . '&days=7" class="btn btn-primary btn-sm">Start Chat</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="no_record">' . $lang->get('com_no_record_found') . '</td></tr>';
                }
                ?>
            </table>
        </div>
    </div></div>

<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>

<script>

    setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
    function delchat(a) {

        var userid =<?php echo $member->getUserId(); ?>;
        var adminid = '';
        adminid = a;

        $.ajax({
            type: "POST",
            url: config_path_site_member + "_chat_del.do",
            data: "&u_id=" + userid + "&a_id=" + adminid,
            error: function () {
                alert("Some Error Occur");
            },
            success: function (msg) {
                alert(msg);
            }
        });
    }


</script>