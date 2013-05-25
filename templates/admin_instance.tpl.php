<?php echo $formscript ?>

<h4><?php echo $data_id == 'new' ? 'Add' : 'Edit' ?> Event</h4>

Enter further detail for this event<br><br>

<script>
function checkCity(el){
	
	if(el.value==''){
		alert('You must select a valid city');
	}
	
	
}
</script>


<div id="data_form">
<div class="formDark" id="box_overview">
<a href="?data_id=<?php echo $data_id ?>"><img src="/images/button_edit.gif" border="0" name="submit" style="float: right; " value="Log In"></a>
<label for="data" style="float: left;">Current Overview:</label>
<?php echo $parent_info['data_title']?>
</div>
</div>
<br><br>

<?php if(isset($_GET['saved'])){ ?><div id="admin_message">Your event information has been saved</div><br><br><?php } ?>
<?php if(isset($_GET['deleted'])){ ?><div id="admin_message">Itinerary information has been removed</div><br><br><?php } ?>

<form action="" method="post" id="data_form" enctype="multipart/form-data" onsubmit="return validate()">

<input type="hidden" name="next_step" id="next_step" value="0">
<input type="hidden" name="instance_id" value="<?php echo $instance_id ?>">
<input type="hidden" name="data_id" value="<?php echo $data_id ?>">
<input type="hidden" name="state_id" id="state_id" value="<?php echo isset($_POST['state_id']) ? $_POST['state_id'] : ( isset($info['state_id']) &&  $info['state_id']!='' ? $info['state_id'] : '' ) ?>">
<input type="hidden" name="city_id" id="city_id" value="<?php echo isset($_POST['city_id']) ? $_POST['city_id'] : ( isset($info['city_id']) &&  $info['city_id']!='' ? $info['city_id'] : '' ) ?>">

<div class="formDark" id="box_instance_title"><label for="instance_title">Event title:</label><input type="text" id="instance_title" name="instance_title" value="<?php echo isset($_POST['instance_title'])? $_POST['instance_title'] : ( isset($info['instance_title']) &&  $info['instance_title']!='' ? $info['instance_title'] : '' ) ?>"></div>

<div class="formDark" id="box_city_id"><label for="city_id">Location:</label>
<select id="city_id" name="city_id" style="font-size: 9px;" onchange="checkCity(this);">
<option value="">any</option>
<?php foreach($location_array['capital'] as $cap){ ?>
<option value="<?php echo $cap['city_id'] ?>"<?php echo isset($_POST['city_id']) && $_POST['city_id'] == $cap['city_id'] ? ' selected' : ( isset($info['city_id']) &&  $info['city_id']==$cap['city_id'] ? ' selected' : '' ) ?>><?php echo $cap['city']?></option>
<?php } ?>
<option value="">----------------------</option>
<?php foreach($location_array['else'] as $regions){ ?>
<option value=""><?php echo $regions[0]['state']?></option>
<?php foreach($regions as $r){ ?>
<option value="<?php echo $r['city_id'] ?>"<?php echo isset($_POST['city_id']) && $_POST['city_id'] == $r['city_id'] ? ' selected' : ( isset($info['city_id']) &&  $info['city_id']==$r['city_id'] ? ' selected' : '' ) ?>>&nbsp;&nbsp;&nbsp;<?php echo $r['city']?></option>
<?php } ?>
<?php } ?>
</select>
</div>

<div class="formDark" id="box_instance_image"><label for="instance_image">Logo:</label><input type="file" id="instance_image" name="instance_image" value=""></div>

<div class="formDark" id="box_instance_venue"><label for="instance_venue">Venue:</label><input type="text" id="instance_venue" name="instance_venue" value="<?php echo isset($_POST['instance_venue'])? $_POST['instance_venue'] : ( isset($info['instance_venue']) &&  $info['instance_venue']!='' ? $info['instance_venue'] : '' ) ?>"></div>

<div class="formDark" id="box_instance_address" style="height:auto"><label for="instance_address">Address:</label><textarea id="instance_address" name="instance_address"><?php echo isset($_POST['instance_address'])? $_POST['instance_address'] : ( isset($info['instance_address']) &&  $info['instance_address']!='' ? $info['instance_address'] : '' ) ?></textarea></div>

<div class="formDark" id="box_instance_map" style="display: none;"><label for="instance_map">Google map:</label><input type="text" id="instance_map" name="instance_map" value="<?php echo isset($_POST['instance_map'])? $_POST['instance_map'] : ( isset($info['instance_map']) &&  $info['instance_map']!='' ? $info['instance_map'] : '' ) ?>"></div>

<div class="formDark" id="box_instance_category_id"><label for="instance_category_id">Category:</label><select id="instance_category_id" name="instance_category_id">
<?php foreach($category_array as $k => $c){ ?><option value="<?php echo $k ?>"<?php echo isset($_POST['instance_category_id'])&&$_POST['instance_category_id']==$k? ' selected' : ( isset($info['instance_category_id']) &&  $info['instance_category_id'] == $k ? ' selected' : '' )  ?>><?php echo $c ?></option><?php } ?></select>
</div>

<div class="formDark" id="box_instance_description" style="height:auto"><label for="instance_description">Full description:</label><textarea id="instance_description" name="instance_description"><?php echo isset($_POST['instance_description'])? $_POST['instance_description'] : ( isset($info['instance_description']) &&  $info['instance_description']!='' ? $info['instance_description'] : '' ) ?></textarea></div>

<div class="formDark" id="box_instance_price"><label for="instance_price">Cost:</label><input type="text" id="instance_price" name="instance_price" value="<?php echo isset($_POST['instance_price'])? $_POST['instance_price'] : ( isset($info['instance_price']) &&  $info['instance_price']!='' ? $info['instance_price'] : '' ) ?>"></div>

<div class="formDark" id="box_contact_name"><label for="contact_name">Contact name:</label><input type="text" id="contact_name" name="contact_name" value="<?php echo isset($_POST['name'])? $_POST['contact_name'] : ( isset($contact_array['name']) &&  $contact_array['name']!='' ? $contact_array['name'] : '' ) ?>"><span onclick="fillFromOwner()" style="margin-left: 30px;color: #FFCC33; cursor: pointer; cursor: hand;">fill from saved</span></div>

<div class="formDark" id="box_contact_email"><label for="contact_email">Contact email:</label><input type="text" id="contact_email" name="contact_email" value="<?php echo isset($_POST['contact_email'])? $_POST['contact_email'] : ( isset($contact_array['email']) &&  $contact_array['email']!='' ? $contact_array['email'] : '' ) ?>"></div>

<div class="formDark" id="box_contact_phone"><label for="contact_phone">Contact phone:</label><input type="text" id="contact_phone" name="contact_phone" value="<?php echo isset($_POST['contact_phone'])? $_POST['contact_phone'] : ( isset($contact_array['phone']) &&  $contact_array['phone']!='' ? $contact_array['phone'] : '' ) ?>"></div>

<div class="formDark" id="box_contact_mobile"><label for="contact_mobile">Contact mobile:</label><input type="text" id="contact_mobile" name="contact_mobile" value="<?php echo isset($_POST['contact_mobile'])? $_POST['contact_mobile'] : ( isset($contact_array['mobile']) &&  $contact_array['mobile']!='' ? $contact_array['mobile'] : '' ) ?>"></div>

<div class="formDark" id="box_contact_fax"><label for="contact_fax">Contact fax:</label><input type="text" id="contact_fax" name="contact_fax" value="<?php echo isset($_POST['contact_fax'])? $_POST['contact_fax'] : ( isset($contact_array['fax']) &&  $contact_array['fax']!='' ? $contact_array['fax'] : '' ) ?>"></div>

<?php if($instance_id == 'new'){ ?>
<input type="image" src="/images/button_saveandcontinue.gif" onclick="nextStep()" style="float: right; margin-left: 10px; margin-top: 6px;">
<?php } else { ?>
<a href="admin_edit.php?data_id=<?php echo $data_id ?>&instance_id=<?php echo $instance_id ?>&delete=instance" onclick="return confirmDelete('event');"><input type="image" src="/images/button_delete.gif" style="float: left; margin-top: 6px; border-width: 0px;"></a>
<?php } ?>
<input type="image" src="/images/button_save.gif" style="float: right; margin-top: 6px;">

</form>

<br><br><br>

<?php if($instance_id != 'new'){ ?>
<form action="" method="get" id="data_form">
<input type="hidden" name="instance_id" value="<?php echo $instance_id ?>">
<input type="hidden" name="data_id" value="<?php echo $data_id ?>">

<div class="formDark" id="box_name">
<a href="?data_id=<?php echo $data_id ?>&instance_id=<?php echo $instance_id ?>&time_id=new"><img src="/images/button_addnew.gif" border="0" name="submit" style="float: right; " value="Log In"></a>
<label for="name" style="float: left;">Select item:</label>
<select id="time_id" name="time_id" style="float: left;">
<option>Please select an itinerary item...</option>
<?php foreach($info['times'] as $i){ ?>
<option value="<?php echo $i['time_id'] ?>"><?php echo $i['time_title'] ?></option>
<?php } ?>
</select>
<input type="image" src="/images/button_edit.gif" name="submit" style="float: left; " value="edit">
</div>
<?php } ?>

</form>
<br>
<br>