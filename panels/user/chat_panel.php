<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$mysql = new mysql();
$userid = $request->GetStr("id");
$userid = urldecode($userid);
// get all the chat history new and old all data
//$sql = "select * from dummy a where a.entry_type='admin' and a.user_id=".$userid;
?>
 <?php
                            // get the online status
                            // $sql = "select a.id,a.username,a.online from " . ADMIN_MASTER . " a where a.`status`=1";
                            $sql = 'select a.online,a.notify,a.img from ' . USER_MASTER . ' a where a.id=' . $member->getUserId();
                            
                            //$result = $mysql->getResult($sql);
                            $qrydata = $mysql->query($sql);
                            if ($mysql->rowCount($qrydata) > 0) {
                                $rows = $mysql->fetchArray($qrydata);
                                $adminchatstatus = $rows[0]['online'];
                                 $adminnotify = $rows[0]['notify'];
                                 $userpic=$rows[0]['img'];
                                
                                if($adminchatstatus==1)
                                    $adminchatstatus='checked';
                                else
                                    $adminchatstatus='';
                                
                                 if($adminnotify==1)
                                    $adminnotify='checked';
                                else
                                    $adminnotify='';
                                
                            }
                            ?>
<div class="panel col-lg-7 col-lg-offset-2">
    <div class="panel-heading">
        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_List_of_Agents')); ?>
        <span data-toggle="tooltip" data-placement="Right" title="<?php echo $admin->wordTrans($admin->getUserLang(),'Click To On/Off Notofications'); ?>" style="float: right">
                                        <input  type="checkbox" id="notification" <?php echo $adminnotify;?> data-plugin="switchery" data-color="#495C74" data-size="small" onchange="setStatus(2);"/>
                                    </span>
    </div>
    
    
    <table class=" table table-striped table-hover">
        <tr>

            <th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Name')); ?></th>
            <th> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Unread_Messages')); ?></th>
            <th style="text-align: center"> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Action')); ?></th>

        </tr>
        <?php
        // $sql = 'select * from ' . Vendor . ' order by id';
        $sql = "select a.id,a.nname,a.online,(select count(b.a_id) countms from ".Chat_Box." b 
                where b.isview=0
                    and b.entry_type='user' 
                        and b.admin_id=a.id
                                            and b.user_id=".$member->getUserId().") msgcount from " . ADMIN_MASTER . " a where a.`status`=1";
        $result = $mysql->getResult($sql);
        //  $i=0;
        if ($result['COUNT']) {
            
            foreach ($result['RESULT'] as $row) {
                //       $i++;
                echo '<tr>';
                //        echo '<td width="10">' . $i . '</td>';
                echo '<td>' . $graphics->status($row['online']) . '  ' . $mysql->prints($row['nname']) . '</td>';
                 echo '<td><span class="fa fa-envelope">    '.$row['msgcount'].'</span></td>';
                echo '<td class="" width=""><a href="' . CONFIG_PATH_SITE_USER . 'chat.html?adminid=' . $row['id'] . '&days=7" class="btn btn-primary btn-sm">'.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Start_Chat')).'</a>  <a href="#"  onclick="delchat(' . $row['id'] . ');" class="btn btn-danger btn-sm"><i class="ion-close"></i> '.$admin->wordTrans($admin->getUserLang(),$lang->get('lbl_Delete_Chat')).'</a></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';
        }
        ?>
    </table>
</div>


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
            url: config_path_site_member+ "_chat_del.do",
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