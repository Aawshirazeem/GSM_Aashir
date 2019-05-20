<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$mysql = new mysql();

$admintimezone = $admin->timezoneofadmin();

$adminname = $admin->getUserName();





// get the admin nick



$sql = 'select a.nname from ' . ADMIN_MASTER . ' a where a.username='.$mysql->quote($adminname);

//echo $sql;exit;

$qrydata = $mysql->query($sql);

if ($mysql->rowCount($qrydata) > 0) {

    $rows = $mysql->fetchArray($qrydata);

    $adminick = $rows[0]['nname'];

}





$username = $request->GetStr('name');

//userid = $request->GetStr("u_id");



$user_id = $request->GetStr('id');

?>

<style>

    tr { cursor: pointer; cursor: hand; }

	.highlight { background-color: #FF9800; color: white }

</style>

<div class="col-lg-4">

	<h4 class="m-b-20">

    	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_List_of_Users')); ?>

    </h4>
	
	<div class="table-responsive">

    <table id="data5" class=" table table-rep-plugin">

        <tr>

            <th colspan="3"><input type="text" id="search2" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Search_Contact')); ?>" class="form-control"></input></th>

          

        </tr>

        <?php

// $sql = 'select * from ' . Vendor . ' order by id';

        $sql = "select a.id,a.username,a.online,last_active_time from " . USER_MASTER . " a where a.`status`=1";

        $sql_onoffuser = 'select a.id,a.username,a.`status`,a.online,a.last_active_time,a.img,(select count(b.a_id) countms from ' . Chat_Box . ' b where b.isview=0 and b.entry_type="admin" and b.user_id=a.id and b.admin_id=' . $admin->getUserId() . ') msgcount,last_active_time from ' . USER_MASTER . ' a where a.`status`=1 order by msgcount desc';

//echo $sql_onoffuser;

//$userlistt = $mysql->getResult($sql_onoffuser);

        $result = $mysql->getResult($sql_onoffuser);

        $i = 0;

        $current_time = time();

        if ($result['COUNT']) {

            foreach ($result['RESULT'] as $row) {

                $timestamp = strtotime($row["last_active_time"]);

                $latest = $current_time - $timestamp;

                $latest = $latest / 60;

                $i++;

                $enocdeis = urlencode($row['id']);

                echo '<tr id="uid' . $row['id'] . '">';

                if ($row['online'] == 1 && $latest <= 15) {

                    echo '<td>' . $graphics->status($row['online']) . '</td>';

                    echo '<td class="msgRow" onclick="delchat(' . $row['id'] . ',\'' . $row['username'] . '\',this);">' . trim($mysql->prints($row['username'])) . '</td>';

                } else {

                    echo '<td>' . $graphics->status(0) . '</td>';

                    echo '<td class="msgRow" onclick="delchat(' . $row['id'] . ',\'' . $row['username'] . '\',this);">' . trim($mysql->prints($row['username'])) . '</td>';

                }

                // echo '<td>' . $graphics->status($row['online']) . '</td>';

                 echo '<td><span class="fa fa-envelope m-r-3"><span class="m-l-5">'.$row['msgcount'].'</span></span></td>';

                // echo '<td>' . $graphics->status($row['online']) . ' ' . $mysql->prints($row['username']) . '</td>';

                //echo '<td>30 Days</td>';

                //echo '<td class="" width=""><a href="' . CONFIG_PATH_SITE_ADMIN . 'chat.html?id=' . $enocdeis. '" target="_blank" class="btn btn-primary btn-sm">Start Chat</a>  <a href="#"  onclick="delchat(' . $row['id'] . ');" class="btn btn-danger btn-sm"><i class="ion-close"></i>Delete Chat</a></td>';

                echo '</tr>';

            }

        } else {

            echo '<tr><td colspan="7" class="no_record">' . $admin->wordTrans($admin->getUserLang(),$lang->get('com_no_record_found')) . '</td></tr>';

        }

        ?>

    </table>
	
	</div>

</div>

<div class="col-lg-6 col-lg-offset-2">

	<h4 class="m-b-20">

    	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Chat_Panel')); ?>

    </h4>

    <div class="col-xs-12 b-1" id="chat_panel_data">Click on user from left side bar to start chat...</div>

</div>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.nicescroll.js"></script>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/jquery.scrollTo.min.js"></script>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/moment.js"></script>

<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/js/moment-timezone-with-data.js"></script>



<script type="text/javascript">

	$("#search2").on("keyup", function(){

		var value = $(this).val();

		value=value.toUpperCase();

        $("table tr").each(function (index){

			if(index !== 0){

				$row = $(this);

				var id = $row.find("td:first,td:nth-child(2)").text().toUpperCase();

				if(id.indexOf(value) !== 0){

					$row.hide();

				}else{

                    $row.show();

                }

            }

        });

    });

</script>

<script type="text/javascript">



//    var sendid='<?php echo $user_id; ?>';

//    var sendname="\'<?php echo $username; ?>\'";

//   // var text = "\"http://example.com\""; 

//    

//    sendid=1;

//    sendname='kashisab';

//    

//    if(sendid!='' && sendname!='')

//        delchat(sendid,sendname);

//    //alert(sendid+sendname);



    var userid = 0;



    $(document).ready(function () {

        //onsole.log( "ready!" );

        setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');

        scrolldown();

        //   $.Notification.notify('custom','top right','Got A MESSAGE', 'New MSG')

        $('.chat-input').focus();

    });

    function scrolldown(){

        $('.conversation-list').scrollTo('100%', '0%', {

            easing: 'swing'

        });

    }



    function scrollup(){

        $('.conversation-list').scrollTo('0%', '0%', {

            easing: 'swing'

        });

    }



    function getMSG(){

        var adminid = '<?php echo $admin->getUserId(); ?>';

        //var userid = "'"<?php echo $userid; ?>'";



        $.ajax({

            type: "POST",

            url: config_path_site_admin + "chat_get.do",

            data: "&a_id=" + adminid + "&u_id=" + userid,

            error: function () {

                //alert("Some Error Occur");

            },

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



    function addMSG(){

        var chatText = $('.chat-input').val();

        var adminid = '<?php echo $admin->getUserId(); ?>';
	
        //var userid = '<?php echo $userid; ?>';

        //alert(adminid);

        //alert(userid);

        //var d = new Date();

        //.var n = d.getTime();

        //var chatTime = n.format("h:mm");

        //console.log(chatText);



        //saves chat entry - You should send ajax call to server in order to save chat enrty

        if (chatText == "") {

            sweetAlert("Oops...", "You forgot to enter your chat message", "error");

            $('.chat-input').focus();

        } else {

            //var id=1;

            var ctime = moment().format();

            var czone = moment.tz.guess();

            var a = moment.tz(ctime, czone);

            var timenow = a.tz("<?php echo $admintimezone; ?>").format('LLLL');

            //var timenow=a.tz(czone).format('LLLL');



            $('<li class="clearfix"><div class="chat-avatar" data-toggle="tooltip" data-placement="bottom" title="' + timenow + '"><img src="<?php echo CONFIG_PATH_PANEL; ?>assets/images/users/avatar-1.jpg" alt="male"><i></i></div><div class="conversation-text"><div class="ctext-wrap"><i><?php echo $adminick; ?></i><p>' + chatText + '</p></div></div></li>').appendTo('.conversation-list');

            $('.chat-input').val('');

            $('.chat-input').focus();

            $('.conversation-list').scrollTo('100%', '100%', {

                easing: 'swing'

            });

            $.ajax({

                type: "POST",
			
                url: config_path_site_admin + "chat_add.do",

                data: "&a_id=" + adminid + "&u_id=" + userid + "&msg=" + chatText,

                error: function () {

                    //alert("Some Error Occur");

                },

                success: function (msg) {



                }

            });

        }

    }









    function enterset(){

        $('#msgmsg').keypress(function (event) {

            if (event.which == 13) {

                addMSG();

                //alert('enter');

            }

            // event.preventDefault();

        });

    }



    setInterval(getMSG, 3000); //300000 MS == 5 minutes



</script>

<script>



    function delchat(a, b, e) {



        //$('#data5')



//alert(e);

        //$(e).css("background-color", "black");

        //   alert(a);

        //alert(e);

        // $('.msgRow').css('background-color','none');

        // $('.msgRow').closest('tr').css('background-color','none');

        //$(e).closest("tr").css('background-color','none');

        //$(e).css('background-color','black');	

        $('#data5 td.highlight').removeClass("highlight");

        $(e).addClass("highlight");



        //var selected = $(this).addClass("highlight");

        //  $("#data tr").removeClass("highlight");

        //if(!selected)

        //      $(this).addClass("highlight");





        var username = '';

        //var userid = '';

        var adminid = <?php echo $admin->getUserId(); ?>;

        userid = a;

        var username = b;

        $("#days").val("7");

        var days = $('#days option:selected').val();

        // alert(days);

        if (days == null)

            days = 7

        //   alert(days);





        $.ajax({

            type: "POST",

            url: config_path_site_admin + "_chat.do",

            data: "&u_id=" + userid + "&a_id=" + username + "&days=" + days,

            

            success: function (msg) {

                //alert(msg);

                //  $('#uid'+a).css('background', 'yellow');

                $('#chat_panel_data').html(msg);

                $('#msgmsg').focus();

                scrolldown();

                enterset();



            }

        });

    }





    function chatgetonchange(a, b, e) {



        //$('#data5')



//alert(e);

        //$(e).css("background-color", "black");

        //   alert(a);

        //alert(e);

        // $('.msgRow').css('background-color','none');

        // $('.msgRow').closest('tr').css('background-color','none');

        //$(e).closest("tr").css('background-color','none');

        //$(e).css('background-color','black');	

        //$('#data5 td.highlight').removeClass("highlight");

        //$(e).addClass("highlight");



        //var selected = $(this).addClass("highlight");

        //  $("#data tr").removeClass("highlight");

        //if(!selected)

        //      $(this).addClass("highlight");





        var username = '';

        //var userid = '';

        var adminid = <?php echo $admin->getUserId(); ?>;

        userid = a;

        var username = b;

        var days = $('#days option:selected').val();

        // alert(days);

        if (days == null)

            days = 7

        //   alert(days);





        $.ajax({

            type: "POST",

            url: config_path_site_admin + "_chat.do",

            data: "&u_id=" + userid + "&a_id=" + username + "&days=" + days,

            error: function () {

               // alert("Some Error Occur");

            },

            success: function (msg) {

                //alert(msg);

                //  $('#uid'+a).css('background', 'yellow');

                $('#chat_panel_data').html(msg);

                $('#msgmsg').focus();

                scrolldown();

                enterset();



            }

        });

    }





</script>