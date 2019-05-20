<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();
	
	$content = "";
	$ids = "";
	if(isset($_POST['chk']))
	{
		$chks = $_POST['chk'];
		foreach($chks as $chk)
		{
			$ids .= $mysql->getInt($chk) . ',';
		}
	}
	
	$ids = rtrim($ids, ',');
	if($ids == "")
	{
		$content = "Please select an IMEI";
	}
	else
	{
		$sql = 'select im.*, im.id as imeiID,
					DATE_FORMAT(im.date_time, "%d-%b-%Y %k:%i") as dtDateTime,
					DATE_FORMAT(im.reply_date_time, "%d-%b-%Y %k:%i") as dtReplyDateTime,
					tm.tool_name as tool_name, 
					cm.countries_name as country_name, 
					nm.network as network_name,
					mm.model as model_name, 
					bm.brand as brand_name
					from ' . ORDER_IMEI_MASTER . ' im
					left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
					left join ' . COUNTRY_MASTER . ' cm on(im.country_id = cm.id)
					left join ' . IMEI_NETWORK_MASTER . ' nm on(im.network_id = nm.id)
					left join ' . IMEI_MODEL_MASTER . ' mm on(im.model_id = mm.id)
					left join ' . IMEI_BRAND_MASTER . ' bm on(im.brand_id = bm.id)
					where im.id in (' . $ids . ') and user_id=' . $member->getUserId() . ' order by im.id DESC';
		$query = $mysql->query($sql);
		if($mysql->rowCount($query) > 0)
		{
			$rows = $mysql->fetchArray($query);
                     //   echo '<pre>';
                     //   var_dump($rows);
                     //   exit;
                        $counter=1;
			foreach($rows as $row)
			{
                            
   
    
				$content .=$row['imei'] . "  ===> ";
//				switch($row['status'])
//				{
//					case 0:
//					case 1:
//						$content .= 'Status: Pending,' . "\r\n";
//						break;
//					case 2:
//						$content .= 'Status: Available' . "\r\n";
//						$content .= 'Unlock Code:' . base64_decode($row['reply']) . "\r\n";
//						break;
//					case 3:
//						$content .=  'Status: Unavailable' . "\r\n";
//						break;
//				}
				
				//$content .= 'Tool Name: ' . $row['tool_name'] . "\r\n";
                              //  $content .= '===> ';
                               
 
                                $temp=strip_tags(base64_decode($row['reply']));
                                $temp=  str_replace($row['imei'],'',$temp);
                                $temp = preg_replace('/\s+/', ' ', $temp);
                                if($temp == '')
                                {
                                    $temp=strip_tags(base64_decode($row['message']));
                                    
                                }
				$content .=  $temp."\r\n";
                                $counter++;
                               
				//$content .= "****************************************************************";
			 	//$content .= "\r\n";
			}
                        

   
		}
	}
	
	$filename = "imei.txt";
	$download = new download();
	$download->downloadContent($content, $filename);
?>
