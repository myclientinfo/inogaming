<h4>Modify or Add Event</h4>

Select the overview whose events you wish to edit, or create a new one.<br><br>

<?php if(isset($_GET['saved'])){ ?><div id="message">Itinerary Item Saved</div><br><br><?php } ?>

<form action="" method="get" id="data_form">

<div class="formDark" id="box_name">
<a href="?data_id=new"><img src="/images/button_addnew.gif" border="0" name="submit" style="float: right; " value="Log In"></a>

<label for="name" style="float: left;">Select Overview:</label>
<select id="data_id" name="data_id" style="float: left;">
<option>Please select an event overview...</option>
<?php foreach($info as $i){ ?>
<option value="<?php echo $i['data_id'] ?>"><?php echo $i['title'] ?></option>
<?php } ?>
</select>
<input type="image" src="/images/button_edit.gif" name="submit" style="float: left;" value="Log In">
</div>


</form>