<?php
//defined("_VALID_ACCESS") or die("Restricted Access");
if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}
$mysql = new mysql();
//$userid = $request->GetStr("id");
$adminid = $request->GetStr("adminid");
$userid = $member->getUserId();
$adminname=$member->getUserName();
$sql = "select * from dummy a where a.entry_type='user' and   a.user_id=" . $userid;
$sql='select a.img from ' . USER_MASTER . ' a where a.id='. $userid;
$qrydata = $mysql->query($sql);
                            if ($mysql->rowCount($qrydata) > 0) {
                                $rows = $mysql->fetchArray($qrydata);
                                $userimg = $rows[0]['img'];
                            }

$daysold=$request->GetStr('days');
if($daysold=='')
    $daysold=7;


$sql = "select a.*,cast(a.time_stamp as time) msgtime,b.nname adminname,c.username,c.img from " . Chat_Box . " a
inner join " . ADMIN_MASTER . " b
on a.admin_id=b.id
inner join " . USER_MASTER . " c
on a.user_id=c.id
where a.entry_type='user' and cast(a.time_stamp as date) >= cast(DATE_SUB(NOW(), INTERVAL ".$daysold." DAY) as date) and a.user_id=" . $userid . " and a.admin_id=" . $adminid;
$sql2 = "update " . Chat_Box . " set isview=1
where entry_type='user' and user_id=" . $userid . " and admin_id=" . $adminid;

$result = $mysql->getResult($sql);
$mysql->query($sql2);
$msgid = '';
$usertimezone = $member->timezone();
$usernamee=$member->getUserName();

?>

<!-- CHAT -->
<div class="col-lg-10">
    <div class="card-box">
        <h4 class="m-t-0 m-b-20 header-title"><b>Chat</b><span style="float: right"> <select name="days" id="days" onchange="okok();">
            <option <?php echo (($daysold == 7) ? 'selected="selected"' : '')?> value="7">7 Days</option>
            <option <?php echo (($daysold == 14) ? 'selected="selected"' : '')?> value="14">14 Days</option>
            <option <?php echo (($daysold == 30) ? 'selected="selected"' : '')?> value="30">30 Days</option>
              <option <?php echo (($daysold == 1000) ? 'selected="selected"' : '')?> value="1000">All Days</option>
        </select></span></h4>

        <div class="chat-conversation">
            <ul class="conversation-list nicescroll">
                <?php
                if ($result['COUNT']) {
                    foreach ($result['RESULT'] as $row) {

                         $msgid.=$row['a_id'] . ',';
                        // set date and time according to time zone of the user
                        // echo $admin->timezone();
                       // $usertimezone = $member->timezone();
                       // $usernamee=$member->getUserName();
                        $dtDateTime = new DateTime($row['time_stamp'], new DateTimeZone($admin->timezone()));
                        $dtDateTime->setTimezone(new DateTimeZone($member->timezone()));
                        $finaldate = $dtDateTime->format('l  \, F j\, Y h:i A');



                        if ($row['isadmin'] == 0) {
                            ?>


                            <li class="clearfix">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="<?php echo $finaldate; ?>">
                                    <img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="male">
                                    <i></i>
                                </div>
                                <div class="conversation-text">
                                    <div class="ctext-wrap">
                                        <i><?php echo $mysql->prints($row['adminname']); ?></i>
                                        <p>
                                            <?php echo $mysql->prints($row['msg']); ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php
                        } else {
                            $userimg = $row['img'];
                            if($userimg=='')
                                $userimg=CONFIG_PATH_PANEL.'assets/images/users/avatar-2.jpg';
                            else
                                 $userimg=CONFIG_PATH_SITE.'images/'.$row['img'];
                            $usernamee = $row['username'];
                            ?>



                            <li class="clearfix odd">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="<?php echo $finaldate; ?>">
                                    
                                    
                                      <?php if($row['img']!='') {?>
                                    
                                    <img src="<?php echo CONFIG_PATH_SITE.'images/'.$row['img']; ?>" alt="User">
                                  <?php } else {
                                      
                                      ?>
                                    <img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-2.jpg" alt="User">
                                  <?php
                                    }
                                    ?>
                                    
                                    
                                    <i></i>
                                </div>
                                <div class="conversation-text">
                                    <div class="ctext-wrap">
                                        <i><?php echo $mysql->prints($row['username']); ?></i>
                                        <p>
                                            <?php echo $mysql->prints($row['msg']); ?>
                                        </p>
                                    </div>
                                </div>
                            </li>


                            <?php
                        }
                    }
                    
                    
                     $msgid = rtrim($msgid, ',');
    // make the isread yes to all $msgids

    $sql = 'update ' . Chat_Box . ' set isview=1
where a_id in (' . $msgid . ')';
    //echo $sql;
    $mysql->query($sql);
    echo $chat_converstaion;
                    
                    
                } else
                    //echo 'chat is empty';
                ?>


            </ul>
            <div class="row">
                <div class="col-sm-9 chat-inputbar">
                    <input type="text" class="form-control chat-input" placeholder="Enter your text">
                </div>
                <div class="col-sm-3 chat-sendd">
                    <button type="submit" class="btn btn-md btn-info btn-block waves-effect waves-light" onclick="addMSG();">Send</button>
                    <!--button type="" class="btn btn-md btn-info btn-block waves-effect waves-light" onclick="scrolldown();">Down</button-->
                </div>
            </div>
        </div>
    </div>

</div>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/moment.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/moment-timezone-with-data.js"></script>
<script type="text/javascript">
                        setPathsMember('', '<?php echo CONFIG_PATH_SITE_USER; ?>');
                        
                        
                         $(document).ready(function () {
                            //onsole.log( "ready!" );
                            scrolldown();
                            $('.chat-input').focus();
                        });
                        $('.chat-input').keypress(function (event) {
                            if (event.which == 13) {
                                addMSG();
                            }
                            // event.preventDefault();
                        });
                        
                        function scrolldown()
                        {
                            $('.conversation-list').scrollTo('100%', '100%', {
                                easing: 'swing'
                            });
                        }


                        setInterval(getMSG, 3000); //300000 MS == 5 minutes

                        function getMSG()
                        {
                            var adminid = '<?php echo $adminid; ?>';
                            var userid = '<?php echo $userid; ?>';

                            $.ajax({
                                type: "POST",
                                url: config_path_site_member + "chat_get.do",
                                //data:,
                                data: "&a_id=" + adminid + "&u_id=" + userid,
                                
                                success: function (msg) {
                                    //alert(msg);
                                    //$('.conversation-list').append(msg);
                                    $('.conversation-list').append(msg);
                                    //$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
                                    //$('.progress-bar-sync').css("width", '25%');
                                    //apiSync(2, id)
                                    if (msg != '')
                                    {
                                        $('.conversation-list').scrollTo('100%', '100%', {
                                            easing: 'swing'
                                        });
                                    }

                                }
                            });

                        }
                        function addMSG()
                        {
                            var chatText = $('.chat-input').val();
                            var userid = '<?php echo $userid; ?>';
                             var adminid = '<?php echo $adminid; ?>';
                            //var d = new Date();
                            //.var n = d.getTime();
                            //var chatTime = n.format("h:mm");
                            //console.log(chatText);
                            //saves chat entry - You should send ajax call to server in order to save chat enrty
                            if (chatText == "") {
                                sweetAlert("Oops...", "You forgot to enter your chat message", "error");
                                $('.chat-input').focus();
                            } else {
                                
                                
                                var ctime = moment().format();
                                var czone = moment.tz.guess();
                                var a = moment.tz(ctime, czone);
                                var timenow = a.tz("<?php echo $usertimezone; ?>").format('LLLL');
                                
                                
                                
                                $('<li class="clearfix odd"><div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' + timenow + '"><img src="<?php echo $userimg; ?>" alt="male"><i></i></div><div class="conversation-text"><div class="ctext-wrap"><i><?php echo $adminname; ?></i><p>' + chatText + '</p></div></div></li>').appendTo('.conversation-list');
                                $('.chat-input').val('');
                                $('.chat-input').focus();
                                $('.conversation-list').scrollTo('100%', '100%', {
                                    easing: 'swing'
                                });

                                $.ajax({
                                    type: "POST",
                                    url: config_path_site_member + "chat_add.do",
                                    data: "&u_id=" + userid + "&msg=" + chatText+"&a_id=" + adminid,
                                    error: function () {
                                        alert("Some Error Occur");
                                    },
                                    success: function (msg) {
                                    }
                                });
                            }
                        }
                        
                        function okok()
                        {
                            var days=$('#days option:selected').val();
                            //alert('hyy');
                             var url = config_path_site_member + "chat.html?adminid=<?php echo $adminid;?>&days="+days;
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
    }




</script>