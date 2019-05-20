<?php
namespace ios;

use ios\Exceptions\CriticalErrorException;

define('API_VERSION',  1); //Should be the same as server API value
define('API_REVISION', 2); //Just for reference
define('API_DATE', '2012-08-23');

define('CR', "\r\n");
define('DEBUG_CURL', false);

class API {
    private $url;
    private $user;
    private $pass;
    
    private $debug;
    
    private $curl_channel;

    private $cryptography;
    
    public function __construct($url, $user, $pass, $debug = 0) {
      if (!extension_loaded('curl')) { 
        throw new CriticalErrorException(sprintf('cURL extension should be installed !')); 
      }

      if (strlen($url) == 0) { 
        throw new CriticalErrorException(sprintf('wrong URL `%s`', $url)); 
      }
      
      if (strlen($user) == 0) { 
        throw new CriticalErrorException(sprintf('wrong Username `%s`', $user)); 
      }
      
      if (strlen($pass) == 0) { 
        throw new CriticalErrorException(sprintf('wrong Password `%s`', $pass)); 
      }

      $this->debug = $debug;
      
      $this->url = $url;
      $this->user = $user;
      $this->pass = $this->PreparePassword($pass);

      $this->cryptography = new Cryptography($this->user, $this->pass);
    }

    public function VersionApi() {
      $Params['Operation'] = 'VersionApi';
      return($this->SendRequest($Params));
    }
    
    public function UserExist($User) {
      $Params['User'] = $User;
      $Params['Operation'] = 'UserExist';
      return($this->SendRequest($Params));
    }

    public function Balance() {
      $Params['Operation'] = 'Balance';
      return($this->SendRequest($Params));
    }

    public function CreditMoveTo($Receiver, $Quantity, $NoteForSender = '', $NoteForReceiver = '') {
      $Params['Operation'] = 'CreditMoveTo';
      $Params['Receiver'] = $Receiver;
      $Params['Quantity'] = $Quantity;
      $Params['NoteForSender'] = $NoteForSender;
      $Params['NoteForReceiver'] = $NoteForReceiver;
      return($this->SendRequest($Params));
    }
    
    public function SL3JobAdd($Imei, $Hash) {
      $Params['Operation'] = 'SL3JobAdd';
      $Params['IMEI'] = $Imei;
      $Params['Hash'] = $Hash;
	  print_r($Params);
      return($this->SendRequest($Params));
    }

    public function SL3JobCheck($Imei) {
      $Params['Operation'] = 'SL3JobCheck';
      $Params['IMEI'] = $Imei;
      return($this->SendRequest($Params));
    }

    public function SL3JobGet() {
      $Params['Operation'] = 'SL3JobGet';
      return($this->SendRequest($Params));
    }

    public function SL3JobCheckId($Id) {
      $Params['Operation'] = 'SL3JobCheckId';
      $Params['id'] = $Id;
      return($this->SendRequest($Params));
    }

    public function SL3JobSetId($Id, $Imei, $MasterCode, $UnlockCode) {
      $Params['Operation'] = 'SL3JobSetId';
      $Params['id'] = $Id;
      $Params['IMEI'] = $Imei;
      $Params['mastercode'] = $MasterCode;
      $Params['unlockcode'] = $UnlockCode;
      return($this->SendRequest($Params));
    }

    private function SendRequest($Params = array()) {
      $Params['Debug'] = $this->debug;
      $Params['Rnd'] = rand();
      $Params['Challenge'] = $Params['Rnd'];
      $Params['ApiVersion'] = API_VERSION;
      $Params['Login'] = $this->user;      

      $Params = $this->PreparePostData($Params);
      
      $this->curl_channel = curl_init();
      curl_setopt($this->curl_channel, CURLOPT_HEADER, false);
      curl_setopt($this->curl_channel, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($this->curl_channel, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($this->curl_channel, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($this->curl_channel, CURLOPT_COOKIEJAR, 'cookie.txt');
      curl_setopt($this->curl_channel, CURLOPT_COOKIEFILE, 'cookie.txt');
      //curl_setopt($this->curl_channel, CURLOPT_FAILONERROR, 1);
      curl_setopt($this->curl_channel, CURLOPT_URL, $this->url);
      //curl_setopt($this->curl_channel, CURLOPT_TIMEOUT, 3);
      curl_setopt($this->curl_channel, CURLOPT_POST, true);
      curl_setopt($this->curl_channel, CURLOPT_POSTFIELDS, $Params);
				
      $Response = curl_exec($this->curl_channel);
      if (DEBUG_CURL && curl_errno($this->curl_channel) != CURLE_OK) {
        trigger_error(curl_error($this->curl_channel), E_USER_WARNING);
      }     
      curl_close($this->curl_channel);
      
      $Response = $this->DecryptResponse($Response);
      return($Response);
    }
    
    private function DecryptResponse($Response) {
      $ResponseArray = explode(CR, $Response);
      if (count($ResponseArray) == 2) { //2 lines of text: cryptogram + signature
      
        $Response = strtoupper($ResponseArray[0]);
        $Response = pack("H*", $Response);
        $Response = $this->cryptography->encryptDecrypt($Response, Cryptography::MODE_DECRYPT);
        
        $signature = $this->cryptography->getSignature($Response);
        $signature = unpack("H*", $signature);
        $signature = $signature[1];
      }

      //Convert answer back to array
      $Response = parse_ini_string($Response);
      return($Response);
    }

    private function PreparePassword($Password) {
      return(strtoupper(sha1($Password)));
    }
    
    private function PreparePostData(array $Params) {
      $RawRequest = $this->arrayToIni($Params);
      $RawRequest = $RawRequest.CR;
      $RawRequest = Cryptography::padForEncryption($RawRequest);
            
      $EncryptedRequest = $this->cryptography->encryptDecrypt($RawRequest, Cryptography::MODE_ENCRYPT);
      $EncryptedRequest = unpack("H*", $EncryptedRequest);
      $EncryptedRequest = $EncryptedRequest[1];
      $EncryptedRequest = strtoupper($EncryptedRequest);

      $signature = $this->cryptography->getSignature($RawRequest);
      $signature = unpack("H*", $signature);
      $signature = $signature[1];
      $signature = strtoupper($signature);

      $Post['ApiVersion'] = API_VERSION;
      $Post['Login'] = $this->user;      
      $Post['Request'] = $EncryptedRequest;
      $Post['Signature'] = $signature;

      return $Post;
    }

    private function arrayToIni(array $data) {
        $result = '';
        foreach ($data as $key => $value) {
            $result .= $key . '=' . $value . CR;
        }
        $result = trim($result);
        return $result;
    }      
}