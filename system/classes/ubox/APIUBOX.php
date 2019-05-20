<?php

	require('nusoap/nusoap.php');

       //The URL 'http://codes.ath.cx/UBoxAPI.asmx?WSDL' is valid for nusoap_client() calls.
       //The URL of the service is 'http://codes.ath.cx/UBoxAPI.asmx'.
       define('UBOX_API_URL', 'http://codes.ath.cx/UBoxAPI.asmx?WSDL');	
		
	if (! extension_loaded('curl'))
	{
		trigger_error('cURL extension not installed', E_USER_ERROR);
	}	
	
	class APIUBOX
	{		
				
		public static function GetApiSign($User, $Pass, $Key)
		{
			$Sign = hash('sha512',  $User.$Pass.$Key );
			return $Sign;
		}
		
		public static function CallMethod ( $Action, $Parameters = array())
		{			
			if (is_string($Action))
			{
				if (is_array($Parameters))
				{
					$client = new nusoap_client(UBOX_API_URL, 'WSDL');
					$client->soap_defencoding = 'utf-8';
					$client->decode_utf8 = false;
					$client->xml_encoding = 'utf-8';					

					$Data = $client->call($Action, $Parameters);
					
					if(is_array($Data))
					{
						$Data = join($Data);
					}
					return $Data;
				}
				
				return false;
			}
			else 
			{
				trigger_error('Action must be a string', E_USER_WARNING);
			}			
			return false;
		}
		
		public static function HasError ($XML)
		{
			$doc = new DOMDocument();
			$loaded = $doc->loadXML($XML);
			if(!$loaded)
				print('Could not load the XML');
				
			$errors = $doc->getElementsByTagName('haserrors');
			foreach ($errors as $error)
			{
				$haserror = $error->nodeValue;
				if($haserror == 'false')
					return false;
				else
					return true;
			}
		}
		
		public static function GetErrorDescription ($XML)
		{
			$doc = new DOMDocument();
			$loaded = $doc->loadXML($XML);
			if(!$loaded)
				print('Could not load the XML');
				
			$errors = $doc->getElementsByTagName('message');
			foreach ($errors as $error)
			{
				$errorDesc = $error->nodeValue;
				//trigger_error($errorDesc, E_USER_WARNING);
				return $errorDesc;
			}
		}			
	}
?>