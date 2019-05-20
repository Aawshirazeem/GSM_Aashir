<?php

class ChimeraApi {
	const URL = "https://chimeratool.com/rapi/";
	private $username;
	private $apiKey;

	public function __construct($username, $apiKey) {
		$this->username = $username;
		$this->apiKey = $apiKey;
	}

	private function sendCmd($cmd, $params=array()) {		
		$params['username'] = $this->username;
		$params['apikey'] = $this->apiKey;

		foreach($params as $key => $value)
			$up[] = $key."=".urlencode($value);

		$r = file_get_contents(self::URL . $cmd . "?" . join("&", $up));

		if($r === false)
			return array("success" => false, "code" => "000", "message" => "Couldn't connect to API!");

		if(empty($r))
			return array("success" => false, "code" => "001", "message" => "No response from API server!");

		return json_decode($r, true);
	}

	public function getTransferList($page = 1, $limit = 50) {
		return $this->sendCmd("list",array('page' => $page, 'limit' => $limit));
	}

	public function getInfo() {
		return $this->sendCmd("info",array());
	}

	public function transferCredit($userId, $amount) {
		return $this->sendCmd("transfer",array('userId' => $userId, 'amount' => $amount));
	}

	public function revokeTransfer($transferId) {
		return $this->sendCmd("revoke",array('transferId' => $transferId));
	}

	public function checkUser($username) {
		return $this->sendCmd("checkuser", array('user' => $username));
	}

	public function checkChimeraCard($serialnumber) {
		return $this->sendCmd("checkuser", array('serialnumber' => $serialnumber));
	}

	public function sellLicence($targetid, $targettype, $licencename) {
		return $this->sendCmd("selllicence", array('targetid' => $targetid, 'targettype' => $targettype, 'licencename' => $licencename));
	}

	public function extendLicence($userlicenceid) {
		return $this->sendCmd("extendlicence", array('userlicenceid' => $userlicenceid));
	}

	public function upgradeLicence($username, $email) {
		return $this->sendCmd("upgradelicence", array('userlicenceid' => $userlicenceid));
	}

}