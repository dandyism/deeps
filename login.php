<form action="index.php" method="post" class="form-horizontal">
    <input name="action" type="hidden" value="login" />
    <input name="page" type="hidden" value="login" />
    <div class="form-group">
        <label for="username" class="col-lg-2 control-label">Username</label>
        <div class="col-lg-10">
            <input id="username" name="username" class="form-control" placeholder="Username" type="text" />
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="col-lg-2 control-label">Password</label>
        <div class="col-lg-10">
            <input id="password" name="password" class="form-control" placeholder="Password" type="password" />
        </div>
    </div>
    <button class="btn btn-default" type="submit">Login</button>
</form>
