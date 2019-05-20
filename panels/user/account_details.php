<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetUser('user_con_detail_148123ghjg38');


$reply = $request->getStr('reply');
//  echo $reply;
$msg = '';
switch ($reply) {
    case 'reply_failed_code_empty':
        $msg = 'Code cannot be empty';
        break;
    case 'reply_success_on':
        $msg = 'Two Step Verificatoin Enabled';
        break;
    case 'reply_success_off':
        $msg = 'Two Step Verificatoin Disabled';
        break;
     case 'reply_code_updated':
        $msg = 'Code Is regenrated successfully';
        break;
    case 'reply_failed':
        $msg = 'Error';
        break;
  
}
include("_message.php");
$id = $request->GetInt('id');

$sql = 'select * from ' . USER_MASTER . ' where id=' . $mysql->getInt($member->getUserId());
$query = $mysql->query($sql);
if ($mysql->rowCount($query) == 0) {
    echo '<h1>Invalid configuration! Please relogin...</h1>';
    exit();
}
$rowCount = $mysql->rowCount($query);
$rows = $mysql->fetchArray($query);
$row = $rows[0];
// echo   CONFIG_PATH_SITE."images/".$row['img'];exit;
?>
<form action="<?php echo CONFIG_PATH_SITE_USER; ?>account_details_process.do" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_contact_details')); ?></div>
                <div class="panel-body">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_Picture')); ?></label>
                    <div class="form-group">
                        <?php
                        
                         if ($row['img'] != '') {


                            echo ' <img src="'.CONFIG_PATH_SITE . 'images/' . $row['img'].'" width="250px" height="250px"/>';
                        } else {
                            echo '<img class="media-object img-circle h-100 w-100" src="'.CONFIG_PATH_PANEL.'assets/images/users/avatar-2.jpg">';
                        }
?>
                       
                        <input type="file" name="myimage" value="<?php echo $row['img']; ?>">

                    </div> 
                    <div class="form-group">
                        <label> <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_company')); ?></label>
                        <input name="company" type="text" class="form-control" id="company" value="<?php echo $row['company']; ?>" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_address')); ?></label>
                        <textarea name="address" class="form-control" id="address" rows="4"><?php echo $row['address']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_city')); ?></label>
                        <input name="city" type="text" class="form-control" id="city" value="<?php echo $row['city']; ?>" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_phone')); ?></label>
                        <input name="phone" type="text" class="form-control" id="phone" value="<?php echo $row['phone']; ?>" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_mobile')); ?></label>
                        <input name="mobile" type="text" class="form-control" id="mobile" value="<?php echo $row['mobile']; ?>" />
                    </div>
                   
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_country')); ?></label>
                        <select name="country" class="form-control" id="country">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_select_country')); ?></option>
                            <?php
                            $sql_country = 'select * from ' . COUNTRY_MASTER . ' order by countries_name';
                            $query_country = $mysql->query($sql_country);
                            $rows_country = $mysql->fetchArray($query_country);
                            foreach ($rows_country as $row_country) {
                                echo '<option ' . (($row_country['id'] == $row['country_id']) ? 'selected="selected"' : '') . ' value="' . $row_country['id'] . '">' . $row_country['countries_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group"
                         <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_time_zone')); ?></label>
                        <select name="timezone" class="form-control" id="timezone">
                            <option value=""><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_select_time_zone')); ?></option>
                            <?php
                            $sql_timezone = 'select * from ' . TIMEZONE_MASTER . ' order by timezone';
                            $query_timezone = $mysql->query($sql_timezone);
                            $rows_timezone = $mysql->fetchArray($query_timezone);
                            foreach ($rows_timezone as $row_timezone) {
                                echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['timezone']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div> <!-- / panel-body -->
                <div class="panel-footer">
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_update_details')); ?>" class="btn btn-success" />
                </div> <!-- / panel-footer -->
            </div> <!-- / panel -->
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_details')); ?></div>
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_user_name')); ?></label>
                        <input name="username" type="text" class="form-control" id="username" value="<?php echo $row['username']; ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_created')); ?></label>
                        <input name="account created" type="text" class="form-control" id="username" value="<?php echo date("d-M Y", strtotime($row['creation_date'])); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_registered_email')); ?></label>
                        <input name="email" type="text" class="form-control" id="email" value="<?php echo $row['email']; ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_first_name')); ?></label>
                        <input name="first_name" type="text" class="form-control" id="first_name" value="<?php echo $row['first_name']; ?>" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_last_name')); ?></label>
                        <input name="last_name" type="text" class="form-control" id="last_name" value="<?php echo $row['last_name']; ?>" />
                    </div>




                </div> <!-- / panel-body -->
            </div> <!-- / panel -->
        </div> <!-- / col-lg-6 -->

</form>
<!-- / col-lg-6 -->
<div class="col-md-6" style="">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_account_security')); ?></div>
        <div class="panel-body">
            <div class="maia-col-6">
                <div class="text-wrap-1">
                    <h3 class="super">
                     
                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_It_is_easier_than_you_think_for_someone_to_steal_your_password')); ?>
                    </h3>
                    <p>
                        
                         <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Any_of_these_common_actions_could_put_you_at_risk_of_having_your_password_stolen:')); ?>
                    </p>
                    <ul>
                        <li>
                            <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Using_the_same_password_on_more_than_one_site')); ?>
                        </li>
                        <li>
                               <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Downloading_software_from_the_Internet')); ?>
                        </li>
                        <li>
                               <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Clicking_on_links_in_email_messages')); ?>
                        </li>
                    </ul>
                    <p>
                        
                           <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_2-Step_Verification_can_help_keep_bad_guys_out_even_if_they_have_your_password.')); ?>
                    </p>
                </div><br><br>
                <?php
                if ($row['two_step_auth'] == 0) {

                    // show the code
                    // echo 'enable it';exit;
                    // first check if user has auth key or not
                    $sql = 'select a.id from ' . USER_MASTER . ' a where a.google_auth_key="NA" and a.id=' . $member->getUserId();
                    $query = $mysql->query($sql);
                    if ($mysql->rowCount($query) > 0) {
                        // generate a new auth key for that user
                        require_once 'GoogleAuthenticator.php';
                        $ga = new PHPGangsta_GoogleAuthenticator();
                        $secret = $ga->createSecret();
                        // echo "Secret is: " . $secret . "\n\n";
                        $tempsitename=str_replace(' ','_',CONFIG_SITE_NAME);
                        $qrCodeUrl = $ga->getQRCodeGoogleUrl($tempsitename, $secret);
                        echo '<img src="' . $qrCodeUrl . '" alt="Mountain View" style="width:atuo;height:auto;float:right;"><br /><br />';

                        //echo '<a href=' . CONFIG_PATH_SITE_USER . 'account_details.html class="btn btn-inverse">Go Back To Profile</a><br />';
                        // add that auth key to user table

                        $sqladd = 'update ' . USER_MASTER . ' set google_auth_key=' . $mysql->quote($secret) . ' where id=' . $member->getUserId();
                        $mysql->query($sqladd);
                    } else {
                        // get the old auth key and make qr cide
                        // $sql = 'select a.id from '.USER_MASTER.' a where a.google_auth_key="NA" and a.id=' . $member->getUserId();
                        $sql2 = 'select a.google_auth_key from ' . USER_MASTER . ' a where a.id=' . $member->getUserId();
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
                                $qrCodeUrl = $ga->getQRCodeGoogleUrl($tempsitename, $googlekey);
                                echo '<img src="' . $qrCodeUrl . '" alt="Mountain View" style="width:atuo;height:auto;float:right;">';

                                $sqladd = 'update ' . USER_MASTER . ' set google_auth_key=' . $mysql->quote($googlekey) . ' where id=' . $member->getUserId();
                                $mysql->query($sqladd);
                                //  echo '<a href=' . CONFIG_PATH_SITE_USER . 'account_details.html class="btn btn-inverse">Go Back To Profile</a><br />';
                            }
                        }
                    }
                    ?>

                    <div class="form-group">
                        
                          
                        <?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Scan_the_code_with_Google_Authenticator_Put_it_in_text_box_and_click_on')); ?>
                       
                        
                        <b><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enable_Two_Step_Verification')); ?></b>

                        <form action="<?php echo CONFIG_PATH_SITE_USER; ?>two_step_auth.do" method="post">
                            <br><input type="text" name="code" /><br><br>
                            <input type="hidden" name="type" value="0" />
             
                            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Enable_Two_Step_Verification')); ?>" class="btn btn-info">
                            <br><br><a href="<?php echo CONFIG_PATH_SITE_USER; ?>regen_google_code.html" class="btn btn-inverse"><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Regenerate_The_Code')); ?></a>
                        </form>
                    </div>
                <?php } else { ?> <br><br>
                    <div class="form-group">

                        <form action="<?php echo CONFIG_PATH_SITE_USER; ?>two_step_auth.do" method="post">

                            <input type="hidden" name="type" value="1" />
    <!--                        <a href="<?php echo CONFIG_PATH_SITE_USER; ?>regenreate_code.html" class="btn btn-inverse">Regenerate The Verification Code</a>-->
                            <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Disable_Two_Step_Verification')); ?>" class="btn btn-danger">
                        </form>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div> <!-- / row -->

