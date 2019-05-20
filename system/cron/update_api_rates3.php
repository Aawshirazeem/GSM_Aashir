<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}

$request = new request();
$mysql = new mysql();
$posted = "";
$erroshow = 0;
require_once("api_dhrufusionapi.class.php");
require_once("UnlockBase.class.php");
require_once("api_codedesk.class.php");
require_once('APIUBOX.php');

$sql = 'select a.id,a.tool_name,a.api_rate_sync,b.server_id,a.api_id,a.api_service_id,b.username, b.url,b.`key`, c.credits,c.service_id sidtohit

from  ' . IMEI_TOOL_MASTER . ' a

inner join  ' . API_MASTER . ' b
on a.api_id=b.id

inner join  ' . API_DETAILS . ' c

on a.api_service_id=c.service_id and a.api_id=c.api_id

where a.is_check_rate=1';

//echo $sql;exit;
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
//   $rows = $mysql->fetchArray($query);
    $rows = $mysql->fetchArray($query);

    foreach ($rows as $row) {

        $id = $row['api_id'];
        $tool_id = $row['id'];
        $tool_name = $row['tool_name'];
        $servicetohit = $row['sidtohit'];
        $oldprice = $row['api_rate_sync'];
        $oldprice=  round($oldprice,2);
        $serverrretohit = $row['server_id'];


        if ($serverrretohit == 8 || $serverrretohit == 1) {

            // for gsmunion and dhuru
            $sql = 'select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
            $query = $mysql->query($sql);
            $rowCount = $mysql->rowCount($query);
            if ($rowCount > 0) {

                $rows = $mysql->fetchArray($query);
                $row = $rows[0];
                //    echo '<H2>IMEI Services</H2>';
                $api = new api_dhrufusionapi('JSON', $row['key'], $row['username'], $row['url']);
                $request = $api->action('imeiservicelist');
                if (isset($request['ERROR'])) {

                    if ($erroshow == 1)
                        echo '<br>Dhu and GsmF error:: ' . $request['ERROR'] . '<br>';
                    //var_dump($request['ERROR']);
                } else {

                    $groups = $request['SUCCESS'][0]['LIST'];
                    if (is_array($groups)) {
                        foreach ($groups as $group) {
                            $tools = $group['SERVICES'];
                            //   echo $groupName = '<b>--------' . $group['GROUPNAME'] . '--------</b><br>';

                            foreach ($tools as $tool) {

                                //var_dump($tool);
                                // echo '<br>'.$tool['SERVICEID'].'----'.$servicetohit.'<br>';
                                // echo '<br>'.$tool['CREDIT'].'----'.$oldprice.'<br>';$tool['SERVICENAME']
                                if (trim($tool['SERVICEID']) == $servicetohit) {
                                    if ($oldprice < round($tool['CREDIT'],2)) {
                                        //echo '<br>off this';
                                        // api order off
                                        $sqlup = 'update ' . IMEI_TOOL_MASTER . '
										set 
											api_service_id=0,
                                                                                        api_id=0
										
										where id=' . $tool_id;
                                        //  echo $sqlup;exit;
                                        $mysql->query($sqlup);
                                        // send email to admin as well
                                        $body = '
				<h4>Dear Admin</h4><br>
				<p><b>Service Name:</b>' . $tool_name . '<p><br>
				<p>API is disconnected for this service because your provider has changed</p> 
                                <p>price for you and new price is exceeding the current limit.</p>
                                <p>Please Recheck Price with your Source/Provide and adjust again new price limit</p>
				<p>--------------------------------------------------</p>				
				';

                                        $emailObj = new email();
                                        $email_config = $emailObj->getEmailSettings();
                                        //echo '<pre>'; print_r($email_config);echo '</pre>';

                                        $admin_email = $email_config['admin_email'];
                                        $from_admin = $email_config['system_email'];
                                        $admin_from_disp = $email_config['system_from'];
                                        $support_email = $email_config['support_email'];
                                        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                        $emailObj->setTo($admin_email);
                                        $emailObj->setFrom($from_admin);
                                        $emailObj->setFromDisplay($admin_from_disp);
                                        $emailObj->setSubject("AUTO API DISCONNECTED");
                                        $emailObj->setBody($body);
                                       // $sent = $emailObj->sendMail();
                                         $save = $emailObj->queue();
                                    }
                                }
                                ob_flush();
                            }
                        }
                    }
                }
            }
        } elseif ($serverrretohit == 9) {

            //unlock base
            $sql = 'select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
            $query = $mysql->query($sql);
            $rowCount = $mysql->rowCount($query);
            if ($rowCount > 0) {

                $rows = $mysql->fetchArray($query);
                $row = $rows[0];
                $UnlockBase = new UnlockBase();

                if (!defined("UNLOCKBASE_API_KEY"))
                    define('UNLOCKBASE_API_KEY', '(' . $row['key'] . ')');

                /* Set this value to true if something goes wrong and you want to display error messages */
                if (!defined("UNLOCKBASE_API_DEBUG"))
                    define('UNLOCKBASE_API_DEBUG', true);

                /* This is the url of the api, don't change it */
                if (!defined("UNLOCKBASE_API_URL"))
                    define('UNLOCKBASE_API_URL', 'http://www.unlockbase.com/xml/api/v3');

                if (!defined("UNLOCKBASE_VARIABLE_ERROR"))
                    define('UNLOCKBASE_VARIABLE_ERROR', '_UnlockBaseError');
                if (!defined("UNLOCKBASE_VARIABLE_ARRAY"))
                    define('UNLOCKBASE_VARIABLE_ARRAY', '_UnlockBaseArray');
                if (!defined("UNLOCKBASE_VARIABLE_POINTERS"))
                    define('UNLOCKBASE_VARIABLE_POINTERS', '_UnlockBasePointers');

                /* Check that cURL is installed */
                if (!extension_loaded('curl')) {
                    trigger_error('cURL extension not installed', E_USER_ERROR);
                }

                /*                 * **************************************************** */


                /* Call the API */
                $XML = $UnlockBase->CallAPI('GetTools');

                if (is_string($XML)) {
                    /* Parse the XML stream */
                    $Data = $UnlockBase->ParseXML($XML);

                    if (is_array($Data)) {
                        if (isset($Data['Error'])) {
                            // issue
                            if ($erroshow == 1)
                                echo '<br>unlock base error::' . $Data['Error'] . '<br>';
                        } else {
                            /* Everything works fine */
                            foreach ($Data['Group'] as $Group) {
                                foreach ($Group['Tool'] as $Tool) {

                                    // $Tool['Credits'] and $Tool['ID'] $Tool['Name']

                                    if (trim($Tool['ID']) == $servicetohit) {
                                        if ($oldprice < round($Tool['Credits'],2)) {
                                            //echo '<br>off this';
                                            // api order off
                                            $sqlup = 'update ' . IMEI_TOOL_MASTER . '
										set 
											api_service_id=0,
                                                                                        api_id=0
										
										where id=' . $tool_id;

                                            $mysql->query($sqlup);


                                            // send email to admin as well
                                            $body = '
				<h4>Dear Admin</h4><br>
				<p><b>Service Name:</b>' . $tool_name . '<p><br>
				<p>API is disconnected for this service because your provider has changed</p> 
                                <p>price for you and new price is exceeding the current limit.</p>
                                <p>Please Recheck Price with your Source/Provide and adjust again new price limit</p>
				<p>--------------------------------------------------</p>				
				';

                                            $emailObj = new email();
                                            $email_config = $emailObj->getEmailSettings();
                                            //echo '<pre>'; print_r($email_config);echo '</pre>';

                                            $admin_email = $email_config['admin_email'];
                                            $from_admin = $email_config['system_email'];
                                            $admin_from_disp = $email_config['system_from'];
                                            $support_email = $email_config['support_email'];
                                            $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                            $emailObj->setTo($admin_email);
                                            $emailObj->setFrom($from_admin);
                                            $emailObj->setFromDisplay($admin_from_disp);
                                            $emailObj->setSubject("AUTO API DISCONNECTED");
                                            $emailObj->setBody($body);
                                          //  $sent = $emailObj->sendMail();
                                             $save = $emailObj->queue();
                                        }
                                    }
                                    ob_flush();
                                }
                            }
                        }
                    } else {
                        /* Parsing error */
                    }
                }
            }
        } elseif ($serverrretohit == 12) {

            // codesskk

            $sql = 'select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
            $query = $mysql->query($sql);
            $rowCount = $mysql->rowCount($query);
            if ($rowCount > 0) {

                $rows = $mysql->fetchArray($query);
                $row = $rows[0];
                $api_codedesk = new api_codedesk();
                if (!defined("REQUESTFORMAT"))
                    define("REQUESTFORMAT", "JSON");
                if (!defined("CODESK_URL"))
                    define('CODESK_URL', $row['url']);
                if (!defined("USERNAME"))
                    define("USERNAME", $row['username']);
                if (!defined("API_ACCESS_KEY"))
                    define("API_ACCESS_KEY", $row['key']);


                $request = $api_codedesk->action('SERVICELIST');
                if ($request['RET'] != 0) {
                    if ($erroshow == 1)
                        echo '<br>codesk error::  ' . $request['RET'] . '<br>';
                    //return array('credits' => '-1', 'msg' => 'Can\'t fetch service list now! Contact site admin for more assistance. ' . $request['RET']);
                } else {
                    foreach ($request['PRODUCTS'] as $key => $tool) {

                        //  $tool['IDPROD'] and $tool['PRODPRICE']


                        if (trim($tool['IDPROD']) == $servicetohit) {
                            if ($oldprice < round($tool['PRODPRICE'],2)) {
                                //echo '<br>off this';
                                // api order off
                                $sqlup = 'update ' . IMEI_TOOL_MASTER . '
										set 
											api_service_id=0,
                                                                                        api_id=0
										
										where id=' . $tool_id;

                                $mysql->query($sqlup);

                                // send email to admin as well
                                $body = '
				<h4>Dear Admin</h4><br>
				<p><b>Service Name:</b>' . $tool_name . '<p><br>
				<p>API is disconnected for this service because your provider has changed</p> 
                                <p>price for you and new price is exceeding the current limit.</p>
                                <p>Please Recheck Price with your Source/Provide and adjust again new price limit</p>
				<p>--------------------------------------------------</p>				
				';

                                $emailObj = new email();
                                $email_config = $emailObj->getEmailSettings();
                                //echo '<pre>'; print_r($email_config);echo '</pre>';

                                $admin_email = $email_config['admin_email'];
                                $from_admin = $email_config['system_email'];
                                $admin_from_disp = $email_config['system_from'];
                                $support_email = $email_config['support_email'];
                                $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                $emailObj->setTo($admin_email);
                                $emailObj->setFrom($from_admin);
                                $emailObj->setFromDisplay($admin_from_disp);
                                $emailObj->setSubject("AUTO API DISCONNECTED");
                                $emailObj->setBody($body);
                             //   $sent = $emailObj->sendMail();
                                 $save = $emailObj->queue();
                            }
                        }
                    }
                }
            }
        } elseif ($serverrretohit == 13) {

            // ubox

            $sql = 'select * from ' . API_MASTER . ' where id=' . $mysql->getInt($id) . ' and is_visible=1';
            $query = $mysql->query($sql);
            $rowCount = $mysql->rowCount($query);
            if ($rowCount > 0) {
                $rows = $mysql->fetchArray($query);
                $row = $rows[0];
                $api_ubox = new api_ubox();

                // Put your API Access key here
                if (!defined("UBOX_API_USER"))
                    define('UBOX_API_USER', $row['username']);

                // Put your API Access key here
                if (!defined("UBOX_API_PASSWORD"))
                    define('UBOX_API_PASSWORD', $row['password']);

                // Put your API Access key here
                if (!defined("UBOX_API_KEY"))
                    define('UBOX_API_KEY', $row['key']);



                /* CHANGE TO true FOR DEBUG */
                define('UBOX_API_DEBUG', false);

                //require('ubox/APIUBOX.php');
                //GET THE API SIGN
                $Sign = APIUBOX::GetApiSign(UBOX_API_USER, UBOX_API_PASSWORD, UBOX_API_KEY);

                //FILL THE API PARAMS
                $params = array(
                    'user' => UBOX_API_USER,
                    'sign' => $Sign
                );

                //DO THE API CALL
                $XML = APIUBOX::CallMethod('GetOperations', $params);

                //PARSE RESULT
                if (is_string($XML)) {
                    if (UBOX_API_DEBUG) {
                        echo 'Response XML:<br>', htmlspecialchars($XML), '<br><br>', PHP_EOL;
                    }

                    $isError = APIUBOX::HasError($XML);
                    if ($isError) {
                        if ($erroshow == 1)
                            trigger_error(APIUBOX::GetErrorDescription($XML), E_USER_ERROR);
                    } else {
                        //PROCESS GETAPIVERSION XML RETURNED
                        $doc = new DOMDocument();
                        $loaded = $doc->loadXML($XML);
                        
                        $nodesNames = $doc->getElementsByTagName('name');
                        $nodesIDs = $doc->getElementsByTagName('id');
                        $nodesCredits = $doc->getElementsByTagName('credits');
                        $nodesResolutionTime = $doc->getElementsByTagName('resolutionTime');
                        $i = 0;
                        foreach ($nodesIDs as $nod) {
                            $myID = $nod->nodeValue;
                            $myName = $nodesNames->item($i)->nodeValue;
                            $myCredits = $nodesCredits->item($i)->nodeValue;
                            $myTime = $nodesResolutionTime->item($i)->nodeValue;
                            $i++;


                            if (trim($myID) == $servicetohit) {
                                if ($oldprice < round($myCredits,2)) {
                                    //echo '<br>off this';
                                    // api order off
                                    $sqlup = 'update ' . IMEI_TOOL_MASTER . '
										set 
											api_service_id=0,
                                                                                        api_id=0
										
										where id=' . $tool_id;

                                    $mysql->query($sqlup);

                                    // send email to admin as well
                                    $body = '
				<h4>Dear Admin</h4><br>
				<p><b>Service Name:</b>' . $tool_name . '<p><br>
				<p>API is disconnected for this service because your provider has changed</p> 
                                <p>price for you and new price is exceeding the current limit.</p>
                                <p>Please Recheck Price with your Source/Provide and adjust again new price limit</p>
				<p>--------------------------------------------------</p>				
				';

                                    $emailObj = new email();
                                    $email_config = $emailObj->getEmailSettings();
                                    //echo '<pre>'; print_r($email_config);echo '</pre>';

                                    $admin_email = $email_config['admin_email'];
                                    $from_admin = $email_config['system_email'];
                                    $admin_from_disp = $email_config['system_from'];
                                    $support_email = $email_config['support_email'];
                                    $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

                                    $emailObj->setTo($admin_email);
                                    $emailObj->setFrom($from_admin);
                                    $emailObj->setFromDisplay($admin_from_disp);
                                    $emailObj->setSubject("AUTO API DISCONNECTED");
                                    $emailObj->setBody($body);
                                //    $sent = $emailObj->sendMail();
                                     $save = $emailObj->queue();
                                }
                            }
                        }
                    }
                } else {
                    // return array('credits' => '-1', 'msg' => 'Could not parse the XML stream');
                }
            }
        } else {
            // nothing happens here
        }
    }
}
//var_dump($rows);

echo '<br><br>.................all done................';
exit;
