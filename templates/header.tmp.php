<style>
    .form-blog .form-control{
        border-radius: 0px !important;
        border: 1px solid transparent !important;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .form-blog button{
        border-radius: 0px;
    }

    .form-blog #cover_preview{
        max-height: 360px;
    }
   /* .head-bar {
        background-color: #03a9f4 !important;
    }
    .head-bar a{
        color: white !important;
    }


    .head-bar a.active{
        background: white;
        color: #03a9f4 !important;
    }

    .navbar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand{
        margin-left: 0px;
    } */

    body{
        background-color: #fff !important
        height:100%;
    }

    .course-more{
        display: none;
    }
    .form-control{
        /*border-radius: 0px !important;*/
        border: 1px solid #9E9E9E !important;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .form-control:focus{
        border-color: #03a9f4 !important;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px #81D4FA;

    }
    .width-50p{
        width: 50%;
    }
    .course-spec-holder .special{
        background-color: #03a9f4;
        padding: 6px;
        color: white;
    }

    .form-group{
        /*background-color: #03a9f4;*/
        padding: 2px;
    }
    .form-group label{
        /*color: white;*/
        padding-left: 4px;
    }
    .form-course input:focus:invalid, .form-course textarea:focus:invalid { /* when a field is considered invalid by the browser */
        /*background: #fff url(images/invalid.png) no-repeat 98% center;*/
        /*box-shadow: 0 0 5px #d45252;*/
        /*border-color: #b03535*/
    }

    .form-course input:required:valid, .form-course textarea:required:valid { /* when a field is considered valid by the browser */
        /*background: #fff url(images/valid.png) no-repeat 98% center;*/
        /*box-shadow: 0 0 5px #5cd053;*/
        /*border-color: #28921f;*/
    }

    .form-footer{
        position: fixed;
        bottom: 0px;
        background-color: #ffffff;
        padding: 8px;
        left: 0px;
        text-align: right;
        border: 2px solid #03a9f4;
        /*box-shadow: #03a9f4 2px 4px inset;*/
    }

    .bootstrap-dialog.type-danger .modal-header {
        background-color: #ff5722;
    }

    .bootstrap-dialog .btn-danger {
        color: #fff;
        background-color: #ff5722;
        border-color: #d43f3a;
    }
    .bootstrap-dialog.type-warning .modal-header{
        background: #03a9f4;
    }
    .bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-danger, .bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-danger{
        background: #FF5722;
    }

    .bootstrap-dialog h3{
        font-size: 18px;

    }

    .container h3{
        font-size:18px;
        margin-left:15px;
        color: #fff;
        background-color: #0648A6;
    }

    .wkdy{
        background: #9c27b0;
    }
    .wked{
        background: #ff5722;
    }

    .crsh{
        background: #0074d9;
    }

    table button{
        color: white;
    }
    .border-m1{
        border: 1px solid #03a9f4;
    }
    .border-m1:hover, .border-m1:active:focus{
        border: 1px solid white;
        color: white;
        background-color: #03a9f4;
    }
    .border-m1:focus{

    }
    .border-m2{
        border: 1px solid #03a9f4;
    }
    .border-m3{
        border: 1px solid #03a9f4;
    }

    /*ul.nav li.active a{
        color: #03a9f4 !important;
        background-color: white;
    }
    .nav>li>a:focus, .nav>li>a:hover{
        color: #03a9f4 !important;
        background-color: white !important;
    }

    .navbar-toggle .icon-bar {
        display: block;
        width: 22px;
        height: 2px;
        border: 1px solid white;
        border-radius: 1px;
    } */

    thead>tr{
        background: white;
        color: #053D87;
    }

    .hr{
        border-top: 1px solid #9E9E9E !important;
        margin: 16px !important;
        border: #ffffff thin ;

    }

    .add-more-holder button{
        padding: 8px;
    }

    .last-column .form-group{
        width: 95%;
    }

    .last-column div.fl-rt{
        font-size: 22px;
        cursor: pointer;
        color: #F44336;
    }

    .row{
        margin-left:0px;
        margin-right:0px;
    }

    #wrapper {
        padding-left: 70px;
        transition: all .4s ease 0s;
        height: 100%    
    }

    #sidebar-wrapper {
        margin-left: -150px;
        left: 0px;
        top: 0px;
        width: 200px;
        background: #0648A6;
        position: fixed;
        height: 100%;
        z-index: 10000;
        transition: all .4s ease 0s;
    }

    .sidebar-nav {
        display: block;
        float: left;
        width: 200px;
        list-style: none;
        margin: 0;
        padding: 0;    

    }

    #page-content-wrapper {
        padding-left: 0;
        margin-left: 0;
        width: 100%;
        height: auto;

    }

    #wrapper.active {
        padding-left: 150px;
    }

    #wrapper.active #sidebar-wrapper {
        left: 150px;
    }

    #sidebar_menu li a, .sidebar-nav li a {
        color: #fff;
        display: block;
        float: left;
        text-decoration: none;
        width: 200px;
        background: #0648A6;
        /* border-top: 1px solid #373737;
        border-bottom: 1px solid #1A1A1A; */
        -webkit-transition: background .5s;
        -moz-transition: background .5s;
        -o-transition: background .5s;
        -ms-transition: background .5s;
        transition: background .5s;
    }

    .sidebar_name {
        padding-top: 25px;
        color: #fff;
        opacity: .7;
    }

    .sidebar-nav li {
      line-height: 40px;
      text-indent: 20px;
    }


    .sidebar-nav li a {
      color: #fff;
      display: block;
      text-decoration: none;
    }


    .sidebar-nav li a:hover {
      color: #fff;
      background: #053D87;
      text-decoration: none;
    }

    #sidebar_menu li{
        text-indent: 20px;    
    }

    #wrapper.active #page-content-wrapper{
        position: absolute;
        margin-right: -200px;
    } 


    .sidebar-nav li a:active,
    .sidebar-nav li a:focus {
      text-decoration: none;
    }

    .sidebar-nav > .sidebar-brand {
      height: 65px;
      line-height: 60px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
    }

    /* .sidebar-nav > .sidebar-brand a {
      color: #fff;
    } */

    .sidebar-nav > .sidebar-brand a:hover {
      color: #fff;
      background: none;
    }

    #main_icon
    {
        float:right;
       padding-right: 20px;
       padding-top: 20px;
    }
    #sub_icon
    {
        float:right;
       padding-right: 20px;
       padding-top: 13px;
    }
    .content-header {
      height: 65px;
      line-height: 65px;
    }

    .content-header h1 {
      margin: 0;
      margin-left: 20px;
      line-height: 65px;
      display: inline-block;
    }

    @media (max-width:767px) {
    #wrapper {
        padding-left: 70px;
        transition: all .4s ease 0s;
    }
    #sidebar-wrapper {
        left: 70px;
    }
    #wrapper.active {
        padding-left: 200px;
    }
    #wrapper.active #sidebar-wrapper {
        left: 200px;
        width: 200px;
        transition: all .4s ease 0s;
    }
    }
</style>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });
</script>


<div id="wrapper" class="active">

    <div id="sidebar-wrapper">
    
        <ul id="sidebar_menu" class="sidebar-nav">
           <li class="sidebar-brand"><a id="menu-toggle" href="#menu-toggle">FORM EASY<span id="main_icon" class="fa fa-bars"></span></a></li>
        </ul>

        <ul id="sidebar" class="sidebar-nav">      
            

                <li class="{{flag_list_form}}">
                    <a href="#/home">
                        <span id="sub_icon" class="fa fa-home"></span> Home
                    </a>
                </li>


                <li class="{{flag_instruction}}">
                    <a href="#/add-form">
                        <span id="sub_icon" class="fa fa-plus"></span>  Add Form
                    </a>
                </li>          



                <li class="{{flag}}">
                    <a href="#/add-form-type">
                        <span id="sub_icon" class="fa fa-file-text"></span> Form Type
                    </a>
                </li>



                <li class="{{flag_language}}">
                    <a href="#/add-language">
                        <span id="sub_icon" class="fa fa-language"></span> Languages
                    </a>
                </li>


                <li class="{{flag_dept}}">
                    <a href="#/add-dept">
                        <span id="sub_icon" class="fa fa-briefcase"></span> Departments
                    </a>
                </li>


                <li class="{{flag_errors}}">
                    <a href="#/error_report">
                        <span id="sub_icon" class="fa fa-exclamation-triangle"></span> Error
                    </a>
                </li>


                <li class="{{flag_requests}}">
                    <a href="#/requests">
                        <span id="sub_icon" class="fa fa-question" style="padding-right: 25px"></span> Requests
                    </a>
                </li>
                
                <li class="{{flag_requests}}">
                    <a href="#/add_admin">
                        <span id="sub_icon" class="fa fa-user"></span> Admins
                    </a>
                </li>

                <li ng-if="admin==1" class="{{flag_adduser}}">
                    <a href="#/add_user">
                        <span id="sub_icon" class="fa fa-user"></span> Users
                    </a>
                </li>
                
                <li>
                    <a href="javascript:;" ng-click="logout()">
                        <span id="sub_icon" class="fa fa-sign-out"></span> Logout
                    </a>
                </li>


            </ul>            
                
        </div>

    </div>    