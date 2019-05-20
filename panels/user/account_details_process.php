<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}

	$member->checkLogin();
	$member->reject();
	$validator->formValidateUser('user_con_detail_148123ghjg38');

    $username = $request->PostStr('username');
    $first_name = $request->PostStr('first_name');
    $last_name = $request->PostStr('last_name');
    $email = $request->PostStr('email');
    $company = $request->PostStr('company');
    $address = $request->PostStr('address');
    $city = $request->PostStr('city');
//    $language = $request->PostInt('language');
    $timezone = $request->PostInt('timezone');
    $country = $request->PostInt('country');
    $phone = $request->PostStr('phone');
    $mobile = $request->PostStr('mobile');
    
		$dir= getcwd();
               // echo $_SERVER['DOCUMENT_ROOT'];exit;
               // echo $dir;exit;
               //var_dump($_POST);exit;
              //  echo $_FILES["myimage"]["name"];exit;
$pro_img_name=$_FILES["myimage"]["name"];
//echo $pro_img_name;exit;
$qry='';
if($pro_img_name != '')
{
$pathto=$_SERVER['DOCUMENT_ROOT'].CONFIG_PATH_SITE.'images/'.$pro_img_name;
$a=move_uploaded_file($_FILES["myimage"]["tmp_name"],$pathto);
$qry=',img="'.$pro_img_name.'"'; 
}
	$sql = 'update ' . USER_MASTER . '
				set
				first_name=' . $mysql->quote($first_name) . ',
				last_name=' . $mysql->quote($last_name) . ',
				company=' . $mysql->quote($company) . ',
				address=' . $mysql->quote($address) . ',
				city=' . $mysql->quote($city) . ',
				
				timezone_id=' . $mysql->getInt($timezone) . ',
				country_id=' . $mysql->getInt($country) . ',
				phone=' . $mysql->quote($phone) . ',
				mobile=' . $mysql->quote($mobile) . '
                                '.$qry.'   
				where id=' . $mysql->getInt($member->getUserId());
	//echo $sql;exit;
        $mysql->query($sql);
	
	$objEmail = new email();
	$args = array(
					'to' => $email,
					'from' => CONFIG_EMAIL,
					'fromDisplay' => CONFIG_SITE_NAME,
					'user_id' => $member->getUserid(),
					'save' => '1',
					'username' => $username,
					'site_admin' => CONFIG_SITE_NAME
					);
	$objEmail->sendEmailTemplate('user_edit_profile', $args);

        
	header("location:" . CONFIG_PATH_SITE_USER . "account_details.html?reply=" . urlencode('reply_update_detail'));
	exit();
?>