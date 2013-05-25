<?php

include_once('api_config.php');
include_once('../class.auth.php');
$auth = Auth::isLoggedIn();
if (!$auth) header("HTTP/1.0 404 Not Found");
$relative_location = '/images/boxshots/';
?>


<html>
<head>
<title>Vurp image upload</title>
<style>
body {margin: 0px; padding: 0px;}
#image_outer {height: 150px; width: 107px; float: left; margin: 10px;}
#image_upload_form {display: inline;}
#image_file {height: 14px; border: 1px solid black; font-size: 8.5px;}
.buttony {cursor:pointer; pointer:hand;}
</style>
</head>

<body>
<script src="/js/prototype.js"></script>
<script>
var dr = window.opener.data_retrieved;
var relative_location = '<?php echo $relative_location ?>';
var cur_pl = window.opener.$F('current_platform');
if (cur_pl == '') cur_pl = 'template';

if(cur_pl == 'template'){
	var data = dr;
	var prefix = 'data_';
} else {
	var data = dr.platforms[cur_pl];
	var prefix = 'instance_';
}

<?php if(!empty($_FILES)){ ?>
var image_file = '<?php echo Data::uploadImage() ?>';
<?php } else { ?>
var image_file = data[prefix+'image'];
<?php } ?>

if(image_file == null || image_file == ''){
	image_file = 'boxshot_'+cur_pl+'.jpg'
}
function saveAndClose(){
	//if($F('image_file')=='') return false;
	data[prefix+'image'] = image_file;
	window.opener.saveData();
	if(cur_pl != 'template'){ 
		window.opener.$('revert_image').style.display = 'block';
		window.opener.$('box_image').style.backgroundColor = window.opener.platform_colours[cur_pl].split(',')[1];
	}
	window.opener.add_log('image', 'image');
	window.opener.$('image_outer').update('<img src="'+relative_location+image_file+'">');
	window.close();
}

function checkSubmit(){
	if($F('image_file')==''){
		alert('You have not uploaded an image');
		return false;
	}
}
</script>
<div>


<div id="uploader_outer">
	<div id="image_outer"></div>
	<div style="float:left; margin-top: 10px;">

		<form action="" method="post" enctype="multipart/form-data" id="image_upload_form" onsubmit="return checkSubmit();">
		<input type="hidden" name="current_platform" id="current_platform" value="">
		<input type="hidden" name="data_id" id="data_id" value="">
		<input type="file" id="image_file" name="image_file" value=""><br>
		<input type="image" src="/images/button_upload.gif" value="replace">
		</form>
		<br><br>
		<img class="buttony" src="/images/button_saveandclose.gif" onclick="saveAndClose();">
	</div>
<div style="clear: both;"></div>
</div>
<div style="clear: both;"></div>
</div>



<script>

$('uploader_outer').style.backgroundColor = window.opener.platform_colours[cur_pl].split(',')[1];
$('image_outer').update('<img src="'+relative_location+image_file+'">');
$('data_id').value = dr.data_id;
$('current_platform').value = cur_pl;

</script>


</body>
</html>