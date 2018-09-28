<style>

    .newfieldSet{height:200px; margin-bottom: 20px;}

    .checkPreset{font-size: small}
    .addNewBtn{margin-top: 22px; margin-right: 30px; float: right!important}
    /* .container{ width: 1320px; float: right; } */
    .form_fileds{border:1px solid #D3D3D3; border-radius: 5px; width: 1255px; padding-left: 10px; overflow-x: hidden; overflow-y: scroll; height: 600px; position: absolute; margin-top: 80px; margin-left: 12px;}
    .inner_div{ width: 1220px; height: 300px; position: absolute; border-radius: 5px; margin-left: 5px; }
    .btns{margin-top: 662px; vertical-align: inherit; position: relative;}
    .form_instruct{width: 1275px;height:300px;}
    .inner-form-div{/*border: 1px solid #D3D3D3;*/ height: 177px; border-radius: 5px;}
    .addNewBtn > #btn{z-index: 99999;margin-top: 50px;}
    /* css for editor */
    .mce-statusbar{display: none!important;}
    .mce-flow-layout{display: none!important;}
    .mce-toolbar-grp{display: none!important;}
    .mce-toolbar{border-width : 0px 0px 0px!important;}
    .publishFields{margin: 73px 0px 0px 814px;width: 211px; z-index: 99999;}


</style>

<div header></div>
<div id="page-content-wrapper">
    <div class="container">

        <h3 class ="well"> Add New Form </h3>
        <br>
        <form name="form_instruct" class="form_instruct">
            <div class="inner-form-div" >

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Select Form type <strong style="color:red">*</strong></label>
                        <select ng-model="form_instruct.form_type" class="form-control" ng-disabled="isDisableFormType" ng-change="selectFormType(form_instruct.form_type.fid)" ng-options="ftype.category_type for ftype in formTypes track by ftype.fid">
                            <option value="">-- Select an option--</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Select State Name</label>
                        <select ng-disabled="isDisableState" ng-model="form_instruct.state" ng-change="selectState()" class="form-control" ng-options="state.sb_category_name for state in states ">
                            <option value="">-- Select an option--</option>
                            <option label="Not Applicable" value="32">Not Applicable</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Select Department<strong style="color:red">*</strong></label>
                        <select ng-disabled="isDisableDept" ng-model="form_instruct.dept_name" class="form-control" ng-options="dept.dept_name for dept in depts track by dept.dept_id">
                            <option value="">-- Select an option--</option>
                        </select>
                    </div>
                </div>

                   <div class="col-md-6">
                    <div class="form-group">
                        <label> Select Admin<strong style="color:red">*</strong></label>
                        <select  ng-model="form_instruct.name" class="form-control" ng-options="admin.name for admin in admins track by admin.id">
                            <option value="">-- Select an option--</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Web link</label>
                        <input type="text" class="form-control" ng-model="form_instruct.add_link" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Upload File</label>
                        <input style="height: auto;" type="file" class="form-control" ng-model="form_instruct.fileToUpload" base-sixty-four-input>
                    </div>
                </div>


            </div>

            <div class="addNewBtn">
                <div class="btn-group" id="btn">
                    <button id="saveCourse" class="fa fa-plus btn btn-primary" type="submit" ng-click="addNewChoice()"> Add More Fields</button>
                </div>
            </div>

            <div class="col-md-6 publishFields">
                <div class="form-group">                    
                    <checkbox large class="btn-info"
                              ng-model="form_instruct.publish"
                              name="custom-name"
                              ng-true-value="Yes"
                              ng-false-value="No"
                              ng-change="onChange()" >
                    </checkbox>
                    <span class="checkPreset"></span>
                    <label>Publish Form Here </label>
                </div>
            </div>

            <div class="form_fileds">

                <h3 style="background-color: #fff; color: #000; text-align: center; padding-top: 20px;padding-bottom: 20px;">FORM ATTRIBUTES</h3>

                <span style="color:red; margin:-50px 0px 0px 830px;" ng-show="isEmptyFormName">Form name is required.</span>

                <div class="inner_div" style="">

                    <div style="height:78px;">

                    <!--     <div class="col-md-6">
                          <div class="form-group">
                              <label>Select Language <strong style="color:red">*</strong></label>
                              <select ng-model="form_instruct.language" id="language" class="form-control" ng-change="validateFormName(form_instruct)" ng-options=" language.language for language in languages">
                                  <option value="">-- Select an option--</option>
                              </select>
                          </div>
                      </div>
 -->

                        <div class="col-md-10">
                            <div class="form-group">
                                <label> Form Name</label>
                                <input type="text" name="frm_name" class="form-control" ng-blur="validateFormName(form_instruct)" ng-model="form_instruct.form_name" required>
                            </div>
                        </div>

                    </div>


                    <fieldset  data-ng-repeat="(key,choice) in choices" ng-style="{'background-color': categoryColor[choices[key].category]}">
                        <a style="float:right; cursor: pointer" class="glyphicon glyphicon-remove" ng-click="removeItem(key)" ></a>
                        <div  class="newfieldSet">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label> Field Name<strong style="color:red">*</strong></label>
                                    <input type="hidden" id="choices[key].field_id">
                                    <input type="hidden" id="choices[key].number">
                                    <input type="hidden" id="choices[key].order">
                                    <input type="text" class="form-control" ng-model="choices[key].value" required >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category <strong style="color:red">*</strong></label>
                                    <select ng-model="choices[key].category" id="category" class="form-control" ng-change="validateFormName(form_instruct)" ng-options=" category for category in categories"  required>
                                        <option value="">-- Select an option--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Field Data<strong style="color:red">*</strong></label>
                                    <input type="hidden" id="choices[key]">
                                    <textarea class="form-control"  ng-model="choices[key].fieldinstruction" required rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                </div>

            </div>


            <div class=" btns col-md-12 col-sm-12 col-lg-12" style="padding-bottom: 20px;">
                <div class="btn-group">
                    <button id="saveCourse" class="btn btn-warning" type="submit" ng-click="add_form_field()">Save</button>
                </div>
                <div class="btn-group">
                    <a id="cancelOrder" href="#/home" class='btn btn-danger'>Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

