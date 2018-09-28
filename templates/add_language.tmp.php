<div header></div>
    <div id="page-content-wrapper">
        <div class="container">
        <h3 class ="well"> Manage Languages </h3>
            <form name="frm_language">

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ isEditLang == true ? 'Edit Language' : 'Add Language' }}</label>
                        <input type="text" name="language" class="form-control" ng-model="language.language" required>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="btn-group">
                        <button id="saveLanguage" ng-disabled="frm_language.$invalid" class="btn btn-warning" type="submit" ng-click="add_language(language.language_id)">{{ isEditLang == true ? 'Update' : 'Save' }}</button>
                    </div>
                    <div class="btn-group">
                        <a id="cancelOrder" data-ng-click="language= ''; isEditLang = false; " class='btn btn-danger'>Cancel</a>
                    </div>
                </div>

            </form>

            <table datatable='ng' class="row-border hover">
                 <!--<dtable options="options" rows="data" class="material">-->
                <thead>
                <th>Sr No</th>
                <th>Language</th>
                <th>Action</th>
                </thead>

                <tbody>
                    <tr ng-repeat="language in languageList">
                        <td>{{$index+1}}</td>
                        <td>{{language.language}}</td>
                        <td><a class="btn btn-primary" href="javascript:" ng-click="getSingleLang(language.language_id)" >Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary" href="javascript:" ng-confirm-click="Are you sure to delete this record ?" confirmed-click ="deleteRec(language.language_id)">Delete</a></td>
                    </tr>
                </tbody>
                <!--</dtable>-->
            </table>
        </div>
    </div>