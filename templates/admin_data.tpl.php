<h4><?php echo $data_id == 'new' ? 'Add' : 'Edit' ?> Event Overview</h4>

This section forms an overall container for related events. Further information is added in later steps.<br><br>

<?php
print $formscript;
?>
<?php if(isset($_GET['saved'])){ ?><div id="admin_message">Overview Saved</div><br><br><?php } ?>
<?php if(isset($_GET['deleted'])){ ?><div id="admin_message">Your event has been removed</div><br><br><?php } ?>

<form action="" method="post" id="data_form" enctype="multipart/form-data" onsubmit="return validate()">

<input type="hidden" name="next_step" id="next_step" value="0">
<input type="hidden" name="data_id" value="<?php echo $data_id ?>">

<div class="formDark" id="box_data_title"><label for="data_title">Event title:</label><input type="text" id="data_title" name="data_title" value="<?php echo isset($_POST['data_title'])? $_POST['data_title'] : ( isset($info['data_title']) &&  $info['data_title']!='' ? $info['data_title'] : '' ) ?>"></div>

<div class="formDark" id="box_data_title"><label for="data_website">Event website:</label><input type="text" id="data_website" name="data_website" value="<?php echo isset($_POST['data_website'])? $_POST['data_website'] : ( isset($info['data_website']) &&  $info['data_website']!='' ? $info['data_website'] : '' ) ?>"></div>

<div class="formDark" id="box_data_image"><label for="data_image">Event logo:</label><input type="file" id="data_image" name="data_image" value=""></div>

<div class="formDark" id="box_data_description" style="height: auto"><label for="data_description">Event description:</label><textarea id="data_description" name="data_description"><?php echo isset($_POST['data_description'])? $_POST['data_description'] : ( isset($info['data_description']) &&  $info['data_description']!='' ? $info['data_description'] : '' ) ?></textarea></div>
<?php if($data_id == 'new'){ ?>
<input type="image" src="/images/button_saveandcontinue.gif" onclick="nextStep();" style="float: right; margin-left: 10px; margin-top: 6px;">
<?php } else { ?>
<a href="admin_edit.php?data_id=<?php echo $data_id ?>&delete=data" onclick="return confirmDelete('overview');"><input type="image" src="/images/button_delete.gif" style="float: left; margin-top: 6px; border-width: 0px;"></a>
<?php } ?>

<input type="image" src="/images/button_save.gif" style="float: right; margin-top: 6px;">
</form>

<br><br>

<?php if($data_id != 'new'){ ?>
<form action="" method="get" id="data_form">
<input type="hidden" name="data_id" value="<?php echo $data_id ?>">
<div class="formDark" id="box_name">
<a href="?data_id=<?php echo $data_id ?>&instance_id=new"><img src="/images/button_addnew.gif" border="0" name="submit" style="float: right; " value="Log In"></a>
<label for="name" style="float: left;">Select event:</label>
<select id="instance_id" name="instance_id" style="float: left;">
<option>Please select an event...</option>
<?php foreach($info['instances'] as $i){ ?>
<option value="<?php echo $i['instance_id'] ?>"><?php echo $i['instance_title'] ?></option>
<?php } ?>
</select>
<input type="image" src="/images/button_edit.gif" name="submit" style="float: left; " value="edit">
</div>
<?php } ?>

</form>
<br>