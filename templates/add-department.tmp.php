<div header></div>
    <div id="page-content-wrapper">
        <div class="container">
            <h3 class ="well"> Manage Departments </h3>

             <form name="frm_dept" class="frm_dept" >   

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ isEditDept == true ? 'Edit Department' : 'Add Department' }} </label>
                        <input type="text" name="deptName" class="form-control" ng-model="dept.dept_name" required>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="btn-group"> 
                        <button id="saveCourse" ng-disabled="frm_dept.$invalid" class="btn btn-warning" type="submit" ng-click="add_dept(dept.dept_id)">{{ isEditDept == true ? 'Update' : 'Save' }}</button>                                
                    </div>
                    <div class="btn-group">
                        <a id="cancelOrder" data-ng-click="dept= ''; isEditDept = false; " class='btn btn-danger'>Cancel</a>                                
                    </div>
                </div> 

            </form>    



            <table datatable='ng' class="row-border hover">
                <thead>
                <th>Sr No</th>
                <th>Dept Name</th>     
                <th>Action</th>
                </thead>

                <tbody>
                    <tr ng-repeat="dept in deptList">    
                       <td>{{$index+1}}</td>
                        <td>{{ dept.dept_name}}</td>
                        <td><a class="btn btn-primary" href="javascript:" ng-click="getSingle(dept.dept_id)" >Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary"href="" ng-confirm-click="Are you sure to delete this record ?" confirmed-click ="deleteRec(dept.dept_id)">Delete</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


