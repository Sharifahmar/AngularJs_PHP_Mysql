<?php

include __DIR__ . '/DB.php';
include __DIR__ . '/messages.php';

if (!isset($_SESSION)) {
    session_start();
}

if (date_default_timezone_get() !== 'Asia/Kolkata')
    date_default_timezone_set('Asia/Kolkata');

define('ENV', 'PRO');


if (ENV == 'DEV') {

    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    define('ASSET_URL', '');
    define('DB_HOST', "localhost");
    define('DB_USER', "root");
    define('DB_PASS', "");
    define('DB_NAME', "form-assistance");
} else {

    ini_set("display_errors", 0);
    error_reporting();

    define('ASSET_URL', '');
    define('DB_HOST', "localhost");
    define('DB_USER', "root");
    define('DB_PASS', "");
    define('DB_NAME', "form-assistance");
}


define('TABLE_ADD_TYPE', 'form_type');
define('TABLE_STATE', 'state_list');
define('TABLE_DEPT', 'department');
define('TABLE_MAIN_FORM', 'main_form');
define('TABLE_PRESET_FIELDS', 'preset_fields');
define('TABLE_LANGUAGE', 'languages');
define('TABLE_ATTRIBUTE_FORM', 'attribute_form');
define('TABLE_ORDER', 'order_table');
define('TABLE_ERRORS', 'error_reporting');
define('TABLE_REQUESTS', 'request_form');
define('USERS','users');

/**/

/**/
define("TSPEAK_ADMIN_APIKEY", 'IJMCE67JGD49TXK1');
define("TSPEAK_API_URL", 'https://api.thingspeak.com/channels/');
