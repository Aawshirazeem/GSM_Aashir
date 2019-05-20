<?php
	if(!defined("_VALID_ACCESS"))
	{
		define("_VALID_ACCESS",1);
		require_once("../../_init.php");
	}		
	
	
	$imei = $request->PostStr('imei');
	if($imei == '')
	{
		$imei = $request->GetStr('imei');
	}
	//split IMEI in new line
	$imeis = explode("&#13;&#10;", $imei);
	$txtImeis = "";
	foreach($imeis as $im)
	{
		if(is_numeric($im))
		{
			$txtImeis .= $im . ',';
		}
		else
		{
			if($im != '')
			{
				$graphics->messagebox($im . ': Not a valid IMEI Number!');
			}
		}
	}
	$txtImeis = rtrim($txtImeis,',');
	
	
	
	$Ids = isset($_POST['Ids']) ? $_POST['Ids'] : array();
	$strIds = '';
	foreach($Ids as $id)
	{
		if(isset($_POST['locked_' . $id]))
		{
			$strIds .= $id . ',';
		}
	}
	$strIds = trim($strIds, ',');
	
	
	$file_ext = $request->PostCheck('file_ext');
	if($file_ext == 0)
	{
		$file_ext = $request->GetCheck('file_ext');
	}
	
	
	$search_tool_id = $request->PostInt('search_tool_id');
	if($search_tool_id == 0)
	{
		$search_tool_id = $request->GetInt('search_tool_id');
	}
	
	$supplier_id = $request->PostInt('supplier_id');
	if($supplier_id == 0)
	{
		$supplier_id = $request->GetInt('supplier_id');
	}
	
	$user_id = $request->PostInt('user_id');
	if($user_id == 0)
	{
		$user_id = $request->GetInt('user_id');
	}
	
	$search_user_id = $request->PostInt('search_user_id');
	if($search_user_id == 0)
	{
		$search_user_id = $request->GetInt('search_user_id');
	}
	
	$type = $request->PostStr('type');
	if($type == '')
	{
		$type = $request->GetStr('type');
	}
	$copyTool = $request->PostCheck('copyTool');
	$copyAlias = $request->PostCheck('copyAlias');
	$copyUsername = $request->PostCheck('copyUsername');
	
		
		$qType = '';
		
		switch($type)
		{
			case 'pending':
				$qType = ' im.status=0 ';
				break;
			case 'locked':
				$qType = ' im.status=1 ';
				break;
			case 'avail':
				$qType = ' im.status=2 ';
				break;
			case 'rejected':
				$qType = ' im.status=3 ';
				break;
			case 'verify':
				$qType = ' im.status=2 and im.verify=1 ';
				break;
		}
		echo $txtImeis;
		if(trim($txtImeis) != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.imei in (' . $txtImeis . ') ';
		}
		if($strIds != '')
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.id in (' . $strIds . ')';
		}
		if($supplier_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.supplier_id = ' . $supplier_id;
		}
		if($search_tool_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' im.tool_id = ' . $search_tool_id;
		}
		if($search_user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($search_user_id) ;
		}
		if($user_id != 0)
		{
			$qType .= (($qType != '') ? ' and ' : '') . ' um.id = ' . $mysql->getInt($user_id) ;
		}
		$qType = ($qType == '') ? '' : ' where ' . $qType;
		
		
		
		$sql = 'select imei,
					um.username as username,
					tm.tool_name as tool_name, 
					tm.tool_alias as tool_alias
					from ' . ORDER_IMEI_MASTER . ' im
					left join ' . USER_MASTER . ' um on(im.user_id = um.id)
					left join ' . IMEI_TOOL_MASTER . ' tm on(im.tool_id = tm.id)
					' . $qType . '
					order by im.id DESC';
		$query = $mysql->query($sql);
		$strReturn = "";
		
		$totalRows = $mysql->rowCount($query);
		$content = "";
		if($totalRows > 0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				$content .= $row['imei'];
				$content .= ($copyTool == 1) ? ',' . $row['tool_name'] : '';
				$content .= ($copyAlias == 1) ? ',' . $row['tool_alias'] : '';
				$content .= ($copyUsername == 1) ? ',' . $row['username'] : '';
				$content .= CR;
			}
		}
		$download = new download();
		$download->downloadContent($content, 'imei.' . (($file_ext == 1) ? 'csv' : 'txt'));
	?>	