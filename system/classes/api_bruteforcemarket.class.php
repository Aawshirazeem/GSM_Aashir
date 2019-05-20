<?php
// (c) bruteforcemarket.com, 2011
// Version: 1.01
// Release date: 2011-12-05

class api_bruteforcemarket {
    private $bfm_url;
    private $bfm_user;
    private $bfm_key;

    function __construct($url, $user, $key) {
	$this->bfm_url = $url;
	$this->bfm_user = $user;
	$this->bfm_key = $key;
    }

    private function connect($cmd, $params=array()) {
	$params['cmd'] = $cmd;
	$params['bfm_user'] = $this->bfm_user;
	$params['bfm_key'] = $this->bfm_key;

	foreach($params as $key => $value)
	    $up[] = "$key=".urlencode($value);

	$r = file_get_contents($this->bfm_url . "?" . join("&", $up));

	if($r === false)
	    return array("error" => 1, "error_no" => "E_000", "error_msg" => "Couldn't connect to API!");

	if(empty($r))
	    return array("error" => 1, "error_no" => "E_001", "error_msg" => "No response from API server!");

	return json_decode($r, true);
    }

    public function uploadFile($file)
	{
		if(!file_exists($file))
			return array("error" => 1, "error_no" => "E_013", "error_msg" => "File does not exist!");

		if(!is_readable($file))
			return array("error" => 1, "error_no" => "E_014", "error_msg" => "File is not readable!");
			
		$pi = pathinfo($file);
		$content = base64_encode(file_get_contents($file));
		return $this->connect("uploadfile", array("filename" => $pi['basename'], "content" => $content));
    }

    public function upload($imei, $hash) {
	return $this->connect("upload", array("imei" => $imei, "hash" => $hash));
    }

    public function delete($imei) {
	return $this->connect("delete", array("imei" => $imei));
    }

    public function getinfo() {
	return $this->connect("getinfo");
    }

    public function myqueue() {
	return $this->connect("myqueue");
    }

    public function job($imei) {
	return $this->connect("job", array("imei" => $imei));
    }
}
?>