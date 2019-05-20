<?php
namespace ios;

use ios\Exceptions\CriticalErrorException;

class Cryptography {
    const MODE_ENCRYPT = 1;
    const MODE_DECRYPT = 2;

    const KEY_SIZE = 24;
    const BLOCK_SIZE = 8;
    const IV_SIZE = 8;

    private $_login;
    private $_password;

    public function __construct($login, $password) {
        $this->_login = $login;
        $this->_password = $password;
    }

    public function getKey() {
        return substr($this->getSignature('Key' . $this->_password . $this->_login . 'Key') .
                    $this->getSignature($this->_password), 0, self::KEY_SIZE);
    }

    public function getIV() {
        return substr(
            $this->getSignature('IV' . $this->_login . $this->_password . 'IV') . $this->getSignature($this->_login), 0,
            self::IV_SIZE);
    }

    public function encryptDecrypt($value, $mode) {
        Validator::assertLenIsMultiple('encryptDecrypt', 'value', $value, self::BLOCK_SIZE);

        $mcryptHandle = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        if ($mcryptHandle === false) {
            throw new CriticalErrorException('mcrypt_module_open failed');
        }
        $key = $this->getKey();
        $iv = $this->getIV();
        $result = mcrypt_generic_init($mcryptHandle, $key, $iv);
        if ($result !== 0) {
            throw new CriticalErrorException(sprintf('mcrypt_generic_init error %s !', $result));
        }

        switch ($mode) {
            case self::MODE_ENCRYPT:
                return mcrypt_generic($mcryptHandle, $value);
                break;
            case self::MODE_DECRYPT:
                return mdecrypt_generic($mcryptHandle, $value);
                break;
            default:
                throw new CriticalErrorException('Invalid encryption mode in encryptDecrypt !');
                break;
        }
    }

    public function getSignature($value) {
        return sha1($value, true);
    }

    public static function padForEncryption($data) {
        if (strlen($data) % Cryptography::BLOCK_SIZE != 0) {
            $data .= str_repeat(' ', Cryptography::BLOCK_SIZE - (strlen($data) % Cryptography::BLOCK_SIZE));
        }
        return $data;
    }
}
