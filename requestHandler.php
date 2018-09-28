<?php

//
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once './Constants.php';
include_once './Database.php';

include_once './Rest.inc.php';
require_once '../PHPMailer/PHPMailerAutoload.php';
//include_once './JWT2.php';


/**/
//include './Device.php';
//use Device;

/**
 * Web Service Request Handler - Using JWT:
 * 
 *
 * PHP version 5
 *
 * @category Base Request Handler
 * @package  RequestHandling_JWT
 * @author   BlackBurn027 <c4gopal@gmail.com>
 * @author   Anonymous < not sure from where half of this is taken >
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     Not Avaliable (atleast for now)
 */
class Request Extends REST {
    /* data string global for use in inherited class */

    public $data = "";

    /* No idea yet */
    public $app_user = array();

    /* used to hold the errors at the instance and pushed in the json formation stage */
    private $error = '';

    /* used to hold the messages at the instance and pushed in the json formation stage */
    private $message = '';

    /* db string private - will be filled once gets connected */
    protected $db = NULL;

    /* private access token for every instance of Device Request */
    private $auth_state = false;
    private $status = false;
    private $isToday = false;
    private $downloadLink = '';
    private $fileStatus;
    /* holds userData */
    private $userData = array();

    /* Holds the access token data while request occurs */
    private $userToken = array();

    /* Holds the exceptions occured while token validation  / other errors */
    private $exc = null;

    /**/
    private $assetUrl = '';

    /**/
    protected $headers = [];

    /**/
    private $sCode = 200;

    /**
     * Class Construct 
     * Initiantes a Database for every instance of itself
     * Sets the headers 
     * Authenticats the database along with an error handled
     * @param null Nothing 
     * @return  null Nothing
     */
    public
            function __construct() {

        parent::__construct();

        $this->db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $this->headers = getallheaders();

        if (!$this->db->isConnected) {
            $this->error = "Database Down";
            $this->response($this->getJson(), 503);
            exit();
            return;
        }
    }

    /**
     * Function which handles every API request, Checks for available api methods 
     * Validates all the inputs and makes sure the inputs are secure
     * @input String An string is obtained as a get parameter 
     * @return Function returns execute of a method called if present else throws an error in ajax response
     */
    public
            function processApi() {

        $this->auth_state = false; /* Assuming the authorization is not valid */

        $requestAction = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'not_found';

        $func = strtolower(trim(str_replace("/", "", $requestAction)));

        if ((int) method_exists($this, $func) > 0) {

            array_filter($_POST, array($this, 'trimValue'));

            return $this->$func();
        } else {

            $this->error = 'Invlaid Request - Method not implemented' . $requestAction;

            return $this->response($this->getJson(array()), 501);
        }
    }

    /**
     * Function to trim the text length
     * @param {string} $value
     */
    private
            function trimValue(&$value) {
        $value = is_string($value) ? trim($value) : $value;    // this removes whitespace and related characters from the beginning and end of the string
    }

    /**
     * Function which compiles a JSON string for any given input (Array)
     * @param Array $data an array which needs to be json encoded
     * @return String a JSON string
     */
    private
            function getJson($data = array()) {

        if (is_array($data)) {

            $this->payLoad = array(
                'list' => $data,
                'downloadLink' => $this->downloadLink,
                'fileExists' => $this->fileStatus,
                'error' => $this->error,
                'message' => $this->message,
                'status' => $this->status,
            );

            return json_encode($this->payLoad, JSON_UNESCAPED_UNICODE);
        }

        return json_encode($this->payLoad);
    }

##################################################
//    /**
//     * API endpoint for Handling login
//     * @return response Object
//     */
##################################################
    /**
     * Function to validate the user name and the password compared with the stored ones
     * @param String $inp_1
     * @param String $inp_2
     * @return Boolean


      /* #######***######## */

    /**
     * Function to process the user data if anything
     * @param Array $data
     * @return Array $data
     */
    private
            function formatUserData($data = []) {

        if ($data) {

            if (array_key_exists('devices', $data)) {

                $deviceData = [];

                $data['devices'] = json_decode($data['devices'], true);

                foreach ($data['devices']['channel_id'] as $k => $device) {

                    $deviceData[$k]['channel_id'] = $data['devices']['channel_id'][$k];

                    $deviceData[$k]['read_key'] = $data['devices']['read_key'][$k];
                }

                $this->userData['devices'] = $deviceData;
            }
        }

        return $data;
    }

    /*     * **************************************************** */

    /**
     * Function to convert to plain text
     * @param type $str
     * @return type
     */
    private
            function htmlToPlainText($str = '') {
        return (utf8_decode(strip_tags($str)));
    }

    /**
     * Function to issue JWT Auth token
     * @param type $tokenPayLoad
     * @return type
     */
    private
            function issueToken($tokenPayLoad) {
        return JWT::encode($tokenPayLoad, KEY);
    }

    /**
     * Function to validate the authenticity of a access token!
     * @return Boolean
     */
    private
            function isValidAccessToken() {

        $access_token = isset($this->headers['Authorization']) ? trim($this->headers['Authorization']) : '';

        $access_token = isset($this->headers['authorization']) ? trim($this->headers['authorization']) : $access_token;

        $access = array();

        try {

            $access = JWT::decode($access_token, KEY);
        } catch (Exception $exc) {

            $this->error = "Access token invalid / Unauthorized. Please login again.";

            $this->message = $exc->getMessage();

            $this->logError($exc);

            $this->response($this->getJson(), 401);

            exit();
        }

        $this->userToken = $access[1];

        return (isset($access[1]) ? TRUE : FALSE);
    }

    private function sendMail($sub, $body, $name, $replyto) {

        $mail = new PHPMailer;
//        $mail->SMTPDebug = 2;                                 // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'md-in-30.webhostbox.net';                            // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'webmaster@appnings.com';                // SMTP username
        $mail->Password = '12Appnings#$';                           // SMTP password
//        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('webmaster@appnings.com', 'Mailer');
        $mail->addAddress(MAIL_ADMIN_EMAIL);     // Add a recipient
//        $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo($replyto, 'Information');
        $mail->addCC(MAIL_CC_EMAIL);
//        $mail->addBCC('bcc@example.com');
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $sub;
        $mail->Body = $body;
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()) {
//            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
//            echo 'Message has been sent';
        }
    }

    private function listFormTypes() {

        $res = [
            'rows' => 0, 'data' => []
        ];
//        
        $this->db->initateRawQuery('SELECT * FROM `form_type` where delete_flag = "0" order by fid DESC');

        $_data = $this->db->prepareFields([]);

        $exe_get = $this->db->query($_data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {
            $data = $res;
            $this->status = true;
            return $this->response($this->getJson($data), 200);
        }

        $this->error = 'Not able to fetch Form Types list';
        return $this->response($this->getJson(), 403);
    }

    private function listState() {

        $data = file_get_contents('../cities.json');

        $data = json_decode($data);

        function cmp($a, $b) {
            return strcmp($a->name, $b->name);
        }

        usort($data, "cmp");

        if ($data) {
            $this->status = true;
            return $this->response($this->getJson($data), 200);
        } else {
            $this->error = 'Not able to fetch state list';
            return $this->response($this->getJson(), 403);
        }
    }

    private function listDepartments() {

        $res = [
            'rows' => 0, 'data' => []
        ];
//        
        $this->db->initateRawQuery('SELECT * FROM `department` where delete_flag = "0" order by dept_id DESC');

        $_data = $this->db->prepareFields([]);

        $exe_get = $this->db->query($_data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {
            $data = $res;
            $this->status = true;
            return $this->response($this->getJson($data), 200);
        }

        $this->error = 'Not able to fetch departments';
        return $this->response($this->getJson(), 403);
    }

    private function searchForms() {

        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
        $dept = filter_input(INPUT_POST, 'dept', FILTER_SANITIZE_STRING);

        if (($type && !$state && !$dept) || (!$type && $state && !$dept) || (!$type && !$state && $dept)) {
            $str = ( ($type) ? 'form_type LIKE "%' . $type . '%"' : (($state) ? 'state_name LIKE "%' . $state . '%"' : (($dept) ? 'dept_name LIKE "%' . $dept . '%"' : '')));
            $this->db->initateRawQuery('SELECT * FROM `main_form` where ' . $str . 'and delete_flag = "0"');
        } else
        if ($type && $state && $dept) {
            $this->db->initateRawQuery('SELECT * FROM `main_form` where delete_flag = "0" AND state_name LIKE "%' . $state . '%" AND dept_name LIKE "%' . $dept . '%" AND form_type LIKE "%' . $type . '%"');
        } elseif (!$type && !$state && !$dept) {
            $this->db->initateRawQuery('SELECT * FROM `main_form` where delete_flag = "0"');
        } elseif (($type && $state) || ($dept && $type) || ($dept && $state)) {
            $str = ( ($type && $state) ? 'form_type LIKE "%' . $type .
                    '%" AND state_name LIKE "%' . $state .
                    '%"' : (($dept && $type) ? 'dept_name LIKE "%' . $dept .
                    '%" AND form_type LIKE "%' . $type .
                    '%"' : (($dept && $state) ? 'dept_name LIKE "%' . $dept . '%" AND state_name LIKE "%' . $state .
                    '%"' : '')));

            $this->db->initateRawQuery('SELECT * FROM `main_form` where ' . $str . 'and delete_flag = "0"');
        }


        $_data = $this->db->prepareFields([]);

        $exe_get = $this->db->query($_data);
        $data2 = [];
        $new_res = [];
        $res = $this->db->get();


        if ($res['rows']) {

            $languages_raw = $res['data'];
            $languages_formatted = [];

            foreach ($languages_raw as $key => $value) {

                $state_decoded = json_decode($value['state_name'], true);
                $dept_decoded = json_decode($value['dept_name'], true);
                $form_type_decoded = json_decode($value['form_type'], true);
                $language_id_decoded = json_decode($value['language_id'], true);

                $languages_formatted[$key] = [
                    'id' => $value['id'],
                    'form_name' => $value['form_name'],
                    'form_uid' => $value['form_uid'],
                    'state_name' => $state_decoded,
                    'dept_name' => $dept_decoded,
                    'form_type' => $form_type_decoded,
                    'language_id' => $language_id_decoded,
                    'fileName' => $value['fileName'],
                    'published' => $value['published']
                ];
            }

            $this->status = true;
            return $this->response($this->getJson($languages_formatted), 200);
        }

        $this->error = 'Form Is Not Available';
        return $this->response($this->getJson(), 403);
    }

    private function recentForms() {
        $res = [
            'rows' => 0, 'data' => []
        ];
//        
        $this->db->initateRawQuery('SELECT * FROM `main_form` where delete_flag = "0" order by id DESC');

        $_data = $this->db->prepareFields([]);

        $exe_get = $this->db->query($_data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {
            $languages_raw = $res['data'];
            $languages_formatted = [];

            foreach ($languages_raw as $key => $value) {

                $state_decoded = json_decode($value['state_name'], true);
                $dept_decoded = json_decode($value['dept_name'], true);
                $form_type_decoded = json_decode($value['form_type'], true);
                $language_id_decoded = json_decode($value['language_id'], true);

                $languages_formatted[$key] = [
                    'id' => $value['id'],
                    'form_name' => $value['form_name'],
                    'form_uid' => $value['form_uid'],
                    'state_name' => $state_decoded,
                    'dept_name' => $dept_decoded,
                    'form_type' => $form_type_decoded,
                    'language_id' => $language_id_decoded,
                    'fileName' => $value['fileName'],
                    'published' => $value['published'],
                    'donloadlink' => $value['webLink']
                ];
            }

            $this->status = true;
            return $this->response($this->getJson($languages_formatted), 200);
        }

        $this->error = 'Not able to fetch forms';
        return $this->response($this->getJson(), 403);
    }

    private function languageAvailableForForm() {

        $main_form_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        $this->db->initateRawQuery('SELECT attr_language,id as attr_id FROM `attribute_form` where main_form_id =:main_form_id order by id DESC');

        $_data = $this->db->prepareFields(['main_form_id' => $main_form_id]);

        $exe_get = $this->db->query($_data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {

            $languages = $res['data'];


            foreach ($languages as $key => $value) {

                $data[] = (json_decode($value['attr_language']));
                $data[$key]->attr_id = $value['attr_id'];
            }
            $this->status = true;
            return $this->response($this->getJson($data), 200);
        }

        $this->error = 'Not able to fetch language available for form';
        return $this->response($this->getJson(), 403);
    }

    private function jsonParse($str) {

        return $parsedData[] = json_decode($str, true);
    }

    private function innerAttributeListing() {

        $attr_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        $this->db->initateRawQuery('SELECT attribute_form.fields,attribute_form.fileName,attribute_form.main_form_id, main_form.webLink from attribute_form join main_form WHERE attribute_form.main_form_id = main_form.id AND attribute_form.id=:id');

        $data = $this->db->prepareFields(['id' => $attr_id]);

        $exe_get = $this->db->query($data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {

            $data = $this->jsonParse($res['data'][0]['fields']);

            /* Re ordering the array based on the order key ! #Gopal 22/03/2017*/
            usort($data, function ($item1, $item2) {
                if ($item1['order'] == $item2['order'])
                    return 0;
                return $item1['order'] < $item2['order'] ? -1 : 1;
            });
//            print_r($data);
            /* Removed default thank you message : Gopal @ 27/02/2017 */
//            array_push($data, array('number' => FID, 'order' => FID, 'value' => Tq_heading, 'fieldinstruction' => Tq_message, 'field_id' => FID,));
            $this->status = true;
            $this->downloadLink = $res['data'][0]['webLink'];
            $this->fileStatus = ($res['data'][0]['fileName']) ? true : false;

            return $this->response($this->getJson($data), 200);
        }
        $this->error = 'Not able to fetch attribute list';
        return $this->response($this->getJson(), 403);
    }

    private function listReorderedArrtibutes($attr_id) {

        $this->db->initateRawQuery('select ot.order_id , ot.orderItem,ot.attr_id , c.webLink
from order_table ot, attribute_form  b, main_form c
where b.id = ot.attr_id and b.id = c.id and ot.attr_id=:attr_id order by ot.order_id DESC limit 1');

        $data = $this->db->prepareFields(['attr_id' => $attr_id]);

        $exe_get = $this->db->query($data);

        $res = $this->db->get();

        return $res;
    }

    private function listOfLanguage() {
        $this->db->initateRawQuery('SELECT * FROM `languages` where delete_flag = "0"');

        $_data = $this->db->prepareFields([]);

        $exe_get = $this->db->query($_data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {
            $data = $res;
            $this->status = true;
            return $this->response($this->getJson($data), 200);
        }

        $this->error = 'Not able to fetch list of language';
        return $this->response($this->getJson(), 403);
    }

    public function fileUpload($fileName = '', $path = '') {

        $fileName = $fileName ?: $_FILES['fileName']['name'];

        $target_dir = $path;
        $target_file = $target_dir . basename($fileName);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        if ($uploadOk == 0) {
            return "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES['fileName']["tmp_name"], $target_file)) {
                return 'uploaded';
            } else {
                return "Sorry, there was an error uploading your file.";
            }
        }
    }

    private function errorReporting() {

        $errorText = filter_input(INPUT_POST, 'errorText', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email_id = filter_input(INPUT_POST, 'emailId', FILTER_SANITIZE_EMAIL);
        $fileName = md5($_FILES['fileName']['name']) . $_FILES['fileName']['name'];
        $fields = ['error_text' => $errorText,
            'error_image' => $fileName,
            'name' => $name,
            'emailId' => $email_id
        ];
        /* removed email id from being mandotory : Gopal @ 27/02/2017 */
        if ((!$errorText) || (!$name)) {
            $this->error = 'Erro text & name  mandatory';
            return $this->response($this->getJson(), 404);
        } else {
            if ($_FILES['fileName']['name'] && $errorText) {
                $path = "../uploads/error_reporting/";
                $imgRes = $this->fileUpload($fileName, $path);
                if ($imgRes == 'uploaded') {
                    $this->db->composeInsert('error_reporting', array_keys($fields));
                } else {
                    $this->error = $imgRes;
                    return $this->response($this->getJson(), 404);
                }
            } elseif ($errorText && !$_FILES['fileName']['name']) {

                $this->db->composeInsert('error_reporting', array_keys($fields));
            }

            $v = $this->db->prepareFields($fields);

            $exe_put = $this->db->query($v);
            if ($exe_put) {
                $name = $name;
                $replyTo = $email_id;
                $sub = 'Form App Alert';
                $body = 'Dear Admin,
One of the app user has sent an error report, Kindly login to the admin panel to check the details.
http://ndezigners.com/form-assist';
//                $this->sendMail($sub, $body, $name, $replyTo);
                $this->status = true;
                $this->message = 'Error reported successfully';
                return $this->response($this->getJson(), 200);
            } else {
                $this->error = 'Error not reported';
                return $this->response($this->getJson(), 403);
            }
        }
    }

    private function formRequest() {

        $formType = filter_input(INPUT_POST, 'formType', FILTER_SANITIZE_STRING);
        $stateName = filter_input(INPUT_POST, 'stateName', FILTER_SANITIZE_STRING);
        $formName = filter_input(INPUT_POST, 'formName', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email_id = filter_input(INPUT_POST, 'emailId', FILTER_SANITIZE_STRING);
        $fileName = md5($_FILES['fileName']['name']) . $_FILES['fileName']['name'];

        /* removed email id from being mandotory : Gopal @ 27/02/2017 */
        if (!$formType || !$formName || !$name) {
            $this->error = 'Form type,name and Form name are mandatory fields';
            return $this->response($this->getJson(), 404);
        } elseif (!preg_match('/^[A-z]+$/', $formType) || ($stateName && !preg_match('/^[A-z]+$/', $stateName))) {
            $this->error = 'form type and form name should be alphabets only';
            return $this->response($this->getJson(), 404);
        } else {
            $fields = ['form_type' => $formType,
                'state_name' => $stateName,
                'form_name' => $formName,
                'form_image' => $fileName,
                'name' => $name,
                'emailId' => $email_id
            ];

            if ($formType && $stateName && $_FILES['fileName']['name']) {
                $path = "../uploads/form_request/";
                $imgRes = $this->fileUpload($fileName, $path);
                if ($imgRes == 'uploaded') {
                    $this->db->composeInsert('request_form', array_keys($fields));
                } else {
                    $this->error = $imgRes;
                }
            } elseif ($formType && $formName && !$_FILES['fileName']['name']) {

                $this->db->composeInsert('request_form', array_keys($fields));
            }

            $v = $this->db->prepareFields($fields);

            $exe_put = $this->db->query($v);
            if ($exe_put) {
                $name = $name;
                $replyTo = $email_id;
                $sub = 'Form App Alert';
                $body = 'Dear Admin,
An app user has requested for this <strong>' . $formName . '</strong>, Login to admin panel for further details.
http://ndezigners.com/form-assist';
//                $this->sendMail($sub, $body, $name, $replyTo);
                $this->status = true;
                $this->message = 'form requested successfully';
                return $this->response($this->getJson(), 200);
            }
            $this->error = 'form not requested';
            return $this->response($this->getJson(), 403);
        }
    }

    private function formDownload() {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        $this->db->initateRawQuery('SELECT * FROM `attribute_form` where id=' . $id);

        $_data = $this->db->prepareFields([]);

        $exe_get = $this->db->query($_data);

        $res = $this->db->get();
        if ($exe_get && $res['rows']) {

            $file_path = '../uploads/filled_forms/' . $res['data'][0]['fileName'] . '';
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $res['data'][0]['fileName'] . '"');
            header('Pragma: no-cache');
            readfile($file_path);
        }
    }

    /**
     * Function which logs the error to the default error.log file of the server in the given format
     * @param Exception $exc Any caught exceptions are passed onto this function for logging it
     * @return string
     */
    private
            function logError($exc = array()) {

        $this->exc = $exc;

        $log_string = "Code:" . $this->errorData('getCode') . "<br/>Message:" . $this->errorData('getMessage') . "<br/>Trace:" . $this->errorData('getTraceAsString');

        error_log($log_string);

        return $log_string;
    }

    /**
     * Function to log error data with return
     * @param type $method
     * @return type
     */
    private
            function errorData($method = 'getMessage') {

        return (method_exists($this->exc, $method) ? $this->exc->$method() : "No Error data available");
    }

###########################################################################

    public
            function dump_dbsql() {

        $key = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        if ($key === DUMP_KEY) {
            try {
                exec('mysqldump --user=' . DB_USER . ' --password=' . DB_PASS . ' --host=' . DB_HOST . ' ' . DB_NAME . ' > ' . __DIR__ . '/db_dump.sql');
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
    }

}

$request = new Request();

/**/
$request->processApi();


