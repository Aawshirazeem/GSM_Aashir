<?php
class api_blockcheck{

	var $gf_channel;
	
	var $result = array();
	
	function getResult()
	{
		//echo htmlentities($this->result);
		return $this->result;
	}
		
	function checkError($result)
	{
		if(isset($result['ERR']))
		{
			echo '<h2> Error Code: ' . $result['STATUS'] . '</h2>';
			echo '<h3>' . $result['ERR'] . '</h3>';
			include('layout_footer.php');
			exit();
		}
	}
	
    function sendCommand($command, $params = array())
    {
		if (is_string($command))
		{
			if (is_array($params))
			{
				$params['api_key'] = IMCK_API_ACCESS_KEY;
				$params['command'] = $command;
				$this->gf_channel = curl_init( );
				// you might want the headers for http codes
				curl_setopt( $this->gf_channel, CURLOPT_HEADER, false );
				// you may need to set the http useragent for curl to operate as
				curl_setopt( $this->gf_channel, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				// you wanna follow stuff like meta and location headers
				curl_setopt( $this->gf_channel, CURLOPT_FOLLOWLOCATION, true );
				// you want all the data back to test it for errors
				curl_setopt( $this->gf_channel, CURLOPT_RETURNTRANSFER, true );
				// probably unecessary, but cookies may be needed to
				curl_setopt( $this->gf_channel, CURLOPT_COOKIEJAR, 'cookie.txt');
				// as above
				curl_setopt( $this->gf_channel, CURLOPT_COOKIEFILE, 'cookie.txt');
				
				// if the $vars are in an array then turn them into a usable string
				if( is_array( $params ) ):
					$vars = implode( '&', $params);
				endif;
				
				// setup the url to post / get from / to
				curl_setopt( $this->gf_channel, CURLOPT_URL, IMCK_API_URL );
				curl_setopt( $this->gf_channel, CURLOPT_POST, true );
				curl_setopt( $this->gf_channel, CURLOPT_POSTFIELDS, $params );
				
				$this->result = curl_exec( $this->gf_channel );
				
				if (IMCK_API_DEBUG && curl_errno($this->gf_channel) != CURLE_OK)
				{
					trigger_error(curl_error($this->gf_channel), E_USER_WARNING);
				}
				
				// close session
				curl_close($this->gf_channel);
			}
		}
    }// End sendCommand
	
	function parse2Array($xml)
	{
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $xml, $vals, $index);
		xml_parser_free($xml_parser);
		
		$params = array();
		$level = array();
		foreach ($vals as $xml_elem)
		{
			if ($xml_elem['type'] == 'open')
			{
				if (array_key_exists('attributes',$xml_elem)) 
				{
					list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
				}
				else
				{
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}
			
			if ($xml_elem['type'] == 'complete')
			{
				$start_level = 1;
				$php_stmt = '$params';
				while($start_level < $xml_elem['level'])
				{
					$php_stmt .= '[$level['.$start_level.']]';
					$start_level++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				eval($php_stmt);
			}
		}
		return $params;
	}// End parse2XML
}