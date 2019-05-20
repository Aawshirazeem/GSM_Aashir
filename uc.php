<?php

    
    $sql = 'select * from ' . Website_Maintinance . ' a where a.id=1';
	$query = $mysql->query($sql);
	if($mysql->rowCount($query) > 0)
	{
		$rows = $mysql->fetchArray($query);
		$statusweb = $rows[0]['status'];
                $msg=$rows[0]['msg'];
	}
        

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD><TITLE>Site Currently Under Maintenance</TITLE><!-- stout -->
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<STYLE type=text/css>#box {
	FONT-SIZE: 21px; MARGIN-LEFT: auto; WIDTH: 650px; MARGIN-RIGHT: auto; FONT-FAMILY: Arial, Helvetica, sans-serif; BACKGROUND-COLOR: #ffffff
}
#box P {
	PADDING-RIGHT: 15px
}
.clear {
	CLEAR: both; FONT-SIZE: 1px; LINE-HEIGHT: 1px
}
BODY {
	BACKGROUND-COLOR: #000000
}
</STYLE>

<META content="MSHTML 6.00.6002.18494" name=GENERATOR></HEAD>
<BODY>
<DIV id=box><IMG src="<?php echo CONFIG_PATH_SITE; ?>images/top.gif">
<P><IMG style="FLOAT: left" height=460 src="<?php echo CONFIG_PATH_SITE; ?>images/sign.jpg" width=288> </P>
<br /><br /><br />
<p><b><?php echo $msg;?></b></p><br />

<P class=clear></P><IMG
src="<?php echo CONFIG_PATH_SITE; ?>images/bottom.gif"><BR></DIV></BODY></HTML>
