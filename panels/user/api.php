<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$show = $request->GetStr('show');
$reset = $request->GetStr('reset');
$ipreset = $request->GetStr('ipreset');

$api_key = 'xxxx-xxxx-xxxx-xxxx';
if ($reset == 'true') {
    $keyword = new keyword();
    $keyNew = $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4) . '-';
    $keyNew .= $keyword->generate(4);
    $keyNew = strtoupper($keyNew);

    $sql = 'update ' . USER_MASTER . ' set api_key=' . $mysql->quote($keyNew) . ' where id=' . $member->getUserId();
    $mysql->query($sql);
}
if ($show == 'true') {
    $sql = 'select api_key from ' . USER_MASTER . ' where id=' . $member->getUserId();
    $query = $mysql->query($sql);
    $rows = $mysql->fetchArray($query);
    $api_key = $rows[0]['api_key'];
}


if ($ipreset == 'true') {
    $sql1 = 'delete from ' . IP_POOL . ' where id=' . $member->getUserId($id);
    $query = $mysql->query($sql1);
}


$sql1 = 'select ip from ' . IP_POOL . ' where id=' . $member->getUserId($id);
$query = $mysql->query($sql1);
$dataa = $mysql->fetchArray($query);

$textdata = '';
foreach ($dataa as $a) {
    $textdata.=$a['ip'] . "\n";
}
?>
<div class="lock-to-top">

</div>
<div class="clear"></div>



<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_api_details')); ?></div>
        <div class="panel-body">
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_url')); ?></label>
                <input name="username" type="text" class="form-control" id="username" value="<?php echo CONFIG_DOMAIN; ?>" />
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_api_key')); ?></label>
                <h2 class="text-danger"><?php echo $api_key; ?></h2>
                <br/><br/><label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_ip_list')); ?></label><br/>
                <textarea name="ip_pool" class="form-control" id="" rows="5" readonly ><?php echo $textdata; ?></textarea>
            </div>

        </div> <!-- / panel-body -->
        <div class="panel-footer">
            <a href="<?php echo CONFIG_PATH_SITE_USER ?>api.html?show=true&reset=true" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_reset_api_key')); ?></a>
            <a href="<?php echo CONFIG_PATH_SITE_USER ?>api.html?show=true" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_show_api_key')); ?></a>

            <a href="#" class="btn btn-default"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_download_api')); ?></a>
            <a href="<?php echo CONFIG_PATH_SITE_USER ?>api.html?ipreset=true" class="btn btn-success"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_reset_IP_list')); ?></a>

        </div> <!-- / panel-footer -->
    </div> <!-- / panel -->
</div>

<div class="clear"></div>