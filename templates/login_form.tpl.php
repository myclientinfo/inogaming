<form id="login_form" method="post" action="">
<label for="login_username">Username</label><input type="text" name="username" id="login_username" value="<?php echo (isset($_POST['username'])?$_POST['username']:(isset($_GET['un'])?$_GET['un']:''))?>"><br>
<label for="login_password">Password</label><input type="password" name="password" id="login_password"><br>

<input type="image" src="/images/button_login.gif" name="submit" style="float: right; width: 55px; height: 14px; border-width: 0px;" value="Log In">
<div style="clear: both;"></div>
</form>
