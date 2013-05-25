<h4>Sign up to GamersEvents</h4>

To add your event to GamersEvents you just need to sign up. All membership is free, and there is no cost to add as many events as you would like.<br><br><br>

<?php if(!isset($_GET['signed'])){ ?>

<?php echo $formscript ?>

<form action="" method="post" id="data_form" onsubmit="return validate()">

<input type="hidden" name="owner_id" value="0">

<div class="formDark" id="box_contact_name"><label for="name">Contact name:</label><input type="text" id="contact_name" name="contact_name" value="<?php echo isset($_POST['contact_name'])? $_POST['contact_name'] : '' ?>"></div>

<div class="formDark" id="box_company_name"><label for="company_name">Company name:</label><input type="text" id="company_name" name="company_name" value="<?php echo isset($_POST['company_name'])? $_POST['company_name'] : '' ?>"></div>

<div class="formDark" id="box_email"><label for="email">Email:</label><input type="text" id="email" name="email" value="<?php echo isset($_POST['email'])? $_POST['email'] : '' ?>"></div>

<div class="formDark" id="box_phone"><label for="email">Phone:</label><input type="text" id="phone" name="phone" value="<?php echo isset($_POST['phone'])? $_POST['phone'] : '' ?>"></div>

<div class="formDark" id="box_mobile"><label for="mobile">Mobile:</label><input type="text" id="mobile" name="mobile" value="<?php echo isset($_POST['mobile'])? $_POST['mobile'] : '' ?>"></div>

<div class="formDark" id="box_fax"><label for="fax">Fax:</label><input type="text" id="fax" name="fax" value="<?php echo isset($_POST['fax'])? $_POST['fax'] : '' ?>"></div>

<div class="formDark" id="box_username"><label for="name">Username:</label><input type="text" id="username" name="username" value="<?php echo isset($_POST['username'])? $_POST['username'] : '' ?>"></div>

<div class="formDark" id="box_password" style="height: auto;">
    <label for="password">Password:</label><input type="password" id="password" name="password"><br>
    <label for="conf_password">Confirm:</label><input type="password" id="conf_password" name="conf_password">
</div>
<input type="image" src="/images/button_signup.gif" name="submit" style="float: right; margin-top: 6px;" value="Sign Up">

</form>

<?php } else { ?>

Thank you for signing up. You may now create your first event in our <a href="/admin.html">admin section</a>

<?php } ?>