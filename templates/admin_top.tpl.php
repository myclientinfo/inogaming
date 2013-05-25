<?php
if($owner_array['message_hide']=='rarrarrar'){
?>
<div id="admin_message">
<b>Welcome to GamersEvents</b><br><br>

There is nothing to do here yet except go to the <a href="admin_edit.html">edit page</a> or <a href="/admin.php?logOut">log out</a>.
</div>

<?php
}
?>
<div id="admin_top_menu">
<div id="admin_menu_outer" style="border: 0px solid #ebebeb; border-bottom-width: 1px;">
<span style="margin: 6px;font-weight: bold;">menu</span>
<div id="admin_menu_inner">
<a href="admin.html">Admin page</a><br>
<a href="admin_edit.html">Add or edit events</a><br>
<!--<a href="admin_list.html">Event listing</a><br>-->
<!--<a href="/api/admin_export.php">Export All</a><br>-->
<a href="admin.html?logOut">Log Out</a><br>
</div>
</div>
</div>
<div id="admin_top_info">

<b>Company:</b> <b style="display: inline;"><?=$owner_array['company_name']?></b><br>
<? if($owner_array['contact_name']!=''){ ?><b>Contact: </b><?=$owner_array['contact_name']?><br><? } ?>
<? if($owner_array['phone']!=''){ ?><b>Phone: </b><?=$owner_array['phone']?><br><? } ?>
<? if($owner_array['email']!=''){ ?><b>Email: </b><?=$owner_array['email']?><br><? } ?>
<? if($owner_array['url']!=''){ ?><b>Website: </b><a href="<?=$owner_array['url']?>" target="_blank"><?=$owner_array['url']?></a><br><? } ?>
<? if($owner_array['location']!=''){ ?><b>Location: </b><?=$owner_array['location']?><br><? } ?>
</div>
<div id="admin_top_logo">
<?php
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/images/dist_'.$owner_array['username'].'.gif')){
	print '<img src="/images/dist_'.$owner_array['username'].'.gif">';
}
?>
</div>
<div style="clear:both;"></div>