

app.controller("headerController", function ($scope, $http, $location) {


    $scope.logout = function () {
        $http({
            method: "POST",
            url: "config/requestHandler.php?action=logout",
            data: {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            $location.path('/');
        }).error(function (res) {
        });
    }

    $scope.isAdmin = function () {
        $http({
            method: "POST",
            url: "config/requestHandler.php?action=is_admin",
            data: {},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            $scope.admin = res.response.isAdmin;
        }).error(function (res) {
        });
    }
    $scope.isAdmin();

});


app.controller('loginCtrl', function ($scope, $location, $rootScope, $http) {

    $scope.login = function () {

        $http({
            method: "POST",
            url: "config/requestHandler.php?action=login",
            data: {aname: $scope.users.a_name, apassword: $scope.users.a_password},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            if (res.response.loggedIn) {
                $location.path('/home');
                $scope.admin = true;
            } else {
                $scope.message = res.response.msg;
                $location.path('/');
            }
        }).error(function (res) {
        });
    }

});

/**
 * To add,edit and delete languages
 */
app.controller('addLanguage', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {

        $scope.flag_language = 'active';
        $scope.isEditLang = false;

        $scope.add_language = function (id) {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=saveLanguageName',
                data: {language: $scope.language.language, id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.language.language = null;
                $scope.listAllLanguages();
                ngToast.success(res.data);
            }, function errorCallback(error) {
            });
        }

        $scope.getSingleLang = function (id) {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=single',
                data: {tableName: 'languages', id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.isEditLang = true;
                $scope.language = res.data.response;

            }, function errorCallback(error) {
            });
        }


        $scope.deleteRec = function (id) {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=deleteLanguage',
                data: {language_id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                if (res) {
                    $scope.listAllLanguages();
                    ngToast.success(res.data);
                }
            }, function errorCallback(error) {
            });
        }

        $scope.listAllLanguages = function () {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=listAllLanguages',
                data: {},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.languageList = res.data.response;
            }, function errorCallback(error) {
            });
        }

        $scope.listAllLanguages();
    }]);

app.controller('addFormType', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {
        $scope.flag = 'active';
        $scope.isEditType = false;
        $scope.add_type = function (id) {
            if (id)
            {
                var url = 'config/requestHandler.php?action=updateFormType';
            } else
            {
                var url = 'config/requestHandler.php?action=saveFormType';
            }
            $http({
                method: 'POST',
                url: url,
                data: {form_type: $scope.formType.form_type, id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.formType = null;
                ngToast.success(res.data);
                $scope.listAllFormType();

            }, function errorCallback(error) {

            });

        }
        $scope.getSingleType = function (id) {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=single',
                data: {tableName: 'form_type', id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.formType = res.data.response;
                $scope.isEditType = true;
            }, function errorCallback(error) {
            });
        }

        $scope.deleteRec = function (id) {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=deleteAll',
                data: {fid: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {

                if (res) {
                    ngToast.success(res.data);
                    $scope.listAllFormType();
                }
            }, function errorCallback(error) {
            });
        }


        $scope.listAllFormType = function () {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=listAllFormType',
                data: {},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {

                $scope.formTypeList = res.data.response;

            }, function errorCallback(error) {
            });
        }


     

        $scope.listAllFormType();


    }]);


/**
 * To add , edit and delete department
 */

app.controller('addDept', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {


        $scope.flag_dept = 'active';
        $scope.isEditDept = false;

        $scope.listAllDept = function () {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=listAllDept',
                data: {},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.deptList = res.data.response;
            }, function errorCallback(error) {
            });
        }

        $scope.deleteRec = function (id) {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=deleteAll',
                data: {dept_id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                if (res.statusText == 'OK')
                {
                    ngToast.success(res.data);
                    $scope.listAllDept();
                }
            }, function errorCallback(error) {
            });
        }

        $scope.listAllDept();
        $scope.add_dept = function (id) {

            if (id) {
                var url = 'config/requestHandler.php?action=updateDeptName';
            } else {
                var url = 'config/requestHandler.php?action=saveDept';
            }

            $http({
                method: 'POST',
                url: url,
                data: {dept_name: $scope.dept.dept_name, id: id, table_name: 'department'},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.dept = null;
                $scope.listAllDept();
                if (res.statusText == 'OK')
                {
                    ngToast.success(res.data);
                }


            }, function errorCallback(error) {
            });
        }

        $scope.getSingle = function (id) {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=single',
                data: {tableName: 'department', id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.isEditDept = true;
                $scope.dept = res.data.response;

            }, function errorCallback(error) {
            });
        }


    }]);



/**
 * To add,edit form and to add new language
 */
app.controller('formInstruction',
        ['$scope', '$location', '$rootScope', '$http', 'ngToast', '$window',
            function ($scope, $location, $rootScope, $http, ngToast, $window) {

                var _url = $location.search();
                $scope.flag_instruction = 'active';
                $scope.isEnabled = false;
                $scope.isDisableState = true;
                $scope.isDisableDept = true;
                $scope.presentLanguage = _url.language;
                $scope.languages = [];
                $scope.fileName = null;
                $scope.orderFlag;
                $scope.responseData = '';
                $scope.edit_instruct = {};
                $scope.form_instruct = {};
                $scope.count;

                /**
                 * setting the categories for form attributes
                 */

                $scope.categories = ['Eligibility','Documents','Process','Instructions'];
                $scope.categoryColor = {'Eligibility':"#99FFFF",'Documents':"#FFFFCC",'Process':"#FAEBD7",'Instructions':"#8FBC8F"};


                /**
                 * method to get the file name of file to be upload
                 * @param {type} files
                 * @returns {undefined}
                 */

                /**
                 * method to get url to upload file
                 * @returns {String}
                 */

                $scope.fileHref = function () {
                    return 'http://localhost/form_easy/uploads/filled_forms/' + $scope.fileName;
                }
        /**
        *
        *Method to call sub categories
        */

      $scope.listCategoryById = function (id) {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=listCategoryById',
                data: {ft_id:id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                if (res.statusText == 'OK')
                {
                    $scope.states = res.data.response;

                     console.log("success")
                }
            }, function errorCallback(error) {
                console.log("error")
            });
        }



                /**
                 * List of All languages
                 * @returns {undefined}
                 */
                $scope.listAllLanguage = function ()
                {

                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=listAllLanguages',
                        data: {id: _url.id},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {
                        if (res.data.success)
                        {
                            $scope.languages = res.data.response;
                        }
                    }, function errorCallback(error) {
                    });
                }



                if ($location.path() == '/add-form') {
                    $scope.listAllLanguage();
                }



                /**
                 * To get language depends on id
                 * @returns {undefined}
                 */
                $scope.getSingleLanguage = function ()
                {
                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=listAllLanguages',
                        data: {},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {

                        if (res.data.success)
                        {
                            $scope.languages = res.data.response;
                        }
                    }, function errorCallback(error) {
                    });
                }

                if ($location.path() == '/edit_form') {
                    $scope.getSingleLanguage();
                }

                /**
                 * list of all form types
                 * @returns {undefined}
                 */
                $scope.listAllFormtype = function ()
                {
                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=listAllFormtype',
                        data: {},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {
                        if (res.data.success)
                        {
                            $scope.formTypes = res.data.response;
                        }
                    }, function errorCallback(error) {
                    });
                }

                $scope.listAllFormtype();


                /**
                 * To get list of all states from json file
                 */
              /*  $http.get('cities.json').success(function (response) {
                    $scope.states = response;
                });*/


                /**
                 * List of all departments
                 * @returns {undefined}
                 */
                $scope.listAllDept = function ()
                {
                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=listAllDept',
                        data: {},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {
                        if (res.data.success)
                        {
                            $scope.depts = res.data.response;
                        }
                    }, function errorCallback(error) {
                    });
                }
                $scope.listAllDept();


                /**
                 * List of all admins
                 * @returns {undefined}
                 */
                $scope.listAllAdmins = function ()
                {
                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=listAllAdmins',
                        data: {},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {
                        if (res.data.success)
                        {
                            $scope.admins = res.data.response;
                        }
                    }, function errorCallback(error) {
                    });
                }
                $scope.listAllAdmins();


                /**
                 * to disable form type while adding new language
                 */
                if ($location.path() == '/add-form' && _url.id)
                {
                    $scope.isDisableFormType = true;
                }


                /**
                 * To validate form name
                 * @param {type} form_instruct
                 * @returns {undefined}
                 */
                $scope.validateFormName = function (form_instruct) {
                    $scope.isEmptyFormName = $scope.form_instruct.frm_name.$error.required;
                };


                /**
                 * To disable state and dept if form type not selected
                 * Depends on form type disable or enable state and dept
                 */
                if ($location.path() == '/add-form')
                {
                    $scope.selectFormType = function (id) {
                        $scope.selectedVal = $scope.form_instruct.form_type.category_type;
                        $scope.isDisableState = ($scope.selectedVal == 'National' || !$scope.selectedVal) ? true : false;
                        $scope.isDisableDept = $scope.selectedVal == 'National' ? false : true;
                        $scope.selectedVal == 'National' ? $scope.form_instruct.state = {"id": "0", "name": "Not Applicable", "state": "Not Applicable"} : '';
                        $scope.listCategoryById(id);

                    }

                    $scope.selectState = function () {
                        $scope.selectedVal = $scope.form_instruct.state;
                        $scope.isDisableDept = $scope.selectedVal ? false : true;
                    }
                }


                /**
                 * Adding fields for new form attribute
                 */

                $scope.choices = [
                    {'number': 1, 'order': 1, 'value': '', 'fieldinstruction': '', 'field_id': 1, 'category': ''}
                ];

                $scope.addNewChoice = function () {

                    $scope.count = $scope.choices.length;

                    var i = $scope.count + 1;


                    $scope.choices.push({'number': i, 'order': i, 'value': '', 'fieldinstruction': '', 'field_id': i, 'category':''});


                };



                /**
                 * To remove form atrributes while editing
                 * @param {type} index
                 * @returns {undefined}
                 */
                $scope.removeItem = function (index) {
                    $scope.choices.splice(index, 1);
                }


                /**
                 * Adding ,Editing form
                 * @returns {undefined}
                 */
                $scope.add_form_field = function ()
                {

                    /**
                     * To covert html tags into plain text
                     * @param {type} text
                     * @returns {String}
                     */
                    function htmlToPlaintext(text) {
                        return text ? String(text).replace(/<[^>]+>/gm, '') : '';
                    }

                    app.filter('htmlToPlaintext', function () {
                        return function (text) {
                           return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
                        };
                    }
                    )
;

                    $scope.plain_text = [];
                    $scope.formAttrs = [];
                    $scope.language = [];


                    angular.forEach($scope.choices, function (value, key) {
                        $scope.plain_text = htmlToPlaintext(value.fieldinstruction);
                        $scope.formAttrs[key] = {'number': value.number, 'order': value.order, 'field_id': value.field_id, 'value': value.value, 'fieldinstruction': $scope.plain_text, 'category': value.category};
                        console.log($scope.formAttrs[key]);
                    });

                    $scope.fieldInstjson = angular.toJson($scope.plain_text);
                    $scope.formAttrsJson = angular.toJson($scope.formAttrs);


                    if ($location.path() == '/edit_form' && _url.id)
                    {
                        $scope.form_name = $scope.edit_instruct.form_name;
                        $scope.main_form_id = _url.mid;
                        $scope.published = $scope.edit_instruct.publish;
                        $scope.addLink = $scope.edit_instruct.add_link;
                        $scope.fileName = $scope.edit_instruct.fileToUpload;
                        $scope.main_form_name = $scope.edit_instruct.main_form_name;
                        var url = 'config/requestHandler.php?action=editFormFields';
                    } else
                    {
                        $scope.formType = angular.toJson($scope.form_instruct.form_type);
                        $scope.admins = angular.toJson($scope.form_instruct.name);
                        $scope.stateName = angular.toJson($scope.form_instruct.state);
                        $scope.dept = angular.toJson($scope.form_instruct.dept_name);
                        $scope.language = angular.toJson($scope.form_instruct.language);
                        $scope.form_name = $scope.form_instruct.form_name;
                      //  $scope.language_id = $scope.form_instruct.language.language_id
                        $scope.published = $scope.form_instruct.publish;
                        $scope.addLink = $scope.form_instruct.add_link;
                        $scope.fileName = $scope.form_instruct.fileToUpload;
                        var url = 'config/requestHandler.php?action=saveFormFields';
                    }

                    $http({
                        method: 'POST',
                        url: url,
                        data: {fid: _url.id, published: $scope.published, main_form_name: $scope.main_form_name, fileName: $scope.fileName, webLink: $scope.addLink, admin:$scope.admins,formType: $scope.formType, mid: $scope.main_form_id, stateName: $scope.stateName, dept: $scope.dept, language_id: null, language: $scope.language, form_name: $scope.form_name, fieldNames: $scope.formAttrsJson}

                    }).then(function successCallback(res) {
                        $scope.form_instruct = null;
                        if (res.statusText == 'OK')
                        {
                            $location.path('/home');
                            ngToast.success(res.data);
                        }

                    }, function errorCallback(error) {
                    });
                }

                /**
                 * To get single record while editing and adding new new language
                 * @returns {undefined}
                 */
                $scope.getSingleForEdit = function () {
                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=getSingleForEdit',
                        data: {form_id: _url.id},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {

                        $scope.responseData = res.data.response;

                        $scope.edit_instruct.dept_name = angular.fromJson($scope.responseData.dept_name);
                        $scope.edit_instruct.form_type = angular.fromJson($scope.responseData.form_type);
                        $scope.edit_instruct.state = angular.fromJson($scope.responseData.state_name);
                        $scope.edit_instruct.language = angular.fromJson($scope.responseData.attr_language);
                        $scope.edit_instruct.form_name = $scope.responseData.form_name;
                        $scope.edit_instruct.main_form_name = $scope.responseData.mainFormName;
                        $scope.fileName = $scope.responseData.fileName;
                        $scope.fields = angular.fromJson($scope.responseData.fields);
                        $scope.edit_instruct.publish = $scope.responseData.published;
                        $scope.edit_instruct.add_link = $scope.responseData.webLink ? $scope.responseData.webLink : '';
                        $scope.fileName = $scope.responseData.fileName;

                        $scope.count = $scope.fields.length;

                        for (var i = 0; i < $scope.fields.length; i++)
                        {
                            $scope.choices[i] = {'number': $scope.fields[i].number, 'order': $scope.fields[i].order, 'value': $scope.fields[i].value,'category': $scope.fields[i].category, 'fieldinstruction': $scope.fields[i].fieldinstruction, 'field_id': $scope.fields[i].field_id};
                        }

                    }, function errorCallback(error) {
                    });
                }

                if ($location.path() == '/edit_form' && _url.id)
                {
                    $scope.getSingleForEdit();
                }

                $scope.getSingleForAddNew = function ()
                {
                    $http({
                        method: 'POST',
                        url: 'config/requestHandler.php?action=getSingleForAddNew',
                        data: {form_id: _url.id},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function successCallback(res) {

                        $scope.responseData = res.data.response;

                        $scope.form_instruct.state = angular.fromJson($scope.responseData.state_name);
                        $scope.form_instruct.dept_name = angular.fromJson($scope.responseData.dept_name);
                        $scope.form_instruct.form_type = angular.fromJson($scope.responseData.form_type);
                        $scope.form_instruct.form_name = $scope.responseData.form_name;
                        $scope.form_instruct.add_link = $scope.responseData.webLink;

                    }, function errorCallback(error) {
                    });
                }

                if ($location.path() == '/add-form' && _url.id)
                {
                    $scope.getSingleForAddNew();
                }

            }]);



/**
 * To list main form attributes on home page
 */
app.controller('list_form', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {

        $rootScope.orderedItems = [];
        $scope.fields = [];
        $scope.fieldAttrs = [];
        $scope.options = [];
        $scope.disabled = 'disabled';
        var name = {};

        /**
         * To disable or enable EDIT and REORDER buttons
         * @param {type} name
         * @param {type} id
         * @returns {undefined}
         */
        $scope.selectedLang = function (name, id) {
            if (name == null || name == 'undefined')
            {
                $scope.disabled = 'btn btn-sm btn-warning disabled';
            } else {
                $scope.disabled = 'btn btn-sm btn-warning enabled';
            }
            $scope.languageToAdd = name;
            var myEl1 = angular.element(document.querySelector('#disableEditBtn-' + id));
            myEl1.attr('class', $scope.disabled);
            var myEl2 = angular.element(document.querySelector('#disableOrderBtn-' + id));
            myEl2.attr('class', $scope.disabled);
        }


        /**
         * To select all language available for the form
         * @returns {undefined}
         */

        $scope.selectLanguage = function () {

            $http({
                method: 'GET',
                url: 'config/requestHandler.php?action=listAvailableLanguageForForm',
                data: {},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {

                $scope.options = res.data.response;

            }, function errorCallback(error) {
            });
        }
        $scope.selectLanguage();


        $scope.deleteForm = function (id) {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=deleteForm',
                data: {form_id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                if (res.statusText == 'OK')
                {
                    ngToast.success(res.data);
                    $scope.listAllAttributes();
                }
            }, function errorCallback(error) {
            });

        }


        /**
         * To show main form attributes on home page
         * @returns {undefined}
         */
        $scope.listAllAttributes = function () {

            $http({
                method: 'GET',
                url: 'config/requestHandler.php?action=listMainFormFields',
                data: {},
            }).then(function successCallback(res) {


                $scope.formAttrs = res.data.response;
                angular.forEach($scope.formAttrs, function (value, key) {

                    $scope.list_dept_name = angular.fromJson(value.dept_name);
                    $scope.list_form_type = angular.fromJson(value.form_type);
                    if (value.state_name == 'undefined')
                    {
                        $scope.list_state_name = value.state_name;
                    } else {
                        $scope.list_state_name = angular.fromJson(value.state_name);
                    }

                    $scope.formAttrs[key] = {id: value.id,
                        form_name: value.form_name,
                        form_type: $scope.list_form_type.form_type,
                        state_name: $scope.list_state_name.state,
                        dept_name: $scope.list_dept_name.dept_name,
                        published: value.published
                    };
                });
            }, function errorCallback(error) {
            });
        }
        $scope.listAllAttributes();
    }]);


/**
 * To reorder form fields
 */
app.controller('list_inst', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {


        $rootScope.orderedItems = [];
        $scope.fields = [];
        $scope.fieldAttrs = [];
        var _url = $location.search();

        /**
         * To fetch attributes according to id and reorder it
         * @returns {undefined}
         */
        $scope.listInst = function () {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=listAttrs',
                data: {aid: _url.aid},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {

                $scope.formfields = res.data.response;
                $scope.fields = angular.fromJson($scope.formfields[0].fields);
                $rootScope.orderedItems = $scope.fields;

            }, function errorCallback(error) {
            });
        }

        if ($location.path() == '/list_instruction') {
            $scope.listInst();
        }

        /**
         * To save reordered attributes
         * @returns {undefined}
         */
        $scope.addOrder = function ()
        {
            $rootScope.save();
        }

    }]);



/**
 *
 */
app.controller('error_reports', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {


        $scope.flag_errors = 'active';

        $scope.listErrors = function () {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=listErrors',
                data: {},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.Errors = res.data.response;
            }, function errorCallback(error) {
            });
        }

        $scope.listErrors();

        $scope.deleteErrors = function (id)
        {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=deleteErrors',
                data: {error_id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                if (res) {
                    $scope.listErrors();
                    ngToast.success(res.data);
                }
            }, function errorCallback(error) {
            });
        }

    }]);




app.controller('form_request', ['$scope', '$location', '$rootScope', '$http', 'ngToast', function ($scope, $location, $rootScope, $http, ngToast) {

        $scope.flag_requests = 'active';

        $scope.formRequests = function () {

            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=formRequests',
                data: {},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                $scope.Requests = res.data.response;
            }, function errorCallback(error) {
            });
        }

        $scope.formRequests();

        $scope.deleteRequests = function (id)
        {
            $http({
                method: 'POST',
                url: 'config/requestHandler.php?action=deleteRequests',
                data: {request_id: id},
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(res) {
                if (res) {
                    $scope.formRequests();
                    ngToast.success(res.data);
                }
            }, function errorCallback(error) {
            });
        }


    }]);

app.controller('add_user', ['$scope', '$http', 'ngToast', function ($scope, $http, ngToast) {

    $scope.addUser = function () {
        $http({
            method: "POST",
            url: "config/requestHandler.php?action=add_user",
            data: {aname: $scope.adduser.username, apassword: $scope.adduser.password},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            ngToast.success(res);
            $scope.listAllUsers();
        }).error(function (res) {
            ngToast.danger("error saving data");
        });
    }

    $scope.listAllUsers = function () {
        $http({
            method: "GET",
            url: "config/requestHandler.php?action=listAllUsers",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            $scope.userlist = res.response;
        }).error(function (res) {
        });
    }
    $scope.listAllUsers();

    $scope.deleteRec = function(id) {
        console.log(id);
        $http({
            method: "POST",
            url: "config/requestHandler.php?action=deleteUser",
            data: {id: id},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            ngToast.success(res);
            $scope.listAllUsers();
        }).error(function (res) {
        });
    }

}]);

app.controller('add_admin', ['$scope', '$http', 'ngToast', function ($scope, $http, ngToast) {

    $scope.addAdmin = function () {
        $http({
            method: "POST",
            url: "config/requestHandler.php?action=add_admin",
            data: {name: $scope.addadmin.aname, uname: $scope.addadmin.username, apassword: $scope.addadmin.password},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            ngToast.success("Admin added successfuly!!");
            $scope.addadmin={}
            $scope.listAllAdmins();
        }).error(function (res) {
            ngToast.danger("error saving data");
        });
    }

    $scope.listAllAdmins = function () {
        $http({
            method: "GET",
            url: "config/requestHandler.php?action=listAllAdmins",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            $scope.userlist = res.response;
        }).error(function (res) {
        });
    }
    $scope.listAllAdmins();

    $scope.deleteRec = function(id) {
        console.log(id);
        $http({
            method: "POST",
            url: "config/requestHandler.php?action=deleteUser",
            data: {id: id},
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (res) {
            ngToast.success(res);
            $scope.listAllUsers();
        }).error(function (res) {
        });
    }

}]);
