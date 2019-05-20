# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.38)
# Database: final_clean_db
# Generation Time: 2016-10-18 12:47:18 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table dummy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dummy`;

CREATE TABLE `dummy` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `msg` varchar(500) NOT NULL DEFAULT '0',
  `isadmin` int(11) NOT NULL DEFAULT '0',
  `isview` int(11) NOT NULL DEFAULT '0',
  `entry_type` varchar(50) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_admin_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_admin_master`;

CREATE TABLE `nxt_admin_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `last_action` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `language_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `online` int(11) NOT NULL,
  `notify` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `nname` varchar(50) NOT NULL,
  `pnumber` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `country` int(11) NOT NULL,
  `two_step_auth` int(11) NOT NULL DEFAULT '0',
  `is_update` int(1) DEFAULT '0',
  `is_tabbed` tinyint(1) NOT NULL DEFAULT '0',
  `google_auth_key` varchar(300) NOT NULL DEFAULT 'NA',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_api_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_api_details`;

CREATE TABLE `nxt_api_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `api_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `credits` float NOT NULL,
  `delivery_time` varchar(200) NOT NULL,
  `requires_network` smallint(6) NOT NULL,
  `requires_mobile` smallint(6) NOT NULL,
  `requires_provider` smallint(6) NOT NULL,
  `requires_pin` smallint(6) NOT NULL,
  `requires_kbh` smallint(6) NOT NULL,
  `requires_mep` smallint(6) NOT NULL,
  `requires_prd` smallint(6) NOT NULL,
  `requires_reference` smallint(6) NOT NULL,
  `model` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `network` varchar(255) NOT NULL,
  `type` smallint(6) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_api_error_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_api_error_log`;

CREATE TABLE `nxt_api_error_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `error_code` varchar(6) COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `date_time` datetime NOT NULL,
  `message` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Dump of table nxt_api_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_api_master`;

CREATE TABLE `nxt_api_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `api_server` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `url_edit` tinyint(1) NOT NULL,
  `username` varchar(50) NOT NULL,
  `username_edit` tinyint(1) NOT NULL,
  `password` varchar(50) NOT NULL,
  `password_edit` tinyint(1) NOT NULL,
  `key` varchar(50) NOT NULL,
  `key_edit` tinyint(1) NOT NULL,
  `table_name_` varchar(50) NOT NULL,
  `file_service` tinyint(1) NOT NULL,
  `requires_sync` tinyint(1) NOT NULL,
  `sync_datetime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_special` tinyint(1) NOT NULL,
  `is_visible` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_banner_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_banner_master`;

CREATE TABLE `nxt_banner_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_category`;

CREATE TABLE `nxt_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_chat_pool
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_chat_pool`;

CREATE TABLE `nxt_chat_pool` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `msg` varchar(500) NOT NULL DEFAULT '0',
  `isadmin` int(11) NOT NULL DEFAULT '0',
  `isview` int(11) NOT NULL DEFAULT '0',
  `entry_type` varchar(50) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_chat_pool_new
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_chat_pool_new`;

CREATE TABLE `nxt_chat_pool_new` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `msg` varchar(500) NOT NULL DEFAULT '0',
  `isadmin` int(11) NOT NULL DEFAULT '0',
  `isview` int(11) NOT NULL DEFAULT '0',
  `entry_type` varchar(50) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uname` varchar(50) NOT NULL,
  `uemail` varchar(50) NOT NULL,
  `uphone` varchar(50) NOT NULL,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_cms_blocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_cms_blocks`;

CREATE TABLE `nxt_cms_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `page_content` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_cms_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_cms_master`;

CREATE TABLE `nxt_cms_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `meta` text NOT NULL,
  `page_content` text NOT NULL,
  `is_home_page` tinyint(1) NOT NULL DEFAULT '0',
  `added_by` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_cms_master` WRITE;
/*!40000 ALTER TABLE `nxt_cms_master` DISABLE KEYS */;

INSERT INTO `nxt_cms_master` (`id`, `title`, `url`, `meta`, `page_content`, `is_home_page`, `added_by`, `added_on`)
VALUES
	(1,'Home','home','','<div class=\"row clearfix\"><div class=\"col-md-12\">\n      <div class=\"row\">\n        <div class=\"header-BG\">\n          <div class=\"container\">\n            <div class=\"col-sm-7 col-md-6 text-left\">\n              <div class=\"bgTitle\">\n                <h1><span style=\"color: inherit; font-family: inherit; font-size: 40px;\">[[domainname]]</span></h1>\n                <p> The #1 Cell Phone Unlocking Company Online in the World. &nbsp; &nbsp; &nbsp; &nbsp; <span style=\"font-size: 22px; font-weight: lighter;\">Send to us your Cell phone detail, and collect your Unlock Code quickly, great for CellPhone Store Service Centers, Webmasters, eBay sellers !</span></p>\n                <p><a href=\"#\"><button type=\"button\" class=\"btn-login\">LOGIN</button></a>&nbsp;   <a href=\"#\"><button type=\"button\" class=\"btn-login\">SIGN UP</button></a></p>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div></div>\n<div class=\"row clearfix\">\n	<div class=\"container\">\n      <div class=\"col-md-12 Workpt\">\n        <div class=\"row\">\n          <div class=\"col-md-6 text-center\">\n            <div class=\"WorkTitle\">\n              <h1 style=\"color: rgb(51, 51, 51);\">HOW DOES IT WORK?</h1>\n              <p>Most of our service are 24/07/365 Active. Your order will be processed automaticly on the time without any delay. we have connected api services and direct database to our services to provide you a real fast services.</p><p>&nbsp; </p>\n            </div>\n          </div>\n          <div class=\"col-md-6 text-center\">\n            <div class=\"workImg\">\n              <img src=\"/public/views/cms/assets/images/wotkIcon.png\" alt=\"workimg\" class=\"Workimg\">\n            </div>\n          </div>\n        </div>\n      </div>\n    </div>\n</div>\n<div class=\"row clearfix\"><div class=\"container\">\n      <div class=\"col-md-12\">\n        <div class=\"row\">\n          <div class=\"BusinessTitle text-center\">\n            <h1></h1><div class=\"edit\"><h2><em>We provide Developer API for&nbsp;your business!</em></h2><p><em><span style=\"font-size: 16px;\">Learn our developers API and make your own server or software<br>communicate with YourSite.Com to automatically place orders</span></em></p></div><p> </p>\n          </div>\n          <div class=\"bussinessboxDiv\">\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"businessBox\">\n                <img src=\"/public/views/cms/assets/images/01.png\" alt=\"interface\" class=\"interfaceImg\">\n                <h2>User Friendly Interface </h2>\n                <div class=\"interfaceConbox text-left\">\n                  <span>We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. &nbsp;You can manage your large volume</span>\n                </div>\n                <a href=\"#\"><button type=\"button\" class=\"btnRead\">Read more</button></a>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"businessBox\">\n                <img src=\"/public/views/cms/assets/images/02.png\" alt=\"interface\" class=\"interfaceImg\">\n                <h2>Manage your assets! </h2>\n                <div class=\"interfaceConbox text-left\">\n                  <span>For every business its important to manage your assets. Our web portal provides you to manage your orders and export them to .txt or . </span>\n                </div>\n                <a href=\"#\"><button type=\"button\" class=\"btnRead\">Read more</button></a>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"businessBox\">\n                <img src=\"/public/views/cms/assets/images/03.png\" alt=\"interface\" class=\"interfaceImg\">\n                <h2>Let your business grow! </h2>\n                <div class=\"interfaceConbox text-left\">\n                  <span>For better growth of any business you need a dedicated team of professional to promote your business, on the other hand you also need a</span>\n                </div>\n                <a href=\"#\"><button type=\"button\" class=\"btnRead\">Read more</button></a>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"businessBox\">\n                <img src=\"/public/views/cms/assets/images/04.png\" alt=\"interface\" class=\"interfaceImg\">\n                <h2>Best price Guarantee </h2>\n                <div class=\"interfaceConbox text-left\">\n                  <span>YourSite.com is known for its best price in the market. If you still find less/cheaper price any where in the industry, feel free to contact us. We will try our best to provide </span>\n                </div>\n                <a href=\"#\"><button type=\"button\" class=\"btnRead\">Read more</button></a>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div></div>\n<div class=\"row clearfix\">\n	<div class=\"col-md-12 blueBg\">\n      <div class=\"row\">\n        <div class=\"container\">\n          <div class=\"ServicesTitle text-center\">\n            <h1><span style=\"color: rgb(51, 51, 51);\">OUR SERVICES</span></h1>\n            <p><span style=\"color: rgb(51, 51, 51);\">&nbsp;We provide a wide range of services to fulfill your business needs. </span></p>\n            <p><span style=\"color: rgb(51, 51, 51);\">&nbsp;Following are our some of the most usefull services we offer.</span></p>\n          </div>\n          <div class=\"serVicesBox\">\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBox\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-android-lock size-64\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\" title=\"\">IMEI SERVICES</a></h3>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBox\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-ios-list size-64\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\">FILE SERVICES</a> </h3>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBox\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-ios-briefcase size-64\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\">SERVER LOGS</a></h3>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBox\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-android-send size-64\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\" title=\"\">PREPAID LOGS</a></h3>\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div>\n</div>\n	<div class=\"row clearfix\"><div class=\"row clearfix\">\n	<div class=\"col-md-12 paymentsSection\">\n      <div class=\"row\">\n        <div class=\"container\">\n          <div class=\"text-center\">\n            <h1>Accepted Payments</h1>\n            \n            \n          </div>\n          <div class=\"paymentsBox\"><img src=\"/public/views/cms/assets/images/payments/paypal.png\">\n<img src=\"/public/views/cms/assets/images/payments/skrill.png\">\n<img src=\"/public/views/cms/assets/images/payments/visa.png\">\n<img src=\"/public/views/cms/assets/images/payments/mastercard.png\">\n<img src=\"/public/views/cms/assets/images/payments/bitcoin.png\">\n<img src=\"/public/views/cms/assets/images/payments/paysafe.png\">\n<img src=\"/public/views/cms/assets/images/payments/moneygram.png\">\n<img src=\"/public/views/cms/assets/images/payments/western_union.png\">\n<img src=\"/public/views/cms/assets/images/payments/banktransfer.png\"></div>\n        </div>\n      </div>\n    </div>\n</div></div>',0,1,'2016-09-30 09:16:45'),
	(2,'Home 2','home-blue','<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n    <meta name=\"description\" content=\"\">\r\n    <meta name=\"author\" content=\"\">','<div class=\"row clearfix\"><div class=\"col-md-12\">      <div class=\"row\">        <div class=\"container\">          <div class=\"AboutTitle\">            <h1>ABOUT YOURSITE<br></h1>            <p> The #1 Cell Phone Unlocking Company Online in the World. Send to us your Cell phone detail, and collect your Unlock Code quickly, great for CellPhone Store Service Centers, Webmasters, eBay sellers !</p>          </div>        </div>      </div>    </div></div><div class=\"row clearfix\">	<div class=\"col-md-12\">      <div class=\"row\">        <div class=\"container\">          <div class=\"IMEIser clearfix\">            <h1>OUR SERVICES</h1>            <hr class=\"SerTitleBorder\">          </div>          <div class=\"row\">            <div class=\"IN2SerivesBox\">              <div class=\"col-sm-6 col-md-3\">                <div class=\"IN2box\">                  <span class=\"lockIcon\"><i class=\"icon ion-unlocked size-32\" aria-hidden=\"true\"></i></span>                  <h1>IMEI SERVICES</h1>                  <p> Unlock your&nbsp;cell phone with only imei number, submit imei number of your device and get unlock codes quickly. this is the easiest and fast way to unlock your device online. </p>                </div>              </div>              <div class=\"col-sm-6 col-md-3\">                <div class=\"IN2box\">                  <span class=\"docIcon\"><i class=\"icon ion-document-text size-32\" aria-hidden=\"true\"></i></span>                  <h1>FILE SERVICES</h1>                  <p> Unlock your&nbsp;cell phone in file service, submit a file with exect data &nbsp;of your device and we will generate unlock code for your device as soon as possible. we will provide 100% correct code. </p>                </div>              </div>              <div class=\"col-sm-6 col-md-3\">                <div class=\"IN2box\">                  <span class=\"LOgICon\"><i class=\"icon ion-android-globe size-32\" aria-hidden=\"true\"></i></span>                  <h1>SERVER LOGS</h1>                  <p> Get a lot of activations, credits and logs for third party services softwares and websites we are official reseller and we provide all type of server logs at best price of the market. </p>                </div>              </div>              <div class=\"col-sm-6 col-md-3\">                <div class=\"IN2box\">                  <span class=\"preICon\"><i class=\"icon ion-android-send\" aria-hidden=\"true\"></i></span>                  <h1>PREPAID LOG</h1>                  <p> Get Instant Activation keys for the other products, we provide the prepaid logs it mean all type of keys, activation keys and user name and passwords with credit to use on other sites. </p>                </div>              </div>            </div>          </div>        </div>      </div>    </div></div><div class=\"row clearfix\">	<div class=\"col-md-12 In2Workbg\">      <div class=\"row\">        <div class=\"container\">          <div class=\"IMEIser clearfix\">            <h1>HOW DOES IT WORK ?</h1>            <hr class=\"HowTitleBorder\">          </div>          <div class=\"workText\">            <p> Most of our service are 24/07/365 Active. Your order will be processed automaticly on the time without any delay. we have connected api services and direct database to our services to provide you a real fast services. </p>          </div>        </div>      </div>    </div></div><div class=\"row clearfix\">	<div class=\"row\">    <div class=\"container\">      <div class=\"apibox\">        <div class=\"col-sm-6 col-md-12\">          <div class=\"col-md-4\">            <div class=\"frndpenal\">              <img src=\"/public/views/cms/assets/images/01.png\" alt=\"penal\" class=\"frndpenalImg\">            </div>          </div>          <div class=\"col-md-8\">            <div class=\"userpenal clearfix\">              <h1>USER FRIENDLY INTERFACE </h1>              <hr class=\"useTitleBorder\">            </div>            <div class=\"workText\">              <p> We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. You can manage your large volume of IMEI numbers within our online portal for better productivity. </p>            </div>          </div>        </div>        <div class=\"col-sm-6 col-md-12\">          <div class=\"col-md-8 text-right\">            <div class=\"userpenal clearfix\">              <h1>MANAGE YOUR ASSETS</h1>              <hr class=\"useTitleBorder border-right\">            </div>            <div class=\"workText\">              <p> We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. You can manage your large volume of IMEI numbers within our online portal for better productivity. </p>            </div>          </div>          <div class=\"col-md-4\">            <div class=\"frndpenal\">              <img src=\"/public/views/cms/assets/images/02.png\" alt=\"penal\" class=\"frndpenalImg\">            </div>          </div>        </div>        <div class=\"col-sm-6 col-md-12\">          <div class=\"col-md-4\">            <div class=\"frndpenal\">              <img src=\"/public/views/cms/assets/images/03.png\" alt=\"penal\" class=\"frndpenalImg\">            </div>          </div>          <div class=\"col-md-8\">            <div class=\"userpenal clearfix\">              <h1>LET YOUR BUSINESS GROW!</h1>              <hr class=\"useTitleBorder\">            </div>            <div class=\"workText\">              <p> We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. You can manage your large volume of IMEI numbers within our online portal for better productivity. </p>            </div>          </div>        </div>        <div class=\"col-sm-6 col-md-12\">          <div class=\"col-md-8 text-right\">            <div class=\"userpenal clearfix\">              <h1>BEST PRICE GUARANTEE </h1>              <hr class=\"useTitleBorder border-right-one\">            </div>            <div class=\"workText\">              <p> We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. You can manage your large volume of IMEI numbers within our online portal for better productivity. </p>            </div>          </div>          <div class=\"col-md-4\">            <div class=\"frndpenal\">              <img src=\"/public/views/cms/assets/images/04.png\" alt=\"penal\" class=\"frndpenalImg\">            </div>          </div>        </div>      </div>    </div>  </div></div>',0,1,'2016-10-01 00:47:13'),
	(16,'Home 3','home-parrot','','<div class=\"row clearfix\">\n	<div class=\"pcBg\">\n      <div class=\"container\">\n        <div class=\"col-md-12\">\n          <div class=\"row\">\n            <div class=\"BusinessTitlepc text-center\">\n              <h1>WE PROVIDE DEVELOPER API FOR <span>YOUR BUSINESS!</span></h1>\n              <p>&nbsp;Learn our developers API and make your own server or software </p>\n              <p>&nbsp;communicate with YourSite.Com to automatically place orders </p>\n            </div>\n            <div class=\"bussinessboxDiv\">\n              <div class=\"col-sm-6 col-md-3 text-center\">\n                <div class=\"businessBoxpc\">\n                  <img src=\"/public/views/cms/assets/images/01.png\" alt=\"interface\" class=\"interfaceImg\">\n                  <h2>User Friendly Interface </h2>\n                  <div class=\"interfaceConbox text-left\">\n                    <span>We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. &nbsp;You can manage your large volume</span>\n                  </div>\n                  <a href=\"#\"><button type=\"button\" class=\"btnReadpc\">Read more</button></a>\n                </div>\n              </div>\n              <div class=\"col-sm-6 col-md-3 text-center\">\n                <div class=\"businessBoxpc\">\n                  <img src=\"/public/views/cms/assets/images/02.png\" alt=\"interface\" class=\"interfaceImg\">\n                  <h2>Manage your assets! </h2>\n                  <div class=\"interfaceConbox text-left\">\n                    <span>For every business its important to manage your assets. Our web portal provides you to manage your orders and export them to .txt or . </span>\n                  </div>\n                  <a href=\"#\"><button type=\"button\" class=\"btnReadpc\">Read more</button></a>\n                </div>\n              </div>\n              <div class=\"col-sm-6 col-md-3 text-center\">\n                <div class=\"businessBoxpc\">\n                  <img src=\"/public/views/cms/assets/images/03.png\" alt=\"interface\" class=\"interfaceImg\">\n                  <h2>Let your business grow! </h2>\n                  <div class=\"interfaceConbox text-left\">\n                    <span>For better growth of any business you need a dedicated team of professional to promote your business, on the other hand you also need a</span>\n                  </div>\n                  <a href=\"#\"><button type=\"button\" class=\"btnReadpc\">Read more</button></a>\n                </div>\n              </div>\n              <div class=\"col-sm-6 col-md-3 text-center\">\n                <div class=\"businessBoxpc\">\n                  <img src=\"/public/views/cms/assets/images/04.png\" alt=\"interface\" class=\"interfaceImg\">\n                  <h2>Best price Guarantee </h2>\n                  <div class=\"interfaceConbox text-left\">\n                    <span>[[domainname]] is known for its best price in the market. If you still find less/cheaper price any where in the industry, feel free to contact us. We will try our best to provide </span>\n                  </div>\n                  <a href=\"#\"><button type=\"button\" class=\"btnReadpc\">Read more</button></a>\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div>\n</div>\n<div class=\"row clearfix\">\n	<div class=\"container\">\n      <div class=\"col-md-12 Workptpc\">\n        <div class=\"row\">\n          <div class=\"col-md-6 text-left\">\n            <div class=\"WorkTitle\">\n              <h1>HOW DOES IT WORK?</h1>\n              <p class=\"p-l\">Most of our service are 24/07/365 Active. Your order will be processed \nautomaticly on the time without any delay. we have connected api \nservices and direct database to our services to provide you a real fast \nservices.</p>\n            </div>\n          </div>\n          <div class=\"col-md-offset-1 col-md-5 text-center\">\n            <div class=\"workImg\">\n              <img src=\"/public/views/cms/assets/images/wotkIcon.png\" alt=\"workimg\" class=\"Workimg\">\n            </div>\n          </div>\n        </div>\n      </div>\n    </div>\n</div>\n<div class=\"row clearfix\"><div class=\"col-md-12 perrotBg\">\n      <div class=\"row\">\n        <div class=\"container\">\n          <div class=\"ServicesTitle text-center\">\n            <h1>OUR SERVICES</h1>\n            <p>&nbsp;We provide a wide range of services to fulfill your business needs. </p>\n            <p>&nbsp;Following are our some of the services we offer.</p>\n          </div>\n          <div class=\"serVicesBox\">\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBoxpc\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-unlocked\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\">IMEI SERVICES</a></h3>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBoxpc\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-android-document\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\">FILE SERVICES</a> </h3>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBoxpc\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-android-globe\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\">SERVICES LOG</a></h3>\n                </div>\n              </div>\n            </div>\n            <div class=\"col-sm-6 col-md-3 text-center\">\n              <div class=\"servicesBoxpc\">\n                <div class=\"IMRIicon\">\n                  <i class=\"icon ion-ios-medical\" aria-hidden=\"true\"></i>\n                  <h3><a href=\"#\">PREPAID LOG</a></h3>\n                </div>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div>\n<div class=\"row clearfix\">\n	<div class=\"col-md-12 paymentsSection\">\n      <div class=\"row\">\n        <div class=\"container\">\n          <div class=\"text-center\">\n            <h1>Accepted Payments</h1>\n            \n            \n          </div>\n          <div class=\"paymentsBox\"><img src=\"/public/views/cms/assets/images/payments/paypal.png\">\n<img src=\"/public/views/cms/assets/images/payments/skrill.png\">\n<img src=\"/public/views/cms/assets/images/payments/visa.png\">\n<img src=\"/public/views/cms/assets/images/payments/mastercard.png\">\n<img src=\"/public/views/cms/assets/images/payments/bitcoin.png\">\n<img src=\"/public/views/cms/assets/images/payments/paysafe.png\">\n<img src=\"/public/views/cms/assets/images/payments/moneygram.png\">\n<img src=\"/public/views/cms/assets/images/payments/western_union.png\">\n<img src=\"/public/views/cms/assets/images/payments/banktransfer.png\"></div>\n        </div>\n      </div>\n    </div>\n</div></div>',1,9,'2016-10-06 04:19:01'),
	(8,'about us','aboutus','<meta name=\"keywords\" content=\"about us\">','<div class=\"ui-draggable\"></div>\n\n	\n\n	\n\n\n\n	\n\n	\n	\n<link href=\"//fonts.googleapis.com/css?family=Chewy\" rel=\"stylesheet\" property=\"stylesheet\" type=\"text/css\"><link href=\"//fonts.googleapis.com/css?family=Roboto\" rel=\"stylesheet\" property=\"stylesheet\" type=\"text/css\">\n	\n\n	\n\n<div class=\"row clearfix\"><div class=\"column full\">\n            <p class=\"is-info2\" style=\"font-size: 35px;\"><b><u>About us</u></b></p>\n            <h1 class=\"size-32 is-title4-32 is-title-bold\"><span style=\"font-weight: normal;\"><i style=\"font-size: 18px;\">We are the multi-range international online store,&nbsp;</i></span><i style=\"font-weight: normal; color: inherit; font-family: inherit; font-size: 18px;\">where you can conveniently purchase telecommunication related technological solutions, Services, GSM/GPRS/EDGE software and hardware products, accessories and spare parts for cell phones, various electronic components and equipment, repair/service tools etc.</i></h1><p><i style=\"font-weight: normal; color: inherit; font-family: inherit; font-size: 18px;\"><br></i></p>\n        </div></div>\n\n    <div class=\"row clearfix\">\n\n        <div class=\"column full\">\n\n            <br><br>\n\n        </div>\n\n    </div>\n\n<div class=\"row clearfix\">\n\n		<div class=\"column full center\">\n\n\n            <div class=\"clearfix is-boxed-button-big\">\n                <a href=\"https://twitter.com/\" style=\"background-color: #00bfff;\"><i class=\"icon ion-social-twitter\"></i></a>\n                <a href=\"https://facebook.com/\" style=\"background-color: #128BDB;\"><i class=\"icon ion-social-facebook\"></i></a>\n                <a href=\"https://www.youtube.com/\" style=\"background-color: #E20000;\"><i class=\"icon ion-social-youtube\"></i></a>	\n                <a href=\"http://www.website.com/\" style=\"background-color: #0569AA;\"><i class=\"icon ion-android-home\"></i></a>		\n                <a href=\"mailto:you@example.com\" style=\"background-color: #ff69B4;\"><i class=\"icon ion-ios-email-outline\"></i></a>\n            </div>\n\n        </div>\n\n	</div>',0,9,'2016-10-03 00:30:32'),
	(9,'NEWPAGE','NEWPAGE','<meta name=\"keywords\" content=\"NEWPAGE\">','\n<div class=\"row clearfix\"><div class=\"column half\">\n\n            		<div class=\"display\">\n\n                		<h1>Beautiful content. Responsive.</h1>\n\n                		<h3>Lorem Ipsum is simply dummy text.</h3>\n\n                		<p style=\"margin:1em 0 2.5em;\"><br></p>\n\n            		</div>\n\n        	</div>\n\n        	<div class=\"column half\">\n\n            		<img src=\"data:image/jpeg;base64,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\">\n\n        	</div></div>\n\n	<div class=\"row clearfix\">\n\n        	<div class=\"column half center\">\n\n            		<div class=\"embed-responsive embed-responsive-4by3\" style=\"margin-right:20px\">\n\n            			<iframe width=\"560\" height=\"315\" src=\"/public/views/cms/assets/minimalist-basic/P5yHEKqx86U(1).html\" frameborder=\"0\" allowfullscreen=\"\"></iframe>\n\n            		</div>\n\n        	</div>\n\n        	<div class=\"column half\">\n\n            		<h3>Lorem Ipsum is simply dummy text</h3>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n\n        	</div>\n\n    	</div>\n\n    \n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full\">\n\n            <h1 style=\"text-align: center; font-size:3em\">Our Services</h1>\n\n        </div>\n\n	</div>\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-monitor-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-gear-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</',0,9,'2016-10-03 01:36:54'),
	(3,'Testing','new-page','','\n\n	\n\n	<div class=\"row clearfix\"><div class=\"column third\">\n\n			<h1>Lorem Ipsum</h1>\n\n			<p>Lorem Ipsum is simply dummy text. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n\n		</div>\n\n		<div class=\"column two-third\">\n\n			<img src=\"/public/views/cms/assets/minimalist-basic/e09-1.jpg\">\n\n		</div></div>\n\n\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full\">\n\n			<div class=\"display center\">\n\n				<h1>Beautiful Content</h1>\n\n				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.</p>\n\n\n			</div>\n\n			<div class=\"center\">\n\n				<img src=\"/public/views/cms/assets/minimalist-basic/b14-1.jpg\"><img src=\"/public/views/cms/assets/minimalist-basic/b14-2.jpg\"><img src=\"/public/views/cms/assets/minimalist-basic/b14-3.jpg\">\n\n			</div>\n\n		</div>\n\n	</div>\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full\">\n\n			<div class=\"display center\">\n\n				<h1>Beautiful Content</h1>\n\n				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.</p>\n\n\n			</div>\n\n			<div class=\"center\">\n\n				<img src=\"/public/views/cms/assets/minimalist-basic/b14-1.jpg\"><img src=\"/public/views/cms/assets/minimalist-basic/b14-2.jpg\"><img src=\"/public/views/cms/assets/minimalist-basic/b14-3.jpg\">\n\n			</div>\n\n		</div>\n\n	</div>\n\n\n\n<div class=\"row clearfix\">\n\n        <div class=\"column full\">\n			 <p class=\"size-21 is-info2\"><i>This is a special report</i></p>\n             <h1 class=\"size-48 is-title1-48 is-title-bold is-upper\">Lorem Ipsum is simply dummy text of the printing industry</h1>\n        </div>\n\n	</div>',0,9,'2016-10-01 03:27:24'),
	(4,'','testnew','','\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full\">\n\n            <h1 style=\"text-align: center; font-size:3em\">Our Services</h1>\n\n        </div>\n\n	</div>\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-monitor-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-gear-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-heart-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n	</div>\n\n    <div class=\"row clearfix\">\n\n        <div class=\"column full\">\n\n            <hr>\n\n        </div>\n\n    </div>\n\n\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-compose-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-world-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n		<div class=\"column third center\">\n			<div class=\"padding-20\">\n					<i class=\"icon ion-ios-calendar-outline size-48\"></i>\n\n					<h4>Lorem Ipsum</h4>\n\n            		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n			</div>\n        </div>\n\n	</div>',0,1,'2016-10-01 09:30:22'),
	(10,'404 - Page not found','pagenotfound','<meta name=\"keywords\" content=\"404 - Page not found\">','\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full center\">\n\n            <div class=\"display\">\n\n                <h1 style=\"font-size: 9em\">404</h1>\n\n            </div>\n\n			 <h1>PAGE NOT FOUND</h1>\n\n            <p><br></p>\n\n        </div>\n\n	</div>\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full center\">\n			<div class=\"clearfix is-rounded-button-medium\">\n\n				<a href=\"http://old.imei.pk/\" style=\"background-color: #fff;\" title=\"\"><i style=\"color:#000\" class=\"icon ion-ios-home\"></i></a>\n			</div>\n\n		</div>\n\n	</div>',0,9,'2016-10-03 02:35:19'),
	(11,'test for video','testforvideo','<meta name=\"keywords\" content=\"test for video\">','<div class=\"row clearfix\">\n\n        <div class=\"column full\">\n\n            <div class=\"display center\">\n\n                <h1 style=\"font-size: 4em; color: rgb(255, 14, 14);\">TESTING</h1>\n\n            </div>\n\n            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. Lore<span style=\"color: rgb(250, 66, 115);\">m ipsum dolor sit amet, consectetur adipiscing elit. </span></p>\n\n        </div>\n\n    </div>\n\n    <div class=\"row clearfix\">\n\n        <div class=\"column full\">\n\n            <div class=\"center\" style=\"margin:1em 0 2.5em;\">\n\n            <a href=\"http://innovastudio.com/builderdemo/assets/minimalist-basic/snippets.html#\" class=\"btn btn-primary edit\">Read More</a>\n\n            </div>\n\n        </div>\n\n    </div>\n\n<div class=\"row clearfix\">\n\n        <div class=\"column full\">\n			 <h1 class=\"size-48 is-title1-48 is-title-bold is-upper\"><span style=\"font-weight: normal;\">Beautiful </span>Content. TEST.</h1>\n             <p class=\"size-21\"><i>Lorem Ipsum is <span style=\"font-size: 20px;\"><span style=\"font-family: &quot;Bowlby One SC&quot;, cursive;\">simply dummy</span> text</span> of the printing and typesetting industry.</i></p>\n        </div>\n\n	</div>\n\n<link href=\"//fonts.googleapis.com/css?family=Bowlby One SC\" rel=\"stylesheet\" property=\"stylesheet\" type=\"text/css\">',0,9,'2016-10-03 03:08:48'),
	(26,'testt','testclau','','',0,1,'2016-10-16 14:53:17'),
	(17,'Footer style 1','','','<div class=\"row clearfix\"><div class=\"row clearfix\">\n  <div class=\"col-md-12 grayBG\">\n    <div class=\"row\">\n      <div class=\"container Footpt\">\n        <div class=\"col-sm-6 col-md-6 text-left\">\n          <div class=\"FootTitle\" style=\"font-size: 16px; color: rgb(236, 236, 236);\">\n            <h1><a href=\"#\" title=\"\">WHY US?</a></h1>\n            <p></p><p style=\"font-size: 18px;\">Our main focus is wholesale, seen other sites selling imei unlocks? Chances are big they buy the unlock from us &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; We supply 70% of the unlocking sites who sell retail. Buying from us, means buying From the source. This reduces Costs and turnaround time.</p>\n          </div>\n        </div>\n        <div class=\"col-sm-6 col-md-offset-2 col-md-4 text-left\">\n          <div class=\"footContact\">\n            <h2>FOLLOW US</h2>\n          </div>\n          <div class=\"bgBtnfoot\">\n            <a href=\"#\" class=\"btn-login\" title=\"\">ABOUT US</a>\n             <a href=\"#\" class=\"btn-down\" title=\"\">CONTANT US</a>\n          </div>\n          <div class=\"SocialFoot\"> \n                    <a href=\"https://www.facebook.com/testpage\"><img src=\"/public/views/cms/assets/images/facebook.png\" alt=\"facebook\" class=\"footICon\"></a>\n                              <a href=\"https://twitter.com/login\"><img src=\"/public/views/cms/assets/images/tweet.png\" alt=\"facebook\" class=\"footICon\"></a>\n                               <a href=\"https://plus.google.com/\"><img src=\"/public/views/cms/assets/images/G-plus.png\" alt=\"facebook\" class=\"footICon\"></a> \n                               <a href=\"https://www.youtube.com/channel/UCRNeXX-0F9hTpLXGRkWeJbw\"><img src=\"/public/views/cms/assets/images/youtube.png\" alt=\"facebook\" class=\"footICon\"></a>\n                    </div>\n        </div>\n      </div>\n      <hr class=\"FootBorder\">\n    </div>\n    <div class=\"CopyRight text-center\"> <span>&nbsp;[[sitename]] © 2016</span> </div>\n  </div>\n</div></div>',0,11,'2016-10-08 07:55:14'),
	(18,'Footer style 2','','','<div class=\"row clearfix\"><div class=\"row clearfix\">\n  <div class=\"col-md-12 Footbg\">\n    <div class=\"row\">\n      <div class=\"container footpt\">\n        <div class=\"col-sm-4 col-md-4\">\n          <div class=\"footerPRo clearfix\">\n            <h1>OUR MAIN SERVICES</h1>\n            <hr class=\"footTitleBorder\">\n          </div>\n          <div class=\"FootServices\">\n            <ul class=\"footersrvc\">\n              <li> <a href=\"#\" title=\"\">IMEI SERVICES</a> </li>\n              <li> <a href=\"#\" title=\"\"> FILE SERVICES </a> </li>\n              <li> <a href=\"#\"> SERVER SERVICES </a> </li>\n            </ul>\n          </div>\n        </div>\n        <div class=\"col-sm-4 col-md-4\">\n          <div class=\"footerPRo clearfix\">\n            <h1>ABOUT COMPANY</h1>\n            <hr class=\"footdownBorder\">\n          </div>\n          <div class=\"FootServices\">\n            <ul class=\"footersrvc\">\n              <li> <a href=\"http://old.imei.pk/aboutus.html\" title=\"\">ABOUT US</a> </li>\n              <li> <a href=\"#\" title=\"\">OUR TEAM</a> </li>\n              <li> <a href=\"#\" title=\"\">PRIVACY POLICY</a> </li>\n            </ul>\n          </div>\n        </div>\n        <div class=\"col-sm-4 col-md-4\">\n          <div class=\"footerPRo clearfix\">\n            <h1>CONTACT US</h1>\n            <hr class=\"footconBorder\">\n          </div>\n          <div class=\"footAdd\"><p>For&nbsp;further&nbsp;information&nbsp;or&nbsp;if you have any&nbsp;questions&nbsp;please do not hesitate&nbsp;to&nbsp;contact&nbsp;us.&nbsp;</p><p>Mobile: 0123456789</p>\n            <a href=\"#\" class=\"btn_foot\">LOGIN</a>\n          </div>\n        </div>\n      </div>\n      <hr class=\"FootBorderIN2\">\n    </div>\n    <div class=\"container\">\n      <div class=\"copyIN2  pull-left\"> [[sitename]] © 2016 </div>\n      <div class=\"FootSocialIN2  pull-right\">\n                <a href=\"https://www.facebook.com/testpage\"> <i class=\"fa fa-facebook footFb\" aria-hidden=\"true\"></i></a>\n                              <a href=\"https://twitter.com/login\"><i class=\"fa fa-twitter foottwt\" aria-hidden=\"true\"></i></a>\n                               <a href=\"https://plus.google.com/\"><i class=\"fa fa-google-plus footplus\" aria-hidden=\"true\"></i></a> \n                               <a href=\"https://www.youtube.com/channel/UCRNeXX-0F9hTpLXGRkWeJbw\"><i class=\"fa fa-youtube footplus\" aria-hidden=\"true\"></i></a>\n                \n      \n      \n      \n         </div>\n    </div>\n  </div>\n</div></div>',0,11,'2016-10-08 09:47:37'),
	(19,'Home 4','home-brown','','<div class=\"row clearfix\"><div class=\"col-md-12\">\n      <div class=\"row\">\n        <div class=\"header_BG\">\n          <div class=\"container\">\n            <div class=\"col-sm-5 col-md-6 text-left\">\n              <div class=\"bgTitle FourColor\">\n                <h1>[[domainname]]</h1>\n                <h2>[[tagline]]</h2>\n                <p> The #1 Cell Phone Unlocking Company Online in the World. &nbsp; &nbsp; &nbsp; &nbsp; Send\n to us your Cell phone detail, and collect your Unlock Code quickly, \ngreat for CellPhone Store Service Centers, Webmasters, eBay sellers ! </p>\n                <p><button type=\"button\" class=\"btn-login FoutBtn\">LOGIN</button>&nbsp;<button type=\"button\" class=\"btn-down FoutBtn\">CREATE ACCOUNT</button></p>\n              </div>\n            </div>\n            <div class=\"col-sm-7 col-md-6 hidden-xs\">\n              <div class=\"headerMObile\">\n                <img src=\"/public/views/cms/assets/images/iphone1.png\" alt=\"unlocked\" class=\"headMobile\">\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div></div><div class=\"row clearfix\">\n	<div class=\"WorkBg\">\n    </div>\n    <div class=\"container\">\n      <div class=\"secondSec\">\n        <div class=\"col-sm-6 col-md-6 text-center\">\n          <div class=\"workImg\">\n            <img src=\"/public/views/cms/assets/images/wotkIcon.png\" alt=\"workimg\" class=\"Workimg\">\n          </div>\n        </div>\n        <div class=\"col-sm-6 col-md-6 text-center\">\n          <div class=\"WorkTitlefour\">\n            <h1>HOW DOES IT WORK?</h1>\n            <p>Most of our service are 24/07/365 Active. Your order will be processed automaticly on the time without any delay. we have connected api services and direct database to our services to provide you a real fast services. </p>\n          </div>\n        </div>\n      </div>\n    </div>\n</div><div class=\"row clearfix\">\n	<div class=\"container\">\n	<div class=\"BusinessTitlefour text-right\">\n        <h1>WE PROVIDE DEVELOPER API FOR <span>YOUR BUSINESS!</span></h1>\n        <p>&nbsp;Learn our developers API and make your own server or software </p>\n        <p>&nbsp;communicate with YourSite.com to automatically place orders </p>\n      </div>\n      <div class=\"col-md-12 busiDiv\">\n        <div class=\"BusinessBox\">\n          <div class=\"col-sm-6 col-md-3 text-center box_one\">\n            <div class=\"businessBoxfour\">\n              <div class=\"SecIMG\">\n                <div class=\"imgBG\">\n                </div>\n                <img src=\"/public/views/cms/assets/images/01.png\" alt=\"interface\" class=\"interfaceImgfour\">\n              </div>\n              <h2>User Friendly Interface </h2>\n              <div class=\"interfaceConbox text-center\">\n                <span>We provide an easy to use &amp; pleasant user friendly experience to manage your business in a natural and intuitive way. &nbsp;You can manage your large volume</span>\n              </div>\n              <a href=\"#\"><button type=\"button\" class=\"btnRead btn-four\">Read more</button></a>\n            </div>\n          </div>\n          <div class=\"col-sm-6 col-md-3 text-center box_two\">\n            <div class=\"businessBoxfour\">\n              <div class=\"SecIMG\">\n                <div class=\"imgBG\">\n                </div>\n                <img src=\"/public/views/cms/assets/images/02.png\" alt=\"interface\" class=\"interfaceImgfour\">\n              </div>\n              <h2>Manage your assets! </h2>\n              <div class=\"interfaceConbox text-center\">\n                <span>For every business its important to manage your assets. Our web portal provides you to manage your orders and export them to .txt or . </span>\n              </div>\n              <a href=\"#\"><button type=\"button\" class=\"btnRead btn-four\">Read more</button></a>\n            </div>\n          </div>\n          <div class=\"col-sm-6 col-md-3 text-center box_three\">\n            <div class=\"businessBoxfour\">\n              <div class=\"SecIMG\">\n                <div class=\"imgBG\">\n                </div>\n                <img src=\"/public/views/cms/assets/images/03.png\" alt=\"interface\" class=\"interfaceImgfour\">\n              </div>\n              <h2>Let your business grow! </h2>\n              <div class=\"interfaceConbox text-center\">\n                <span>For better growth of any business you need a dedicated team of professional to promote your business, on the other hand you also need a</span>\n              </div>\n              <a href=\"#\"><button type=\"button\" class=\"btnRead btn-four\">Read more</button></a>\n            </div>\n          </div>\n          <div class=\"col-sm-6 col-md-3 text-center box_four\">\n            <div class=\"businessBoxfour\">\n              <div class=\"SecIMG\">\n                <div class=\"imgBG\">\n                </div>\n                <img src=\"/public/views/cms/assets/images/04.png\" alt=\"interface\" class=\"interfaceImgfour\">\n              </div>\n              <h2>Best price Guarantee </h2>\n              <div class=\"interfaceConbox text-center\">\n                <span>MEI.PK is known for its best price in the market. If you still find less/cheaper price any where in the industry, feel free to contact us. We will try our best to provide </span>\n              </div>\n              <a href=\"#\"><button type=\"button\" class=\"btnRead btn-four\">Read more</button></a>\n            </div>\n          </div>\n        </div>\n      </div>\n      </div>\n</div>\n\n<div class=\"row clearfix\">\n	<div class=\"divService\">\n	<div class=\"servicesBg\">\n    </div>\n    <div class=\"SecSerivces\">\n      <div class=\"container\">\n        <div class=\"ServicesTitle text-center\">\n          <h1>OUR SERVICES</h1>\n          <p>&nbsp;We provide a wide range of services to fulfill your business needs. </p>\n          <p>&nbsp;Following are our some of the services we offer.</p>\n        </div>\n        <div class=\"serVicesBox\">\n          <div class=\"col-xs-6 col-sm-6 col-md-3 text-center\">\n            <div class=\"servicesBoxfour\">\n              <div class=\"IMRIicon\">\n                <i class=\"icon ion-locked\" aria-hidden=\"true\"></i>\n                <h3><a href=\"#\" title=\"\">IMEI SERVICES</a></h3>\n              </div>\n            </div>\n          </div>\n          <div class=\"col-xs-6 col-sm-6 col-md-3 text-center\">\n            <div class=\"servicesBoxfour\">\n              <div class=\"IMRIicon\">\n                <i class=\"icon ion-ios-paper\" aria-hidden=\"true\"></i>\n                <h3><a href=\"#\">FILE SERVICES</a> </h3>\n              </div>\n            </div>\n          </div>\n          <div class=\"col-xs-6 col-sm-6 col-md-3 text-center\">\n            <div class=\"servicesBoxfour\">\n              <div class=\"IMRIicon\">\n                <i class=\"icon ion-cube\" aria-hidden=\"true\"></i>\n                <h3><a href=\"#\" title=\"\">SERVER LOGS</a></h3>\n              </div>\n            </div>\n          </div>\n          <div class=\"col-xs-6 col-sm-6 col-md-3 text-center\">\n            <div class=\"servicesBoxfour\">\n              <div class=\"IMRIicon\">\n                <i class=\"icon ion-shuffle\" aria-hidden=\"true\"></i>\n                <h3><a href=\"#\" title=\"\">PREPAID LOGS</a></h3>\n              </div>\n            </div>\n          </div>\n        </div>\n      </div>\n    </div>\n    </div>\n</div>',0,9,'2016-10-10 07:41:03'),
	(20,'Login','login','','<div aria-hidden=\"true\" aria-labelledby=\"myModalLabel\" role=\"dialog\" tabindex=\"-1\" id=\"searchPanel\" class=\"modal fade\">\n    <div class=\"modal-dialog\">\n        <div class=\"modal-content\">\n            <div class=\"modal-header\">\n                <button aria-hidden=\"true\" data-dismiss=\"modal\" class=\"close\" type=\"button\"><i class=\"icon-remove\"></i></button>\n                <h4 class=\"modal-title\"> Forgot Password</h4>\n            </div>\n            <div class=\"modal-body\">\n                <form action=\"/user/password_recover_process.do\" method=\"post\" class=\"noAutoLoad\">\n\n                    <div class=\"form-group\">\n                        <label>User Name</label>\n                        <input name=\"username\" class=\"form-control\" id=\"username\" required=\"\" placeholder=\"Enter Your Username To Recover Password Via Email\" type=\"text\">\n                    </div>\n                    <div class=\"form-group\">\n                        <label>Email</label>\n                        <input name=\"email\" class=\"form-control\" id=\"email\" required=\"\" placeholder=\"Email Registered With The Above Username\" type=\"text\">\n                    </div>\n                    <input value=\"Get Password Via E-mail\" class=\"btn btn-success\" type=\"submit\">\n                </form>\n            </div>\n        </div>\n    </div>\n</div>\n\n<div class=\"row clearfix column\">\n	<div class=\"loginbg\">\n      <div class=\"container\">\n        <div class=\"whiteBG\">\n          <div class=\"BGpurple text-center\">\n            <h3>Sign In To <span> [[sitename]]</span></h3>\n            <h4>User Panel</h4>\n          </div>\n          <form action=\"/user/login_process.do\" method=\"post\">\n          <div class=\"lgoinForm clearfix\">\n            \n              <div class=\"form-group is-empty\">\n                <input class=\"form-control Themeone\" name=\"username\" placeholder=\"Enter your username\" required=\"\" type=\"text\">\n              </div>\n              <div class=\"form-group is-empty\">\n                <input class=\"form-control Themeone\" name=\"password\" placeholder=\"Enter your Password\" required=\"\" type=\"password\">\n              </div>\n            \n            <div class=\"ckeckDiv clearfix\">\n              <div class=\"checkbox pull-left\">\n                <label>\n                  <input name=\"stay_signed_in\" value=\"1\" class=\"ckeckerem\" type=\"checkbox\">\n                  Remember Me </label>\n              </div>\n              <div class=\"forgot pull-right\">\n                <a href=\"#searchPanel\" data-toggle=\"modal\">Forgot Password </a>\n              </div>\n            </div>\n            <div class=\"buttonLogin clearfix\">\n              <button type=\"submit\" class=\"btn-loginForm\">LOG IN</button>\n              <a href=\"register.html\"><button type=\"button\" class=\"btn-signForm\">SIGN UP</button></a>\n            </div>\n          </div>\n          </form>\n        </div>\n      </div>\n    </div>\n</div>',0,9,'2016-10-10 09:17:08'),
	(21,'Register','register','','<div class=\"row clearfix column\"><div class=\"loginbg\">\n    <div class=\"container\">\n      <div class=\"whiteBGsign\">\n        <div class=\"BGpurplesign text-center\">\n          <h3>Sign Up To <span> [[sitename]]</span></h3>\n          <h4>User Panel</h4>\n        </div>\n        <form id=\"signupform\" role=\"form\" action=\"/user/register_process.do\" method=\"post\">\n          <div class=\"lgoinForm clearfix\">\n          <div class=\"clearfix\">\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\">\n                <input name=\"first_name\" class=\"form-control Themeone\" placeholder=\"First Name *\" required=\"\" type=\"text\">\n              </div>\n            </div>\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\">\n                <input name=\"last_name\" class=\"form-control Themeone\" placeholder=\"Last name *\" required=\"\" type=\"text\">\n              </div>\n            </div>\n            </div>\n            \n            <div class=\"clearfix\">\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\">\n                <input name=\"username\" class=\"form-control Themeone\" placeholder=\"User name *\" required=\"\" type=\"text\">\n              </div>\n            </div>\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\">\n                <input name=\"email\" class=\"form-control Themeone\" placeholder=\"Email *\" required=\"\" type=\"email\">\n              </div>\n            </div>\n            </div>\n            \n            <div class=\"clearfix\">\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\">\n                <input name=\"password_new\" class=\"form-control Themeone\" placeholder=\"Password *\" required=\"\" type=\"password\">\n              </div>\n            </div>\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\">\n                <input name=\"password_confim\" class=\"form-control Themeone\" placeholder=\"Confirm password *\" required=\"\" type=\"password\">\n              </div>\n            </div>\n            </div>\n            \n            <div class=\"clearfix\">\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\"> [[country]] </div>\n            </div>\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\"> [[language]] </div>\n            </div>\n            </div>\n            \n            <div class=\"clearfix\">\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\"> [[currency]] </div>\n            </div>\n            <div class=\"col-md-6\">\n              <div class=\"form-group is-empty\"> [[timezone]] </div>\n            </div>\n            </div>\n            \n            <div class=\"clearfix\">\n              <div class=\"col-md-6\">\n                <div class=\"form-group is-empty\">\n                  <input name=\"city\" class=\"form-control Themeone\" placeholder=\"City *\" required=\"\" type=\"text\">\n                </div>\n              </div>\n              <div class=\"col-md-6\">\n                <div class=\"form-group is-empty\">\n                  <input name=\"phone\" class=\"form-control Themeone\" placeholder=\"Phone *\" required=\"\" type=\"text\">\n                </div>\n              </div>\n            </div>\n            <div class=\"clearfix\">\n            [[additional_fields]]\n            </div>\n            <div class=\"clearfix\">\n              <div class=\"col-md-6\"> <img src=\"captcha_image.do?rand=[[rand]]\" alt=\"code\" class=\"codeImg\" id=\"captchaimg\"> <a href=\"javascript: refreshCaptcha();\" class=\"Clickcode\">Cannot Read The Image Click Here To Refresh</a> </div>\n              <div class=\"col-md-6\">\n                <div class=\"form-group is-empty\">\n                  <input name=\"captchaCode\" class=\"form-control Themeone\" placeholder=\"Captcha *\" required=\"\" type=\"text\">\n                </div>\n              </div>\n            </div>\n            [[terms_service]]\n            <div class=\"buttonLogin clearfix\">\n              <button type=\"submit\" class=\"btn-loginForm\">CREATE ACCOUNT</button>\n              <a href=\"login.html\">\n              <button type=\"button\" class=\"btn-signForm\"> GO TO LOGIN PAGE</button>\n              </a> </div>\n          </div>\n        </form>\n      </div>\n    </div>\n  </div></div>\n<script type=\"text/javascript\">\n    function refreshCaptcha()\n    {\n        var img = document.images[\'captchaimg\'];\n        img.src = img.src.substring(0, img.src.lastIndexOf(\"?\")) + \"?rand=\" + Math.random() * 1000;\n    }\n</script>',0,9,'2016-10-10 09:18:54'),
	(22,'Footer Style 3','','','<div class=\"col-md-12 FootBG\"><div class=\"row\">\n        <div class=\"container Footpt\">\n          <div class=\"col-sm-6 col-md-6 text-left\">\n            <div class=\"FootTitlefour\">\n              <h1><a href=\"#\" class=\"Footlogo\" title=\"\">WHY US?</a></h1>\n              <p> </p><div class=\"edit\"><p>Our main focus is wholesale, seen other sites selling imei unlocks? Chances are big they buy the unlock from us: We supply 70% of the unlocking sites who sell retail. Buying from us, means buying from the source. This reduces Costs and turnaround time.</p></div> <p></p>\n            </div>\n          </div>\n          <div class=\"col-sm-6 col-md-offset-2 col-md-4 text-left\">\n            <div class=\"footContactfour\">\n              <h2>FOLLOW US</h2>\n            </div>\n            <div class=\"bgBtnfoot\">\n              <a href=\"#\"><button type=\"button\" class=\"btn-login FoutBtn\">ABOUT US</button></a>\n              <a href=\"#\"><button type=\"button\" class=\"btn-down FoutBtn\">SEND EMAIL</button></a>\n            </div>\n            <div class=\"SocialFoot\">\n              <a href=\"https://www.facebook.com/testpage\"><img src=\"/public/views/cms/assets/images/facebook.png\" alt=\"facebook\" class=\"footICon\"></a> <a href=\"https://twitter.com/login\"><img src=\"/public/views/cms/assets/images/tweet.png\" alt=\"facebook\" class=\"footICon\"></a> <a href=\"https://plus.google.com/\"><img src=\"/public/views/cms/assets/images/G-plus.png\" alt=\"facebook\" class=\"footICon\"></a> <a href=\"https://www.youtube.com/channel/UCRNeXX-0F9hTpLXGRkWeJbw\"><img src=\"/public/views/cms/assets/images/youtube.png\" alt=\"facebook\" class=\"footICon\"></a>\n            </div>\n          </div>\n        </div>\n        <hr class=\"FootBorderfour\">\n      </div>\n      <div class=\"CopyRightfour text-center\">\n        <span>&nbsp;[[sitename]] © 2016</span>\n      </div></div>',0,9,'2016-10-10 09:36:54'),
	(24,'Team','team','','<div class=\"row clearfix\">\n		<div class=\"column full center\">\n            	<h1 style=\"margin:1.2em 0;font-size:3em\">Meet The Team</h1>\n        </div>\n	</div>\n	\n	<div class=\"row clearfix\"><div class=\"column third\">\n            <div class=\"is-card is-dark-text\">\n				<div class=\"margin-25 center\">\n					<img src=\"/public/views/cms/assets/minimalist-basic/z05-1.jpg\" style=\"border-radius: 500px;\" alt=\"\">			\n			    	<h3 style=\"font-size: 16px;\">Administrator<br></h3><h3 style=\"font-size: 24px;\">Cris</h3>\n					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n					<div class=\"is-social edit\" style=\"margin:2em 0\">\n                		<a href=\"https://twitter.com/\"><i class=\"icon ion-social-twitter\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"https://www.facebook.com/\"><i class=\"icon ion-social-facebook\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"https://plus.google.com/\"><i class=\"icon ion-social-googleplus\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"mailto:you@example.com\"><i class=\"icon ion-ios-email\"></i></a>\n					</div>              \n        		</div>\n        	</div>\n		</div>\n\n		<div class=\"column third\">\n            <div class=\"is-card is-dark-text\">\n				<div class=\"margin-25 center\">\n			    	<span style=\"font-size: 18px;\"><span style=\"border-radius: 500px;\"><img src=\"/public/views/cms/assets/minimalist-basic/z05-2.jpg\" style=\"border-radius: 500px;\" alt=\"\"></span>		\n			     	<h3 style=\"font-size: 16px;\">Programer<br></h3><h3 style=\"font-size: 24px;\">Ever</h3>\n					<p style=\"font-size: 16px;\">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n            		<div class=\"is-social edit\" style=\"font-size: 16px; margin: 2em 0px;\">\n                		<a href=\"https://twitter.com/\"><i class=\"icon ion-social-twitter\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"https://www.facebook.com/\"><i class=\"icon ion-social-facebook\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"https://plus.google.com/\"><i class=\"icon ion-social-googleplus\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"mailto:you@example.com\"><i class=\"icon ion-ios-email\"></i></a>\n					</div>    \n        		</span></div>\n        	</div>\n		</div>\n\n		<div class=\"column third\">\n            <div class=\"is-card is-dark-text\">\n				<div class=\"margin-25 center\">\n			  		<img src=\"/public/views/cms/assets/minimalist-basic/z05-3.jpg\" style=\"border-radius: 500px;\" alt=\"\">\n			   	 	<h3 style=\"font-size: 16px;\">Customer Service<br></h3><h3 style=\"font-size: 24px;\">Alice</h3>\n					<p style=\"font-size: 16px;\">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\n            		<div class=\"is-social edit\" style=\"font-size: 16px; margin: 2em 0px;\">\n					<div class=\"is-social edit\" style=\"margin:2em 0\">\n                		<a href=\"https://twitter.com/\"><i class=\"icon ion-social-twitter\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"https://www.facebook.com/\"><i class=\"icon ion-social-facebook\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"https://plus.google.com/\"><i class=\"icon ion-social-googleplus\" style=\"margin-right: 1em\"></i></a>\n                		<a href=\"mailto:you@example.com\"><i class=\"icon ion-ios-email\"></i></a>\n					</div>         \n        		</div>\n         	</div>\n		</div></div></div>\n\n    \n\n    <div class=\"row clearfix\">\n\n        <div class=\"column full\">\n\n            <hr>\n\n        </div>\n\n    </div>\n\n\n\n\n\n	<div class=\"row clearfix\"><div class=\"column full center\">\n\n            		    <div class=\"display\">\n\n                		    <p><span style=\"font-size: 35px;\">Our Team is Everything</span></p><p><span style=\"font-size: 14px;\">All we have is because of our team. All our team work properly with full responsibility of everthing without our team we are not completed. Our team is always available for your help and everyone of our is availble for customers support.</span><br></p></div>\n\n                </div></div>\n\n	<div class=\"row clearfix\">\n\n		<div class=\"column full center\">\n\n            		<div class=\"clearfix is-rounded-button-medium\">\n\n                		<a href=\"https://twitter.com/\" style=\"background-color: #00bfff\"><i class=\"icon ion-social-twitter\"></i></a>\n\n                		<a href=\"https://www.facebook.com/\" style=\"background-color: #128BDB\"><i class=\"icon ion-social-facebook\"></i></a>\n\n						<a href=\"https://plus.google.com/\" style=\"background-color: #DF311F\"><i class=\"icon ion-social-googleplus\"></i></a>           			\n\n				</div>\n\n       </div>\n\n	</div>',0,9,'2016-10-10 14:46:20'),
	(25,'Privacy Policy','privacy','','<div class=\"row clearfix\">\n		<div class=\"column full\">\n            <h1 class=\"size-32 is-title5-32 is-title-bold\">PRIVACY POLICY</h1>\n            <p>We understand that security and privacy are important issues for visitors of our web site and undertakes to keep your information secure and confidential. That is why we maintain the following standards to protect personal information. We reserve the right to change this Privacy Policy at any time without prior notice.</p><h2 style=\"margin-top: 0px; margin-right: 0px; margin-left: 0px; padding: 10px 0px 0px; border: 0px; font-stretch: normal; font-size: 20px; line-height: normal; font-family: Arial; vertical-align: baseline; color: rgb(51, 51, 51);\">Collection of Information</h2><p style=\"padding: 0px; border: 0px; font-variant-numeric: inherit; font-stretch: inherit; line-height: 20px; font-family: Arial, Helvetica, sans-serif; vertical-align: baseline;\">The information we learn from customers helps us to perform transactions, dispatch goods and provide customer support and continually improve your shopping experience at our online store. Here are the types of information we collect:</p><ul style=\"margin-right: 0px; margin-left: 15px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-weight: normal; font-stretch: inherit; font-size: medium; font-family: Arial, Helvetica, sans-serif; vertical-align: baseline; list-style: none; color: rgb(0, 0, 0);\"><li style=\"margin: 0px 0px 0px 15px; padding: 0px; border: 0px; font-stretch: normal; font-size: 14px; line-height: 24px; font-family: Arial; vertical-align: baseline; list-style-type: disc; color: rgb(51, 51, 51);\"><strong style=\"margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Information you give us.</strong>&nbsp;We receive and store any information you enter on our web site or give us in any other way. We use the information that you provide for such purposes as responding to your requests, customizing future shopping for you, improving our service, and communicating with you.</li><li style=\"margin: 0px 0px 0px 15px; padding: 0px; border: 0px; font-stretch: normal; font-size: 14px; line-height: 24px; font-family: Arial; vertical-align: baseline; list-style-type: disc; color: rgb(51, 51, 51);\"><strong style=\"margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Automatic information.</strong>&nbsp;We receive and store certain types of information whenever you interact with us. It includes the Internet protocol (IP) address used to connect your computer to the Internet, computer and connection information such as browser type, version, and time zone setting, operating system, etc. This information is used for administration, analysis and website improvement.</li><li style=\"margin: 0px 0px 0px 15px; padding: 0px; border: 0px; font-stretch: normal; font-size: 14px; line-height: 24px; font-family: Arial; vertical-align: baseline; list-style-type: disc; color: rgb(51, 51, 51);\"><strong style=\"margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-stretch: inherit; font-size: inherit; line-height: inherit; font-family: inherit; vertical-align: baseline;\">Information from other sources.</strong>&nbsp;We might receive information about you from other sources and add it to our account information. We can use updated delivery and address information from our carriers or other third parties to correct our records and deliver your next purchase more quickly and communicate with you more easily.</li></ul><h2 style=\"margin-top: 0px; margin-right: 0px; margin-left: 0px; padding: 10px 0px 0px; border: 0px; font-stretch: normal; font-size: 20px; line-height: normal; font-family: Arial; vertical-align: baseline; color: rgb(51, 51, 51);\"><a name=\"use\" style=\"margin: 0px; padding: 0px; border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: 14px; line-height: inherit; vertical-align: baseline; outline: none; color: rgb(51, 153, 204);\"></a>Use of Information</h2><p style=\"padding: 0px; border: 0px; font-variant-numeric: inherit; font-stretch: inherit; line-height: 20px; font-family: Arial, Helvetica, sans-serif; vertical-align: baseline;\">our company uses your personal information for the following purposes: to process your orders and administer your account, to administer and improve the site and related services, to notify you of our products, services, promotional events or special offers that may interest you, etc.</p><p style=\"padding: 0px; border: 0px; font-variant-numeric: inherit; font-stretch: inherit; line-height: 20px; font-family: Arial, Helvetica, sans-serif; vertical-align: baseline;\"></p><div class=\"edit\"><h2><span style=\"font-size: 20px;\">Security of Information </span></h2><p>Our Company is committed to protect customer personal information.</p><ul><li>We implement the following security measures: secure servers, firewalls and other technologies and procedures to protect customer personal information from unauthorized access, use, or disclosure.</li></ul><p>Please Note! Be sure to sign off when finished using a shared computer in order to protect your account against unauthorized access.</p><p><br></p><p><b><i><u>IF YOU HAVE ANY DOUBT OR NEED MORE INFORMATION SO PLEASE CONTACT WITH US TO ASK MORE DETAIL!</u></i></b></p></div><br><p></p>\n        </div>\n	</div>',0,9,'2016-10-10 15:20:39');

/*!40000 ALTER TABLE `nxt_cms_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_cms_menu_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_cms_menu_master`;

CREATE TABLE `nxt_cms_menu_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `json` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logo` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_cms_menu_master` WRITE;
/*!40000 ALTER TABLE `nxt_cms_menu_master` DISABLE KEYS */;

INSERT INTO `nxt_cms_menu_master` (`id`, `json`, `date`, `logo`)
VALUES
	(1,'[{\"label\":\"Home\",\"page\":\"1\",\"url\":\"/\",\"childs\":[]},{\"label\":\"Products\",\"page\":\"3\",\"url\":\"\",\"childs\":[{\"label\":\"IMEI Service\",\"page\":\"\",\"url\":\"http://old.imei.pk/products_imei.html\"},{\"label\":\"File Service\",\"page\":\"\",\"url\":\"http://old.imei.pk/products_file.html\"},{\"label\":\"Server Log\",\"page\":\"\",\"url\":\"http://old.imei.pk/products_server_logs.html\"},{\"label\":\"Prepaid Log\",\"page\":\"\",\"url\":\"http://old.imei.pk/products_prepaid_logs.html\"}]},{\"label\":\"Login\",\"page\":\"20\",\"url\":\"\",\"childs\":[]},{\"label\":\"Register\",\"page\":\"21\",\"url\":\"\",\"childs\":[]}]','2016-10-01 10:45:17','461350.png');

/*!40000 ALTER TABLE `nxt_cms_menu_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_cms_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_cms_settings`;

CREATE TABLE `nxt_cms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config` varchar(500) NOT NULL,
  `value` varchar(500) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_cms_settings` WRITE;
/*!40000 ALTER TABLE `nxt_cms_settings` DISABLE KEYS */;

INSERT INTO `nxt_cms_settings` (`id`, `config`, `value`, `date`)
VALUES
	(1,'header_collapsed','0','2016-10-06 10:11:07'),
	(2,'website_color','423e9c','2016-10-06 13:11:49'),
	(3,'header_background','423e9c','2016-10-07 19:25:47'),
	(4,'menu_color','','2016-10-07 19:26:02'),
	(5,'active_footer','17','2016-10-08 13:59:57');

/*!40000 ALTER TABLE `nxt_cms_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_config_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_config_master`;

CREATE TABLE `nxt_config_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `value_int` int(11) NOT NULL,
  `value_float` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_config_master` WRITE;
/*!40000 ALTER TABLE `nxt_config_master` DISABLE KEYS */;

INSERT INTO `nxt_config_master` (`id`, `field`, `value`, `value_int`, `value_float`)
VALUES
	(1,'USER_NOTES','1',0,0),
	(2,'ADMIN_NOTES','1',0,0),
	(3,'TER_CON','BLAH BLAH',1,0);

/*!40000 ALTER TABLE `nxt_config_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_country_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_country_master`;

CREATE TABLE `nxt_country_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `countries_iso_code_2` char(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `credits` double unsigned NOT NULL DEFAULT '0',
  `addl` double unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_country_master` WRITE;
/*!40000 ALTER TABLE `nxt_country_master` DISABLE KEYS */;

INSERT INTO `nxt_country_master` (`id`, `countries_name`, `countries_iso_code_2`, `credits`, `addl`, `status`)
VALUES
	(1,'Afghanistan','AF',18,6,1),
	(2,'Albania','AL',18,6,1),
	(3,'Algeria','DZ',18,6,1),
	(4,'American Samoa','AS',18,6,1),
	(5,'Andorra','AD',18,6,1),
	(6,'Angola','AO',18,6,1),
	(7,'Anguilla','AI',18,6,1),
	(9,'Antigua and Barbuda','AG',6,2,1),
	(10,'Argentina','AR',18,6,1),
	(11,'Armenia','AM',67,2,1),
	(12,'Aruba','AW',56,2,1),
	(13,'Australia','AU',256,2,1),
	(14,'Austria','AT',234,2,1),
	(15,'Azerbaijan','AZ',18,6,1),
	(16,'Bahamas','BS',18,6,1),
	(17,'Bahrain','BH',18,5,1),
	(18,'Bangladesh','BD',18,4,1),
	(19,'Barbados','BB',18,6,1),
	(20,'Belarus','BY',18,6,1),
	(21,'Belgium','BE',18,4,1),
	(22,'Belize','BZ',18,6,1),
	(23,'Benin','BJ',18,6,1),
	(24,'Bermuda','BM',18,6,1),
	(25,'Bhutan','BT',18,6,1),
	(26,'Bolivia','BO',18,6,1),
	(27,'Bosnia and Herzegowina','BA',18,6,1),
	(28,'Botswana','BW',18,6,1),
	(29,'Bouvet Island','BV',18,6,1),
	(30,'Brazil','BR',18,6,1),
	(31,'British Indian Ocean Territory','IO',18,6,1),
	(32,'Brunei Darussalam','BN',18,6,1),
	(33,'Bulgaria','BG',18,6,1),
	(34,'Burkina Faso','BF',18,6,1),
	(35,'Burundi','BI',18,6,1),
	(36,'Cambodia','KH',18,6,1),
	(37,'Cameroon','CM',18,6,1),
	(38,'Canada','CA',18,6,1),
	(39,'Cape Verde','CV',18,6,1),
	(40,'Cayman Islands','KY',18,6,1),
	(41,'Central African Republic','CF',17,6,1),
	(42,'Chad','TD',17,6,1),
	(43,'Chile','CL',17,6,1),
	(44,'China','CN',17,6,1),
	(45,'Christmas Island','CX',17,6,1),
	(46,'Cocos (Keeling) Islands','CC',17,6,1),
	(47,'Colombia','CO',17,6,1),
	(48,'Comoros','KM',17,6,1),
	(49,'Congo','CG',17,6,1),
	(50,'Cook Islands','CK',17,6,1),
	(51,'Costa Rica','CR',17,6,1),
	(52,'Cote D\'Ivoire','CI',17,6,1),
	(53,'Croatia','HR',17,6,1),
	(54,'Cuba','CU',17,6,1),
	(55,'Cyprus','CY',17,6,1),
	(56,'Czech Republic','CZ',17,6,1),
	(57,'Denmark','DK',17,6,1),
	(58,'Djibouti','DJ',17,6,1),
	(59,'Dominica','DM',17,6,1),
	(60,'Dominican Republic','DO',17,6,1),
	(61,'East Timor','TP',17,6,1),
	(62,'Ecuador','EC',17,6,1),
	(63,'Egypt','EG',17,6,1),
	(64,'El Salvador','SV',17,6,1),
	(65,'Equatorial Guinea','GQ',17,6,1),
	(66,'Eritrea','ER',17,6,1),
	(67,'Estonia','EE',17,6,1),
	(68,'Ethiopia','ET',17,6,1),
	(69,'Falkland Islands (Malvinas)','FK',17,6,1),
	(70,'Faroe Islands','FO',17,6,1),
	(71,'Fiji','FJ',17,6,1),
	(72,'Finland','FI',17,6,1),
	(73,'France','FR',17,6,1),
	(74,'France, Metropolitan','FX',17,6,1),
	(75,'French Guiana','GF',17,6,1),
	(76,'French Polynesia','PF',17,6,1),
	(77,'French Southern Territories','TF',17,6,1),
	(78,'Gabon','GA',17,6,1),
	(79,'Gambia','GM',17,6,1),
	(80,'Georgia','GE',17,6,1),
	(81,'Germany','DE',17,6,1),
	(82,'Ghana','GH',17,6,1),
	(83,'Gibraltar','GI',17,6,1),
	(84,'Greece','GR',17,6,1),
	(85,'Greenland','GL',17,6,1),
	(86,'Grenada','GD',17,6,1),
	(87,'Guadeloupe','GP',17,6,1),
	(88,'Guam','GU',17,6,1),
	(89,'Guatemala','GT',17,6,1),
	(90,'Guinea','GN',17,6,1),
	(91,'Guinea-bissau','GW',17,6,1),
	(92,'Guyana','GY',17,6,1),
	(93,'Haiti','HT',17,6,1),
	(94,'Heard and Mc Donald Islands','HM',17,6,1),
	(95,'Honduras','HN',17,6,1),
	(96,'Hong Kong','HK',17,6,1),
	(97,'Hungary','HU',17,6,1),
	(98,'Iceland','IS',17,6,1),
	(99,'India','IN',1,1,1),
	(100,'Indonesia','ID',17,6,1),
	(101,'Iran (Islamic Republic of)','IR',17,6,1),
	(102,'Iraq','IQ',17,6,1),
	(103,'Ireland','IE',17,6,1),
	(104,'Israel','IL',17,6,1),
	(105,'Italy','IT',17,6,1),
	(106,'Jamaica','JM',17,6,1),
	(107,'Japan','JP',17,6,1),
	(108,'Jordan','JO',17,6,1),
	(109,'Kazakhstan','KZ',17,6,1),
	(110,'Kenya','KE',17,6,1),
	(111,'Kiribati','KI',17,6,1),
	(112,'Korea, Democratic People\'s Republic of','KP',17,6,1),
	(113,'Korea, Republic of','KR',17,6,1),
	(114,'Kuwait','KW',17,6,1),
	(115,'Kyrgyzstan','KG',17,6,1),
	(116,'Lao People\'s Democratic Republic','LA',17,6,1),
	(117,'Latvia','LV',17,6,1),
	(118,'Lebanon','LB',17,6,1),
	(119,'Lesotho','LS',17,6,1),
	(120,'Liberia','LR',17,6,1),
	(121,'Libyan Arab Jamahiriya','LY',17,6,1),
	(122,'Liechtenstein','LI',17,6,1),
	(123,'Lithuania','LT',17,6,1),
	(124,'Luxembourg','LU',17,6,1),
	(125,'Macau','MO',17,6,1),
	(126,'Macedonia, The Former Yugoslav Republic of','MK',17,6,1),
	(127,'Madagascar','MG',17,6,1),
	(128,'Malawi','MW',17,6,1),
	(129,'Malaysia','MY',17,6,1),
	(130,'Maldives','MV',17,6,1),
	(131,'Mali','ML',17,6,1),
	(132,'Malta','MT',17,6,1),
	(133,'Marshall Islands','MH',17,6,1),
	(134,'Martinique','MQ',17,6,1),
	(135,'Mauritania','MR',17,6,1),
	(136,'Mauritius','MU',17,6,1),
	(137,'Mayotte','YT',17,6,1),
	(138,'Mexico','MX',17,6,1),
	(139,'Micronesia, Federated States of','FM',17,6,1),
	(140,'Moldova, Republic of','MD',17,6,1),
	(141,'Monaco','MC',17,6,1),
	(142,'Mongolia','MN',17,6,1),
	(143,'Montserrat','MS',17,6,1),
	(144,'Morocco','MA',17,6,1),
	(145,'Mozambique','MZ',17,6,1),
	(146,'Myanmar','MM',17,6,1),
	(147,'Namibia','NA',17,6,1),
	(148,'Nauru','NR',17,6,1),
	(149,'Nepal','NP',17,6,1),
	(150,'Netherlands','NL',17,6,1),
	(151,'Netherlands Antilles','AN',17,6,1),
	(152,'New Caledonia','NC',17,6,1),
	(153,'New Zealand','NZ',17,6,1),
	(154,'Nicaragua','NI',17,6,1),
	(155,'Niger','NE',17,6,1),
	(156,'Nigeria','NG',17,6,1),
	(157,'Niue','NU',17,6,1),
	(158,'Norfolk Island','NF',17,6,1),
	(159,'Northern Mariana Islands','MP',17,6,1),
	(160,'Norway','NO',17,6,1),
	(161,'Oman','OM',17,6,1),
	(162,'Pakistan','PK',17,6,1),
	(163,'Palau','PW',17,6,1),
	(164,'Panama','PA',17,6,1),
	(165,'Papua New Guinea','PG',17,6,1),
	(166,'Paraguay','PY',17,6,1),
	(167,'Peru','PE',17,6,1),
	(168,'Philippines','PH',17,6,1),
	(169,'Pitcairn','PN',17,6,1),
	(170,'Poland','PL',17,6,1),
	(171,'Portugal','PT',17,6,1),
	(172,'Puerto Rico','PR',17,6,1),
	(173,'Qatar','QA',17,6,1),
	(174,'Reunion','RE',17,6,1),
	(175,'Romania','RO',17,6,1),
	(176,'Russian Federation','RU',17,6,1),
	(177,'Rwanda','RW',17,6,1),
	(178,'Saint Kitts and Nevis','KN',17,6,1),
	(179,'Saint Lucia','LC',17,6,1),
	(180,'Saint Vincent and the Grenadines','VC',17,6,1),
	(181,'Samoa','WS',17,6,1),
	(182,'San Marino','SM',17,6,1),
	(183,'Sao Tome and Principe','ST',17,6,1),
	(184,'Saudi Arabia','SA',17,6,1),
	(185,'Senegal','SN',17,6,1),
	(186,'Seychelles','SC',17,6,1),
	(187,'Sierra Leone','SL',17,6,1),
	(188,'Singapore','SG',17,6,1),
	(189,'Slovakia (Slovak Republic)','SK',17,6,1),
	(190,'Slovenia','SI',17,6,1),
	(191,'Solomon Islands','SB',17,6,1),
	(192,'Somalia','SO',17,6,1),
	(193,'South Africa','ZA',17,6,1),
	(194,'South Georgia and the South Sandwich Islands','GS',17,6,1),
	(195,'Spain','ES',17,6,1),
	(196,'Sri Lanka','LK',17,6,1),
	(197,'St. Helena','SH',17,6,1),
	(198,'St. Pierre and Miquelon','PM',17,6,1),
	(199,'Sudan','SD',17,6,1),
	(200,'Suriname','SR',17,6,1),
	(201,'Svalbard and Jan Mayen Islands','SJ',17,6,1),
	(202,'Swaziland','SZ',17,6,1),
	(203,'Sweden','SE',17,6,1),
	(204,'Switzerland','CH',17,6,1),
	(205,'Syrian Arab Republic','SY',17,6,1),
	(206,'Taiwan','TW',17,6,1),
	(207,'Tajikistan','TJ',17,6,1),
	(208,'Tanzania, United Republic of','TZ',17,6,1),
	(209,'Thailand','TH',17,6,1),
	(210,'Togo','TG',17,6,1),
	(211,'Tokelau','TK',17,6,1),
	(212,'Tonga','TO',17,6,1),
	(213,'Trinidad and Tobago','TT',17,6,1),
	(214,'Tunisia','TN',17,6,1),
	(215,'Turkey','TR',17,6,1),
	(216,'Turkmenistan','TM',17,6,1),
	(217,'Turks and Caicos Islands','TC',17,6,1),
	(218,'Tuvalu','TV',17,6,1),
	(219,'Uganda','UG',17,6,1),
	(220,'Ukraine','UA',17,6,1),
	(221,'United Arab Emirates','AE',18,6,1),
	(222,'United Kingdom','GB',18,6,1),
	(223,'United States','US',18,6,1),
	(224,'United States Minor Outlying Islands','UM',18,6,1),
	(225,'Uruguay','UY',18,6,1),
	(226,'Uzbekistan','UZ',18,6,1),
	(227,'Vanuatu','VU',18,6,1),
	(228,'Vatican City State (Holy See)','VA',18,6,1),
	(229,'Venezuela','VE',18,6,1),
	(230,'Viet Nam','VN',18,6,1),
	(231,'Virgin Islands (British)','VG',18,6,1),
	(232,'Virgin Islands (U.S.)','VI',18,6,1),
	(233,'Wallis and Futuna Islands','WF',18,6,1),
	(234,'Western Sahara','EH',18,6,1),
	(235,'Yemen','YE',18,6,1),
	(236,'Yugoslavia','YU',18,6,1),
	(237,'Zaire','ZR',18,6,1),
	(238,'Zambia','ZM',18,6,1),
	(239,'Zimbabwe','ZW',18,6,1),
	(241,'Bonarie','BC',18,6,1);

/*!40000 ALTER TABLE `nxt_country_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_credit_transection_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_credit_transection_master`;

CREATE TABLE `nxt_credit_transection_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_id2` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_id2` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `credits` float NOT NULL,
  `credits_acc` float NOT NULL,
  `credits_acc_process` float NOT NULL,
  `credits_acc_used` float NOT NULL,
  `credits_after` float NOT NULL,
  `credits_after_process` float NOT NULL,
  `credits_after_used` float NOT NULL,
  `credits_acc_2` float NOT NULL,
  `credits_acc_process_2` float NOT NULL,
  `credits_acc_used_2` float NOT NULL,
  `credits_after_2` float NOT NULL,
  `credits_after_process_2` float NOT NULL,
  `credits_after_used_2` float NOT NULL,
  `order_id_imei` int(11) NOT NULL,
  `order_id_file` int(11) NOT NULL,
  `order_id_server` int(11) NOT NULL,
  `order_id_prepaid` int(11) NOT NULL,
  `info` varchar(100) NOT NULL,
  `trans_type` smallint(6) NOT NULL COMMENT '1: Add, 2: ToProcess, 3: ToUsed, 4: ProcessToCr, 5: UsedToCr',
  `admin_note` varchar(200) NOT NULL,
  `user_note` varchar(200) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `views` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_currency_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_currency_master`;

CREATE TABLE `nxt_currency_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(20) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  `suffix` varchar(5) NOT NULL,
  `rate` float NOT NULL,
  `is_default` bigint(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_currency_master` WRITE;
/*!40000 ALTER TABLE `nxt_currency_master` DISABLE KEYS */;

INSERT INTO `nxt_currency_master` (`id`, `currency`, `prefix`, `suffix`, `rate`, `is_default`, `status`)
VALUES
	(1,'USD','$','',1.09,0,1),
	(2,'EUR','€','',1,1,1),
	(3,'GBP','£','',0.77,0,1);

/*!40000 ALTER TABLE `nxt_currency_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_custom_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_custom_fields`;

CREATE TABLE `nxt_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_type` int(11) DEFAULT NULL,
  `s_id` int(11) DEFAULT NULL,
  `f_type` int(11) DEFAULT NULL,
  `f_name` varchar(100) DEFAULT NULL,
  `f_desc` varchar(300) DEFAULT NULL,
  `f_opt` varchar(500) DEFAULT NULL,
  `f_valid` varchar(100) DEFAULT NULL,
  `f_req` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_email_queue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_email_queue`;

CREATE TABLE `nxt_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_to` varchar(255) NOT NULL,
  `mail_to_cc` varchar(255) NOT NULL,
  `mail_from` varchar(255) NOT NULL,
  `mail_from_display` varchar(255) NOT NULL,
  `mail_subject` text NOT NULL,
  `mail_body` text NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_email_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_email_templates`;

CREATE TABLE `nxt_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `subject` text NOT NULL,
  `mailbody` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `email_by_id` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_email_templates` WRITE;
/*!40000 ALTER TABLE `nxt_email_templates` DISABLE KEYS */;

INSERT INTO `nxt_email_templates` (`id`, `category_id`, `code`, `subject`, `mailbody`, `status`, `email_by_id`)
VALUES
	(1,16,'admin_user_imei_unavail','Your Unlock Code is Not Available :-(','<p>Dear <strong><em>{$username}</em></strong></p>\r\n\r\n<p><br />\r\nYour unlock code is Not Available.<br />\r\n<br />\r\n=================================<br />\r\nIMEI : {$imei}<br />\r\n<em><strong>Reason : &nbsp;</strong></em><strong><em>{$reason}</em></strong><br />\r\nOrders details<br />\r\nOrder ID : {$order_id}<br />\r\nService Name : {$tool_name}<br />\r\nCredits : {$credits}<br />\r\n=================================</p>\r\n',1,2),
	(2,16,'suuplier_user_imei_avail','Your Unlock Code is Available :-)','<p>Dear <strong><em>{$username}</em></strong></p>\r\n\r\n<p><br />\r\nYour unlock code has been successfully calculated.<br />\r\n<br />\r\n=================================<br />\r\nIMEI : {$imei}<br />\r\n<em><strong>Unlock code :&nbsp; {$unlock_code}</strong></em><br />\r\nOrders details<br />\r\nOrder ID : {$order_id}<br />\r\nService Name : {$tool_name}<br />\r\nCredits : {$credits}<br />\r\n<br />\r\n=================================</p>\r\n\r\n',1,2),
	(3,19,'admin_user_edit_login_details','Your Profile details Updated ','<p>Dear {$username}</p>\r\n\r\n<p>Your Profile or Login Detail is&nbsp;Updated</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Username : {$username}</p>\r\n\r\n<p>User Type: {$user_type}</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Please Login &amp; Check Your Profile. if you see something wrong inside.</p>\r\n\r\n<p>so contact with us!</p>\r\n\r\n<p>&nbsp;</p>\r\n',1,2),
	(5,17,'admin_user_order_file_update','Your File Order Successfully Completed','Dear {$username}\r\n\r\nYour unlock code for Bruteforce service has successfully updated\r\n\r\n\r\nOrders details:\r\n============================\r\nOrder ID : {$order_id}\r\nService Name : {$file_service}\r\nCredit : {$credits}  \r\n============================',1,0),
	(14,17,'supplier_user_order_file_update','Your File Order Successfully Completed','Dear {$username}\r\n\r\nYour unlock code for File service is successfully updated\r\n\r\n\r\nOrders details:\r\nOrder ID : {$order_id}\r\nService Name : {$file_service}\r\nCredit : {$credits}   \r\n',1,0),
	(18,23,'admin_user_register_reject','You are Rejected','<p>Dear {$username}</p>\r\n\r\n<p>Your registration has been Rejected due to Security reason!</p>\r\n\r\n<p>For any Question contact us</p>\r\n',1,2),
	(6,17,'admin_user_order_file_reject','Your File Service Order is Rejected','Dear {$username}\r\n\r\nYour File service order has been cancelled!\r\n\r\n\r\nOrders details:\r\n========================\r\nOrder ID : {$order_id}\r\nService Name : {$file_service}\r\nFile Name :{$file_name}\r\nCredit : {$credits}   \r\n========================',1,0),
	(19,24,'admin_user_api_reset','Your API access details','<p>Dear {$username},</p>\r\n\r\n<p>The remote API allows you to request codes, get account information and check the status of your orders without having the need to login to the our site. This allows you to integrate your own site with ours.</p>\r\n\r\n<p>We have made our remote API interface relatively simply to use so anyone with any a little XML and PHP experience should be able to implement and develop a system to use on their site.</p>\r\n\r\n<p>=======================================</p>\r\n\r\n<p>API URL: &nbsp;{$api_url}</p>\r\n\r\n<p>Your API Access Key : {$api_key}</p>\r\n\r\n<p>=======================================&nbsp;</p>\r\n',1,2),
	(7,26,'admin_supplier_add','Your Account Added Successfully','<p>Dear {$username},</p>\r\n\r\n<p>Congratulations!</p>\r\n\r\n<p>Your account has been approved and activated by our Sales Team</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Username : {$username}</p>\r\n\r\n<p>Password : *****</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Now You need to buy credits to use our services.</p>\r\n\r\n<p>Kindly Buy credits To Work With us.</p>\r\n',1,2),
	(8,26,'admin_supplier_password_change','Your Password Successfully Changed.','<p>Dear {$username},</p>\r\n\r\n<p>Your log in password has been Successfully changed.</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Password : {$password}</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>If you have not requested this change, your account most likely has already been compromised.</p>\r\n\r\n<p>You should immediately contact us.</p>\r\n',1,2),
	(9,16,'admin_user_imei_avail','Your Unlock Code is Available :-)','<p>Dear <strong><em>{$username}</em></strong></p>\r\n\r\n<p><br />\r\nYour unlock code has been successfully calculated.<br />\r\n<br />\r\n=================================<br />\r\nIMEI : {$imei}<br />\r\n<em><strong>Unlock code :&nbsp; {$unlock_code}</strong></em><br />\r\nOrders details<br />\r\nOrder ID : {$order_id}<br />\r\nService Name : {$tool_name}<br />\r\nCredits : {$credits}<br />\r\n<br />\r\n=================================</p>\r\n',1,2),
	(10,23,'admin_user_add','Your Registration is Accepted','<p>Dear {$username}</p>\r\n\r\n<p>Congratulations!</p>\r\n\r\n<p>Your account has been approved and activated!</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Username : {$username}</p>\r\n\r\n<p>Password : ********</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>You need to buy credits to use our services.</p>\r\n',1,2),
	(11,20,'admin_user_credit_add','Your Credits Recharged Successfully','<p><em><strong>Dear {$username},</strong></em></p>\r\n\r\n<p>Credits Recharged Successfully in Your User!</p>\r\n\r\n<p>========================</p>\r\n\r\n<p><em><strong>Now Credits Added: {$credits}</strong></em></p>\r\n\r\n<p>Total Credits Available: {$total}</p>\r\n\r\n<p>========================</p>\r\n\r\n<p>Thanks To You!</p>\r\n',1,2),
	(12,20,'admin_user_credit_revoke','Your Credits Rebated Successfully','<p><em><strong>Dear {$username},</strong></em></p>\r\n\r\n<p>Credits Rebated Successfully From&nbsp;Your Account!</p>\r\n\r\n<p>========================</p>\r\n\r\n<p><em><strong>Now Credits Rebated: {$credits}</strong></em></p>\r\n\r\n<p>Total Credits Available After Rebate: {$total}</p>\r\n\r\n<p>========================</p>\r\n\r\n<p>Thanks &amp; Sorry To You!</p>\r\n',1,2),
	(15,1,'admin_order_imei_new','New IMEI Order','<p>There is new IMEI order<br />\r\n<strong>Orders details</strong><br />\r\nIMEI: {$imei}<br />\r\nOrder ID : {$order_id}<br />\r\nService Name : {$tool_name}<br />\r\nCredit : {$credits}<br />\r\n&nbsp;</p>\r\n\r\n<p>Submitted By<br />\r\n{$username}<br />\r\n&nbsp;</p>\r\n',1,2),
	(16,16,'supplier_user_imei_unavail','Your Unlock Code is Not Available :-(','<p>Dear <strong><em>{$username}</em></strong></p>\r\n\r\n<p><br />\r\nYour unlock code is Not Available.<br />\r\n<br />\r\n=================================<br />\r\nIMEI : {$imei}<br />\r\n<em><strong>Reason : &nbsp;</strong></em><strong><em>{$reason}</em></strong><br />\r\nOrders details<br />\r\nOrder ID : {$order_id}<br />\r\nService Name : {$tool_name}<br />\r\nCredits : {$credits}<br />\r\n=================================</p>\r\n',1,2),
	(17,2,'admin_new_file_order','New File Order','<p>There is new File Order:</p>\r\n\r\n<p><strong>Orders details</strong></p>\r\n\r\n<p><strong>============================</strong><br />\r\nFile Name: {$file_name}<br />\r\nOrder ID : {$order_id}<br />\r\nService Name : {$tool_name}<br />\r\nCredit : {$credits}</p>\r\n\r\n<p><strong>?============================</strong></p>\r\n\r\n<p>Submitted By<br />\r\n{$username}<br />\r\n&nbsp;</p>\r\n',1,2),
	(20,18,'admin_user_order_server_log_avail','Your Server Order Successfully Completed','Dear {$username}\r\n\r\nYour server log order is successfully Completed.\r\n\r\nOrders details\r\n==========================\r\nServer Log Name  : {$server_log_name}\r\nCredit : {$credits}\r\nOrder_id:{$order_id}\r\n\r\n===========================\r\n\r\n',1,0),
	(21,18,'admin_user_order_server_log_unavail','Your Server Order is Rejected','Dear {$username}\r\n\r\nYour server log order is Not Found.\r\n\r\nOrders details\r\n===========================\r\nServer Log Name  : {$server_log_name}\r\nCredit : {$credits}\r\nOrder_id:{$order_id}\r\n===========================\r\n ',1,0),
	(22,18,'supplier_user_order_server_log_avail','Your Server Order Successfully Completed','Dear {$username}\r\n\r\nYour server log order is successfully Completed.\r\n\r\nOrders details\r\n==========================\r\nServer Log Name  : {$server_log_name}\r\nCredit : {$credits}\r\nOrder_id:{$order_id}\r\n\r\n===========================',1,0),
	(23,18,'supplier_user_order_server_log_unavail','Your Server Order is Rejected','Dear {$username}\r\n\r\nYour server log order is Not Found.\r\n\r\nOrders details\r\n===========================\r\nServer Log Name  : {$server_log_name}\r\nCredit : {$credits}\r\nOrder_id:{$order_id}\r\n===========================',1,0),
	(24,17,'supplier_user_order_file_avail','Your File Order Successfully Completed','Dear {$username}\r\n\r\nYour unlock code for Bruteforce service has successfully updated\r\n\r\n\r\nOrders details:\r\n============================\r\nOrder ID : {$order_id}\r\nService Name : {$file_service}\r\nCredit : {$credits}  \r\n============================',1,0),
	(25,17,'supplier_user_order_file_unavail','Your File Service Order is Rejected','Dear {$username}\r\n\r\nYour File service order has been cancelled!\r\n\r\n\r\nOrders details:\r\n========================\r\nOrder ID : {$order_id}\r\nService Name : {$file_service}\r\nFile Name :{$file_name}\r\nCredit : {$credits}   \r\n========================',1,0),
	(27,22,'user_close_support_ticket_user','Your Ticket Closed','Your Ticket is Closed\r\n\r\nTicket Details:\r\n===================\r\nTicket: {$ticket_subject}\r\nStatus: Close\r\n===================',1,0),
	(30,22,'admin_close_support_ticket','Your Ticket Closed','Dear {$username} \r\n\r\nYour Ticket closed is Closed by Support\r\n=================== \r\nTicket: {$ticket_subject}\r\nStatus: Closed\r\n=================== \r\n\r\n',1,0),
	(31,19,'user_edit_login_details','Login details Updated Successfully','<p>Dear {$username}</p>\r\n\r\n<p>You Have Changed Your Login Credentials.</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>Username : {$username}</p>\r\n\r\n<p>Password : {$password}</p>\r\n\r\n<p>=============================</p>\r\n\r\n<p>If you have not requested this change or if u did not make this change by your self.</p>\r\n\r\n<p>so your account most likely has already been compromised.</p>\r\n\r\n<p>You should immediately contact us.</p>\r\n',1,2),
	(34,10,'user_add_support_ticket_admin','New Ticket Added','<p>A new Ticket has been added.</p>\r\n\r\n<p>The details of ticket are shown below.</p>\r\n\r\n<p>=======================</p>\r\n\r\n<p>Subject: {$ticket_subject}</p>\r\n\r\n<p>Status: Added</p>\r\n\r\n<p>=======================</p>\r\n\r\n<p>You can view the ticket at any time</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Submitted By: {$username}</p>\r\n',1,2),
	(35,10,'user_add_post_ticket_admin','User Posted on Ticket','<p>User Has Posted on Ticket</p>\r\n\r\n<p>Ticket Details:</p>\r\n\r\n<p>=========================</p>\r\n\r\n<p>Subject: {$ticket_subject}</p>\r\n\r\n<p>Status: Posted</p>\r\n\r\n<p>=========================</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Submitted By: {$username}</p>\r\n',1,2),
	(37,22,'admin_add_post_ticket_user','Admin posted on Ticket','Dear {$username},\r\n\r\nAdmin/Support Has Posted on Ticket\r\n\r\nTicket Details:\r\n=========================\r\nSubject: {$ticket_subject}\r\nStatus: Post\r\n=========================\r\n\r\n',1,0),
	(38,22,'admin_close_support_ticket_user','Your Ticket Closed','Dear {$username}\r\n\r\nYour Ticket closed by Support/Admin\r\n\r\nTicket Details:\r\n===================\r\nTicket: {$ticket_subject}\r\nStatus: Close\r\n===================',1,0),
	(40,20,'user_transfer_credit_user','Credits Transfered successfully','<p>Dear {$username_1},</p>\r\n\r\n<p>Your credits has been transfered successfully to {$username}.</p>\r\n\r\n<p>===========</p>\r\n\r\n<p>credits transfered:{$credits}</p>\r\n\r\n<p>===========</p>\r\n\r\n<p>If you have not requested this change.</p>\r\n\r\n<p>You should immediately contact us.</p>\r\n',1,2),
	(41,9,'user_transfer_credit_admin','Credits Transfered Successfully','<p>{$username_1} has transfered credits successfully to {$username}</p>\r\n\r\n<p>===========</p>\r\n\r\n<p>credits transfered:{$credits}</p>\r\n\r\n<p>===========</p>\r\n',1,2),
	(42,20,'user_transfer_credit_other_user','Credits Recieved successfully','<p>Dear {$username} ,</p>\r\n\r\n<p>you have recieved following credits succesfully in your account from {$username_1}</p>\r\n\r\n<p>===========</p>\r\n\r\n<p>credits transfered:$credits</p>\r\n\r\n<p>===========</p>\r\n\r\n<p>If you have not requested this change.</p>\r\n\r\n<p>You should immediately contact us.</p>\r\n',1,2),
	(56,20,'user_add_credits_user','credits addedd successfully','<p>Dear {$username}</p>\r\n\r\n<p>You have successfully added credits to {username}&nbsp;</p>\r\n\r\n<p>======================</p>\r\n\r\n<p>Credits Added={credits_add}&nbsp;</p>\r\n\r\n<p>======================</p>\r\n\r\n<p>By: {username}</p>\r\n',1,2),
	(57,20,'user_add_credits_other_user','credits recieved successfully','dear {$username}\r\n\r\n{$username_1} have successfully added credits to your account\r\n\r\n===============\r\n\r\nCredits Added={$credits_add}\r\n===============\r\n\r\nBy:\r\n\r\n{$username_1}',1,0),
	(58,20,'user_add_credits_admin','credits addedd successfully','{$username} have successfully added credits to {$username} account\r\n\r\n===============\r\n\r\nCredits Added={$credits_add}\r\n===============\r\n\r\nBy:\r\n\r\n{$username_1}\r\n',1,0),
	(59,20,'user_revoke_credits_user','credits revoked successfully','dear {$username_1}\r\n\r\nYou have successfully revoked credits from {$username} account\r\n\r\n===============\r\n\r\nCredits={$credits_revoke}\r\n===============\r\n\r\nBy:\r\n\r\n{$username_1}',1,0),
	(60,20,'user_revoke_credits_other_user','credits revoked successfully','dear {$username_1}\r\n\r\n{$username} have successfully revoked credits from your account\r\n\r\n===============\r\n\r\nCredits Revoked={$credits_revoke}\r\n===============\r\n\r\nBy:\r\n\r\n{$username_1}',1,0),
	(61,9,'user_revoke_credits_admin','Credits Rebated successfully','<p>{$username_1} have successfully revoked credits from {$username} account</p>\r\n\r\n<p>===============</p>\r\n\r\n<p>Credits Revoked={$credits_revoke}</p>\r\n\r\n<p>===============</p>\r\n',1,2),
	(69,19,'admin_user_news_letter','your password reset successfully','dear {$username}\r\n\r\n .......................\r\n\r\n {$news_letter}\r\n\r\n .......................',1,0),
	(78,19,'password_change','Password updated successfully','Dear {$username},\r\nYour password updated successfully\r\n=======================\r\nUsername:{$username}\r\nPassword:{$password}\r\n\r\n=======================\r\n\r\nBy:\r\n{$site_admin}',1,0),
	(87,16,'imei_service_price_update','IMEI Service Price Update','<p>Dear <strong><em>{$username}</em></strong><br />\nThis email is to notify you about an IMEI service price update.</p>\n\n<p>Details Are Give Below.<br />\n=================================<br />\nGroup Name : {$group_name}<br />\nService Name : {$tool_name}<br />\nNew Price : {$credits}<br />\n=================================</p>\n',1,2);

/*!40000 ALTER TABLE `nxt_email_templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_email_templates_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_email_templates_category`;

CREATE TABLE `nxt_email_templates_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(60) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_email_templates_category` WRITE;
/*!40000 ALTER TABLE `nxt_email_templates_category` DISABLE KEYS */;

INSERT INTO `nxt_email_templates_category` (`id`, `category`, `type`)
VALUES
	(1,'admin : imei',1),
	(2,'admin : file_service',1),
	(3,'admin : server_log',1),
	(4,'admin : prepaid_log',1),
	(5,'admin : mep',1),
	(6,'admin : profile',0),
	(7,'admin : model',1),
	(8,'admin : network',1),
	(9,'admin : credits',1),
	(10,'admin : support_ticket',1),
	(11,'admin : mange reseller',0),
	(12,'admin : api',1),
	(13,'admin : faq',1),
	(14,'admin : country',1),
	(15,'admin : brand',1),
	(16,'user : imei',2),
	(17,'user : file_service',2),
	(18,'user : server_log',2),
	(19,'user : profile',2),
	(20,'user : credit',2),
	(21,'user : ip',2),
	(22,'user : support_ticket',2),
	(23,'user : registration',2),
	(24,'user : api',2),
	(25,'user : prepaid_log',0),
	(26,'supplier : registration',1),
	(27,'admin : reseller',1),
	(28,'admin : supplier',1),
	(29,'user : admin',2);

/*!40000 ALTER TABLE `nxt_email_templates_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_file_extensions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_extensions`;

CREATE TABLE `nxt_file_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_ext` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_file_service_amount_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_service_amount_details`;

CREATE TABLE `nxt_file_service_amount_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `amount_purchase` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_file_service_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_service_master`;

CREATE TABLE `nxt_file_service_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_name` varchar(200) NOT NULL,
  `api_id` int(11) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `delivery_time` varchar(20) NOT NULL,
  `reply_type` tinyint(1) NOT NULL,
  `info` text NOT NULL,
  `faq_id` int(11) NOT NULL,
  `download_link` varchar(200) NOT NULL,
  `notification_email` varchar(200) NOT NULL,
  `verification` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_send_noti` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_file_service_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_service_users`;

CREATE TABLE `nxt_file_service_users` (
  `service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `service_id` (`service_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_file_spl_credits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_spl_credits`;

CREATE TABLE `nxt_file_spl_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `service_id` int(11) unsigned NOT NULL,
  `amount` float unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_file_spl_credits_reseller
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_spl_credits_reseller`;

CREATE TABLE `nxt_file_spl_credits_reseller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `reseller_id` int(11) unsigned NOT NULL,
  `service_id` int(11) unsigned NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;



# Dump of table nxt_file_supplier_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_file_supplier_details`;

CREATE TABLE `nxt_file_supplier_details` (
  `supplier_id` int(11) unsigned NOT NULL,
  `service_id` int(11) unsigned NOT NULL,
  `credits_purchase` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_form_validate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_form_validate`;

CREATE TABLE `nxt_form_validate` (
  `form_id` varchar(50) NOT NULL,
  `form_key` varchar(20) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `supplier_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_validate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_form_validate` WRITE;
/*!40000 ALTER TABLE `nxt_form_validate` DISABLE KEYS */;

INSERT INTO `nxt_form_validate` (`form_id`, `form_key`, `admin_id`, `supplier_id`, `user_id`, `date_validate`)
VALUES
	('services_gateway_edit_54434ghh2','QqC0h20pzGrLnTCd50Mq',1,'',0,'2016-06-14 11:46:14'),
	('user_add_59905855d2','18HmhOLpWIXt07o973x2',1,'',0,'2016-06-14 18:13:56');

/*!40000 ALTER TABLE `nxt_form_validate` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_gateway_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_gateway_details`;

CREATE TABLE `nxt_gateway_details` (
  `user_id` int(11) unsigned NOT NULL,
  `gateway_id` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_gateway_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_gateway_master`;

CREATE TABLE `nxt_gateway_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway` varchar(100) NOT NULL,
  `gateway_id` varchar(255) NOT NULL,
  `charges` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL,
  `demo_mode` tinyint(1) NOT NULL,
  `details` text NOT NULL,
  `logo` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_gateway_master` WRITE;
/*!40000 ALTER TABLE `nxt_gateway_master` DISABLE KEYS */;

INSERT INTO `nxt_gateway_master` (`id`, `gateway`, `gateway_id`, `charges`, `min`, `max`, `demo_mode`, `details`, `logo`, `status`)
VALUES
	(1,'<i class=\"fa fa-paypal\"></i> PayPal','test@gsmunion.net',5,2,9999,0,'Its an Automatic way','pg_paypal.png',1),
	(2,'<i class=\"fa fa-paypal\"></i> PayPal Mass Pay','test@gsmunion.net',0,100,10000,0,'If you already have a Premier or Business PayPal account, you can pay from your account balance without any fee.','pg_paypal_masspay.png',1),
	(3,'Moneybookers','test@gsmunion.net',3,100,10000,0,'Moneybookers is another secure way to pay online.','pg_moneybookers.png',1),
	(4,'<i class=\"fa fa-envelope\"></i> Email','',0,0,0,1,'Don&#39;t have a Paypal account or dont have credit card? Just send credit request via email to use an alternative payment method(Bank Transfer, Western Union, ...)','pg_email.png',0),
	(5,'<i class=\"fa fa-bank\"></i> Manual Bank Transfer','Manual Bank Transfer',0,50,10000,0,'','pg_bank.png',1),
	(6,'RedSys','put here your gateway id',1,10,499,0,'In This Way You Can Pay With Your Credit/Debit Card (Visa,VisaElectron,Master,American Express)\r\n\r\n\r\n\r\n\r\n\r\nOnce Your Payment Paid So Credits Will be Added Instantly Automatic in Your User.\r\n\r\n\r\n24-07-365 Automatic Credits Purchasing.\r\n\r\n\r\n','redsys.png',0);

/*!40000 ALTER TABLE `nxt_gateway_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_imei_brand_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_brand_master`;

CREATE TABLE `nxt_imei_brand_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_faq_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_faq_master`;

CREATE TABLE `nxt_imei_faq_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_group_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_group_master`;

CREATE TABLE `nxt_imei_group_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) NOT NULL,
  `display_order` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_mep_group_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_mep_group_master`;

CREATE TABLE `nxt_imei_mep_group_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mep_group` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_mep_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_mep_master`;

CREATE TABLE `nxt_imei_mep_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mep` varchar(20) NOT NULL,
  `mep_group_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_model_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_model_master`;

CREATE TABLE `nxt_imei_model_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(100) NOT NULL,
  `brand` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_model_master_2
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_model_master_2`;

CREATE TABLE `nxt_imei_model_master_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imei_id` int(11) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  `model_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_network_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_network_master`;

CREATE TABLE `nxt_imei_network_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `network` varchar(200) NOT NULL,
  `country` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_spl_credits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_spl_credits`;

CREATE TABLE `nxt_imei_spl_credits` (
  `user_id` int(11) unsigned NOT NULL,
  `tool_id` int(11) unsigned NOT NULL,
  `currency_id` int(11) NOT NULL,
  `amount` float unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_spl_credits_reseller
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_spl_credits_reseller`;

CREATE TABLE `nxt_imei_spl_credits_reseller` (
  `user_id` int(11) unsigned NOT NULL,
  `tool_id` int(11) unsigned NOT NULL,
  `reseller_id` int(11) NOT NULL,
  `amount` float unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_supplier_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_supplier_details`;

CREATE TABLE `nxt_imei_supplier_details` (
  `supplier_id` int(11) unsigned NOT NULL,
  `tool` int(11) unsigned NOT NULL,
  `credits_purchase` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_tool_amount_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_tool_amount_details`;

CREATE TABLE `nxt_imei_tool_amount_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tool_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `amount` float DEFAULT NULL,
  `amount_purchase` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_tool_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_tool_master`;

CREATE TABLE `nxt_imei_tool_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tool_name` varchar(255) NOT NULL,
  `tool_alias` varchar(255) NOT NULL,
  `delivery_time` varchar(255) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `api_id` int(11) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `api_priority` smallint(6) NOT NULL,
  `brand_id` int(11) NOT NULL COMMENT '-1: All/0: No/# for single',
  `country_id` int(11) NOT NULL COMMENT '-1: All/0: No/# for single',
  `custom_field_name` varchar(50) NOT NULL,
  `custom_field_message` varchar(255) NOT NULL,
  `custom_field_value` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `notification_mail` varchar(255) NOT NULL,
  `download_link` varchar(255) NOT NULL,
  `faq_id` int(11) NOT NULL,
  `field_mep` tinyint(1) NOT NULL,
  `mep_group_id` int(11) NOT NULL,
  `field_pin` tinyint(1) NOT NULL,
  `field_kbh` tinyint(1) NOT NULL,
  `field_prd` tinyint(1) NOT NULL,
  `field_type` tinyint(1) NOT NULL,
  `imei_type` tinyint(1) NOT NULL,
  `imei_field_name` varchar(255) NOT NULL,
  `imei_field_info` text NOT NULL,
  `imei_field_length` varchar(50) NOT NULL,
  `imei_field_alpha` tinyint(1) NOT NULL,
  `verify_checksum` tinyint(1) NOT NULL,
  `accept_duplicate` tinyint(1) NOT NULL,
  `message` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `verification` tinyint(1) NOT NULL,
  `cancel` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `custom_required` tinyint(1) NOT NULL DEFAULT '0',
  `custom_range` varchar(20) NOT NULL,
  `auto_success` int(11) NOT NULL DEFAULT '0',
  `is_custom` int(11) DEFAULT '0',
  `is_send_noti` int(11) DEFAULT '1',
  `price_update` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tool_name` (`tool_name`),
  KEY `sort_order` (`sort_order`),
  KEY `group_id` (`group_id`),
  KEY `api_id` (`api_id`),
  KEY `api_service_id` (`api_service_id`),
  KEY `brand_id` (`brand_id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_imei_tool_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_imei_tool_users`;

CREATE TABLE `nxt_imei_tool_users` (
  `tool_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `tool_id` (`tool_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_invoice_edit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_invoice_edit`;

CREATE TABLE `nxt_invoice_edit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(110) NOT NULL DEFAULT '0',
  `detail` varchar(500) NOT NULL DEFAULT '0',
  `detail2` varchar(500) NOT NULL DEFAULT '0',
  `detail3` varchar(500) NOT NULL DEFAULT '0',
  `detail4` varchar(500) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_invoice_edit` WRITE;
/*!40000 ALTER TABLE `nxt_invoice_edit` DISABLE KEYS */;

INSERT INTO `nxt_invoice_edit` (`id`, `logo`, `detail`, `detail2`, `detail3`, `detail4`)
VALUES
	(1,'GSMUnionFinalwithoutline-e1455297091228.png','Gsm Market Limmted sdgdsg\r','Hong Kong 3534543\r','Corporate Office BCN','');

/*!40000 ALTER TABLE `nxt_invoice_edit` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_invoice_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_invoice_log`;

CREATE TABLE `nxt_invoice_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inv_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `credits` int(11) NOT NULL,
  `gateway_id` varchar(200) NOT NULL DEFAULT '',
  `date_time` datetime NOT NULL,
  `receiver` int(11) NOT NULL,
  `last_updated_by` int(11) NOT NULL,
  `remarks` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32;



# Dump of table nxt_invoice_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_invoice_master`;

CREATE TABLE `nxt_invoice_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `amount` float NOT NULL,
  `credits` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `payer_email` varchar(100) NOT NULL,
  `receiver_email` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
  `date_time_paid` datetime NOT NULL,
  `currency_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  `paid_status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_invoice_request
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_invoice_request`;

CREATE TABLE `nxt_invoice_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `amount` float NOT NULL,
  `credits` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `payer_email` varchar(100) NOT NULL,
  `receiver_email` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
  `date_time_paid` datetime NOT NULL,
  `currency_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_ip_pool
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_ip_pool`;

CREATE TABLE `nxt_ip_pool` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT '0',
  `ip` varchar(50) DEFAULT '0',
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_language_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_language_details`;

CREATE TABLE `nxt_language_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_id` int(11) NOT NULL,
  `lang_code` varchar(255) NOT NULL,
  `caption_en` varchar(255) NOT NULL,
  `caption_hi` varchar(255) NOT NULL,
  `caption_fr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_code` (`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_language_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_language_master`;

CREATE TABLE `nxt_language_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(100) NOT NULL,
  `language_code` varchar(60) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `language_default` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_language_master` WRITE;
/*!40000 ALTER TABLE `nxt_language_master` DISABLE KEYS */;

INSERT INTO `nxt_language_master` (`id`, `language`, `language_code`, `file_name`, `language_default`)
VALUES
	(1,'English','en','en',0),
	(5,'Chinese','cn','cn',0),
	(3,'French','fr','fr',0),
	(4,'Spanish','sp','sp',0),
	(6,'Romanian','ro','ro',0),
	(7,'Swedish','se','se',0);

/*!40000 ALTER TABLE `nxt_language_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_mail_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_mail_history`;

CREATE TABLE `nxt_mail_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `plain_mail` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_news_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_news_master`;

CREATE TABLE `nxt_news_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `news` text,
  `date_creation` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `admin_update_id` int(11) DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_news_ticker_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_news_ticker_master`;

CREATE TABLE `nxt_news_ticker_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `news` text,
  `date_creation` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `admin_update_id` int(11) DEFAULT NULL,
  `publish` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_online_customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_online_customers`;

CREATE TABLE `nxt_online_customers` (
  `session_id` varchar(50) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_order_file_service_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_order_file_service_master`;

CREATE TABLE `nxt_order_file_service_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `extern_id` bigint(20) NOT NULL,
  `api_id` int(11) NOT NULL,
  `api_name` varchar(200) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `api_tries` int(11) NOT NULL,
  `api_tries_done` int(11) NOT NULL,
  `file_service_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_paid` tinyint(1) NOT NULL,
  `supplier_paid_on` datetime NOT NULL,
  `fileask` varchar(50) NOT NULL,
  `filerpl` varchar(50) NOT NULL,
  `unlock_code` text NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_time` datetime NOT NULL,
  `credits` float NOT NULL,
  `credits_amount` float NOT NULL,
  `credits_purchase` float NOT NULL,
  `credits_discount` float NOT NULL,
  `reply` varchar(255) NOT NULL,
  `reply_by` smallint(6) NOT NULL,
  `reply_date_time` datetime NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `custom_value` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_order_imei_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_order_imei_master`;

CREATE TABLE `nxt_order_imei_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `extern_id` varchar(20) NOT NULL,
  `api_id` int(11) NOT NULL,
  `api_name` varchar(200) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `api_tries` int(11) NOT NULL,
  `api_tries_done` int(11) NOT NULL,
  `tool_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_id_done` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_id_done` int(11) NOT NULL,
  `supplier_paid` tinyint(1) NOT NULL,
  `supplier_paid_on` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `imei` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL,
  `credits` float NOT NULL,
  `credits_amount` float NOT NULL,
  `credits_purchase` float NOT NULL,
  `credits_discount` float NOT NULL,
  `reply` text NOT NULL,
  `reply_by` smallint(6) NOT NULL COMMENT '1:Admin, 2: Supp, 3: API',
  `reply_date_time` datetime NOT NULL,
  `brand_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `network_id` int(11) NOT NULL,
  `mep_id` int(11) NOT NULL,
  `pin` varchar(30) NOT NULL,
  `prd` varchar(30) NOT NULL,
  `itype` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `custom_value` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `admin_note` varchar(250) DEFAULT NULL,
  `verify` smallint(6) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `custom_1` varchar(250) DEFAULT NULL,
  `custom_2` varchar(250) DEFAULT NULL,
  `custom_3` varchar(250) DEFAULT NULL,
  `custom_4` varchar(250) DEFAULT NULL,
  `custom_5` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tool_id` (`tool_id`),
  KEY `api_id` (`api_id`,`api_service_id`,`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_order_prepaid_log_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_order_prepaid_log_master`;

CREATE TABLE `nxt_order_prepaid_log_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prepaid_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_time` datetime NOT NULL,
  `credits` float NOT NULL,
  `cr_amount` float NOT NULL,
  `credits_purchase` float NOT NULL,
  `credits_discount` float NOT NULL,
  `reply` varchar(255) NOT NULL,
  `reply_by` smallint(6) NOT NULL,
  `reply_date_time` datetime NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `custom_value` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_order_server_log_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_order_server_log_master`;

CREATE TABLE `nxt_order_server_log_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_paid` tinyint(1) NOT NULL,
  `supplier_paid_on` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_time` datetime NOT NULL,
  `credits` float NOT NULL,
  `credits_amount` float NOT NULL,
  `credits_purchase` float NOT NULL,
  `credits_discount` float NOT NULL,
  `reply` varchar(255) NOT NULL,
  `reply_by` smallint(6) NOT NULL,
  `reply_date_time` datetime NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `custom_value` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `custom_1` varchar(250) DEFAULT NULL,
  `custom_2` varchar(250) DEFAULT NULL,
  `custom_3` varchar(250) DEFAULT NULL,
  `custom_4` varchar(250) DEFAULT NULL,
  `custom_5` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_package_file_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_package_file_details`;

CREATE TABLE `nxt_package_file_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_package_imei_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_package_imei_details`;

CREATE TABLE `nxt_package_imei_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `tool_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_package_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_package_master`;

CREATE TABLE `nxt_package_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_package_prepaid_log_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_package_prepaid_log_details`;

CREATE TABLE `nxt_package_prepaid_log_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_package_server_log_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_package_server_log_details`;

CREATE TABLE `nxt_package_server_log_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_package_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_package_users`;

CREATE TABLE `nxt_package_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_prepaid_log_amount_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_amount_details`;

CREATE TABLE `nxt_prepaid_log_amount_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `amount_purchase` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_prepaid_log_group_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_group_master`;

CREATE TABLE `nxt_prepaid_log_group_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) NOT NULL,
  `display_order` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_prepaid_log_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_master`;

CREATE TABLE `nxt_prepaid_log_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prepaid_log_name` varchar(255) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `info` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `delivery_time` varchar(255) DEFAULT NULL,
  `is_send_noti` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_prepaid_log_spl_credits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_spl_credits`;

CREATE TABLE `nxt_prepaid_log_spl_credits` (
  `user_id` int(11) unsigned NOT NULL,
  `log_id` int(11) unsigned NOT NULL,
  `amount` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_prepaid_log_spl_credits_reseller
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_spl_credits_reseller`;

CREATE TABLE `nxt_prepaid_log_spl_credits_reseller` (
  `user_id` int(11) unsigned NOT NULL,
  `reseller_id` int(11) unsigned NOT NULL,
  `log_id` int(11) unsigned NOT NULL,
  `amount` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;



# Dump of table nxt_prepaid_log_un_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_un_master`;

CREATE TABLE `nxt_prepaid_log_un_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prepaid_log_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_order` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `credit` float NOT NULL,
  `random_no` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_prepaid_log_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_prepaid_log_users`;

CREATE TABLE `nxt_prepaid_log_users` (
  `prepaid_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `prepaid_log_id` (`prepaid_log_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_products`;

CREATE TABLE `nxt_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(300) NOT NULL,
  `style_number` varchar(50) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `img` varchar(50) NOT NULL,
  `warrenty` char(1) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `def_price` decimal(10,2) NOT NULL,
  `min_price` decimal(10,2) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK1_CAT_PRODUCT` (`category_id`),
  KEY `FK2_VEN_PRODUCT` (`vendor_id`),
  CONSTRAINT `FK1_CAT_PRODUCT` FOREIGN KEY (`category_id`) REFERENCES `nxt_category` (`id`),
  CONSTRAINT `FK2_VEN_PRODUCT` FOREIGN KEY (`vendor_id`) REFERENCES `nxt_vendor` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_reseller_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_reseller_master`;

CREATE TABLE `nxt_reseller_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reseller` varchar(100) DEFAULT NULL,
  `type` smallint(6) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `yahoo` varchar(100) DEFAULT NULL,
  `msn` varchar(100) DEFAULT NULL,
  `skype` varchar(100) DEFAULT NULL,
  `icq` varchar(50) DEFAULT NULL,
  `sonork` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_server_log_amount_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_amount_details`;

CREATE TABLE `nxt_server_log_amount_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `amount_purchase` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_server_log_group_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_group_master`;

CREATE TABLE `nxt_server_log_group_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) NOT NULL,
  `display_order` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_server_log_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_master`;

CREATE TABLE `nxt_server_log_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_log_name` varchar(255) NOT NULL,
  `delivery_time` varchar(255) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `api_id` int(11) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL COMMENT '-1: All/0: No/# for single',
  `country_id` int(11) NOT NULL COMMENT '-1: All/0: No/# for single',
  `custom_field_name` varchar(50) NOT NULL,
  `custom_field_message` varchar(255) NOT NULL,
  `custom_field_value` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `notification_mail` varchar(255) NOT NULL,
  `download_link` varchar(255) NOT NULL,
  `faq_id` int(11) NOT NULL,
  `field_mep` tinyint(1) NOT NULL,
  `mep_group_id` int(11) NOT NULL,
  `field_pin` tinyint(1) NOT NULL,
  `field_kbh` tinyint(1) NOT NULL,
  `field_prd` tinyint(1) NOT NULL,
  `field_type` tinyint(1) NOT NULL,
  `imei_type` tinyint(1) NOT NULL,
  `verification` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `chimera` int(1) DEFAULT '0',
  `chimera_user_id` varchar(50) DEFAULT NULL,
  `chimera_api_key` varchar(50) DEFAULT NULL,
  `is_custom` int(11) DEFAULT '0',
  `is_send_noti` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_server_log_spl_credits
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_spl_credits`;

CREATE TABLE `nxt_server_log_spl_credits` (
  `user_id` int(11) unsigned NOT NULL,
  `log_id` int(11) unsigned NOT NULL,
  `amount` float unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_server_log_spl_credits_reseller
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_spl_credits_reseller`;

CREATE TABLE `nxt_server_log_spl_credits_reseller` (
  `user_id` int(11) unsigned NOT NULL,
  `reseller_id` int(11) unsigned NOT NULL,
  `log_id` int(11) unsigned NOT NULL,
  `amount` float unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;



# Dump of table nxt_server_log_supplier_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_supplier_details`;

CREATE TABLE `nxt_server_log_supplier_details` (
  `supplier_id` int(11) unsigned NOT NULL,
  `log_id` int(11) unsigned NOT NULL,
  `credits_purchase` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_server_log_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_server_log_users`;

CREATE TABLE `nxt_server_log_users` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  KEY `log_id` (`log_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_slider_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_slider_master`;

CREATE TABLE `nxt_slider_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_title` varchar(300) NOT NULL,
  `image` text NOT NULL,
  `notes` varchar(300) NOT NULL,
  `is_active` varchar(1) NOT NULL DEFAULT '1',
  `added_by` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_width` int(11) NOT NULL,
  `s_height` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_slider_master` WRITE;
/*!40000 ALTER TABLE `nxt_slider_master` DISABLE KEYS */;

INSERT INTO `nxt_slider_master` (`id`, `slider_title`, `image`, `notes`, `is_active`, `added_by`, `added_on`, `s_width`, `s_height`)
VALUES
	(15,'','215424.png','','1',1,'2016-10-14 20:16:55',0,0),
	(16,'','680151.png','','1',1,'2016-10-14 20:17:29',0,0);

/*!40000 ALTER TABLE `nxt_slider_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_smtp_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_smtp_config`;

CREATE TABLE `nxt_smtp_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_date` datetime NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `support_email` varchar(100) NOT NULL,
  `system_email` varchar(100) NOT NULL,
  `system_from` varchar(100) NOT NULL,
  `admin_signature` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `smtp_port` int(10) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `show_price` int(1) DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `last_updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(10) unsigned NOT NULL,
  `is_custom` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK1_CBY` (`created_by`),
  KEY `FK2_LAST_UBY` (`last_updated_by`),
  CONSTRAINT `FK1_CBY` FOREIGN KEY (`created_by`) REFERENCES `nxt_admin_master` (`id`),
  CONSTRAINT `FK2_LAST_UBY` FOREIGN KEY (`last_updated_by`) REFERENCES `nxt_admin_master` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_social_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_social_master`;

CREATE TABLE `nxt_social_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social_name` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_social_master` WRITE;
/*!40000 ALTER TABLE `nxt_social_master` DISABLE KEYS */;

INSERT INTO `nxt_social_master` (`id`, `social_name`, `url`, `is_active`)
VALUES
	(1,'Facebook','https://www.facebook.com/testpage',1),
	(2,'Twitter','https://twitter.com/login',1),
	(3,'Google Plus','https://plus.google.com/',1),
	(4,'You Tube','https://www.youtube.com/channel/UCRNeXX-0F9hTpLXGRkWeJbw',1);

/*!40000 ALTER TABLE `nxt_social_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_stats_admin_actions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_stats_admin_actions`;

CREATE TABLE `nxt_stats_admin_actions` (
  `user_id` int(10) unsigned NOT NULL,
  `process` tinytext NOT NULL,
  `process_type` varchar(20) NOT NULL,
  `admin_id` int(10) unsigned NOT NULL,
  `date_time` datetime NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_stats_admin_login_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_stats_admin_login_master`;

CREATE TABLE `nxt_stats_admin_login_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_stats_admin_login_master` WRITE;
/*!40000 ALTER TABLE `nxt_stats_admin_login_master` DISABLE KEYS */;

INSERT INTO `nxt_stats_admin_login_master` (`id`, `username`, `success`, `ip`, `date_time`)
VALUES
	(1,'admin',1,'::1','2016-06-03 18:50:42'),
	(2,'admin',1,'::1','2016-06-14 11:01:08'),
	(3,'admin',1,'::1','2016-06-14 14:58:51'),
	(4,'admin',1,'::1','2016-06-14 18:10:49'),
	(5,'admin',1,'::1','2016-06-15 12:22:27');

/*!40000 ALTER TABLE `nxt_stats_admin_login_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_stats_user_actions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_stats_user_actions`;

CREATE TABLE `nxt_stats_user_actions` (
  `user_id` int(10) unsigned NOT NULL,
  `process` tinytext NOT NULL,
  `process_type` varchar(20) NOT NULL,
  `admin_id` int(10) unsigned NOT NULL,
  `date_time` datetime NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_stats_user_login_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_stats_user_login_master`;

CREATE TABLE `nxt_stats_user_login_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `success` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_time` datetime NOT NULL,
  `b_info` varchar(250) DEFAULT NULL,
  `p_info` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_supplier_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_supplier_master`;

CREATE TABLE `nxt_supplier_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `api_key` varchar(20) NOT NULL,
  `login_key` varchar(20) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `last_action` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `city` varchar(150) NOT NULL,
  `address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `show_user` tinyint(1) NOT NULL,
  `show_credits` tinyint(1) NOT NULL,
  `country_id` int(11) NOT NULL,
  `reseller_id` int(11) NOT NULL,
  `user_type` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL,
  `last_login_time` datetime NOT NULL,
  `current_login_time` datetime NOT NULL,
  `last_ip` varchar(15) NOT NULL,
  `current_ip` varchar(15) NOT NULL,
  `last_action_time` datetime NOT NULL,
  `creation_date` datetime NOT NULL,
  `service_imei` smallint(6) NOT NULL,
  `service_file` smallint(6) NOT NULL,
  `service_logs` smallint(6) NOT NULL,
  `service_shop` smallint(6) NOT NULL,
  `theme` varchar(20) NOT NULL,
  `lang` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `credits` float NOT NULL,
  `credits_used` float NOT NULL,
  `credits_inprocess` float NOT NULL,
  `sms` int(11) NOT NULL,
  `sms_used` int(11) NOT NULL,
  `sms_country_code` varchar(10) NOT NULL,
  `note` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `ip1a` varchar(15) NOT NULL,
  `ip1b` varchar(15) NOT NULL,
  `ip2a` varchar(15) NOT NULL,
  `ip2b` varchar(15) NOT NULL,
  `ip3a` varchar(15) NOT NULL,
  `ip3b` varchar(15) NOT NULL,
  `ip4a` varchar(15) NOT NULL,
  `ip4b` varchar(15) NOT NULL,
  `ip5a` varchar(15) NOT NULL,
  `ip5b` varchar(15) NOT NULL,
  `online` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_supplier_payment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_supplier_payment`;

CREATE TABLE `nxt_supplier_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `credits_paid` float NOT NULL,
  `comments` text NOT NULL,
  `date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_tbl_maintenance
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_tbl_maintenance`;

CREATE TABLE `nxt_tbl_maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL,
  `msg` varchar(250) DEFAULT NULL,
  `msg2` varchar(250) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  `time_stamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `nxt_tbl_maintenance` WRITE;
/*!40000 ALTER TABLE `nxt_tbl_maintenance` DISABLE KEYS */;

INSERT INTO `nxt_tbl_maintenance` (`id`, `status`, `msg`, `msg2`, `admin`, `time_stamp`)
VALUES
	(1,1,'Site is Offline Due to Mainenance','online',1,'2016-06-06 06:15:29');

/*!40000 ALTER TABLE `nxt_tbl_maintenance` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_template_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_template_master`;

CREATE TABLE `nxt_template_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `template` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_testimonials_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_testimonials_master`;

CREATE TABLE `nxt_testimonials_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` longtext NOT NULL,
  `ip` varchar(50) NOT NULL,
  `publish` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_ticket_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_ticket_details`;

CREATE TABLE `nxt_ticket_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` text COLLATE latin1_general_ci NOT NULL,
  `user_admin` tinyint(1) NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Dump of table nxt_ticket_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_ticket_master`;

CREATE TABLE `nxt_ticket_master` (
  `ticket_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `trans_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `department` smallint(6) NOT NULL DEFAULT '0',
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_ticket` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `awating` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;



# Dump of table nxt_timezone_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_timezone_master`;

CREATE TABLE `nxt_timezone_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timezone` char(64) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone names';

LOCK TABLES `nxt_timezone_master` WRITE;
/*!40000 ALTER TABLE `nxt_timezone_master` DISABLE KEYS */;

INSERT INTO `nxt_timezone_master` (`id`, `timezone`, `is_default`)
VALUES
	(1,'Africa/Abidjan',0),
	(2,'Africa/Accra',0),
	(3,'Africa/Addis_Ababa',0),
	(4,'Africa/Algiers',0),
	(5,'Africa/Asmara',0),
	(6,'Africa/Asmera',0),
	(7,'Africa/Bamako',0),
	(8,'Africa/Bangui',0),
	(9,'Africa/Banjul',0),
	(10,'Africa/Bissau',0),
	(11,'Africa/Blantyre',0),
	(12,'Africa/Brazzaville',0),
	(13,'Africa/Bujumbura',0),
	(14,'Africa/Cairo',0),
	(15,'Africa/Casablanca',0),
	(16,'Africa/Ceuta',0),
	(17,'Africa/Conakry',0),
	(18,'Africa/Dakar',0),
	(19,'Africa/Dar_es_Salaam',0),
	(20,'Africa/Djibouti',0),
	(21,'Africa/Douala',0),
	(22,'Africa/El_Aaiun',0),
	(23,'Africa/Freetown',0),
	(24,'Africa/Gaborone',0),
	(25,'Africa/Harare',0),
	(26,'Africa/Johannesburg',0),
	(27,'Africa/Kampala',0),
	(28,'Africa/Khartoum',0),
	(29,'Africa/Kigali',0),
	(30,'Africa/Kinshasa',0),
	(31,'Africa/Lagos',0),
	(32,'Africa/Libreville',0),
	(33,'Africa/Lome',0),
	(34,'Africa/Luanda',0),
	(35,'Africa/Lubumbashi',0),
	(36,'Africa/Lusaka',0),
	(37,'Africa/Malabo',0),
	(38,'Africa/Maputo',0),
	(39,'Africa/Maseru',0),
	(40,'Africa/Mbabane',0),
	(41,'Africa/Mogadishu',0),
	(42,'Africa/Monrovia',0),
	(43,'Africa/Nairobi',0),
	(44,'Africa/Ndjamena',0),
	(45,'Africa/Niamey',0),
	(46,'Africa/Nouakchott',0),
	(47,'Africa/Ouagadougou',0),
	(48,'Africa/Porto-Novo',0),
	(49,'Africa/Sao_Tome',0),
	(50,'Africa/Timbuktu',0),
	(51,'Africa/Tripoli',0),
	(52,'Africa/Tunis',0),
	(53,'Africa/Windhoek',0),
	(54,'America/Adak',0),
	(55,'America/Anchorage',0),
	(56,'America/Anguilla',0),
	(57,'America/Antigua',0),
	(58,'America/Araguaina',0),
	(59,'America/Argentina/Buenos_Aires',0),
	(60,'America/Argentina/Catamarca',0),
	(61,'America/Argentina/ComodRivadavia',0),
	(62,'America/Argentina/Cordoba',0),
	(63,'America/Argentina/Jujuy',0),
	(64,'America/Argentina/La_Rioja',0),
	(65,'America/Argentina/Mendoza',0),
	(66,'America/Argentina/Rio_Gallegos',0),
	(67,'America/Argentina/Salta',0),
	(68,'America/Argentina/San_Juan',0),
	(69,'America/Argentina/San_Luis',0),
	(70,'America/Argentina/Tucuman',0),
	(71,'America/Argentina/Ushuaia',0),
	(72,'America/Aruba',0),
	(73,'America/Asuncion',0),
	(74,'America/Atikokan',0),
	(75,'America/Atka',0),
	(76,'America/Bahia',0),
	(77,'America/Barbados',0),
	(78,'America/Belem',0),
	(79,'America/Belize',0),
	(80,'America/Blanc-Sablon',0),
	(81,'America/Boa_Vista',0),
	(82,'America/Bogota',0),
	(83,'America/Boise',0),
	(84,'America/Buenos_Aires',0),
	(85,'America/Cambridge_Bay',0),
	(86,'America/Campo_Grande',0),
	(87,'America/Cancun',0),
	(88,'America/Caracas',0),
	(89,'America/Catamarca',0),
	(90,'America/Cayenne',0),
	(91,'America/Cayman',0),
	(92,'America/Chicago',0),
	(93,'America/Chihuahua',0),
	(94,'America/Coral_Harbour',0),
	(95,'America/Cordoba',0),
	(96,'America/Costa_Rica',0),
	(97,'America/Cuiaba',0),
	(98,'America/Curacao',0),
	(99,'America/Danmarkshavn',0),
	(100,'America/Dawson',0),
	(101,'America/Dawson_Creek',0),
	(102,'America/Denver',0),
	(103,'America/Detroit',0),
	(104,'America/Dominica',0),
	(105,'America/Edmonton',0),
	(106,'America/Eirunepe',0),
	(107,'America/El_Salvador',0),
	(108,'America/Ensenada',0),
	(109,'America/Fort_Wayne',0),
	(110,'America/Fortaleza',0),
	(111,'America/Glace_Bay',0),
	(112,'America/Godthab',0),
	(113,'America/Goose_Bay',0),
	(114,'America/Grand_Turk',0),
	(115,'America/Grenada',0),
	(116,'America/Guadeloupe',0),
	(117,'America/Guatemala',0),
	(118,'America/Guayaquil',0),
	(119,'America/Guyana',0),
	(120,'America/Halifax',0),
	(121,'America/Havana',0),
	(122,'America/Hermosillo',0),
	(123,'America/Indiana/Indianapolis',0),
	(124,'America/Indiana/Knox',0),
	(125,'America/Indiana/Marengo',0),
	(126,'America/Indiana/Petersburg',0),
	(127,'America/Indiana/Tell_City',0),
	(128,'America/Indiana/Vevay',0),
	(129,'America/Indiana/Vincennes',0),
	(130,'America/Indiana/Winamac',0),
	(131,'America/Indianapolis',0),
	(132,'America/Inuvik',0),
	(133,'America/Iqaluit',0),
	(134,'America/Jamaica',0),
	(135,'America/Jujuy',0),
	(136,'America/Juneau',0),
	(137,'America/Kentucky/Louisville',0),
	(138,'America/Kentucky/Monticello',0),
	(139,'America/Knox_IN',0),
	(140,'America/La_Paz',0),
	(141,'America/Lima',0),
	(142,'America/Los_Angeles',0),
	(143,'America/Louisville',0),
	(144,'America/Maceio',0),
	(145,'America/Managua',0),
	(146,'America/Manaus',0),
	(147,'America/Marigot',0),
	(148,'America/Martinique',0),
	(149,'America/Mazatlan',0),
	(150,'America/Mendoza',0),
	(151,'America/Menominee',0),
	(152,'America/Merida',0),
	(153,'America/Mexico_City',0),
	(154,'America/Miquelon',0),
	(155,'America/Moncton',0),
	(156,'America/Monterrey',0),
	(157,'America/Montevideo',0),
	(158,'America/Montreal',0),
	(159,'America/Montserrat',0),
	(160,'America/Nassau',0),
	(161,'America/New_York',0),
	(162,'America/Nipigon',0),
	(163,'America/Nome',0),
	(164,'America/Noronha',0),
	(165,'America/North_Dakota/Center',0),
	(166,'America/North_Dakota/New_Salem',0),
	(167,'America/Panama',0),
	(168,'America/Pangnirtung',0),
	(169,'America/Paramaribo',0),
	(170,'America/Phoenix',0),
	(171,'America/Port-au-Prince',0),
	(172,'America/Port_of_Spain',0),
	(173,'America/Porto_Acre',0),
	(174,'America/Porto_Velho',0),
	(175,'America/Puerto_Rico',0),
	(176,'America/Rainy_River',0),
	(177,'America/Rankin_Inlet',0),
	(178,'America/Recife',0),
	(179,'America/Regina',0),
	(180,'America/Resolute',0),
	(181,'America/Rio_Branco',0),
	(182,'America/Rosario',0),
	(183,'America/Santarem',0),
	(184,'America/Santiago',0),
	(185,'America/Santo_Domingo',0),
	(186,'America/Sao_Paulo',0),
	(187,'America/Scoresbysund',0),
	(188,'America/Shiprock',0),
	(189,'America/St_Barthelemy',0),
	(190,'America/St_Johns',0),
	(191,'America/St_Kitts',0),
	(192,'America/St_Lucia',0),
	(193,'America/St_Thomas',0),
	(194,'America/St_Vincent',0),
	(195,'America/Swift_Current',0),
	(196,'America/Tegucigalpa',0),
	(197,'America/Thule',0),
	(198,'America/Thunder_Bay',0),
	(199,'America/Tijuana',0),
	(200,'America/Toronto',0),
	(201,'America/Tortola',0),
	(202,'America/Vancouver',0),
	(203,'America/Virgin',0),
	(204,'America/Whitehorse',0),
	(205,'America/Winnipeg',0),
	(206,'America/Yakutat',0),
	(207,'America/Yellowknife',0),
	(208,'Antarctica/Casey',0),
	(209,'Antarctica/Davis',0),
	(210,'Antarctica/DumontDUrville',0),
	(211,'Antarctica/Mawson',0),
	(212,'Antarctica/McMurdo',0),
	(213,'Antarctica/Palmer',0),
	(214,'Antarctica/Rothera',0),
	(215,'Antarctica/South_Pole',0),
	(216,'Antarctica/Syowa',0),
	(217,'Antarctica/Vostok',0),
	(218,'Arctic/Longyearbyen',0),
	(219,'Asia/Aden',0),
	(220,'Asia/Almaty',0),
	(221,'Asia/Amman',0),
	(222,'Asia/Anadyr',0),
	(223,'Asia/Aqtau',0),
	(224,'Asia/Aqtobe',0),
	(225,'Asia/Ashgabat',0),
	(226,'Asia/Ashkhabad',0),
	(227,'Asia/Baghdad',0),
	(228,'Asia/Bahrain',0),
	(229,'Asia/Baku',0),
	(230,'Asia/Bangkok',0),
	(231,'Asia/Beirut',0),
	(232,'Asia/Bishkek',0),
	(233,'Asia/Brunei',0),
	(234,'Asia/Calcutta',0),
	(235,'Asia/Choibalsan',0),
	(236,'Asia/Chongqing',0),
	(237,'Asia/Chungking',0),
	(238,'Asia/Colombo',0),
	(239,'Asia/Dacca',0),
	(240,'Asia/Damascus',0),
	(241,'Asia/Dhaka',0),
	(242,'Asia/Dili',0),
	(243,'Asia/Dubai',0),
	(244,'Asia/Dushanbe',0),
	(245,'Asia/Gaza',0),
	(246,'Asia/Harbin',0),
	(247,'Asia/Ho_Chi_Minh',0),
	(248,'Asia/Hong_Kong',0),
	(249,'Asia/Hovd',0),
	(250,'Asia/Irkutsk',0),
	(251,'Asia/Istanbul',0),
	(252,'Asia/Jakarta',0),
	(253,'Asia/Jayapura',0),
	(254,'Asia/Jerusalem',0),
	(255,'Asia/Kabul',0),
	(256,'Asia/Kamchatka',0),
	(257,'Asia/Karachi',1),
	(258,'Asia/Kashgar',0),
	(259,'Asia/Kathmandu',0),
	(260,'Asia/Katmandu',0),
	(261,'Asia/Kolkata',0),
	(262,'Asia/Krasnoyarsk',0),
	(263,'Asia/Kuala_Lumpur',0),
	(264,'Asia/Kuching',0),
	(265,'Asia/Kuwait',0),
	(266,'Asia/Macao',0),
	(267,'Asia/Macau',0),
	(268,'Asia/Magadan',0),
	(269,'Asia/Makassar',0),
	(270,'Asia/Manila',0),
	(271,'Asia/Muscat',0),
	(272,'Asia/Nicosia',0),
	(273,'Asia/Novosibirsk',0),
	(274,'Asia/Omsk',0),
	(275,'Asia/Oral',0),
	(276,'Asia/Phnom_Penh',0),
	(277,'Asia/Pontianak',0),
	(278,'Asia/Pyongyang',0),
	(279,'Asia/Qatar',0),
	(280,'Asia/Qyzylorda',0),
	(281,'Asia/Rangoon',0),
	(282,'Asia/Riyadh',0),
	(283,'Asia/Saigon',0),
	(284,'Asia/Sakhalin',0),
	(285,'Asia/Samarkand',0),
	(286,'Asia/Seoul',0),
	(287,'Asia/Shanghai',0),
	(288,'Asia/Singapore',0),
	(289,'Asia/Taipei',0),
	(290,'Asia/Tashkent',0),
	(291,'Asia/Tbilisi',0),
	(292,'Asia/Tehran',0),
	(293,'Asia/Tel_Aviv',0),
	(294,'Asia/Thimbu',0),
	(295,'Asia/Thimphu',0),
	(296,'Asia/Tokyo',0),
	(297,'Asia/Ujung_Pandang',0),
	(298,'Asia/Ulaanbaatar',0),
	(299,'Asia/Ulan_Bator',0),
	(300,'Asia/Urumqi',0),
	(301,'Asia/Vientiane',0),
	(302,'Asia/Vladivostok',0),
	(303,'Asia/Yakutsk',0),
	(304,'Asia/Yekaterinburg',0),
	(305,'Asia/Yerevan',0),
	(306,'Atlantic/Azores',0),
	(307,'Atlantic/Bermuda',0),
	(308,'Atlantic/Canary',0),
	(309,'Atlantic/Cape_Verde',0),
	(310,'Atlantic/Faeroe',0),
	(311,'Atlantic/Faroe',0),
	(312,'Atlantic/Jan_Mayen',0),
	(313,'Atlantic/Madeira',0),
	(314,'Atlantic/Reykjavik',0),
	(315,'Atlantic/South_Georgia',0),
	(316,'Atlantic/St_Helena',0),
	(317,'Atlantic/Stanley',0),
	(318,'Australia/ACT',0),
	(319,'Australia/Adelaide',0),
	(320,'Australia/Brisbane',0),
	(321,'Australia/Broken_Hill',0),
	(322,'Australia/Canberra',0),
	(323,'Australia/Currie',0),
	(324,'Australia/Darwin',0),
	(325,'Australia/Eucla',0),
	(326,'Australia/Hobart',0),
	(327,'Australia/LHI',0),
	(328,'Australia/Lindeman',0),
	(329,'Australia/Lord_Howe',0),
	(330,'Australia/Melbourne',0),
	(331,'Australia/NSW',0),
	(332,'Australia/North',0),
	(333,'Australia/Perth',0),
	(334,'Australia/Queensland',0),
	(335,'Australia/South',0),
	(336,'Australia/Sydney',0),
	(337,'Australia/Tasmania',0),
	(338,'Australia/Victoria',0),
	(339,'Australia/West',0),
	(340,'Australia/Yancowinna',0),
	(341,'Brazil/Acre',0),
	(342,'Brazil/DeNoronha',0),
	(343,'Brazil/East',0),
	(344,'Brazil/West',0),
	(345,'CET',0),
	(346,'CST6CDT',0),
	(347,'Canada/Atlantic',0),
	(348,'Canada/Central',0),
	(349,'Canada/East-Saskatchewan',0),
	(350,'Canada/Eastern',0),
	(351,'Canada/Mountain',0),
	(352,'Canada/Newfoundland',0),
	(353,'Canada/Pacific',0),
	(354,'Canada/Saskatchewan',0),
	(355,'Canada/Yukon',0),
	(356,'Chile/Continental',0),
	(357,'Chile/EasterIsland',0),
	(358,'Cuba',0),
	(359,'EET',0),
	(360,'EST',0),
	(361,'EST5EDT',0),
	(362,'Egypt',0),
	(363,'Eire',0),
	(364,'Etc/GMT',0),
	(365,'Etc/GMT+0',0),
	(366,'Etc/GMT+1',0),
	(367,'Etc/GMT+10',0),
	(368,'Etc/GMT+11',0),
	(369,'Etc/GMT+12',0),
	(370,'Etc/GMT+2',0),
	(371,'Etc/GMT+3',0),
	(372,'Etc/GMT+4',0),
	(373,'Etc/GMT+5',0),
	(374,'Etc/GMT+6',0),
	(375,'Etc/GMT+7',0),
	(376,'Etc/GMT+8',0),
	(377,'Etc/GMT+9',0),
	(378,'Etc/GMT-0',0),
	(379,'Etc/GMT-1',0),
	(380,'Etc/GMT-10',0),
	(381,'Etc/GMT-11',0),
	(382,'Etc/GMT-12',0),
	(383,'Etc/GMT-13',0),
	(384,'Etc/GMT-14',0),
	(385,'Etc/GMT-2',0),
	(386,'Etc/GMT-3',0),
	(387,'Etc/GMT-4',0),
	(388,'Etc/GMT-5',0),
	(389,'Etc/GMT-6',0),
	(390,'Etc/GMT-7',0),
	(391,'Etc/GMT-8',0),
	(392,'Etc/GMT-9',0),
	(393,'Etc/GMT0',0),
	(394,'Etc/Greenwich',0),
	(395,'Etc/UCT',0),
	(396,'Etc/UTC',0),
	(397,'Etc/Universal',0),
	(398,'Etc/Zulu',0),
	(399,'Europe/Amsterdam',0),
	(400,'Europe/Andorra',0),
	(401,'Europe/Athens',0),
	(402,'Europe/Belfast',0),
	(403,'Europe/Belgrade',0),
	(404,'Europe/Berlin',0),
	(405,'Europe/Bratislava',0),
	(406,'Europe/Brussels',0),
	(407,'Europe/Bucharest',0),
	(408,'Europe/Budapest',0),
	(409,'Europe/Chisinau',0),
	(410,'Europe/Copenhagen',0),
	(411,'Europe/Dublin',0),
	(412,'Europe/Gibraltar',0),
	(413,'Europe/Guernsey',0),
	(414,'Europe/Helsinki',0),
	(415,'Europe/Isle_of_Man',0),
	(416,'Europe/Istanbul',0),
	(417,'Europe/Jersey',0),
	(418,'Europe/Kaliningrad',0),
	(419,'Europe/Kiev',0),
	(420,'Europe/Lisbon',0),
	(421,'Europe/Ljubljana',0),
	(422,'Europe/London',0),
	(423,'Europe/Luxembourg',0),
	(424,'Europe/Madrid',0),
	(425,'Europe/Malta',0),
	(426,'Europe/Mariehamn',0),
	(427,'Europe/Minsk',0),
	(428,'Europe/Monaco',0),
	(429,'Europe/Moscow',0),
	(430,'Europe/Nicosia',0),
	(431,'Europe/Oslo',0),
	(432,'Europe/Paris',0),
	(433,'Europe/Podgorica',0),
	(434,'Europe/Prague',0),
	(435,'Europe/Riga',0),
	(436,'Europe/Rome',0),
	(437,'Europe/Samara',0),
	(438,'Europe/San_Marino',0),
	(439,'Europe/Sarajevo',0),
	(440,'Europe/Simferopol',0),
	(441,'Europe/Skopje',0),
	(442,'Europe/Sofia',0),
	(443,'Europe/Stockholm',0),
	(444,'Europe/Tallinn',0),
	(445,'Europe/Tirane',0),
	(446,'Europe/Tiraspol',0),
	(447,'Europe/Uzhgorod',0),
	(448,'Europe/Vaduz',0),
	(449,'Europe/Vatican',0),
	(450,'Europe/Vienna',0),
	(451,'Europe/Vilnius',0),
	(452,'Europe/Volgograd',0),
	(453,'Europe/Warsaw',0),
	(454,'Europe/Zagreb',0),
	(455,'Europe/Zaporozhye',0),
	(456,'Europe/Zurich',0),
	(457,'Factory',0),
	(458,'GB',0),
	(459,'GB-Eire',0),
	(460,'GMT',0),
	(461,'GMT+0',0),
	(462,'GMT-0',0),
	(463,'GMT0',0),
	(464,'Greenwich',0),
	(465,'HST',0),
	(466,'Hongkong',0),
	(467,'Iceland',0),
	(468,'Indian/Antananarivo',0),
	(469,'Indian/Chagos',0),
	(470,'Indian/Christmas',0),
	(471,'Indian/Cocos',0),
	(472,'Indian/Comoro',0),
	(473,'Indian/Kerguelen',0),
	(474,'Indian/Mahe',0),
	(475,'Indian/Maldives',0),
	(476,'Indian/Mauritius',0),
	(477,'Indian/Mayotte',0),
	(478,'Indian/Reunion',0),
	(479,'Iran',0),
	(480,'Israel',0),
	(481,'Jamaica',0),
	(482,'Japan',0),
	(483,'Kwajalein',0),
	(484,'Libya',0),
	(485,'MET',0),
	(486,'MST',0),
	(487,'MST7MDT',0),
	(488,'Mexico/BajaNorte',0),
	(489,'Mexico/BajaSur',0),
	(490,'Mexico/General',0),
	(491,'NZ',0),
	(492,'NZ-CHAT',0),
	(493,'Navajo',0),
	(494,'PRC',0),
	(495,'PST8PDT',0),
	(496,'Pacific/Apia',0),
	(497,'Pacific/Auckland',0),
	(498,'Pacific/Chatham',0),
	(499,'Pacific/Easter',0),
	(500,'Pacific/Efate',0),
	(501,'Pacific/Enderbury',0),
	(502,'Pacific/Fakaofo',0),
	(503,'Pacific/Fiji',0),
	(504,'Pacific/Funafuti',0),
	(505,'Pacific/Galapagos',0),
	(506,'Pacific/Gambier',0),
	(507,'Pacific/Guadalcanal',0),
	(508,'Pacific/Guam',0),
	(509,'Pacific/Honolulu',0),
	(510,'Pacific/Johnston',0),
	(511,'Pacific/Kiritimati',0),
	(512,'Pacific/Kosrae',0),
	(513,'Pacific/Kwajalein',0),
	(514,'Pacific/Majuro',0),
	(515,'Pacific/Marquesas',0),
	(516,'Pacific/Midway',0),
	(517,'Pacific/Nauru',0),
	(518,'Pacific/Niue',0),
	(519,'Pacific/Norfolk',0),
	(520,'Pacific/Noumea',0),
	(521,'Pacific/Pago_Pago',0),
	(522,'Pacific/Palau',0),
	(523,'Pacific/Pitcairn',0),
	(524,'Pacific/Ponape',0),
	(525,'Pacific/Port_Moresby',0),
	(526,'Pacific/Rarotonga',0),
	(527,'Pacific/Saipan',0),
	(528,'Pacific/Samoa',0),
	(529,'Pacific/Tahiti',0),
	(530,'Pacific/Tarawa',0),
	(531,'Pacific/Tongatapu',0),
	(532,'Pacific/Truk',0),
	(533,'Pacific/Wake',0),
	(534,'Pacific/Wallis',0),
	(535,'Pacific/Yap',0),
	(536,'Poland',0),
	(537,'Portugal',0),
	(538,'ROC',0),
	(539,'ROK',0),
	(540,'Singapore',0),
	(541,'Turkey',0),
	(542,'UCT',0),
	(543,'US/Alaska',0),
	(544,'US/Aleutian',0),
	(545,'US/Arizona',0),
	(546,'US/Central',0),
	(547,'US/East-Indiana',0),
	(548,'US/Eastern',0),
	(549,'US/Hawaii',0),
	(550,'US/Indiana-Starke',0),
	(551,'US/Michigan',0),
	(552,'US/Mountain',0),
	(553,'US/Pacific',0),
	(554,'US/Pacific-New',0),
	(555,'US/Samoa',0),
	(556,'UTC',0),
	(557,'Universal',0),
	(558,'W-SU',0),
	(559,'WET',0),
	(560,'Zulu',0);

/*!40000 ALTER TABLE `nxt_timezone_master` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nxt_user_credit_purchase_detail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_user_credit_purchase_detail`;

CREATE TABLE `nxt_user_credit_purchase_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `credit_package` varchar(100) DEFAULT NULL,
  `credits` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_user_group_detail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_user_group_detail`;

CREATE TABLE `nxt_user_group_detail` (
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_user_group_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_user_group_master`;

CREATE TABLE `nxt_user_group_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_user_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_user_master`;

CREATE TABLE `nxt_user_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `api_key` varchar(20) NOT NULL,
  `android_key` varchar(20) NOT NULL,
  `api_access` tinyint(1) NOT NULL,
  `login_key` varchar(20) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `last_action` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_cc` varchar(255) NOT NULL,
  `change_password` tinyint(1) NOT NULL,
  `company` varchar(100) NOT NULL,
  `city` varchar(150) NOT NULL,
  `address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL,
  `reseller_id` int(11) NOT NULL,
  `user_type` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL,
  `last_login_time` datetime NOT NULL,
  `current_login_time` datetime NOT NULL,
  `last_ip` varchar(15) NOT NULL,
  `current_ip` varchar(15) NOT NULL,
  `last_action_time` datetime NOT NULL,
  `creation_date` datetime NOT NULL,
  `service_imei` smallint(6) NOT NULL,
  `service_file` smallint(6) NOT NULL,
  `service_logs` smallint(6) NOT NULL,
  `service_prepaid` smallint(6) NOT NULL,
  `service_shop` smallint(6) NOT NULL,
  `theme` varchar(20) NOT NULL,
  `lang` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `credits` float NOT NULL,
  `user_credit_transaction_limit` float NOT NULL,
  `credits_used` float NOT NULL,
  `credits_inprocess` float NOT NULL,
  `pg_paypal` float NOT NULL,
  `pg_moneybookers` float NOT NULL,
  `auto_pay` tinyint(1) NOT NULL DEFAULT '1',
  `sms` int(11) NOT NULL,
  `sms_used` int(11) NOT NULL,
  `sms_country_code` varchar(10) NOT NULL,
  `note` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `ip1a` varchar(15) NOT NULL,
  `ip1b` varchar(15) NOT NULL,
  `ip2a` varchar(15) NOT NULL,
  `ip2b` varchar(15) NOT NULL,
  `ip3a` varchar(15) NOT NULL,
  `ip3b` varchar(15) NOT NULL,
  `ip4a` varchar(15) NOT NULL,
  `ip4b` varchar(15) NOT NULL,
  `ip5a` varchar(15) NOT NULL,
  `ip5b` varchar(15) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `account_suspension_start_date` datetime NOT NULL,
  `account_suspension_days` int(11) NOT NULL,
  `img` varchar(250) NOT NULL,
  `notify` int(11) NOT NULL DEFAULT '1',
  `last_active_time` datetime NOT NULL,
  `wrong_password_count` int(11) DEFAULT '0',
  `two_step_auth` int(11) NOT NULL DEFAULT '0',
  `google_auth_key` varchar(300) NOT NULL DEFAULT 'NA',
  `custom_1` varchar(250) DEFAULT NULL,
  `custom_2` varchar(250) DEFAULT NULL,
  `custom_3` varchar(250) DEFAULT NULL,
  `custom_4` varchar(250) DEFAULT NULL,
  `custom_5` varchar(250) DEFAULT NULL,
  `pin` varchar(250) DEFAULT NULL,
  `master_pin` int(11) DEFAULT '0',
  `ovd_c_limit` float DEFAULT '0',
  `imei_suc_noti` int(11) DEFAULT '1',
  `imei_rej_noti` int(11) DEFAULT '1',
  `file_suc_noti` int(11) DEFAULT '1',
  `file_rej_noti` int(11) DEFAULT '1',
  `slog_suc_noti` int(11) DEFAULT '1',
  `slog_rej_noti` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`),
  UNIQUE KEY `username_uni` (`username`),
  KEY `username` (`username`),
  KEY `status` (`status`),
  KEY `username_2` (`username`,`api_key`,`login_key`,`email`,`country_id`,`reseller_id`,`status`,`credits`,`user_credit_transaction_limit`,`credits_used`,`credits_inprocess`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_user_master_new
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_user_master_new`;

CREATE TABLE `nxt_user_master_new` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `api_key` varchar(20) NOT NULL,
  `android_key` varchar(20) NOT NULL,
  `api_access` tinyint(1) NOT NULL,
  `login_key` varchar(20) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `last_action` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_cc` varchar(255) NOT NULL,
  `change_password` tinyint(1) NOT NULL,
  `company` varchar(100) NOT NULL,
  `city` varchar(150) NOT NULL,
  `address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL,
  `reseller_id` int(11) NOT NULL,
  `user_type` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL,
  `last_login_time` datetime NOT NULL,
  `current_login_time` datetime NOT NULL,
  `last_ip` varchar(15) NOT NULL,
  `current_ip` varchar(15) NOT NULL,
  `last_action_time` datetime NOT NULL,
  `creation_date` datetime NOT NULL,
  `service_imei` smallint(6) NOT NULL,
  `service_file` smallint(6) NOT NULL,
  `service_logs` smallint(6) NOT NULL,
  `service_prepaid` smallint(6) NOT NULL,
  `service_shop` smallint(6) NOT NULL,
  `theme` varchar(20) NOT NULL,
  `lang` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `credits` float NOT NULL,
  `user_credit_transaction_limit` float NOT NULL,
  `credits_used` float NOT NULL,
  `credits_inprocess` float NOT NULL,
  `pg_paypal` float NOT NULL,
  `pg_moneybookers` float NOT NULL,
  `auto_pay` tinyint(1) NOT NULL,
  `sms` int(11) NOT NULL,
  `sms_used` int(11) NOT NULL,
  `sms_country_code` varchar(10) NOT NULL,
  `note` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `timezone_id` int(11) NOT NULL,
  `ip1a` varchar(15) NOT NULL,
  `ip1b` varchar(15) NOT NULL,
  `ip2a` varchar(15) NOT NULL,
  `ip2b` varchar(15) NOT NULL,
  `ip3a` varchar(15) NOT NULL,
  `ip3b` varchar(15) NOT NULL,
  `ip4a` varchar(15) NOT NULL,
  `ip4b` varchar(15) NOT NULL,
  `ip5a` varchar(15) NOT NULL,
  `ip5b` varchar(15) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `account_suspension_start_date` datetime NOT NULL,
  `account_suspension_days` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_user_register_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_user_register_master`;

CREATE TABLE `nxt_user_register_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activation_code` varchar(20) NOT NULL,
  `country_id` int(10) NOT NULL,
  `currency_id` int(10) NOT NULL,
  `city` varchar(150) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `timezone_id` int(11) DEFAULT '424',
  `hash` varchar(50) DEFAULT NULL,
  `lang` varchar(50) DEFAULT NULL,
  `custom_1` varchar(250) DEFAULT NULL,
  `custom_2` varchar(250) DEFAULT NULL,
  `custom_3` varchar(250) DEFAULT NULL,
  `custom_4` varchar(250) DEFAULT NULL,
  `custom_5` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table nxt_vendor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_vendor`;

CREATE TABLE `nxt_vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `c_person` varchar(50) NOT NULL,
  `c_number` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_video_category_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_video_category_master`;

CREATE TABLE `nxt_video_category_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_video_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_video_details`;

CREATE TABLE `nxt_video_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table nxt_warehouse_master
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nxt_warehouse_master`;

CREATE TABLE `nxt_warehouse_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
