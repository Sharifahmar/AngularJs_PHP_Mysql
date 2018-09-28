<?php

ini_set("display_errors", 1);

error_reporting(E_ALL);

define('ENV', 'PRD');
//define('ENV', 'DEV');

if (ENV == 'DEV') {
    define('KEY', '!@iot-app#$');
    define('ASSET_URL', 'http://www.formeasyapi/api');
    define('DB_HOST', "localhost");
    define('DB_USER', "root");
    define('DB_PASS', "");
    define('DB_NAME', "form-assistance");
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else {
    define('KEY', '!@iot-app#$');
    define('ASSET_URL', 'http://www.formeasyapi/api');
    define('DB_HOST', "localhost");
    define('DB_USER', "root");
    define('DB_PASS', "");
    define('DB_NAME', "form-assistance");
}

/**/

/**/
define('TBL_TIPS', "blog");


/**/
define('MAIL_HOST', 'md-in-30.webhostbox.net');
define('MAIL_USER', "webmaster@nammashivamogga.com");
define('MAIL_PASS', "12Appnings#$");
define('MAIL_FROM_NAME', 'Diet Pro');
//define('MAIL_FROM_EMAIL', 'info@dietpro.com');
define('MAIL_FROM_EMAIL', 'info@appnings.com');
define('MAIL_ADMIN_EMAIL', 'gopal.appnings@gmail.com');
define('MAIL_CC_EMAIL', 'seema.appnings@gmail.com');
define('MAIL_SMTP_PORT', 587);

/**/
define("USER_NAME_FEILD", 'username');
define("USER_PASS_FEILD", 'password');
define("USER_ROLE_FEILD", 'usertype');
define("Tq_heading", "Thank You");
define("FID", "999");
define("Tq_message" , 'A big thanks for using Form Assistance, hope we made form filling experience easier for you.Please rate us if you liked our services or provide the feedback to improve.');

/* * */
define('DUMP_KEY', 'bb027');


/**/
define('APP_VERSION', '0.1');

/**/
define("GCM_API_KEY", 'AIzaSyDbqTGq_69QFhViOMsEtPHqp6VuoUVW6J8');
define("GCM_API_URL", 'https://android.googleapis.com/gcm/send');
