<div header></div>
    <div id="page-content-wrapper">
        <div class="container">
        <h3 class ="well">Manage Form Type</h3>
            <form name="form_type">   
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ isEditType == true ? 'Edit the form type' : 'Add the form type' }}</label>
                        <input type="text" class="form-control"  ng-model="formType.form_type" required >
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="btn-group"> 
                        <!--<button id="saveCourse" class="btn btn-warning" type="submit" ng-click="add_type(formType.fid)">Save</button>-->                    
                        <button id="saveCourse" ng-disabled="form_type.$invalid" class="btn btn-warning" type="submit" ng-click="add_type(formType.fid)"> {{ isEditType == true ? 'Update' : 'Save' }}</button>
                    </div>
                    <div class="btn-group">
                        <button id="courseCancel" type="button" data-ng-click="formType = ''; isEditType = false; " class="btn btn-danger">Cancel</button>                                
                    </div>
                </div> 
                
            </form>



                <table datatable='ng' class="row-border hover">
                    <thead>
                    <th>Sr No</th> 
                    <th>Form Type</th>     
                    <th>Action</th>
                    </thead>

                    <tbody>
                        <tr ng-repeat="formType in formTypeList">    
                            <td>{{$index+1}}</td>                   
                            <td>{{ formType.form_type}}</td>
                            <td><a href="javascript:"  class="btn btn-primary " ng-click="getSingleType(formType.fid)" >Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="javascript:" class="btn btn-primary" ng-confirm-click="Are you sure to delete this record ?" confirmed-click ="deleteRec(formType.fid)">Delete</a></td>
                        </tr>
                    </tbody>
                </table>

        </div>
    </div>