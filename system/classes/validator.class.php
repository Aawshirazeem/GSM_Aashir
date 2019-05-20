<?php
class validator
{
    public function formSetAdmin($form_id)
	{
		$admin = new admin();
		$mysql = new mysql();
		$keyword = new keyword();
		$cookie = new cookie();
		
		$key = $keyword->generate(20);
		
		$cookie->setCookie('keyForm',$key);
			
		$sql = 'delete from ' .FORM_VALIDATE .' where form_id=' .$mysql->quote($form_id). ' and admin_id=' .$admin->getUserId();
		$mysql->query($sql);
		
		$sql = 'insert into ' .FORM_VALIDATE .'
			(form_id,form_key, admin_id, date_validate)
			values(
			' .$mysql->quote($form_id). ',
			' .$mysql->quote($key). ', 
			' . $admin->getUserId() . ',
			now())';
		$mysql->query($sql);
	}
	
	public function formValidateAdmin($form_id)
	{
		$cookie = new cookie();
		$mysql = new mysql();
		$keyword = new keyword();
		$admin = new admin();
		
		//echo $cookie->getCookie('keyForm');
		$sql = 'select * from ' .FORM_VALIDATE . 
					' where
						form_id=' . $mysql->quote($form_id) .
					' and form_key=' . $mysql->quote($cookie->getCookie('keyForm')) .
					' and admin_id=' . $mysql->quote($admin->getUserId());
		$query = $mysql->query($sql);
		if($mysql->rowCount($query)>0)
		{
		  $rows = $mysql->fetchArray($query);
		  foreach($rows as $row)
		  {
		     if($row['form_id']==$form_id and $row['form_key']==$cookie->getCookie('keyForm') and $row['admin_id']==$admin->getUserId())
			 {
			    
				$cookie->deleteCookie('keyForm');
             }
			 else
			 {
			    header("location:" . CONFIG_PATH_SITE_ADMIN . "log_out.do");
                exit();				
			 }
		  }
		
		        
	    }
    }		
	
	
	
	
     public function formSetSupplier($form_id)
	{
		$supplier = new supplier();
		$mysql = new mysql();
		$keyword = new keyword();
		$cookie = new cookie();
		
		$key = $keyword->generate(20);
		
		$cookie->setCookie('keyForm',$key);
			
		$sql = 'delete from ' .FORM_VALIDATE .' where form_id=' .$mysql->quote($form_id). ' and supplier_id=' .$supplier->getUserId();
		$mysql->query($sql);
		
		$sql = 'insert into ' .FORM_VALIDATE .'
			(form_id,form_key, supplier_id, date_validate)
			values(
			' .$mysql->quote($form_id). ',
			' .$mysql->quote($key). ', 
			' . $supplier->getUserId() . ',
			now())';
		$mysql->query($sql);
	}
	
	public function formValidateSupplier($form_id)
	{
		$cookie = new cookie();
		$mysql = new mysql();
		$keyword = new keyword();
		$supplier = new supplier();
		
		//echo $cookie->getCookie('keyForm');
		$sql = 'select * from ' .FORM_VALIDATE . 
					' where
						form_id=' . $mysql->quote($form_id) .
					' and form_key=' . $mysql->quote($cookie->getCookie('keyForm')) .
					' and admin_id=' . $mysql->quote($supplier->getUserId());
		$query = $mysql->query($sql);
		if($mysql->rowCount($query)>0)
		{
		  $rows = $mysql->fetchArray($query);
		  foreach($rows as $row)
		  {
		     if($row['form_id']==$form_id and $row['form_key']==$cookie->getCookie('keyForm') and $row['supplier_id']==$supplier->getUserId())
			 {
			    
				$cookie->deleteCookie('keyForm');
             }
			 else
			 {
			    header("location:" . CONFIG_PATH_SITE_ADMIN . "log_out.do");
                exit();				
			 }
		  }
		
		        
	    }
    }

   public function formSetUser($form_id)
	{
		$member = new member();
		$mysql = new mysql();
		$keyword = new keyword();
		$cookie = new cookie();
		
		$key = $keyword->generate(20);
		
		$cookie->setCookie('keyForm',$key);
			
		$sql = 'delete from ' .FORM_VALIDATE .' where form_id=' .$mysql->quote($form_id). ' and user_id=' .$member->getUserId();
		$mysql->query($sql);
		
		$sql = 'insert into ' .FORM_VALIDATE .'
			(form_id,form_key, user_id, date_validate)
			values(
			' .$mysql->quote($form_id). ',
			' .$mysql->quote($key). ', 
			' . $member->getUserId() . ',
			now())';
		$mysql->query($sql);
	}
	
	public function formValidateUser($form_id)
	{
		$cookie = new cookie();
		$mysql = new mysql();
		$keyword = new keyword();
		$member = new member();
		
		//echo $cookie->getCookie('keyForm');
		$sql = 'select *
						from ' .FORM_VALIDATE . '
					where form_id=' . $mysql->quote($form_id) . '
						and form_key=' . $mysql->quote($cookie->getCookie('keyForm')) . '
						and user_id=' . $mysql->quote($member->getUserId());
		$query = $mysql->query($sql);
		if($mysql->rowCount($query)>0)
		{
			$rows = $mysql->fetchArray($query);
			foreach($rows as $row)
			{
				if($row['form_id']==$form_id and $row['form_key']==$cookie->getCookie('keyForm') and $row['user_id']==$member->getUserId())
				{
					$cookie->deleteCookie('keyForm');
				}
				else
				{
					header("location:" . CONFIG_PATH_SITE_ADMIN . "log_out.do");
					exit();				
				}
			}
	    }
    }			
	
	
	
	
	
	public function isValidURL($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
	
	public function goOffline($errorMsg, $Type)
	{
		die($errorMsg . " : " . $Type);
	}
	
	public function getStr($val)
	{
		return htmlentities(stripslashes($val));
	}
	
	public function getInt($val)
	{
		return intval($val);
	}
	public function getFloat($val)
	{
		return floatVal($val);
	}
	public function getBool($val)
	{
		return (($val == 1) ? 1 : 0);
	}

}
?>