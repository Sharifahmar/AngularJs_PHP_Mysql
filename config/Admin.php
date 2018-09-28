<?php

include __DIR__ . '/Config.php';

Class PanelAdmin {
    /**/

    public $error;
    public $inputData;
    /**/
    public $resCode = 200;

    /**/
    private $allowedTypes;

    /**/
    private $blogErrors;

    /**/

    function __construct() {

        $this->inputData = $requestParameters = json_decode(file_get_contents('php://input'), true);
//        $this->formData = $_POST;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = $requestParameters;
        } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $_GET = $requestParameters;
        }

        $_REQUEST = $requestParameters;
    }

    /**
     * Login function
     * @return type
     */
    public function login() {

        $user_name = $this->inputData['aname'];

        $user_pass = $this->inputData['apassword'];

        if ($user_name) {

            if ($user_pass) {

                /* Function Call to validateUser() */
                $check = $this->validateUser($user_name, $user_pass);
                if ($check == 'loggedIn') {
                    return $this->ajaxResponse(['loggedIn' => $check]);
                } else {
                    return $this->ajaxResponse(['msg' => $check]);
                }
            } else {

                return $error = 'Password is required';
            }
        } else {

            return $error = 'Username is required';
        }
    }

    /*
     *
     * Function Definition - Validates user credentials against the db values
     * @params Input $a:Username $b:Password
     * @output Boolean Result
     */

    protected function validateUser($username, $password) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = "SELECT * FROM `users` WHERE username='$username' and password='$password';";
        $result = $db->initateRawQuery($query);
        $data = $db->prepareFields(['id' => $id]);
        $exe_get = $db->query($data);
        $result = $db->get('fetch');
        if ($result['rows'] == 1) {
                $_SESSION['user'] = $username;
                $_SESSION['authkey'] = 1;
                $_SESSION['isAdmin'] = $result['data']['isAdmin'];
                return 'loggedIn';
        } 
        else {
                return $error = "Invalid Username or password";
        }
    }

    public function is_loggedin() {
        if (isset($_SESSION['user'])) {
            $data = ['isLoggedIn' => true, 'user' => $_SESSION['user']];
            return $this->ajaxResponse($data);
        } else {
            return $this->ajaxResponse(['isLoggedIn' => false, 'user' => $_SESSION['user']]);
        }
    }

    public function is_admin() {
        if (isset($_SESSION['user'])) {
            $data = ['isAdmin' => $_SESSION['isAdmin'], 'user' =>$_SESSION['user']];
            return $this->ajaxResponse($data);
        } else {
            return $this->ajaxResponse( ['isAdmin' => false, 'user' =>$_SESSION['user']]);
        }
    }


    public function add_user() {

        $username = $this->inputData['aname'];

        $password = $this->inputData['apassword'];

        $isAdmin = 0;

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($username && $password) {

            $this->insert(compact('username','password','isAdmin'),USERS);
        }

    }

    public function add_admin() {

    	$name = $this->inputData['name'];

        $username = $this->inputData['uname'];

        $password = $this->inputData['apassword'];

        $isAdmin = 1;

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($name && $username && $password) {

            $this->insert(compact('name','username','password','isAdmin'),ADMINS);
        }

    }

 public function listAllAdmins($fields = [], $cond = []) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $result = ['rows'=>0, 'data'=>[]];

        $cond = ['isAdmin' => 1];

        $db->composeSelect(ADMINS, $fields, array_keys($cond));

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);

    }

    public function listAllUsers($fields = [], $cond = []) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $result = ['rows'=>0, 'data'=>[]];

        $cond = ['isAdmin' => 0];

        $db->composeSelect(USERS, $fields, array_keys($cond));

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);

    }


    public function deleteUser() {

        $id = $this->inputData['id'];
        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query="delete from users where id = $id;";
        $db->initateRawQuery($query);
        $data = $db->prepareFields(['id' => $id]);
        $exe_get = $db->query($data);
        if($exe_get) {
            echo 'Data Deleted Successfully';
        } else {
            return $error = "error deleting";
        }
    }
    /*
     * Function to handle ajax response
     * @param type $dt
     */

    protected function ajaxResponse($dt = []) {

        $resData = ['error' => $this->error, 'response' => $dt, 'success' => $this->resCode == 200 && !$this->error ? true : false];

        header('Content-type: application/json', FALSE, $this->resCode);

        echo json_encode($resData);

        exit();
    }

    /**
     * Function definitio - To list departments
     * @param type $fields
     * @param type $cond
     * @return type list of depts
     */
    public function listAllDept($fields = [], $cond = []) {

        $result = ['rows' => 0, 'data' => []];

        $cond = ['delete_flag' => '0'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect(TABLE_DEPT, $fields, array_keys($cond));

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    /**
     * function definition - To list all states
     * @param type $fields
     * @param type $cond
     * @return type list of states
     */
    public function listAllState($fields = [], $cond = []) {

        $result = ['rows' => 0, 'data' => []];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect(TABLE_STATE, $fields, array_keys($cond));

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    /**
     * Function definitio - To list sub categories
     * @param type $fields
     * @param type $cond
     * @return type list of sub categories
     */
    public function listCategoryById($fields = [], $cond = []) {

        $id = $this->inputData['ft_id'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
/*
        $db->initateRawQuery('SELECT fields from attribute_form WHERE id=:id and delete_flag =:delete_flag ');
*/
        $db->initateRawQuery('SELECT * from sub_categories WHERE ft_id=:id order by sb_category_name asc');

        $data = $db->prepareFields(['id' => $id]);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {
            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
//
    }










    /**
     * function definition - To list all languages
     * @param type $fields
     * @param type $cond
     * @return type list of languages
     */
    public function listAllLanguages($fields = [], $cond = []) {
        $result = ['rows' => 0, 'data' => []];

        $cond = ['delete_flag' => '0'];

        $id = isset($this->inputData['id']) ? $this->inputData['id'] : 0;

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect(TABLE_LANGUAGE, $fields, array_keys($cond));

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if ($id) {

            /*
             * if Language Already added then remove it from language list in add new language
             */

            $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            $db->initateRawQuery('SELECT language_id from main_form WHERE id = :id');

            $data = $db->prepareFields(['id' => $id]);

            $exe_get = $db->query($data);

            $lang_id = $db->get('fetch');

            foreach ($result['data'] as $key => $val) {
                if (in_array($result['data'][$key]['language_id'], json_decode($lang_id['data']['language_id']))) {
                    unset($result['data'][$key]);
                }
            }
        }

        $result['data'] = array_values($result['data']);

        return $this->ajaxResponse($result['data']);
    }

    /**
     * function definition - To list all FormType
     * @param type $fields
     * @param type $cond
     * @return type list of all form types
     */
    public function listAllFormType($fields = [], $cond = []) {

        $result = ['rows' => 0, 'data' => []];

        $cond = ['delete_flag' => '0'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect(TABLE_ADD_TYPE, $fields, array_keys($cond));

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? array_reverse($result['data']) : []);
    }

    /**
     * Function defination - To list all languages available for the form
     * @param type $fields
     * @param type $cond
     * @return type list of language
     */
    public function listAvailableLanguageForForm($fields = [], $cond = []) {
        $result = ['rows' => 0, 'data' => []];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->initateRawQuery('SELECT attribute_form.id ,attribute_form.main_form_id, attribute_form.attr_language from attribute_form');

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        $formattedData = $this->mapFormAttrIdToLanguage($result['data']);

        return $this->ajaxResponse($result['rows'] ? $formattedData : []);
    }

    /**
     * Function Definition - To map main form id to language
     * @param type $_attr_data
     * @return type list of form with available language
     */
    private function mapFormAttrIdToLanguage($_attr_data) {

        $languageWithAttr = [];

        foreach ($_attr_data as $attr) {

            $languageData = json_decode($attr['attr_language']);

            $languageWithAttr[$attr['main_form_id']][] = ['attr_id' => $attr['id'], 'language' => $languageData->language];
        }

        return $languageWithAttr;
    }

    /**
     * Function Definition - To list main form Fields
     * @param type $fields
     * @param type $cond
     * @return type list of main form fields
     */
    public function listMainFormFields($fields = [], $cond = []) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->initateRawQuery('SELECT * from main_form where delete_flag = 0');

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);
        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    /**
     *
     * @param type $fields
     * @param type $cond
     * @return type
     */
    public function listReorderedArrtibutes($fields = [], $cond = []) {

        $attr_id = $this->inputData['aid'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->initateRawQuery('select * from order_table where attr_id =:attr_id order by order_id DESC limit 1');

        $data = $db->prepareFields(['attr_id' => $attr_id]);

        $exe_get = $db->query($data);

        $res = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $res;
    }

    /**
     * Function Definition - To fetch form inner atrributes
     * @param type $fields
     * @param type $cond
     * @return type list of form inner attributes
     */
    public function listAttrs($fields = [], $cond = []) {


        $id = $this->inputData['aid'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->initateRawQuery('SELECT fields from attribute_form WHERE id=:id and delete_flag =:delete_flag ');

        $data = $db->prepareFields(['id' => $id, 'delete_flag' => '0']);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {
            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
//
    }

    public function listErrors($fields = [], $cond = []) {

        $result = ['rows' => 0, 'data' => []];

        $cond = ['delete_flag' => '0'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect(TABLE_ERRORS, $fields, array_keys($cond), ['error_id' => 'DESC']);

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    public function formRequests($fields = [], $cond = []) {

        $result = ['rows' => 0, 'data' => []];

        $cond = ['delete_flag' => '0'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect(TABLE_REQUESTS, $fields, array_keys($cond), ['form_request_id' => 'DESC']);

        $data = $db->prepareFields($cond);

        $exe_get = $db->query($data);

        $result = $db->get();



        // exit;

        if (!$exe_get) {

            $this->error = 'Error querying database';
        }

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    /**
     * Save Department in db
     */
    public function saveDept() {

        $dept_name = @$this->inputData['dept_name'];
        if ($dept_name) {
            $this->insert(compact('dept_name'), TABLE_DEPT);
        }
    }

    /**
     * Save language in db
     */
    public function saveFormType() {

        $form_type = $this->inputData['form_type'];
        $fid = @$this->inputData['id'];

        if ($fid && $form_type) {
            $this->update(compact('fid'), compact('form_type'), TABLE_ADD_TYPE);
        } else if ($form_type) {
            $this->insert(compact('form_type'), TABLE_ADD_TYPE);
        }
    }

    public function saveLanguageName() {
        $language = $this->inputData['language'];
        $language_id = @$this->inputData['id'];

        if ($language_id && $language) {
            $this->update(compact('language_id'), compact('language'), TABLE_LANGUAGE);
        } else if ($language) {
            $this->insert(compact('language'), TABLE_LANGUAGE);
        }
    }

    public function b64StringToImage($base64string, $path) {

        list($base64string) = explode(';', $base64string);
        list($base64string) = explode(',', $base64string);
        $base64string = base64_decode($base64string);

        $r = file_put_contents($path, $base64string);

        return $r > 0 ? true : false;
    }

    /**
     * Edit form attributes
     */
    public function editFormFields() {

        $attr_id = @$this->inputData['fid'];

        $id = @$this->inputData['mid'];

        $main_form_name = $this->inputData['main_form_name'];

        $attr_form_name = $this->inputData['form_name'];

        $fields = $this->inputData['fieldNames'];

        $published = $this->inputData['published'];

        $webLink = $this->inputData['webLink'];



        $fileObj = isset($this->inputData['fileName']) ? $this->inputData['fileName'] : '';

        if ($fileObj) {
            $base64string = $this->inputData['fileName']['base64'];
            $fileName = md5($this->inputData['fileName']['filename']) . $this->inputData['fileName']['filename'];
        } else {
            $base64string = '';
            $fileName = '';
        }

        if ($base64string != '') {

            $path = "/var/www/html/uploads/filled_forms/" . $fileName;

            $uploadFlag = $this->b64StringToImage($base64string, $path);
        }

        if ($webLink) {
            if (!filter_var($webLink, FILTER_VALIDATE_URL) === false) {
                $webLink = $webLink;
            } else {
                $webLink = 'http://' . $webLink;
            }
        }
        if ($this->updateForm(compact('id'), ['published' => $published, 'webLink' => $webLink, 'fileName' => $fileName, 'form_name' => $main_form_name], TABLE_MAIN_FORM)) {

            $this->update(['id' => $attr_id],['form_name'=>$attr_form_name , 'fields' => $fields,'fileName' =>$fileName], TABLE_ATTRIBUTE_FORM);
        }
    }

    /**
     * fincion to save form fields
     */
    public function saveFormFields() {


        $id = @$this->inputData['fid'] ? $this->inputData['fid'] : '';

        $attr_language = $this->inputData['language'] == 'undefined' ? '' : $this->inputData['language'];

        $new_language = $this->inputData['language_id'];
        $a = [];

        $a[] = $new_language;

        $language_id = json_encode($a);

        $form_type = $this->inputData['formType'];

        $state_name = $this->inputData['stateName'];

        $dept_name = $this->inputData['dept'];

        $admin = $this->inputData['admin'];

        $webLink = @$this->inputData['webLink'];

        if (($webLink)) {
            if (!filter_var($webLink, FILTER_VALIDATE_URL) === false) {
                $webLink = $webLink;
            } else {
                $webLink = 'http://' . $webLink;
            }
        }


        $form_name = $this->inputData['form_name'];

        $fileObj = isset($this->inputData['fileName']) ? $this->inputData['fileName'] : '';

        if ($fileObj) {
            $base64string = $this->inputData['fileName']['base64'];
            $fileName = md5($this->inputData['fileName']['filename']) . $this->inputData['fileName']['filename'];
        } else {
            $base64string = '';
            $fileName = '';
        }



        $fields = $this->inputData['fieldNames'];

        $published = $this->inputData['published'];

        $main_form_id = $id;



        if ($base64string != '') {

            $path = "/var/www/html/uploads/filled_forms/" . $fileName;

            $uploadFlag = $this->b64StringToImage($base64string, $path);
        }

        /**
         * To add new language for the form
         */
        if ($new_language && $id) {

            $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            $db->initateRawQuery('SELECT language_id from main_form WHERE id = :id');

            $data = $db->prepareFields(['id' => $id]);

            $exe_get = $db->query($data);

            $result = $db->get('fetch');

            $added = json_decode($result['data']['language_id']);

            if (!in_array($new_language, $added)) {

                $added[].= $new_language;
                if ($this->updateOrder(['id' => $id], ['language_id' => json_encode($added)], TABLE_MAIN_FORM))
                    $this->insert(compact('main_form_id', 'form_name', 'fields', 'attr_language'), TABLE_ATTRIBUTE_FORM);
            }
        } else if ($form_type && $dept_name && $language_id) {

            $this->insertPreset(compact('language', 'dept_name', 'state_name', 'form_type', 'form_name', 'fileName', 'language_id', 'published', 'webLink','admin'), TABLE_MAIN_FORM);
        }
    }

    /**
     * To save reordered Items
     * @param type $aid
     */
    public function saveReorederdItems($aid) {
        $attr_id = $aid;
        $fields = json_encode($this->inputData);
        $res = $this->saveOrder(['id' => $attr_id], compact('fields'), TABLE_ATTRIBUTE_FORM);
    }

    /**
     * To update Dept name
     */
    public function updateDeptName() {
        $dept_id = $this->inputData['id'];
        $dept_name = $this->inputData['dept_name'];
        $this->update(compact('dept_id'), compact('dept_name'), TABLE_DEPT);
    }

    /**
     * To update State name
     */
    public function updateStateName() {
        $sid = $this->inputData['id'];
        $state_name = $this->inputData['state_name'];
        $this->update(compact('sid'), compact('state_name'), TABLE_STATE);
    }

    /**
     * To update Form Type
     */
    public function updateFormType() {
        $fid = $this->inputData['id'];
        $form_type = $this->inputData['form_type'];
        $this->update(compact('fid'), compact('form_type'), TABLE_ADD_TYPE);
    }

    /*
     * Function to insert the data
     * @param type $data
     * @param type $table
     */

    private function insert($data, $table) {
        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $db->composeInsert($table, array_keys($data));
        $format_data = $db->prepareFields($data);
        $exe_put = $db->query($format_data);
        if ($exe_put) {
            echo "Data Added Successfully";
        }
    }

    /**
     * To insert form inner attributes in to Attribute table
     * @param type $data
     * @param type $table
     */
    private function insertPreset($data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $db->composeInsert($table, array_keys($data));
        $format_data = $db->prepareFields($data);
        $exe_put = $db->query($format_data);

        if ($exe_put) {

            $id = $db->last_insert_id();

            $digits = 4;
            $firstFour = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $secondFour = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $form_uid = $firstFour . $id . $secondFour;
            if ($id && $form_uid) {
                $this->updateForm(compact('id'), compact('form_uid'), TABLE_MAIN_FORM);
            }

            $attr_language = $this->inputData['language'];

            $fields = $this->inputData['fieldNames'];

            $form_name = $this->inputData['form_name'];

            $fileName = $data['fileName'];

            $main_form_id = $id;
//
            if ($main_form_id && $attr_language && $fields && $form_name) {

                $this->insert(compact('main_form_id', 'attr_language', 'fields', 'form_name', 'fileName'), TABLE_ATTRIBUTE_FORM);
            }
        }
    }

    private function updateForm($c = [], $data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeUpdate($table, array_keys($data), array_keys($c));

        $format_data = $db->prepareFields(array_merge($data, $c));

        $exe_put = $db->query($format_data);

        if ($exe_put) {
            return true;
        }
    }

    private function updateOrder($c = [], $data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeUpdate($table, array_keys($data), array_keys($c));

        $format_data = $db->prepareFields(array_merge($data, $c));

        $exe_put = $db->query($format_data);

        if ($exe_put) {
            return true;
        }
    }

    private function updateDelete($c = [], $data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeUpdate($table, array_keys($data), array_keys($c));

        $format_data = $db->prepareFields(array_merge($data, $c));

        $exe_put = $db->query($format_data);

        if ($exe_put) {
            echo 'Data Deleted Successfully';
        }
    }

    /**
     * Function to update existing user details
     * @param type $c
     * @param type $data
     * @param type $table
     */
    /**/
    private function update($c = [], $data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeUpdate($table, array_keys($data), array_keys($c));

        $format_data = $db->prepareFields(array_merge($data, $c));

        $exe_put = $db->query($format_data);

        if ($exe_put) {
            echo 'Data Updated Successfully';
        }
    }

    private function saveOrder($c = [], $data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeUpdate($table, array_keys($data), array_keys($c));

        $format_data = $db->prepareFields(array_merge($data, $c));

        $exe_put = $db->query($format_data);

        if ($exe_put) {
            echo 'Order saved Successfully';
        }
    }

    private function updateAttr($c = [], $data, $table) {

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeUpdate($table, array_keys($data), array_keys($c));

        $format_data = $db->prepareFields(array_merge($data, $c));

        $exe_put = $db->query($format_data);

        if ($exe_put) {

            $instructions = $this->inputData['fieldInstruction'];

            $fields = $this->inputData['fieldNames'];

            $form_name = $this->inputData['form_name'];

            $main_form_id = $this->inputData['fid'];

            if ($main_form_id && $instructions && $fields && $form_name)
                $this->update(compact('main_form_id'), compact('fields', 'instructions', 'form_name'), TABLE_ATTRIBUTE_FORM);
        }
    }

    /**
     * Function to get single user data from db
     * @param array $f fields
     * @param array $c condition
     * @return type
     * */
    /**/
    public function single(array $c = [], array $f = []) {

        $tableName = $this->inputData['tableName'];
        $id = $this->inputData['id'];


        if ($tableName == 'department') {
            $c = ['dept_id' => $id];
        } elseif ($tableName == 'form_type') {
            $c = ['fid' => $id];
        } elseif ($tableName == 'languages') {
            $c = ['language_id' => $id];
        }

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->composeSelect($tableName, $f, array_keys($c));

        $data = $db->prepareFields($c);

        $exe_get = $db->query($data);

        $result = $db->get('fetch');

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    /**
     * Function to delete data from db
     * @param array $f fields
     * @param array $c condition
     * @return type
     */
    public function deleteAll() {

        $delete_flag = 1;

        $fid = @$this->inputData['fid'];

        $dept_id = @$this->inputData['dept_id'];

        if ($fid) {
            $this->updateDelete(compact('fid'), compact('delete_flag'), TABLE_ADD_TYPE);
        } elseif ($dept_id) {
            $this->updateDelete(compact('dept_id'), compact('delete_flag'), TABLE_DEPT);
        }
    }

    /**
     * To delete language
     */
    public function deleteLanguage() {

        $delete_flag = 1;

        $language_id = $this->inputData['language_id'];

        $this->updateDelete(compact('language_id'), compact('delete_flag'), TABLE_LANGUAGE);
    }

    public function deleteErrors() {

        $delete_flag = 1;

        $error_id = $this->inputData['error_id'];

        $this->updateDelete(compact('error_id'), compact('delete_flag'), TABLE_ERRORS);
    }

    public function deleteRequests() {

        $delete_flag = 1;

        $form_request_id = $this->inputData['request_id'];

        $this->updateDelete(compact('form_request_id'), compact('delete_flag'), TABLE_REQUESTS);
    }

    public function deleteForm() {

        $id = $this->inputData['form_id'];

        $delete_flag = 1;

        if ($this->updateOrder(compact('id'), compact('delete_flag'), TABLE_MAIN_FORM)) {
            $this->updateDelete(['main_form_id' => $id], compact('delete_flag'), TABLE_ATTRIBUTE_FORM);
        }
    }

    /*
     * To fetch sinlge Record from db
     */

    public function getSingleForEdit(array $c = [], array $f = []) {

        $id = $this->inputData['form_id'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->initateRawQuery('SELECT attribute_form.form_name,main_form.form_type,main_form.form_name as mainFormName , attribute_form.fileName,main_form.dept_name,main_form.state_name,attribute_form.attr_language,attribute_form.fields,main_form.webLink,main_form.published from attribute_form join main_form WHERE attribute_form.main_form_id = main_form.id AND attribute_form.id=:id and main_form.delete_flag = 0');

        $data = $db->prepareFields(['id' => $id]);

        $exe_get = $db->query($data);

        $result = $db->get('fetch');

        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    public function getSingleForAddNew(array $c = [], array $f = []) {

        $id = $this->inputData['form_id'];

        $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $db->initateRawQuery('SELECT * from main_form WHERE id=:id and delete_flag = 0');

        $data = $db->prepareFields(['id' => $id]);

        $exe_get = $db->query($data);

        $result = $db->get('fetch');


        return $this->ajaxResponse($result['rows'] ? $result['data'] : []);
    }

    /**
     * Logout
     * @return type
     */
    public function logout() {

        $_SESSION['authkey'] = 0;
        unset($_SESSION['user']);
        echo $_SESSION['user'];
        session_destroy();
        return $this->ajaxResponse('Loggedout Successfully', true, [], 200);
    }

}
