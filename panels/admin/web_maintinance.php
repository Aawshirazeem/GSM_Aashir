<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$statusweb = 0;
$sql = 'select a.`status`,a.msg from ' . Website_Maintinance . ' a where a.id=1';
$query = $mysql->query($sql);
if ($mysql->rowCount($query) > 0) {
    $rows = $mysql->fetchArray($query);
    $statusweb = $rows[0]['status'];
     $cmsg = $rows[0]['msg'];
}
?>
<div class="row">
	<div class="col-md-4 col-xs-12">
    	<div class="form-group">
            <form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>web_maintinance_process.do" method="post">
                <div class="form-group" >
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_website_status')); ?> </label>
                    <select class="form-control" name="web_status" id="type">
                        <option value="1" <?php echo ($statusweb == '1') ? 'selected="selected"' : ''; ?> ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Online')); ?> </option>

                        <option value="0" <?php echo ($statusweb == '0') ? 'selected="selected"' : ''; ?> ><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Offline')); ?> </option>
                    </select>
                </div>
                <div class="form-group" id="row_dim">
                    <label><?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('lbl_Custom_Message_In_Case_of_Offline')); ?> </label>
                    <input type="text" class="form-control" name="msg" id="cmsg" value="<?php echo ($statusweb == '0') ? $cmsg : ''; ?>" />
                </div>             
                <div class="form-group">
                    <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(),$lang->prints('com_update')); ?>" class="btn btn-success btn-sm" />
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(function() {
	if($('#type').val() == '1')
		$('#row_dim').hide(); 
    $('#type').change(function(){
        if($('#type').val() == '0') {
            $('#row_dim').show(); 
        } else {
            $('#row_dim').hide(); 
        } 
    });
});
</script>