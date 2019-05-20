<?php
namespace ios;

use ios\Exceptions\InputDataException;

class Validator {

    public static function assertLenIsMultiple($point, $name, $value, $expectedMultiple) {
        if (strlen($value) % $expectedMultiple !== 0) {
            throw new InputDataException(sprintf('[%s]: Parameter "%s" must be a multiple of %d, but "%d" found', $point, $name, $expectedMultiple, strlen($value)));
        }
    }

    public static function assertIsHexadecimal($point, $name, $value) {
        if (!preg_match('/^[0-9a-fA-F]+$/', $value)) {
            throw new InputDataException(sprintf('[%s]: Parameter "%s" must be a hexadecimal, but "%s" found', $point, $name, $value));
        }
    }

    public static function assertIsDecimal($point, $name, $value) {
        if (!preg_match('/^\\d+$/', $value)) {
            throw new InputDataException(sprintf('[%s]: Parameter "%s" must be a decimal, but "%s" found', $point, $name, $value));
        }
    }

    public static function assertLen($point, $name, $value, $expectedLen) {
        if (strlen($value) !== $expectedLen) {
            throw new InputDataException(sprintf('[%s]: Parameter "%s" must be %d symbols length, but %d found', $point, $name, $expectedLen, strlen($value)));
        }
    }

    public static function assertLenInRange($point, $name, $value, $min, $max) {
        if ((strlen($value) < $min) || (strlen($value) > $max)) {
            throw new InputDataException(sprintf('[%s]: Parameter "%s" must be %d - %d symbols length, but %d found', $point, $name, $min, $max, strlen($value)));
        }
    }
    
    public static function hex2bin($hex) {
      return(pack("H*", $hex));
    }    

}
