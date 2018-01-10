<div class="signin-form">
    <div class="container">
        <form class="form-signin" name="adminform" method="get" id="login-form" action="">
            <h2 class="form-signin-heading">Admin Login</h2>
            <hr />
            <div class="alerts" id="match">
            <div class="alert alert-danger text-center">Please Fill up the Form</div>
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-user"></span>
                </span>

                <input type="text" class="form-control" name="empid" placeholder="ID" id="di" type="text" required />
            </div>

            <div class="form-group input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-lock"></span>
                </span>
                <input type="password" class="form-control" name="passwords" id="wordpass" placeholder="Password" />
            </div>
            <hr />
            <div class="form-group">
                <button type="submit" id="btn4" disabled name="btn-login" class="btn btn-default">
                    <i class="glyphicon glyphicon-log-in"></i> &nbsp;Login
                </button>
            </div>
        </form>
    </div>
</div>