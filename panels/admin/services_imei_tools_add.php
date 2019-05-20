<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<?php
defined("_VALID_ACCESS") or die("Restricted Access");
$validator->formSetAdmin('services_imei_tools_add_53938484h2');
$group_id = $request->getInt('group_id');
?>
<form action="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools_add_process.do" method="post">
    <h3 class="m-b-20 text-center"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_new_imei_unlocking_tool')); ?></h3>
    <div class="row">
        <div class="col-xs-10">
            <h5 class="m-b-20"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_general')); ?></h5>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_imei_service_tool_name')); ?> </label>
                        <input name="tool_name" type="text" class="form-control" id="tool_name" required />
                    </div>
                    <!--                                    <div class="col-sm-6">
                                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_alias')); ?> </label>
                                            <input name="tool_alias" type="text" class="form-control" id="tool_alias" />
                                        </div>-->
                </div>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_imei_service_group')); ?></label>
                <select name="group_id[]" id="group_id" class="" multiple="multiple">
                    <?php
                    $sql = "select * from " . IMEI_GROUP_MASTER;
                    $query = $mysql->query($sql);
                    $strReturn = "";
                    if ($mysql->rowCount($query) > 0) {
                        $rows = $mysql->fetchArray($query);
                        foreach ($rows as $row) {
                            echo '<option ' . (($group_id == $row['id']) ? 'selected="selected"' : '') . ' value="' . $row['id'] . '">' . $row['group_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_credits')); ?></label>
                <div class="row">
                    <?php
                    $sql_curr = 'select * from ' . CURRENCY_MASTER . ' where `status`=1 order by is_default DESC';
                    $currencies = $mysql->getResult($sql_curr);
                    foreach ($currencies['RESULT'] as $currency) {
                        ?>
                        <div class="col-sm-3">
                            <input type="hidden" name="currency_id[]" value="<?php echo $currency['id']; ?>" />
                            <div class="alert <?php echo (($currency['is_default'] == 1) ? 'alert-success' : 'alert-info') ?>">
                                <label><?php echo $currency['currency']; ?></label>
                                <div class="form-group">
                                    <input onblur="calculaterate(this);" name="amount_<?php echo $currency['id']; ?>" id="amount_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_selling_price')); ?>" required />
                                </div>
                                <input onblur="calculaterate2(this);" name="amount_purchase_<?php echo $currency['id']; ?>" id="amount_purchase_<?php echo $currency['id']; ?>" type="text" class="form-control" placeholder="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_purchase_price')); ?>" />
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_delivery_time')); ?></label>
                <input name="delivery_time" type="text" class="form-control" id="delivery_time" />
<!--                <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_e.g._instant_or_1-2_days')); ?></p>-->
            </div>
            <!--            <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_verification')); ?></label>
                            <label class="c-input c-radio">
                                    <input type="radio" name="verification" value="1" checked="checked">
                                <span class="c-indicator c-indicator-default"></span>
                                <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_yes')); ?> </span>
                            </label>
                            <label class="c-input c-radio">
                                    <input type="radio" name="verification" value="0">
                                <span class="c-indicator c-indicator-default"></span>
                                <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_no')); ?> </span>
                            </label>
                            
                            <p class="help-block"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_if_no_user_canot_send_verify_the_code')); ?></p>
                        </div>-->
            <!--            <div class="form-group">
                            <label><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_status')); ?> <br></label>
                            <label class="c-input c-radio">
                                    <input type="radio" name="status" value="1" checked="checked">
                                <span class="c-indicator c-indicator-default"></span>
                                <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_active')); ?> </span>
                            </label>
                            <label class="c-input c-radio">
                                    <input type="radio" name="status" value="0">
                                <span class="c-indicator c-indicator-default"></span>
                                <span class="c-input-text"> <?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_inactive')); ?> </span>
                            </label>
                        </div>-->
            <div class="form-group">
                <input type="submit" value="<?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('lbl_add_unlocking_tool')); ?>" class="btn btn-success" onclick="return checkval();" />
                <a href="<?php echo CONFIG_PATH_SITE_ADMIN; ?>services_imei_tools.html?group_id=<?php echo $group_id; ?>" class="btn btn-danger"><?php echo $admin->wordTrans($admin->getUserLang(), $lang->prints('com_cancel')); ?></a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">

    setPathsAdmin('<?php echo CONFIG_PATH_SITE_ADMIN ?>');

    //document.getElementById('editor1').value = '<?php echo $mainbody; ?>';
function checkval()
{
    //alert($('select#my_multiselect').val)
    var countries = [];
        $.each($("#group_id option:selected"), function(){            
            countries.push($(this).val());
        });
       if(countries.length > 0)
           return true;
       else
       {
           alert('SELECT SERVICE GROUP FIRST');
           return false;
       }
}
    function calculaterate(e) {

        var id = e.id;

        var cur_id = id.substring(7);

        var value = $('#' + id).val();

        //  alert($('#'+id).val());

        // alert(cur_id);



        $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {

            /* data will hold the php array as a javascript object */

            $.each(data, function (key, val) {

                $('#amount_' + val.id).val(val.valuee);

                $('#amount_' + val.id).attr('value', val.valuee);

                $('#amount_' + val.id).html(val.valuee);



                //document.getElementById("amount_" + val.id).html =val.valuee;

                //$('#amount_'+key)

                //    $('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');

            });

        });

    }

    function calculaterate2(e) {

        var id = e.id;

        var cur_id = id.substring(16);

        var value = $('#' + id).val();

        //  alert($('#'+id).val());

        // alert(cur_id);



        $.getJSON(config_path_site_admin + 'service_imei_rate_calcuatios.do', {cur: cur_id, valuee: value}, function (data) {

            /* data will hold the php array as a javascript object */

            $.each(data, function (key, val) {

                $('#amount_purchase_' + val.id).val(val.valuee);

                // $('#amount_' + val.id).attr('value', val.valuee);

                //$('#amount_' + val.id).html(val.valuee);



                //document.getElementById("amount_" + val.id).html =val.valuee;

                //$('#amount_'+key)

                //    $('#chat_panel_data').append('<li id="' + key + '">' + val.first_name + ' ' + val.last_name + ' ' + val.email + ' ' + val.age + '</li>');

            });

        });





//                                                                $.ajax({

//                                                                    type: "POST",

//                                                                    

//                                                                    // url: '<?php echo $url2; ?>',

//                                                                url: config_path_site_admin + "service_imei_rate_calcuatios.do",

//                                                                data: "&curId=" + cur_id + "&curval=" + value,

//                                                                success: function (msg) {

//                                                                  //  alert(msg);

//                                                                    //  $('#uid'+a).css('background', 'yellow');

//                                                                    $('#chat_panel_data').html(msg);

//                                                                }

//                                                            });

    }
</script>
<script>
    $(document).ready(function () {
//$('#group_id').multiselect();
        $('#group_id').multiselect({
            maxHeight: 200,
            buttonWidth: '300px'
        });
    });
</script>