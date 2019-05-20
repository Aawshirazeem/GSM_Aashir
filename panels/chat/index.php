<?php
if (!defined("_VALID_ACCESS")) {
    define("_VALID_ACCESS", 1);
    require_once("../../_init.php");
}
$mysql = new mysql();
$sql = "select a.id,a.nname,a.online,(select count(b.a_id) countms from " . Chat_Box . " b 
                where b.isview=0
                    and b.entry_type='user' 
                        and b.admin_id=a.id
                                            and b.user_id=" . $member->getUserId() . ") msgcount from " . ADMIN_MASTER . " a where a.`status`=1";
//echo $sql;exit;
$sql = 'select a.id,a.nname,a.`status`,a.online from ' . ADMIN_MASTER . ' a where a.`status`=1 and a.online=1';
$result = $mysql->getResult($sql);
//  $i=0;
$a = 0;
if ($result['COUNT']) {
    $a = 1;
    $query_admins = $mysql->query($sql);
    $rows_admins = $mysql->fetchArray($query_admins);
}
?>


<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="wrapper-page signup-signin-page">
                    <div class="card-box">
                        <div class="panel-heading">
                            <h3 class="text-center"> Welcome to <strong class="text-custom"><?php echo CONFIG_SITE_NAME;?></strong></h3>
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="p-20">
                                        <h4>If You Are An <b>Existing</b> Customer</h4>
                                        <a href="<?php echo CONFIG_PATH_SITE_USER;?>"  class="btn btn-inverse lg-side">Sign In</a>
                                        <h4>If Not Then Go To <b>Live Chat</b></h4>
                                    </div>
                                </div>

                                <?php
                                if ($a == 1) {
                                    ?>

                                    <div class="col-lg-6">
                                        <div class="p-20">
                                            <h4><b>Live Chat</b></h4>
                                            <form class="form-horizontal m-t-20" action="<?php echo CONFIG_PATH_SITE_CHAT; ?>chat_3.html" method="post">



                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <input class="form-control" type="text" required="" name="uname" placeholder="Your Good Name">
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <div class="col-xs-12">
                                                        <input class="form-control" type="email" required="" name="uemail" placeholder="Your Email">
                                                    </div>
                                                </div>

<!--                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <input class="form-control" type="text" required="" name="uphone" placeholder="Your Phone">
                                                    </div>
                                                </div>-->

                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <select name="adminn" class="form-control" id="adminn">
                                                            <option value="">Select Admin To Chat With</option>
                                                            <?php
                                                            foreach ($rows_admins as $row_timezone) {
                                                                echo '<option ' . (($row_timezone['id'] == $row['timezone_id']) ? 'selected="selected"' : '') . ' value="' . $row_timezone['id'] . '">' . $mysql->prints($row_timezone['nname']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>





                                                <div class="form-group text-center m-t-20 m-b-0">
                                                    <div class="col-xs-12">
                                                        <button class="btn btn-pink text-uppercase waves-effect waves-light w-sm" type="submit">
                                                            Go to Chat Panel
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="col-lg-6">
                                        <div class="p-20">
                                            <h4>No <b>Admin</b> Available At This Time.Chat Is <b>Off</b></h4>

                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div></div>