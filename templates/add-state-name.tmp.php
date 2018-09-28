<div header></div>
<div id="page-content-wrapper">
    <div class="container" style="width: 1320px; float: right;">
        <form name="select_state">

            <div class="col-md-6">
                <div class="form-group">
                    <label> Add State</label>
                     <input type="text" class="form-control" ng-model="state.state_name" >
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="btn-group">
                    <button id="saveCourse" class="btn btn-warning" type="submit" ng-click="add_state(state.sid)">Save</button>
                </div>
                <div class="btn-group">
                    <button onclick="window.location = 'home.php'" id="courseCancel" type="button" class="btn btn-danger">Cancel</button>
                </div>
            </div>

        </form>
    </div>

    <div class="container">
            <table class="table table-responsive">
                <thead>
                <th>Sr No</th>
                <th>State Name</th>
                <th>Action</th>
                </thead>

                <tbody>
                    <tr ng-repeat="state in stateList">
                        <td>{{ state.sid}}</td>
                        <td>{{ state.state_name}}</td>
                        <td><a href="javascript:" ng-click="getSingleState(state.sid)" >Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="" ng-confirm-click="Are you sure to delete this record ?" confirmed-click ="deleteRec(state.sid)">Delete</a></td>
                    </tr>
                </tbody>
            </table>
    </div>
</div>