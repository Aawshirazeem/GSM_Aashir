<?php
//defined("_VALID_ACCESS") or die("Restricted Access");
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$mysql = new mysql();

//var_dump($_POST);exit;
$userid = rand();
//$adminid = $request->GetInt("adminn");
$adminid = $request->PostInt('adminn');
$username = $request->PostStr('uname');
$useremail = $request->PostStr('uemail');
$userphone = $request->PostStr('uphone');
$userphone='';
//echo $adminid;exit;

if ($adminid == "") {
    header("location:" . CONFIG_PATH_SITE_CHAT . "index.html?reply=" . urlencode('reply_admin_nos'));
    exit();
}
//$userid = $member->getUserId();
$adminname = $username;
$sql = "select * from dummy a where a.entry_type='user' and   a.user_id=" . $userid;
$sql = 'select a.img from ' . USER_MASTER . ' a where a.id=' . $userid;
echo $sql;
//$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $userimg = $rows[0]['img'];
}

$daysold = $request->GetStr('days');
if ($daysold == '')
    $daysold = 7;


$sql = "select a.*,cast(a.time_stamp as time) msgtime,b.nname adminname,c.username,c.img from " . Chat_Box . " a
inner join " . ADMIN_MASTER . " b
on a.admin_id=b.id
inner join " . USER_MASTER . " c
on a.user_id=c.id
where a.entry_type='user' and cast(a.time_stamp as date) >= cast(DATE_SUB(NOW(), INTERVAL " . $daysold . " DAY) as date) and a.user_id=" . $userid . " and a.admin_id=" . $adminid;
$sql2 = "update " . Chat_Box . " set isview=1
where entry_type='user' and user_id=" . $userid . " and admin_id=" . $adminid;

//$result = $mysql->getResult($sql);
//$mysql->query($sql2);
$msgid = '';
$userimg = CONFIG_PATH_PANEL . 'assets/images/users/avatar-2.jpg';
//$usertimezone = $member->timezone();
//$usernamee=$member->getUserName();
?>

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <?php
//echo $adminid.$username.$useremail.$userphone;
        ?>
        <!-- CHAT -->
        <div class="col-lg-10">
            <div class="card-box">
                <div class="chat-conversation">
                    <ul class="conversation-list nicescroll">
                        <?php
                        if ($result['COUNT']) {
                            
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
                            <button  class="btn btn-md btn-danger btn-block waves-effect waves-light" onclick="delchat();">Chat End</button>

                        </div>
                    </div>
                </div>
            </div>


        </div></div></div>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/init_mmember.js" ></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/moment.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL; ?>assets/js/moment-timezone-with-data.js"></script>
<?php $urladdmsg = CONFIG_PATH_SITE_CHAT . "chat_add.do"; ?>
<?php $urlgetmsg = CONFIG_PATH_SITE_CHAT . "chat_get.do"; ?>
<?php $urldelchat = CONFIG_PATH_SITE_CHAT . "_chat_del.do"; ?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js">
</script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.min.js">
</script>

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
                                     var tz = jstz.determine(); // Determines the time zone of the browser client
                                    var timezone = tz.name();

                                   $.ajax({
                                       type: "POST",
                                       url: "<?php echo $urlgetmsg; ?>",
                                       //data:,
                                       data: "&a_id=" + adminid + "&u_id=" + userid+"&u_tzz=" + timezone,
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
                                   var uname = '<?php echo $username; ?>';
                                   var uemail = '<?php echo $useremail; ?>';
                                   var uphone = '<?php echo $userphone; ?>';
                                   //var d = new Date();
                                   //.var n = d.getTime();
                                   //var chatTime = n.format("h:mm");
                                   //console.log(chatText);
                                   //saves chat entry - You should send ajax call to server in order to save chat enrty
                                   if (chatText == "") {
                                       sweetAlert("Oops...", "You forgot to enter your chat message", "error");
                                       $('.chat-input').focus();
                                   } else {


                                       //  var ctime = moment().format();
                                       //  var czone = moment.tz.guess();
                                       // var a = moment.tz(ctime, czone);
                                       var d = new Date();

                                       //var currentdate = new Date(); 
                                       //var datetime = currentdate.getDate();
                                       var timenow = d.toLocaleString();
                                       
                                       //var d = new Date();
                                      //  var timenow = d.toDateString();



                                       $('<li class="clearfix odd"><div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' + timenow + '"><img src="<?php echo $userimg; ?>" alt="male"><i></i></div><div class="conversation-text"><div class="ctext-wrap"><i><?php echo $adminname; ?></i><p>' + chatText + '</p></div></div></li>').appendTo('.conversation-list');
                                       $('.chat-input').val('');
                                       $('.chat-input').focus();
                                       $('.conversation-list').scrollTo('100%', '100%', {
                                           easing: 'swing'
                                       });

                                       $.ajax({
                                           type: "POST",
                                           url: "<?php echo $urladdmsg; ?>",
                                           data: "&u_id=" + userid + "&msg=" + chatText + "&a_id=" + adminid + "&u_name=" + uname + "&u_email=" + uemail + "&u_phone=" + uphone,
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
                                   var days = $('#days option:selected').val();
                                   //alert('hyy');
                                   var url = config_path_site_member + "chat.html?adminid=<?php echo $adminid; ?>&days=" + days;
                                   if (url) { // require a URL
                                       window.location = url; // redirect
                                   }
                                   return false;
                               }
                               function delchat() {

                                   var userid = '<?php echo $userid; ?>';
                                   var adminid = '<?php echo $adminid; ?>';
                                   // var userid =<?php echo $member->getUserId(); ?>;
                                   //var adminid = '';
                                   // adminid = a;

                                   $.ajax({
                                       type: "POST",
                                       url: "<?php echo $urldelchat; ?>",
                                       data: "&u_id=" + userid + "&a_id=" + adminid,
                                       error: function () {
                                           alert("Some Error Occur");
                                       },
                                       success: function (msg) {
                                           //alert(msg);
                                           window.location.href = '<?php echo CONFIG_PATH_SITE_CHAT; ?>';
                                       }
                                   });
                               }



</script>