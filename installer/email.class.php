<?php

class installer_email {

    private $valUserID;
    private $valTo;
    private $valToCC;
    private $valFrom;
    private $valFromDisplay;
    private $valSubject;
    private $valBody;
    private $valPlainBody;

    public function getTo() {

        return $this->valTo;
    }

    public function setTo($val) {

        $this->valTo = trim($val);
    }

    public function getToCC() {

        return $this->valToCC;
    }

    public function setToCC($val) {

        $this->valToCC = trim($val);
    }

    public function getFrom() {

        return $this->valFrom;
    }

    public function setFrom($val) {

        $this->valFrom = trim($val);
    }

    public function getFromDisplay() {

        return $this->valFromDisplay;
    }

    public function setFromDisplay($val) {

        $this->valFromDisplay = trim($val);
    }

    public function getSubject() {

        return $this->valSubject;
    }

    public function setSubject($val) {

        $this->valSubject = trim($val);
    }

    public function getUserID() {

        return $this->valUserID;
    }

    public function setUserID($val) {

        $this->valUserID = trim($val);
    }

    public function getBody() {

        return $this->valBody;
    }

    public function setBody($val) {

        $this->valBody = trim($val);
    }

    public function getPlainBody() {

        return $this->valPlainBody;
    }

    public function setPlainBody($val) {

        $this->valPlainBody = trim($val);
    }

    public function formatMail() {



        return '

					<html>

						<style>

							body{ font-family:Arial, tahoma, verdana; font-size:100%;}

						</style>

						<body>

						' . $this->valBody . '

						</body>

					</html>';
    }

    public function sendMail() {

        /*  $email_type descriptions

          A  =  From IMEI Admin to Users,Suppliers, etc.

          R  =  From user to Admin, after new Registration

         */

        $mailBody = $this->formatMail();



        $test = 1;





        $system_email = '';

        $system_from = '';

        $admin_signature = '';



        $type = '';

        $smtp_port = '';

        $smtp_host = '';

        $smtp_user = '';

        $smtp_pass = '';









        $email_settings = $this->getEmailSettings();





        if (sizeof($email_settings) > 0) {

            $id = $email_settings['id'];

            $support_email = $email_settings['support_email'];

            $system_email = $email_settings['system_email'];

            $system_from = $email_settings['system_from'];

            $admin_signature = $email_settings['admin_signature'];



            $type = $email_settings['type'];

            $smtp_port = $email_settings['smtp_port'];

            $smtp_host = $email_settings['smtp_host'];

            $smtp_user = $email_settings['smtp_user'];

            $smtp_pass = $email_settings['smtp_pass'];
        } else {

            echo 'SMTP configurations not found!';

            return false;
        }



        require_once 'mail/PHPMailerAutoload.php';

        $mail = new PHPMailer();



        try {

            $mail->IsSMTP(); // send via SMTP
            //IsSMTP(); // send via SMTP

            $mail->SMTPDebug = 0;

            $mail->SMTPAuth = true; // turn on SMTP authentication

            $mail->Host = $smtp_host;

            $mail->Port = $smtp_port;


            $mail->Username = $smtp_user;

            $mail->Password = $smtp_pass;
            $mail->SMTPSecure = "tls";


            //$mail->Username = GMAIL_USERNAME; // SMTP username
            //$mail->Password = GMAIL_PASSWORD; // SMTP password
            //$webmaster_email = GMAIL_USERNAME; //Reply to this email ID



            $name = $this->valSubject; // Recipient's name



            $mail->From = $this->valFrom;

            $mail->FromName = $this->valFromDisplay;

            $email = $this->valTo; //Recpeint email address

            $mail->AddAddress($email, $name);

            //$mail->AddReplyTo($webmaster_email,"Webmaster");

            $mail->WordWrap = 50; // set word wrap
            //$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
            //$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment



            $mail->IsHTML(true); // send as HTML

            $mail->Subject = $this->valSubject;

            $mail->Body = $mailBody; //HTML Body



            $mail->AltBody = "Mail from " . $this->valFromDisplay . ' '; //Text Body

            $mail->Send();

            //
        } catch (phpmailerException $e) {
            //echo $e->errorMessage(); //Pretty error messages from PHPMailer
            return false;
        }



        /* 	if(!$mail->Send())

          {

          return false;

          }

          else

          {

          return true;

          } */

        //test
//                            $mail             = new phpmailer();
//                       
//                          $body='testing mail';  
//

//         
//$mail->IsSMTP(); // telling the class to use SMTP
////$mail->Host       = "smtp.gmail.com"; // SMTP server
//$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
//                                           // 1 = errors and messages
//                                           // 2 = messages only
//$mail->SMTPAuth   = true;                  // enable SMTP authentication
//$mail->Host       = "smtp.gmail.com"; // sets the SMTP server
//$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
//$mail->Username = GMAIL_USERNAME; // SMTP usernam
//$mail->Password = GMAIL_PASSWORD; // SMTP password
//

//$mail->From =GMAIL_USERNAME;
// $mail->AddAddress("csexpert111@gmail.com", "Josh Adams");
//

//

////$mail->AddReplyTo("csexpert111@gmail.com","First Last");
//

//$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";
//

//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
// 
////$mail->MsgHTML($body);
////echo $mail->Host;
////exit;
////$mail->to('csexpert111@gmail.com');
////echo $mail->Host;exit;
////$mail->AddAddress($address, "John Doe");
//

////$mail->AddAttachment("images/phpmailer.gif");      // attachment
////$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
//

//if(!$mail->Send()) {
//  echo "Mailer Error: " . $mail->ErrorInfo;
//} else {
//  echo "Message sent!";
//}
//                               // echo $test;
//                                exit;
        //end test
        //}











        return true;
    }

    function saveMail() {

        $mysql = new mysql();





        $sql = 'insert into ' . MAIL_HISTORY . '(user_id, date_time, subject, content, plain_mail) values(

					' . $mysql->getInt($this->valUserID) . ',

					now(),

					' . $mysql->quote($this->valSubject) . ',

					' . $mysql->quote($this->valBody) . ',

					' . $mysql->quote($this->valPlainBody) . '

					)';

        $mysql->query($sql);
    }

    function queue() {

        $mysql = new mysql();



        $sql = 'insert into ' . EMAIL_QUEUE . '(mail_to, mail_from, mail_from_display, mail_subject, mail_body) values(

					' . $mysql->quote($this->valTo) . ',

					' . $mysql->quote($this->valFrom) . ',

					' . $mysql->quote($this->valFromDisplay) . ',

					' . $mysql->quote($this->valSubject) . ',

					' . $mysql->quote($this->valBody) . '

					)';



        $mysql->query($sql);
    }

    public function flush() {

        $mysql = new mysql();

        $sql = 'select * from ' . EMAIL_QUEUE . ' order by id  limit 50';

        $query = $mysql->query($sql);



        $ids = '';

        if ($mysql->rowCount($query)) {

            $rows = $mysql->fetchArray($query);





            foreach ($rows as $row) {

                $ids .= $row['id'] . ',';
            }

            $ids = trim($ids, ',');



            foreach ($rows as $row) {

                $this->setTo($row['mail_to']);

                $this->setToCC($row['mail_to_cc']);

                $this->setFrom($row['mail_from']);

                $this->setFromDisplay($row['mail_from_display']);

                $this->setSubject($row['mail_subject']);

                $this->setBody($row['mail_body']);

                if ($this->sendMail()) {

                    $sql = 'delete from ' . EMAIL_QUEUE . ' where id=' . $row['id'];

                    error_log($sql, 3, CONFIG_PATH_SITE_ABSOLUTE . "/sql_del.log");

                    $mysql->query($sql);
                }
            }
        }

        return true;
    }

    function CheckEmail($Email) {

        return (bool) preg_match('/^[0-9a-z_\\-\\.]+@([0-9a-z][0-9a-z\\-]*[0-9a-z]\\.)+[a-z]{2,}$/i', $Email);
    }

    function sendEmailTemplate($code, $args, $send_mail = false) {

        $email_config = $this->getEmailSettings();

        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);

        try {

            //Skip if demo mode is enabled

            if (defined("DEMO")) {

                return;
            }

            $i = 1;



            foreach ($args as $key => $val) {

                $$key = $val;

                //eval("\${$key} = \"{$val}\";");
            }



            $this->setTo($to);

            if (isset($toCC)) {

                $this->setToCC($toCC);
            }

            $this->setFrom($from);

            $this->setFromDisplay($fromDisplay);

            if (isset($user_id) && isset($save)) {

                $this->setUserID($user_id);
            }



            $mysql = new mysql();

            $site_name = CONFIG_SITE_TITLE;

            $sql = 'select * from ' . EMAIL_TEMPLATES . ' where code=' . stripslashes($mysql->quote($code));

            $query = $mysql->query($sql);

            if ($mysql->rowCount($query) > 0) {

                $rows = $mysql->fetchArray($query);

                $this->setSubject($rows[0]['subject']);



                $email_by_id = $rows[0]['email_by_id'];

                switch ($email_by_id) {

                    case 0:

                        $this->setFrom($email_config['system_email']);

                        break;

                    case 1:

                        $this->setFrom($email_config['admin_email']);

                        break;

                    case 2:

                        $this->setFrom($email_config['system_email']);

                        break;

                    case 3:

                        $this->setFrom($email_config['support_email']);

                        break;
                }

                //$this->setFrom($from);



                $mailbody = $rows[0]['mailbody'];

                foreach ($args as $key => $val) {

                    $mailbody = str_replace('{$' . $key . '}', $val, $mailbody);
                }

                //Please don't chnage the code below



                $this->setPlainBody($mailbody);

                $this->setBody($mailbody);

                if (isset($save)) {

                    $this->setPlainBody($mailbody);

                    $this->saveMail();
                }



                if ($send_mail) {

                    $sent = $this->sendMail();
                }

                $this->queue();

                //echo ($temp);
                //exit();
            }
        } catch (Exception $e) {

            //echo 'Message: ' .$e->getMessage();
        }
    }

    function sendMultiEmailTemplate($code, $argsAll, $send_mail = false) {

        $email_config = $this->getEmailSettings();

        $signatures = "<br /><br /><br /><br />" . nl2br($email_config['admin_signature']);



        try {

            $mysql = new mysql();



            $site_name = CONFIG_SITE_TITLE;

            $sql = 'select * from ' . EMAIL_TEMPLATES . ' where code=' . stripslashes($mysql->quote($code));

            $query = $mysql->query($sql);



            if ($mysql->rowCount($query) > 0) {

                $rows = $mysql->fetchArray($query);

                $sqlQueue = '';

                foreach ($argsAll as $args) {

                    //Skip if demo mode is enabled

                    if (defined("DEMO")) {

                        return;
                    }

                    $i = 1;

                    foreach ($args as $key => $val) {

                        $$key = $val;

                        //eval("\${$key} = \"{$val}\";");
                    }



                    $this->setTo($to);

                    if (isset($toCC)) {


                        $this->setToCC($toCC);
                    }

                    $this->setFrom($from);

                    $this->setFromDisplay($fromDisplay);

                    if (isset($user_id) && isset($save)) {

                        $this->setUserID($user_id);
                    }



                    $email_by_id = $rows[0]['email_by_id'];

                    switch ($email_by_id) {

                        case 0:

                            $this->setFrom($email_config['system_email']);

                            break;

                        case 1:

                            $this->setFrom($email_config['admin_email']);

                            break;

                        case 2:

                            $this->setFrom($email_config['system_email']);

                            break;

                        case 3:

                            $this->setFrom($email_config['support_email']);

                            break;
                    }





                    $this->setSubject($rows[0]['subject']);

                    $mailbody = $rows[0]['mailbody'];

                    //Please don't chnage the code below
                    //eval('$temp = @"' . nl2br($mailbody) . '";');

                    foreach ($args as $key => $val) {

                        $mailbody = str_replace('{$' . $key . '}', $val, $mailbody);
                    }



                    $this->setPlainBody($mailbody);

                    $this->setBody($mailbody);



                    if ($send_mail) {

                        $sent = $this->sendMail();
                    }



                    $sqlQueue .= 'insert

										into ' . EMAIL_QUEUE . '

											(mail_to, mail_to_cc, mail_from, mail_from_display, mail_subject, mail_body)

										values(

										' . $mysql->quote($this->valTo) . ',

										' . $mysql->quote($this->valToCC) . ',

										' . $mysql->quote($this->valFrom) . ',

										' . $mysql->quote($this->valFromDisplay) . ',

										' . $mysql->quote($this->valSubject) . ',

										' . $mysql->quote($this->valBody) . '

										);';
                }

                if ($sqlQueue != '') {

                    $mysql->multi_query($sqlQueue);
                }
            } else {

                error_log('Invalid code: ' . $code, 3, CONFIG_PATH_SITE_ABSOLUTE . "/emailError.log");
            }
        } catch (Exception $e) {

            //echo 'Message: ' .$e->getMessage();
        }
    }

    function getEmailSettings() {

       $con = mysqli_connect("sv82.ifastnet.com","gsmunion_upuser","S+OXupg8lqaW","gsmunion_upload");
        $sql = 'select * from nxt_smtp_config limit 1';



      //  $mysql = new mysql();

        $query = $con->query($sql);
       // var_dump($query);exit;
        $settings = '';

        if ($query->num_rows > 0) {

            $rows = mysqli_fetch_assoc($query);

            $settings['id'] = $rows['id'];

            $settings['admin_email'] = $rows['admin_email'];

            $settings['support_email'] = $rows['support_email'];

            $settings['system_email'] = $rows['system_email'];

            $settings['system_from'] = $rows['system_from'];

            $settings['admin_signature'] = $rows['admin_signature'];



            $settings['type'] = $rows['type'];

            $settings['smtp_port'] = $rows['smtp_port'];

            $settings['smtp_host'] = $rows['smtp_host'];

            $settings['smtp_user'] = $rows['username'];

            $settings['smtp_pass'] = $rows['password'];



            return $settings;
        } else {

            return false;
        }
    }

}

?>