<?php
namespace ios;

//===== Write below your Username and Password =====
define('IOS_USER', $args['username']); // your Infinity Online Service account username
define('IOS_PASS', $args['password']); // your Infinity Online Service account password
define('IOS_DEBUG', 1);            // 0: Debug Mode Disabled, 1: Debug Mode Enabled - no changes in database

		
//===== DO NOT change anything below this line ! =====
define('IOS_URL', 'http://service.infinity-best.com:83/dcapi.php');
//define('IOS_URL', 'http://dc.localhost/dcapi.php');
echo "aaa";
define('IOS_PHP_VERSION_REQUIRED', '5.3.0');
if (version_compare(IOS_PHP_VERSION_REQUIRED, PHP_VERSION) >= 0) { 
  die(sprintf('PHP %s required instead of %s', IOS_PHP_VERSION_REQUIRED, PHP_VERSION)); 
}
echo "bbb";
define('IOS_FILE_BOOTSTRAP', CONFIG_PATH_EXTERNAL_ABSOLUTE . 'infinity/ios/bootstrap.php');
if (!@is_file(IOS_FILE_BOOTSTRAP)) { 
  die(sprintf('Can not find bootstrap `%s` !', IOS_FILE_BOOTSTRAP)); 
}
echo "ccc";
require_once(IOS_FILE_BOOTSTRAP);
//if(!class_exists('API')) { 
  //die('Autoloader is not setup correctly !'); 
//}

try {
  $Api = new API(IOS_URL, IOS_USER, IOS_PASS, IOS_DEBUG);
} catch(\Exception $e) {
  die(sprintf('API error: %s', $e->getMessage()));
}
