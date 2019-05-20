<?php
	$data = array();
	$f = array();
	include("./en/api_add.php");
	
	$lang2 = $_POST['lang2'];
	$langArray = explode("\n",  $lang2);
	
	$keys = array_keys($data['lang']);
?>
	<form method="post" action="">
		<textarea rows="5" cols="130" name="eng"><?php
				foreach ( $data['lang'] as $d=>$value )
				{
					echo $value . "\n";
				}
			?></textarea>
		<textarea rows="5" cols="130" name="lang2"><?php echo $lang2;?></textarea>
		<input type="submit" name="submit" value="Submit">
	</form>
				
	<textarea rows="5" cols="130" name="frn"><?php
			$i = 0;
			if(is_array($langArray))
			{
				foreach ( $data['lang'] as $key=>$value )
				{
					echo '$data["lang"]["' . $key . "\"] = \"" . trim($langArray[$i++]) . "\";". "\n";
				}
			}
	?></textarea>