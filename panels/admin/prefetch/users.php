<?php

$username = $request->GetStr("username");
$group_id = $request->GetInt("group_id");
$email = $request->GetStr("email");
$firstC = $request->GetStr('firstC');
$limit = $request->getInt('limit');
$offset = $request->getInt('offset');
$user_id = $request->getInt('user_id');
if ($limit == 0) {
    $limit = 60;
}

$getString = "";
if ($firstC != '') {
    $getString .= '&firstC=' . $firstC;
}
if ($limit != 0) {
    $getString .= '&limit=' . $limit;
}
if ($offset != 0) {
    $getString .= '&offset=' . $offset;
}
if ($username != '') {
    $getString .= '&username=' . $username;
}

$getString = trim($getString, '&');

$displaySearch = false;
if ($username != '' or $email != '') {
    $displaySearch = true;
}

$prefix = $suffix = '';
$prefixD = $suffixD = '';
$rate = $rateD = 0;
$sql_curr = 'select * from ' . CURRENCY_MASTER . ' where is_default=1';
$query_curr = $mysql->query($sql_curr);
$rows_curr = $mysql->fetchArray($query_curr);
$prefixD = $rows_curr[0]['prefix'];
$suffixD = $rows_curr[0]['suffix'];
$rateD = $rows_curr[0]['rate'];













$offset = (isset($_GET["offset"])) ? $_GET["offset"] : 0;
$limit = 40; // Default
$paging = new paging();
if (isset($_POST['req_type']) && $_POST['req_type'] != '' && $_POST['req_type'] == 'P') {

    if (isset($_POST['dlist_page_size']) && $_POST['dlist_page_size'] != 0) {
        $limit = ($_POST['dlist_page_size'] > 0 ? $_POST['dlist_page_size'] : $limit);
    } else {
        $limit = ($_POST['page_size'] > 0 ? $_POST['page_size'] : $limit);
    }
} else if (isset($_GET['limit']) && $_GET['limit'] != '') {
    $limit = ($_GET['limit'] > 0 ? $_GET['limit'] : $limit);
} else {
    $limit = ((defined('CONFIG_ORDER_PAGE_SIZE') && CONFIG_ORDER_PAGE_SIZE > 0) ? CONFIG_ORDER_PAGE_SIZE : 40);
}
//$search = $request->getInt('search');
$qLimit = ' limit ' . $offset . ',' . $limit;
$extraURL = '&limit=' . $limit . '&firstC=' . $firstC;
$firstC = $request->getStr('firstC');
$qStr = '';
if ($firstC != "") {
    $qStr .= (($qStr == '') ? '' : ' and ') . ' um.username like"' . $firstC . '%"';
}

if ($username != "") {
    $qStr .= (($qStr == '') ? '' : ' and ') . ' um.username like ' . $mysql->quoteLike($username);
}
if ($email != "") {
    $qStr .= (($qStr == '') ? '' : ' and ') . ' um.email like ' . $mysql->quoteLike($email) . ' or um.first_name like ' . $mysql->quoteLike($email) . ' or um.last_name like ' . $mysql->quoteLike($email);
}
if ($user_id != 0) {
    $qStr .= (($qStr == '') ? '' : ' and ') . ' um.id = ' . $user_id;
}
if ($group_id != '') {
    $qStr .= (($qStr == '') ? '' : ' and ') . ' um.id in(select user_id from ' . USER_GROUP_DETAIL . ' where group_id=' . $mysql->getInt($group_id) . ')';
}
$qStr = ($qStr == '') ? '' : ' where ' . $qStr;
$sql = 'select um.*, rm.id as reseller_id,rm.username as reseller,
				um.last_login_time, date_format(um.last_login_time,"%d %M, %Y") as lastLoginDate,
				cm.countries_iso_code_2, cm.countries_name,
				crm.prefix, crm.suffix, crm.rate
				from ' . USER_MASTER . ' um
				left join ' . USER_MASTER . ' rm on (um.reseller_id = rm.id)
				left join ' . COUNTRY_MASTER . ' cm on (um.country_id = cm.id)
				left join ' . CURRENCY_MASTER . ' crm on (um.currency_id = crm.id)
				' . $qStr . '
				order by um.id';
$query_users = $mysql->query($sql . $qLimit);

$strReturn = "";
$sql = 'select um.id
			from ' . USER_MASTER . ' um
			' . $qStr . '
			order by um.username';
$pCode = $paging->recordsetNav($sql, CONFIG_PATH_SITE_ADMIN . 'users.html', $offset, $limit, $extraURL);
?>