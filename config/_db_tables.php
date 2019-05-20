<?php
	//defined("_VALID_ACCESS") or die("Restricted Access");
	
	/******************************************
				Database Tables
	*******************************************/
	define ("ADMIN_MASTER", CONFIG_DB_PREFIX . "admin_master");
	define ("API_DETAILS", CONFIG_DB_PREFIX . "api_details");
	define ("API_ERROR_LOG", CONFIG_DB_PREFIX . "api_error_log");
	define ("API_MASTER", CONFIG_DB_PREFIX . "api_master");
	
	define ("BANNER_MASTER", CONFIG_DB_PREFIX . "banner_master");
	
	
	define ("CONFIG_MASTER", CONFIG_DB_PREFIX . "config_master");
	define ("COUNTRY_MASTER", CONFIG_DB_PREFIX . "country_master");
	define ("CREDIT_TRANSECTION_MASTER", CONFIG_DB_PREFIX . "credit_transection_master");
	
	define ("EMAIL_QUEUE", CONFIG_DB_PREFIX . "email_queue");
	define ("EMAIL_TEMPLATES", CONFIG_DB_PREFIX . "email_templates");
	define ("EMAIL_TEMPLATES_CATEGORY", CONFIG_DB_PREFIX . "email_templates_category");
	
	define ("FILE_PACKAGE_DETAIL", CONFIG_DB_PREFIX . "file_package_detail");
	define ("FILE_PACKAGE_MASTER", CONFIG_DB_PREFIX . "file_package_master");
	define ("FILE_PACKAGE_USER_DETAIL", CONFIG_DB_PREFIX . "file_package_user_detail");

	define ("CURRENCY_MASTER", CONFIG_DB_PREFIX . "currency_master");
	
	define ("FILE_EXTENSIONS", CONFIG_DB_PREFIX . "file_extensions");
	define ("FILE_SERVICE_AMOUNT_DETAILS", CONFIG_DB_PREFIX . "file_service_amount_details");
	define ("FILE_SERVICE_MASTER", CONFIG_DB_PREFIX . "file_service_master");
	define ("FILE_SERVICE_USERS", CONFIG_DB_PREFIX . "file_service_users");
	define ("FILE_SPL_CREDITS", CONFIG_DB_PREFIX . "file_spl_credits");
        define ("FILE_SPL_CREDITS_RESELLER", CONFIG_DB_PREFIX . "file_spl_credits_reseller");
	define ("FILE_SUPPLIER_DETAILS", CONFIG_DB_PREFIX . "file_supplier_details");
	define ("FORM_VALIDATE", CONFIG_DB_PREFIX . "form_validate");
	
	define ("GATEWAY_DETAILS", CONFIG_DB_PREFIX . "gateway_details");
	define ("GATEWAY_MASTER", CONFIG_DB_PREFIX . "gateway_master");

	define ("IMEI_BRAND_MASTER", CONFIG_DB_PREFIX . "imei_brand_master");
	define ("IMEI_FAQ_MASTER", CONFIG_DB_PREFIX . "imei_faq_master");
	define ("IMEI_GROUP_MASTER", CONFIG_DB_PREFIX . "imei_group_master");
	define ("IMEI_MEP_GROUP_MASTER", CONFIG_DB_PREFIX . "imei_mep_group_master");
	define ("IMEI_MEP_MASTER", CONFIG_DB_PREFIX . "imei_mep_master");
	define ("IMEI_MODEL_MASTER", CONFIG_DB_PREFIX . "imei_model_master");
	define ("IMEI_NETWORK_MASTER", CONFIG_DB_PREFIX . "imei_network_master");
	define ("IMEI_SPL_CREDITS", CONFIG_DB_PREFIX . "imei_spl_credits");
        define ("IMEI_SPL_CREDITS_RESELLER", CONFIG_DB_PREFIX . "imei_spl_credits_reseller");
	define ("IMEI_SUPPLIER_DETAILS", CONFIG_DB_PREFIX . "imei_supplier_details");
	define ("IMEI_TOOL_AMOUNT_DETAILS", CONFIG_DB_PREFIX . "imei_tool_amount_details");
	define ("IMEI_TOOL_MASTER", CONFIG_DB_PREFIX . "imei_tool_master");
	define ("IMEI_TOOL_USERS", CONFIG_DB_PREFIX . "imei_tool_users");

	
	define ("INVOICE_MASTER", CONFIG_DB_PREFIX . "invoice_master");
	define ("INVOICE_REQUEST", CONFIG_DB_PREFIX . "invoice_request");
	define ("INVOICE_LOG", CONFIG_DB_PREFIX . "invoice_log");
        
	define ("LANGUAGE_DETAILS", CONFIG_DB_PREFIX . "language_details");
	define ("LANGUAGE_MASTER", CONFIG_DB_PREFIX . "language_master");
	
	define ("MAIL_HISTORY", CONFIG_DB_PREFIX . "mail_history");
	
	define ("NEWS_MASTER", CONFIG_DB_PREFIX . "news_master");
	define ("NEWS_TICKER_MASTER", CONFIG_DB_PREFIX . "news_ticker_master");
	
	define ("ONLINE_CUSTOMERS", CONFIG_DB_PREFIX . "online_customers");

	define ("ORDER_FILE_SERVICE_MASTER", CONFIG_DB_PREFIX . "order_file_service_master");
	define ("ORDER_IMEI_MASTER", CONFIG_DB_PREFIX . "order_imei_master");
	define ("ORDER_IMEI_QUEUE", CONFIG_DB_PREFIX . "order_imei_queue");
	define ("ORDER_SERVER_LOG_MASTER", CONFIG_DB_PREFIX . "order_server_log_master");
	define ("RESELLER_MASTER", CONFIG_DB_PREFIX . "reseller_master");

	define ("PREPAID_LOG_AMOUNT_DETAILS", CONFIG_DB_PREFIX . "prepaid_log_amount_details");
	define ("PREPAID_LOG_GROUP_MASTER", CONFIG_DB_PREFIX . "prepaid_log_group_master");
	define ("PREPAID_LOG_MASTER", CONFIG_DB_PREFIX . "prepaid_log_master");
	define ("PREPAID_LOG_USERS", CONFIG_DB_PREFIX . "prepaid_log_users");
	define ("PREPAID_LOG_SPL_CREDITS", CONFIG_DB_PREFIX . "prepaid_log_spl_credits");
        define ("PREPAID_LOG_SPL_CREDITS_RESELLER", CONFIG_DB_PREFIX . "prepaid_log_spl_credits_reseller");
	define ("PREPAID_LOG_UN_MASTER", CONFIG_DB_PREFIX . "prepaid_log_un_master");
	define ("PREPAID_LOG_PACKAGE_DETAIL", CONFIG_DB_PREFIX . "prepaid_log_package_detail");
	define ("PREPAID_LOG_PACKAGE_MASTER", CONFIG_DB_PREFIX . "prepaid_log_package_master");
	define ("PREPAID_LOG_PACKAGE_USER_DETAIL", CONFIG_DB_PREFIX . "prepaid_log_package_user_detail");
	
	define ("PRODUCT_CATEGORY_MASTER", CONFIG_DB_PREFIX . "product_category_master");
	define ("PRODUCT_MASTER", CONFIG_DB_PREFIX . "product_master");
	define ("PRODUCT_BRAND_MASTER", CONFIG_DB_PREFIX . "product_brand_master");
	define ("PRODUCT_BANNER_MASTER", CONFIG_DB_PREFIX . "product_banner_master");
	define ("PRODUCT_COLOR_MASTER", CONFIG_DB_PREFIX . "product_color_master");
	define ("PRODUCT_COLOR_DETAILS", CONFIG_DB_PREFIX . "product_color_details");
	define ("PRODUCT_REVIEW_MASTER", CONFIG_DB_PREFIX . "product_review_master");
	define ("PRODUCT_CATEGORY_DETAILS", CONFIG_DB_PREFIX . "product_category_details");
	define ("PRODUCT_ORDER_DETAILS", CONFIG_DB_PREFIX . "product_order_details");
	define ("PRODUCT_ORDER_MASTER", CONFIG_DB_PREFIX . "product_order_master");
	define ("PRODUCT_VIDEO_CATEGORY_MASTER", CONFIG_DB_PREFIX . "product_video_category_master");
	define ("PRODUCT_VIDEO_DETAILS", CONFIG_DB_PREFIX . "product_video_details");
	define ("PRODUCT_WISHLIST_DETAILS", CONFIG_DB_PREFIX . "product_wishlist_details");
	
	define ("PACKAGE_FILE_DETAILS", CONFIG_DB_PREFIX . "package_file_details");
	define ("PACKAGE_IMEI_DETAILS", CONFIG_DB_PREFIX . "package_imei_details");
	define ("PACKAGE_MASTER", CONFIG_DB_PREFIX . "package_master");
	define ("PACKAGE_PREPAID_LOG_DETAILS", CONFIG_DB_PREFIX . "package_prepaid_log_details");
	define ("PACKAGE_SERVER_LOG_DETAILS", CONFIG_DB_PREFIX . "package_server_log_details");
	define ("PACKAGE_USERS", CONFIG_DB_PREFIX . "package_users");
	
	define ("RELATED_PRODUCT_DETAILS", CONFIG_DB_PREFIX . "related_product_details");

	define ("SERVER_LOG_AMOUNT_DETAILS", CONFIG_DB_PREFIX . "server_log_amount_details");
	define ("SERVER_LOG_GROUP_MASTER", CONFIG_DB_PREFIX . "server_log_group_master");
	define ("SERVER_LOG_MASTER", CONFIG_DB_PREFIX . "server_log_master");
	define ("SERVER_LOG_USERS", CONFIG_DB_PREFIX . "server_log_users");
	define ("SERVER_LOG_SPL_CREDITS", CONFIG_DB_PREFIX . "server_log_spl_credits");
        define ("SERVER_LOG_SPL_CREDITS_RESELLER", CONFIG_DB_PREFIX . "server_log_spl_credits_reseller");
	define ("SERVER_LOG_SUPPLIER_DETAILS", CONFIG_DB_PREFIX . "server_log_supplier_details");
	define ("SERVER_LOG_PACKAGE_DETAIL", CONFIG_DB_PREFIX . "server_log_package_detail");
	define ("SERVER_LOG_PACKAGE_MASTER", CONFIG_DB_PREFIX . "server_log_package_master");
	define ("SERVER_LOG_PACKAGE_USER_DETAIL", CONFIG_DB_PREFIX . "server_log_package_user_detail");

	define ("STATS_ADMIN_ACTIONS", CONFIG_DB_PREFIX . "stats_admin_actions");
	define ("STATS_ADMIN_LOGIN_MASTER", CONFIG_DB_PREFIX . "stats_admin_login_master");
	define ("STATS_USER_ACTIONS", CONFIG_DB_PREFIX . "stats_user_actions");
	define ("STATS_USER_LOGIN_MASTER", CONFIG_DB_PREFIX . "stats_user_login_master");
	
	define ("TEMPLATE_MASTER", CONFIG_DB_PREFIX . "template_master");
	define ("TESTIMONIALS_MASTER", CONFIG_DB_PREFIX . "testimonials_master");
	define ("TIMEZONE_MASTER", CONFIG_DB_PREFIX . "timezone_master");
	define ("USER_CREDIT_PPURCHASE_DETAIL", CONFIG_DB_PREFIX . "user_credit_purchase_detail");
	
	define ("TICKET_DETAILS", CONFIG_DB_PREFIX . "ticket_details");
	define ("TICKET_MASTER", CONFIG_DB_PREFIX . "ticket_master");

	define ("USER_GROUP_DETAIL", CONFIG_DB_PREFIX . "user_group_detail");
	define ("USER_GROUP_MASTER", CONFIG_DB_PREFIX . "user_group_master");
	define ("USER_MASTER", CONFIG_DB_PREFIX . "user_master");
	define ("USER_REGISTER_MASTER", CONFIG_DB_PREFIX . "user_register_master");
	define ("SUPPLIER_MASTER", CONFIG_DB_PREFIX . "supplier_master");
	define ("SUPPLIER_PAYMENT", CONFIG_DB_PREFIX . "supplier_payment");
	define ("USER_SPL_CREDITS_MASTER", CONFIG_DB_PREFIX . "user_spl_credits_master");
	define ("USER_SPL_PACKAGE_MASTER", CONFIG_DB_PREFIX . "user_spl_package_master");
	
	define ("WAREHOUSE_MASTER", CONFIG_DB_PREFIX . "warehouse_master");

	define ("SMTP_CONFIG", CONFIG_DB_PREFIX . "smtp_config");
        define ("INVOICE_EDIT", CONFIG_DB_PREFIX . "invoice_edit");
        define ("IP_POOL", CONFIG_DB_PREFIX . "ip_pool");
        
        //ecom table
        
        define ("Category", CONFIG_DB_PREFIX . "category");
        define ("Vendor", CONFIG_DB_PREFIX . "vendor");
        define ("Product", CONFIG_DB_PREFIX . "products");
        
         define ("Chat_Box", CONFIG_DB_PREFIX . "chat_pool");
          define ("Website_Maintinance", CONFIG_DB_PREFIX . "tbl_maintenance");
          define ("IMEI_MODEL_MASTER_2", CONFIG_DB_PREFIX . "imei_model_master_2");
            define ("Chat_Box_NEW", CONFIG_DB_PREFIX . "chat_pool_new");
			
	/* Tables for CMS management */	
	define ("CMS_PAGE_MASTER", CONFIG_DB_PREFIX . "cms_master");
	define ("CMS_MENU_MASTER", CONFIG_DB_PREFIX . "cms_menu_master");
	define ("SLIDER_MASTER", CONFIG_DB_PREFIX . "slider_master");
	define ("CMS_SETTINGS", CONFIG_DB_PREFIX . "cms_settings");
	define ("CMS_SOCIAL", CONFIG_DB_PREFIX . "social_master");
	define ("CMS_BLOCKS", CONFIG_DB_PREFIX . "cms_blocks");
	
	/* Tables for language management */	
	define ("LANG_MASTER", CONFIG_DB_PREFIX . "lang_master");
	
	define ("TRANSLATION_MASTER", CONFIG_DB_PREFIX . "translation_master");
	
	
        
?>