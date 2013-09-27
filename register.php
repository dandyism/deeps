<form action="/register/" method="post" class="form-horizontal">
    <input name="action" type="hidden" value="register" />
    <input name="page" type="hidden" value="registration" />
    <div class="form-group">
        <label class="col-lg-2 control-label" for="username">Username</label>
        <div class="col-lg-10">
            <input id="username" class="form-control" name="username" placeholder="Username" type="text" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label" for="password">Password</label>
        <div class="col-lg-10">
            <input id="password" class="form-control" name="password" placeholder="Password" type="password" />
            <input id="password_check" class="form-control" name="password_check" placeholder="Retype Password" type="password" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label" for="email">Email</label>
        <div class="col-lg-10"><input id="email" class="form-control" name="email" placeholder="Email" type="email" /></div>
    </div>
    <button type="submit" class="btn btn-default">Register</button>
</form>
