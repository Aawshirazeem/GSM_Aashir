<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$mysql = new mysql();
$admin = new admin();
$adminid = $admin->getUserId();
$userid = $request->GetStr("id");
$userid = urldecode($userid);
// get all the chat history new and old all data
//$sql = "select * from dummy a where a.entry_type='admin' and a.user_id=".$userid;

$sql = "select a.*,cast(a.time_stamp as time) msgtime,b.nname adminname,c.username,c.img from " . Chat_Box . " a
inner join " . ADMIN_MASTER . " b
on a.admin_id=b.id
inner join " . USER_MASTER . " c
on a.user_id=c.id
where a.entry_type='admin' and a.user_id=" . $userid . " and a.admin_id=" . $adminid;
$sql2 = "update " . Chat_Box . " set isview=1
where entry_type='admin' and user_id=" . $userid . " and admin_id=" . $adminid;
//echo $sql2;
$result = $mysql->getResult($sql);
$mysql->query($sql2);
$msgid = '';
$admintimezone = $admin->timezoneofadmin();
$adminname = $admin->getUserName();
$sql = 'select a.nname from ' . ADMIN_MASTER . ' a where a.username='.$mysql->quote($adminname);
//echo $sql;exit;
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $adminick = $rows[0]['nname'];
}
?>
<div class="row m-b-20">
	<div class="col-lg-12">
    	<h4 class="m-b-20">Chat</h4>
        <div class="chat-conversation">
            <ul class="conversation-list nicescroll">
                <?php
                if ($result['COUNT']) {
                    foreach ($result['RESULT'] as $row) {
                        $msgid.=$row['a_id'] . ',';
                        // set date and time according to time zone of the admin
                        // echo $admin->timezone();
                        // $admintimezone = $admin->timezoneofadmin();
                        // $adminname = $admin->getUserName();
                        $dtDateTime = new DateTime($row['time_stamp'], new DateTimeZone($admin->timezone()));
                        $dtDateTime->setTimezone(new DateTimeZone($admin->timezoneofadmin()));
                        $finaldate = $dtDateTime->format('l  \, F j\, Y h:i A');
                        //$dtDateTime = '';
                        if ($row['isadmin'] == 0) {
                            ?>


                            <li class="clearfix" style="">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="<?php echo $finaldate; ?>">
                                    <img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="male">
                                    <i >
                                    </i>
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
                            ?>



                            <li class="clearfix odd" style="">
                                <div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="<?php echo $finaldate; ?>">
                                   
                                  <?php if($row['img']!='') {?>
                                    
                                    <img src="<?php echo CONFIG_PATH_SITE; ?>images/<?php echo $row['img']; ?>" alt="User">
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

                    $sql = 'update ' . Chat_Box . ' set isview=1 where a_id in (' . $msgid . ')';
                    //echo $sql;
                    $mysql->query($sql);
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
                    <!--button type="" class="btn btn-md btn-info btn-block waves-effect waves-light" onclick="scrolldown();">Down</button>
                    <button type="" class="btn btn-md btn-info btn-block waves-effect waves-light" onclick="scrollup();">Up</button-->

                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/moment.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/moment-timezone-with-data.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.nicescroll.js"></script>
<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.scrollTo.min.js"></script>
<script>
$(document).ready(function (){
    // 
    setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');
    imeiOrders();
});
</script>
<script type="text/javascript">
$(window).load(function (){
	//onsole.log( "ready!" );
	$(".nicescroll").niceScroll();
	scrolldown();
	
	//   $.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')
	$('.chat-input').focus();
});

function scrolldown(){
	console.log($('.conversation-list'))
	$('.conversation-list').scrollTo('100%', '0%', {
		easing: 'swing'
	});
}

function scrollup(){
	$('.conversation-list').scrollTo('0%', '0%', {
		easing: 'swing'
	});
}

setInterval(getMSG, 3000); //300000 MS == 5 minutes
function getMSG(){
	var adminid = '<?php echo $admin->getUserId(); ?>';
	var userid = '<?php echo $userid; ?>';
	$.ajax({
		type: "POST",
		url: config_path_site_admin + "chat_get.do",
		data: "&a_id=" + adminid + "&u_id=" + userid,
		error: function () {
			// alert("Some Error Occur");
		},
		success: function (msg) {
			//alert(msg);
			//$('.conversation-list').append(msg);
			$('.conversation-list').append(msg);
			//$('#load_details').scrollTop($('#load_details')[0].scrollHeight);
			//$('.progress-bar-sync').css("width", '25%');
			//apiSync(2, id)
			if (msg != ''){
				$('.conversation-list').scrollTo('100%', '100%', {
					easing: 'swing'
				});
			}
		}
	});
}

function addMSG(){
	var chatTextt = $('.chat-input').val();
	var adminid = '<?php echo $admin->getUserId(); ?>';
	var userid = '<?php echo $userid; ?>';
	//alert(adminid);
	//alert(userid);
	//var d = new Date();
	//.var n = d.getTime();
	//var chatTime = n.format("h:mm");
	//console.log(chatText);
	//saves chat entry - You should send ajax call to server in order to save chat enrty
	if (chatTextt == "") {
		sweetAlert("Oops...", "You forgot to enter your message", "error");
		$('.chat-input').focus();
	} else {
		//var id=1;
		var ctime = moment().format();
		var czone = moment.tz.guess();
		var a = moment.tz(ctime, czone);
		var timenow = a.tz("<?php echo $admintimezone; ?>").format('LLLL');
		//var timenow=a.tz(czone).format('LLLL');
		$('<li class="clearfix"><div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' + timenow + '"><img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="male"><i></i></div><div class="conversation-text"><div class="ctext-wrap"><i><?php echo $adminick; ?></i><p>' + chatTextt + '</p></div></div></li>').appendTo('.conversation-list');
		$('.chat-input').val('');
		$('.chat-input').focus();
		$('.conversation-list').scrollTo('100%', '100%', {
			easing: 'swing'
		});
		$.ajax({
			type: "POST",
			url: config_path_site_admin + "chat_add.do",
			data: "&a_id=" + adminid + "&u_id=" + userid + "&msg=" + chatTextt,
			error: function () {
				//alert("Some Error Occur");
			},
			success: function (msg) {
			}
		});
	}
}

$('.chat-input').keypress(function (event) {
	if (event.which == 13) {
		addMSG();
	}
	// event.preventDefault();
});

$("button").click(function(){
	loadPage();
	<?php //include(CONFIG_PATH_ADMIN_ABSOLUTE . $page . '.php'); ?>
//        $("#div1").load("chat.html?id=3", function(responseTxt, statusTxt, xhr){
//            if(statusTxt == "success")
//                alert("External content loaded successfully!");
//            if(statusTxt == "error")
//                alert("Error: " + xhr.status + ": " + xhr.statusText);
//        });
});
function loadPage(){
	var url='<?php echo $page;?>';
	//url=url.replace('#page','');
	
	//$('#loading').css('visibility','visible');
	//url='chat.html';
	$.ajax({
		type: "POST",
		url: config_path_site_admin+ "load_page.do",
		data: 'page='+url,
		dataType: "html",
		success: function(msg){
			//alert(msg);
			if(parseInt(msg)!=0)
			{
				$('#div1').html(msg);
				//$('#loading').css('visibility','hidden');
			}
		}
		
	});
}
</script>