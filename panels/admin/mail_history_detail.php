<?php

defined("_VALID_ACCESS") or die("Restricted Access");

$mysql = new mysql();

//$idd="";

$e_id = $request->GetStr("e_id");

$u_id= $request->GetStr("u_id");



if($e_id != ""){

	$useremailll="";

    if($u_id!=""){

		$sql2='select a.email from nxt_user_master a where a.id='.$u_id;

		$query22 = $mysql->query($sql2);

		$rowCount2 = $mysql->rowCount($query22);

		if($rowCount2 != 0){

			$rowsss = $mysql->fetchArray($query22);

			$row223 = $rowsss[0];

			$useremailll = $row223['email'];

		}

	}

	//echo $emailid;

?>

	<div class="col-lg-10">

    	<div class="panel m-t-5" style="padding-left:50px;overflow-y: scroll;height: 500px;">

        	<div class="panel-heading">

            	<h3><?php echo $admin->wordTrans($admin->getUserLang(),'Email Detail'); ?></h3>

                <hr>

            </div>

            <?php

            $sql = 'select b.id,b.mail_subject as subject,b.mail_body as content,b.time_stamp date_time from nxt_email_queue b 

where b.mail_to=' . $mysql->quote($useremailll) . ' and b.id=' . $e_id . '

order by b.time_stamp desc';

//echo $sql;exit;

            //  $pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_USER . 'mail_history.html', $offset, $limit, $extraURL);

            //  echo $sql;

            $query = $mysql->query($sql);



            if($mysql->rowCount($query) > 0){

				$rows = $mysql->fetchArray($query);



                //   $result = $mysql->getResult($sql);

                //   if ($result['COUNT']) {

                foreach($rows as $row){

                    echo $row['content'];

                }

            }

            ?>

        </div>

    </div>

<?php } ?>