<?php

class api_file{
	public function send($api_id, $service_id, $file, $username, $password, $key, $url, $model, $provider, $network, $custom)
	{
		$id = 0;
		switch($api_id)
		{
			case '1':
				$id = $this->bruteforcemarket_send($service_id, $file, $username, $password, $key, $url, $model, $provider, $network, $custom);
				break;
			case '2':
				
				break;
		}
		return $id;
	}
	
	public function bruteforcemarket_send($service_id, $file, $username, $password, $key, $url, $model, $provider, $network, $custom)
	{
		$api = new api_bruteforcemarket($url, $username, $key);
		$response = $api->uploadFile($file);
		if(isset($response['error']))
		{
			return array('id' => '-1', 'msg' => $response['error_msg']);
		}
		if($response['success'])
			return array('id' => $response['imei'], 'msg' => "ok");
		else
			return array('id' => '-1', 'msg' => 'Can\'t submit IMEI now! Contact site admin for more assistance.');
	}
}
?>