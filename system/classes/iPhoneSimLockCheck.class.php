<?php

class iPhoneSimLockCheck
	{
		var $gf_channel;
		var $result = array();
		
		function getReply($imei)
		{
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
			
			$param = array('msisdn' => '53311378' , 'imei' => $imei);
			
			// setup the url to post / get from / to
			curl_setopt( $this->gf_channel, CURLOPT_URL, 'http://tsd.unlock.netcom.no/Home/Unlock' );
			curl_setopt( $this->gf_channel, CURLOPT_POST, true );
			curl_setopt( $this->gf_channel, CURLOPT_POSTFIELDS, $param );
			
			$result = curl_exec( $this->gf_channel );

			
			if (TRUE && curl_errno($this->gf_channel) != CURLE_OK)
			{
				trigger_error(curl_error($this->gf_channel), E_USER_WARNING);
			}
			
			if(strpos($result, 'iTunes.') != false)
			{
				return 'Unlocked';
			}
			else
			{
				return 'Locked';
			}
			
			// close session
			curl_close($this->gf_channel);
		}
}