<!doctype html>

<html>

<head>

<meta charset="utf-8">

<title>Untitled Document</title>

</head>



<body>

</body>

</html>

<?php



$sql = 'select * from ' . ADMIN_MASTER . ' where id=' . $mysql->getInt($admin->getUserId());

$query = $mysql->query($sql);

if ($mysql->rowCount($query) == 0) {

    echo '<h1>'. $admin->wordTrans($admin->getUserLang(),'Invalid configuration! Please relogin...').'</h1>';

    exit();

}

$rowCount = $mysql->rowCount($query);

$rows = $mysql->fetchArray($query);

$row = $rows[0];



$reply = $request->getStr('reply');

//  echo $reply;

$msg = '';

switch ($reply) {

    case 'reply_failed_code_empty':

        $msg = $admin->wordTrans($admin->getUserLang(),'Code cannot be empty');

        break;

    case 'reply_success_on':

        $msg = $admin->wordTrans($admin->getUserLang(),'Two Step Verificatoin Enabled');

        break;

    case 'reply_success_off':

        $msg = $admin->wordTrans($admin->getUserLang(),'Two Step Verificatoin Disabled');

        break;

     case 'reply_code_updated':

        $msg = $admin->wordTrans($admin->getUserLang(),'Code Is regenrated successfully');

        break;

    case 'reply_failed':

        $msg = $admin->wordTrans($admin->getUserLang(),'Error');

        break;

  

}

include("_message.php");



?>

<div class="row">

	<div class="col-xs-12">

    	<h4 class="m-b-20">

        	<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_security')); ?>

        </h4>

        <div class="col-xs-10">

        	<div class="text-wrap-1">

                <h3 class="super">

                   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Its easier than you think for someone to steal your password')); ?> 

                </h3>

                <p>

                    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Any of these common actions could put you at risk of having your password stolen:')); ?>

                   

                </p>

                <ul>

                    <li><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Using the same password on more than one site')); ?>

                    </li>

                    <li>

                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Downloading software from the Internet')); ?>

                    </li>

                    <li>

                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Clicking on links in email messages')); ?>

                    </li>

                </ul>

                <p>

                    <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_2-Step Verification can help keep bad guys out, even if they have your password.')); ?>

                </p>

                <?php

                if ($row['two_step_auth'] == 0) {



				// show the code

				// echo 'enable it';exit;

				// first check if user has auth key or not

				$sql = 'select a.id from ' . ADMIN_MASTER . ' a where a.google_auth_key="NA" and a.id=' . $admin->getUserId();

				$query = $mysql->query($sql);

				if ($mysql->rowCount($query) > 0) {

					// generate a new auth key for that user

					require_once 'GoogleAuthenticator.php';

					$ga = new PHPGangsta_GoogleAuthenticator();

					$secret = $ga->createSecret();

					// echo "Secret is: " . $secret . "\n\n";

					$qrCodeUrl = $ga->getQRCodeGoogleUrl('GSMUnion', $secret);

					echo '<img src="' . $qrCodeUrl . '" alt="Mountain View" style="width:atuo;height:auto;float:right;"><br /><br />';



					//echo '<a href=' . CONFIG_PATH_SITE_USER . 'account_details.html class="btn btn-inverse">Go Back To Profile</a><br />';

					// add that auth key to user table



					$sqladd = 'update ' . ADMIN_MASTER . ' set google_auth_key=' . $mysql->quote($secret) . ' where id=' . $admin->getUserId();

					$mysql->query($sqladd);

				} else {

					// get the old auth key and make qr cide

					// $sql = 'select a.id from '.USER_MASTER.' a where a.google_auth_key="NA" and a.id=' . $member->getUserId();

					$sql2 = 'select a.google_auth_key from ' . ADMIN_MASTER . ' a where a.id=' . $admin->getUserId();

					$query = $mysql->query($sql2);

					if ($mysql->rowCount($query) > 0) {

						$rows = $mysql->fetchArray($query);

						$googlekey = $rows[0]['google_auth_key'];



						if ($googlekey != "") {

							require_once 'GoogleAuthenticator.php';

							$ga = new PHPGangsta_GoogleAuthenticator();

							//$secret = $ga->createSecret();

							//  echo "Secret is: " . $googlekey . "\n\n";

							$tempsitename=str_replace(' ','_',CONFIG_SITE_NAME);

							//echo CONFIG_SITE_NAME;

						   // echo $tempsitename;

							$qrCodeUrl = $ga->getQRCodeGoogleUrl($tempsitename, $googlekey);

							echo '<img src="' . $qrCodeUrl . '" alt="Mountain View" style="width:atuo;height:auto;float:right;">';



							$sqladd = 'update ' . ADMIN_MASTER . ' set google_auth_key=' . $mysql->quote($googlekey) . ' where id=' . $admin->getUserId();

							$mysql->query($sqladd);

							//  echo '<a href=' . CONFIG_PATH_SITE_USER . 'account_details.html class="btn btn-inverse">Go Back To Profile</a><br />';

						}

					}

				}

				?>

                <div class="form-group">

                      

				   <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Scan the code with Google Authenticator , Put it in text box and click on ')); ?>

                    

                    <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enable Two Step Verification')); ?> </b>

                    <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>two_step_auth.do" method="post">

                        <br><input type="text" name="code" /><br><br>

                        <input type="hidden" name="type" value="0" />



                        <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enable Two Step Verification')); ?>" class="btn btn-info">

                        <br><br><a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>regen_google_code.html" class="btn btn-success-200"><?php echo $admin->wordTrans($admin->getUserLang(),'Regenerate The Code'); ?></a>

                    </form>

                </div>

                <?php } else { ?>

                <div class="form-group">



                        <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>two_step_auth.do" method="post">



                            <input type="hidden" name="type" value="1" />

    <!--                        <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>regenreate_code.html" class="btn btn-inverse">Regenerate The Verification Code</a>-->

                            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Disable Two Step Verification')); ?>" class="btn btn-danger">

                        </form>

                    </div>



                <?php } ?>

            </div>

        </div>

    </div>

</div>