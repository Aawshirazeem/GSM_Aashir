<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined("_VALID_ACCESS") or die("Restricted Access");
$sql = 'select a.currency,a.prefix from  ' . CURRENCY_MASTER . ' a where a.is_default=1';
$qrydata = $mysql->query($sql);
if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    $defcuridpfx = $rows[0]['currency'];
    $defcuridpfx2 = $rows[0]['prefix'];
}
// weekly rpt
//$months = array("MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY", "SUNDAY");
$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

$sql = "select sum(okk.JANUARY) JANUARY ,
sum(okk.FEBRUARY) FEBRUARY ,
sum(okk.MARCH) MARCH ,
sum(okk.APRIL) APRIL ,
sum(okk.MAY) MAY ,
sum(okk.JUNE) JUNE ,
sum(okk.JULY) JULY ,
sum(okk.AUGUST) AUGUST ,
sum(okk.SEPTEMBER) SEPTEMBER ,
sum(okk.OCTOBER) OCTOBER ,
sum(okk.NOVEMBER) NOVEMBER ,
sum(okk.DECEMBER) DECEMBER 


from(

select(
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=1) as JANUARY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=2) as FEBRUARY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=3) as MARCH
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=4) as APRIL
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=5) as MAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=6) as JUNE
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=7) as JULY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=8) as AUGUST
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=9) as SEPTEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=10) as OCTOBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=11) as NOVEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now()) and month(a.date_time)=12) as DECEMBER
union 
select(
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=1) as JANUARY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=2) as FEBRUARY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=3) as MARCH
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=4) as APRIL
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=5) as MAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=6) as JUNE
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=7) as JULY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=8) as AUGUST
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=9) as SEPTEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=10) as OCTOBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=11) as NOVEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=12) as DECEMBER




union
select(
select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=1) as JANUARY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=2) as FEBRUARY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=3) as MARCH
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=4) as APRIL
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=5) as MAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=6) as JUNE
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=7) as JULY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=8) as AUGUST
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=9) as SEPTEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=10) as OCTOBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=11) as NOVEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=12) as DECEMBER


union 
select(
select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=1) as JANUARY,
(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=2) as FEBRUARY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=3) as MARCH
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=4) as APRIL
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=5) as MAY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=6) as JUNE
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=7) as JULY
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=8) as AUGUST
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=9) as SEPTEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=10) as OCTOBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=11) as NOVEMBER
,(
select  ifnull(sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and year(a.date_time)=year(now()) and month(a.date_time)=12) as DECEMBER
) okk";


$sql = "select sum(okk.prfit) Profit,okk.dayy from(

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.date_time) as dayy
from nxt_order_imei_master a inner join 
nxt_imei_tool_master tm on tm.id=a.tool_id 
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=2 and year(a.date_time)=year(now())

group by month(a.date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.date_time) as dayy
from nxt_order_server_log_master a inner join 
nxt_server_log_master tm on tm.id=a.server_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1 and month(a.date_time)=month(now())
and year(a.date_time)=year(now())
group by month(a.date_time)

union all

select ifnull( sum(if(cm.is_default=1,round((a.credits- a.b_rate),2),round((a.credits- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.date_time) as dayy
from nxt_order_file_service_master a inner join 
nxt_file_service_master tm on tm.id=a.file_service_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1
and year(a.date_time)=year(now())
group by month(a.date_time)


union all

select ifnull( sum(if(cm.is_default=1,round((a.credit- a.b_rate),2),round((a.credit- a.b_rate)/ cm.rate ,2))),0) as prfit,
monthname(a.date_time) as dayy
from nxt_prepaid_log_un_master a inner join 
nxt_prepaid_log_group_master tm on tm.id=a.prepaid_log_id
inner join nxt_user_master um on um.id=a.user_id 
inner join nxt_currency_master cm on cm.id=um.currency_id 
where a.`status`=1
and year(a.date_time)=year(now())
group by month(a.date_time)

) okk

group by okk.dayy";
$qrydata = $mysql->query($sql);
$IMEIvaluessum = array();

if ($mysql->rowCount($qrydata) > 0) {
    $rows = $mysql->fetchArray($qrydata);
    // var_dump($rows);
    foreach ($rows as $rowdata) {
        //     echo $rowdata['dayy'];
        foreach ($months as $row) {
            // echo $rows[0][$row]['dayy'];
            //   echo $row;
            // var_dump($rowdata);
            if ($rowdata['dayy'] == $row && $IMEIvaluessum[$row] == "") {
                if ($rowdata['Profit'] < 0)
                    $IMEIvaluessum[$row] = 0;
                else
                    $IMEIvaluessum[$row] = $rowdata['Profit'];
            }

            else {
                if ($IMEIvaluessum[$row] == "")
                    $IMEIvaluessum[$row] = 0;
            }
        }
    }
}
//$datetime = DateTime::createFromFormat('YmdHi',getdate());
//echo $datetime->format('D');
//echo 'Todays Profit:' . $d_Prfit . ' ' . $defcuridpfx2;
?>

<div class="row">

    <div class="col-sm-10">
        <label>  Monthly Profit Report :: <?php echo date('F Y');?></label>
        <canvas id="lineChart" height="300" width="650"></canvas>
    </div>

</div>   

<!--<script src="<?php echo CONFIG_PATH_PANEL_ADMIN; ?>assets/plugins/Chart.js/Chart.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.js"></script>
<script>
    var LineChart = {
        //labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
        labels: <?= json_encode($months); ?>,
        datasets: [
            {
                fillColor: "rgba(93, 156, 236,0.5)",
                strokeColor: "rgba(93, 156, 236,1)",
                pointColor: "rgba(93, 156, 236,1)",
                pointStrokeColor: "#fff",
                data: <?= json_encode(array_values($IMEIvaluessum)); ?>
            }

        ]
    };
    //console.log(LineChart);
    var ctx2 = document.getElementById("lineChart").getContext("2d");
    var data2 = {
        //  labels: ["January", "February", "March", "April", "May", "June", "July"],
        labels: <?= json_encode($months); ?>,
        datasets: [
            {
                label: "<?php echo $defcuridpfx; ?>",
                fill: true,
                lineTension: 0.1,
                backgroundColor: "rgba(14,50, 21, 1)",
                borderColor: "rgba(14, 23, 21, 1)",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "rgba(14, 23, 21, 1)",
                pointBackgroundColor: "#0275d8",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                pointHoverBorderColor: "rgba(14, 23, 21, 1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: <?= json_encode(array_values($IMEIvaluessum)); ?>
            }

        ]
    };
//        var myLineChart = Chart.Line(ctx, {
//            data: data,
//            options: {
//                responsive: true
//            }
//        });
    // var myLineChart = new Chart(ctx).Line(LineChart);
//        var lineDemo = new Chart(ctx).Line(LineChart, {
//            responsive: true,
//            // pointDotRadius: 3,
//            // bezierCurve: false,
//            scaleShowVerticalLines: false,
//            //scaleGridLineColor: 'black'
//        });
    //var myNewChart = new Chart(ctx).PolarArea(data);


    $(document).ready(function (e) {
        var myLineChart = new Chart(ctx2, {
            type: 'line',
            data: data2,
            options: {
                legend: {display: true, }
            }
        });
    });


</script>
