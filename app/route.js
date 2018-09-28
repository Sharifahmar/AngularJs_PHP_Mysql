
var app = angular.module('myApp', ['ngRoute',
    'ngToast',
    'ui.bootstrap',
    'ui.checkbox',
    'ui.tinymce',
    'angularjs-dropdown-multiselect',
    'ui.select2',
    'gm.dragDrop',
    'datatables',
    'naif.base64'
]);

app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
        $routeProvider
                .when('/', {
                    templateUrl: 'templates/login.tmp.php',
                    controller: 'loginCtrl',
                })
                .when('/home', {
                    templateUrl: 'templates/list_form.tmp.html',
                    controller: 'list_form',
                })
                .when('/add-form-type', {
                    templateUrl: 'templates/add-form-type.tmp.php',
                    controller: 'addFormType'
                })
                .when('/add-language', {
                    templateUrl: 'templates/add_language.tmp.php',
                    controller: 'addLanguage',
                })
                .when('/add-dept', {
                    templateUrl: 'templates/add-department.tmp.php',
                    controller: 'addDept',
                })
                .when('/add-form', {
                    templateUrl: 'templates/form-instruction.tmp.php',
                    controller: 'formInstruction',
                })
                .when('/edit_form', {
                    templateUrl: 'templates/edit_form.tmp.html',
                    controller: 'formInstruction',
                })
                .when('/list_instruction', {
                    templateUrl: 'templates/list_instructions.tmp.html',
                    controller: 'list_inst',
                })
                .when('/error_report', {
                    templateUrl: 'templates/error_reports.tmp.html',
                    controller: 'error_reports',
                })
                .when('/requests', {
                    templateUrl: 'templates/form_request.tmp.html',
                    controller: 'form_request',
                })
                .when('/add_user', {
                    templateUrl: 'templates/add_user.tmp.html',
                    controller: 'add_user',
                })
                .when('/add_admin', {
                    templateUrl: 'templates/add_admin.tmp.html',
                    controller: 'add_admin',
                })
                .when('/logout', {
                    templateUrl: '',
                    controller: 'loginCtrl',
                })

                .otherwise({redirectTo: '/'});


    }]);


app.run(run);

function run($rootScope, $filter, $http, ngToast, $location) {


    $rootScope.onHover = function (item) {
        return function (dragItem, mouseEvent) {
            if (item != dragItem)
                dragItem.order = item.order + ((mouseEvent.offsetY || -1) > 0 ? 0.5 : -0.5)
        }
    }

    $rootScope.save = function () {
        var _url = $location.search();
        var mid = _url.mid;
        var aid = _url.aid;

        $http({
            method: 'POST',
            url: 'config/requestHandler.php?action=saveReorederdItems&aid=' + aid,
            data: $rootScope.orderedItems,
        }).then(function successCallback(res) {
            ngToast.success(res.data);
            $location.path('/home');
        }, function errorCallback(error) {
        });
    }

    $rootScope.reorder = function reorder() {

        var _orderedItems = $filter('orderBy')($rootScope.orderedItems, 'order');
        for (var i = 0; i < _orderedItems.length; i++) {
            _orderedItems[i].number = _orderedItems[i].order = i + 1;
        }
    }

    $rootScope.reset = function reset(droppedItem) {
        droppedItem.order = droppedItem.number;
    }

    $rootScope.leftZone = {
        items: []
    };

    $rootScope.rightZone = {
        items: []
    };

    $rootScope.getDropHandler = function (category) {
        return function (dragOb) {
            if (category.items.indexOf(dragOb.item) < 0) {
                dragOb.category.items.splice(dragOb.category.items.indexOf(dragOb.item), 1);
                category.items.push(dragOb.item);
                return true;  // Returning truthy value since we're modifying the view model
            }
        }
    }

}

app.factory('Auth', function () {
    var user;

    return{
        setUser: function (aUser) {
            user = aUser;
        },
        isLoggedIn: function () {
            return(user) ? user : false;
        }
    }
})


app.run(function (logincheck, $location, $rootScope) {
    var routePermission = ['/'];
    $rootScope.$on('$routeChangeStart', function () {
        if (routePermission.indexOf($location.path()) == -1)
        {
            var checkLoggedIn = logincheck.isLoggedIn();
            checkLoggedIn.then(function (msg) {
                if (!msg.data.response.isLoggedIn)
                {
                    $location.path('/');
                }
            });
        }
    });


});


app.factory('logincheck', function ($http) {

    return{
        isLoggedIn: function () {
            var $checksession = $http.post('config/requestHandler.php?action=is_loggedin');
            return $checksession;
        }
    }

});

