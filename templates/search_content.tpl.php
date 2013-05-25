<div id="searchForm" style="padding-top: 17px; padding-bottom: 7px;text-align: center; height: 31px;">
<form action="/search.html" method="post">
&nbsp;&nbsp;&nbsp;<input type="hidden" name="edit_type" id="edit_type" value="s">
<label for="search_text">Search for: </label>
<input type="text" name="search_text" name="search_text" value="" style="font-size: 9px; height: 16px; width: 120px; border: 1px solid black;float: left;">
<label for="location">Location: </label>
<select id="location" name="location" style="font-size: 9px;float: left;">
<option value="">any</option>
<?php foreach($location_array['capital'] as $cap){ ?>
<option value="<?php echo $cap['state'].'|'.$cap['city_id'] ?>"><?php echo $cap['city']?></option>
<?php } ?>
<option value="">----------------------</option>
<?php foreach($location_array['else'] as $regions){ ?>
<option value="<?php echo $regions[0]['state_id'] ?>"><?php echo $regions[0]['state']?></option>
<?php foreach($regions as $r){ ?>
<option value="<?php echo $r['state_id'].'|'.$r['city_id'] ?>">&nbsp;&nbsp;&nbsp;<?php echo $r['city']?></option>
<?php } ?>
<?php } ?>
</select>

<label for="category_id">Type: </label>
<select id="category_id" name="category_id" style="font-size: 9px;float: left;">
<option value="">any</option>
<?php foreach($category_array as $id => $cat){ ?>
<option value="<?php echo $id ?>"><?php echo $cat?></option>
<?php } ?>
</select>&nbsp;&nbsp;&nbsp;
<input type="image" name="submit" src="/images/button_search.gif" style="float: left; margin-top: 3px;">
</form>
<div style="clear:both;"></div>
</div>