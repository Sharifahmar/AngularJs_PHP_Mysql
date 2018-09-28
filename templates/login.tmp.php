<!--<div class="container">-->

<form class="" name="loginFrm" ng-submit="login()">
    <h2 class="head-bar">admin panel</h2>
    <div class="form-group">
        <label for="inputEmail" class="">Username</label>
        <input type="text" id="inputEmail" ng-model="users.a_name" class="form-control" placeholder="Username" required autofocus>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="">Password</label>
        <input type="password" id="inputPassword" ng-model="users.a_password" class="form-control" placeholder="Password" required>
    </div>
    <button class="btn btn-lg btn-primary btn-block btn-omit" type="submit">Sign in</button>
    
    <span style="color: red"> {{message}} </span>
</form>

<!--</div>--> 