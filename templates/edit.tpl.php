<?php
$etf = ($edit_type=='d'?'data':'instance');
?>

<script>

var date_mode = 'simple';

function getReleaseDate(){
	var y = document.getElementById('release_year').options[document.getElementById('release_year').selectedIndex].value;
	var m = document.getElementById('release_month').options[document.getElementById('release_month').selectedIndex].value;
	var d = document.getElementById('release_day').options[document.getElementById('release_day').selectedIndex].value;
	var t = ' 00:00:00';
	var h = '-';

	if(d.length == 1) d = '0'+d;
	if(m.length == 1) m = '0'+m;

	document.getElementById('release_time').value = y+h+m+h+d+t;
}

function getReleaseEndDate(){
	var y = document.getElementById('release_end_year').options[document.getElementById('release_end_year').selectedIndex].value;
	var m = document.getElementById('release_end_month').options[document.getElementById('release_end_month').selectedIndex].value;
	var d = document.getElementById('release_end_day').options[document.getElementById('release_end_day').selectedIndex].value;
	var t = ' 00:00:00';
	var h = '-';

	if(d.length == 1) d = '0'+d;
	if(m.length == 1) m = '0'+m;

	document.getElementById('release_end_time').value = y+h+m+h+d+t;
}




function confirmRemovePlatform(){
	//document.getElementById('remove_platform').value = 1;
	return confirm('Removing this platform will erase any information you have saved to it, and is irreversible. Are you sure you wish to do this?');

}

function setEndItem(type){
	document.getElementById('release_end_'+type).selectedIndex = document.getElementById('release_'+type).selectedIndex;
}

function noRemovePlatform(){
	alert('To remove a platform, edit that platform\'s information and click "Remove Platform".');
}

var platform_colours = {template:'#666666,#999999',
						ps3:'#ee0d0d,#f68686',
						psp:'#cc0066,#e57fb2',
						ps2:'#b92c2c,#dc9595',
						gba:'#003399,#7f99cc',
						pc:'#ff9900,#ffcc7f',
						x360:'#339900,#99cc7f',
						nds:'#0066cc,#7fb2e5',
						wii:'#0099ff,#7fccff'

						};


function wipe_form(){
	var all_inputs = Form.getElements($('edit_form'));
	all_inputs.each( function(inp){
		if(inp.name!='save_button') inp.value='';
	});
}

function deleteInstance(category_id){
	if(confirm('Are you sure you wish to delete this platform? All associated data will be lost.')){
		new Ajax.Request('/api/delete_instance.php',{
			parameters: {data_id: $('data_id').value, platform:category_id},
		  	onSuccess: function(response){

		      getData($('data_id').value);
		    }
		});
	}
}

function deleteData(data_id){

}


function setImage(image_file){
	$('image_outer').update('<img src="/images/boxshots/'+image_file+'">');
}

function disable_div(){
	dd = $('disable_div');
	var size = $('edit_form').getDimensions();
	possy = Position.page($('edit_form'));
	var p_left = possy[0]+'px';
	var p_top = possy[1]+'px';
	dd.style.width = size.width+'px';
	dd.style.height = size.height+'px';
	dd.style.top = p_top;
	dd.style.left = p_left;
	dd.show();
}

var data_retrieved = '';



function clickPlatform(select_platform){
	var lights = document.getElementsByClassName('formLight', 'edit_form');
	var darks = document.getElementsByClassName('formDark', 'edit_form');
	var offs = document.getElementsByClassName('platform_off', 'platform_list');
	var ons = document.getElementsByClassName('platform_on', 'platform_list');
	var text_versions = document.getElementsByClassName('text_version', 'edit_form');
	var form_versions = document.getElementsByClassName('form_version', 'edit_form');

	data_retrieved = $H(data_retrieved);

	var platform_images = $$('#platform_list img');
	var platform_colour = platform_colours[select_platform];
	platform_colour = platform_colour.split(',');
	
	if(select_platform=='template'){
		$('edit_type').value = 'd';
		$('current_platform').value = '';
		text_versions.each( function(txt){
			txt.hide();
		});
		form_versions.each( function(frm){
			frm.show();
		});

		if(data_retrieved.data_image == null || data_retrieved.data_image == ''){
			setImage('boxshot_template.jpg');
		} else {
			setImage(data_retrieved.data_image);
		}

		if(data_retrieved.data_start_time != data_retrieved.data_end_time){
			toAdvancedTime();
		} else {
			toSimpleTime();
		}
		if(data_retrieved.data_start_time!='0000-00-00 00:00:00'){
			hideTime();
        }
		$$('#box_release div.message')[0].style.color = '#999999';
	} else {
		$('edit_type').value = 'i';
		$('current_platform').value = select_platform;
		$('instance_id').value = data_retrieved.platforms[select_platform].instance_id;

		if(data_retrieved.platforms[select_platform].instance_start_time != data_retrieved.platforms[select_platform].instance_end_time){
			toAdvancedTime();
		} else {
			toSimpleTime();
		}
		
		if(data_retrieved.platforms[select_platform].instance_image != null && data_retrieved.platforms[select_platform].instance_image != '' ){
			setImage(data_retrieved.platforms[select_platform].instance_image);
		} else if(data_retrieved.data_image != null && data_retrieved.data_image != '') {
			setImage(data_retrieved.data_image);
		} else {
			setImage('boxshot_'+select_platform+'.jpg');
		}

		text_versions.each( function(txt){
			txt.style.display = 'block';
		});
		form_versions.each( function(frm){
			frm.hide();
		});

		$$('#box_release div.message')[0].style.color = '#FFFFFF';
	}
	offs.each( function(platform){
		platform.hide();
	});

	platform_images.each( function(image){
		image.style.top = '0px';
	});

	offs.each( function(platform){
		platform.hide();
	});
	ons.each( function(platform){
		platform.show();
	});

	var platform_image = $('platform_'+select_platform);
	if(select_platform!='template'){
		$('disable_'+select_platform).style.display='block';
		$('enable_'+select_platform).style.display='none';
	}
	platform_image.style.position = 'relative';
	platform_image.style.zIndex = '10';

	lights.each( function(form_element){
		form_element.style.backgroundColor = platform_colour[1];
	});

	if(select_platform != 'template'){
		var platforms =  $H(data_retrieved['platforms']);

		var this_platform = $H(platforms[select_platform]);
		var remove_string = 'instance_';
		this_platform.each( function(platty){

			plat = platty[0].replace('instance_','');
			if(plat == 'start_time' || plat == 'end_time'){
				$(plat+'_display').innerHTML = makeLongDateString(platty[1])
			}
			if($(plat) && plat != 'id' && plat != 'title'){
				if(platty[1]!='null' && platty[1] != null && platty[1]!='' && platty[1]!=0 && platty[1] != '0000-00-00 00:00:00' ){
					$(plat).value = platty[1];


					if(plat != 'category_id' && plat!= 'start_time' && plat!= 'end_time'){
						$('revert_'+plat).style.display = 'block';
					}
				} else {
					if(plat != 'category_id' && plat!= 'start_time' && plat!= 'end_time'){
						$('revert_'+plat).style.display = 'none';
						$('box_'+plat).style.backgroundColor = '#999999';
					} else {

					}
				}
			}
		});
	} else {
		var platforms =  $H(data_retrieved);
		var revert_divs = document.getElementsByClassName('revert_div');

		revert_divs.each( function(rev){
			rev.style.display = 'none';
		});
		var this_platform = platforms;
		this_platform.each( function(platty){

			plat = platty[0].replace('data_','');

			if($(plat) && plat != 'id' && plat != 'title'){
				$(plat).value = platty[1];
			}
		});
	}

	darks.each( function(form_element){
		form_element.style.backgroundColor = platform_colour[0];
	});
}

function clone(myObj)
{
	if(typeof(myObj) != 'object') return myObj;
	if(myObj == null) return myObj;

	var myNewObj = new Object();

	for(var i in myObj)
		myNewObj[i] = clone(myObj[i]);

	return myNewObj;
}

var blank_data = {data_website:'',data_image:'',data_id:'',data_title:'',data_genre_id:0,data_rating_id:1,data_developer:'',data_publisher:'',data_start_time:'0000-00-00 00:00:00',data_end_time:'0000-00-00 00:00:00',data_description:'',data_price:'',platforms:''};
var platform_list = {x360:'',ps3:'',psp:'',gba:'',wii:'',nds:'',ps2:'',pc:''};

function clearAdminPanel(){
	$H(platform_list).each( function(plat){
		$('image_'+plat[0]).style.filter = 'alpha(opacity=25)';
		$('image_'+plat[0]).style.opacity = 0.25;
		if(plat[0]!='template'){
			$('image_'+plat[0]).style.cursor = 'default';
		}
		$('image_'+plat[0]).removeAttribute('onclick');
		$('enable_'+plat[0]).style.color = '#000000';
	});
}

function create_new(){
	clickPlatform('template');
	clearAdminPanel();
	setImage('boxshot_template.jpg');
	data_retrieved = clone(blank_data);
	$('disable_div').hide();

	$('top_game_title').update('');
	$('start_time_display').update('Click to set time &raquo;');
	$('end_time_display').update('Click to set time &raquo;');

	var input_list = $$('#edit_form input, #edit_form select, #edit_form textarea', 'edit_form');
	input_list.each( function(inp){
		if (inp.tagName=='SELECT'){
			inp.selectedIndex = 0;
		} else {
			inp.value = '';
		}
	});
	$$('#box_release div.message')[0].style.color = '#FFFFFF';
	$('date_periods').style.display = 'none';
	$('input_field_advance').style.display = 'none';
	$('simple_button').style.display = 'none';
	$('advanced_button').style.display = 'block';
	$('start_time_calendar').style.display = 'block';
}

function getData(data_id){
	$('disable_div').hide();
	clearAdminPanel();

	if(data_retrieved!='')clickPlatform('template');
	new Ajax.Request('/api/get_data.php',
	{
		method:'get',
		parameters: {id: data_id, format: 'json'},

		onSuccess: function(transport, json){
			var edit_type = 'data';
			$('data_id').value = data_id;
			var full_para, field;
			var h_json = $H(json);
			data_retrieved = h_json;

			if(data_retrieved.data_start_time != data_retrieved.data_end_time){
				toAdvancedTime();
			}

			$$('#box_release div.message')[0].style.color = '#999999';

			h_json.each(function(para){
				field = para[0].replace(edit_type+'_','');

				if($(field)){
					if($(field).tagName=='INPUT'||$(field).tagName=='TEXTAREA'||$(field).tagName=='HIDDEN'){
						$(field).value = para[1];
					} else if($(field).tagName=='SELECT') {

						var field_options = $A($(field).options);
						var i = 0;
						field_options.each( function(opt){
							if(opt.value == para[1]){
								$(field).selectedIndex = i;
							}
							i++;
						});
					}

				}

				if($(field+'_text_version')){
					if(field=='genre_id'){
						$(field+'_text_version').innerHTML = data_retrieved.genre;
					} else {
						$(field+'_text_version').innerHTML = para[1];
					}
				}

				if(field == 'start_time' || field == 'end_time'){
					var long_date_string = makeLongDateString(para[1]);
					if(!long_date_string){
						$(field+'_display').innerHTML = 'Click to set time &raquo;';
					} else {
						//$(field+'_display').innerHTML = long_date_string;
						hideTime();
					}
				}
				if(field == 'image'){
					if(para[1] == null || para[1] == ''){
						setImage('boxshot_template.jpg');
					} else {
						setImage(para[1]);
					}
				}
			});

			var pf = $H(h_json.platforms);
			var imgs = $$('#platform_list img');
			//var all_enables = getElementsByClassname('platform_on');
			var all_enables = document.getElementsByClassName('platform_on', 'platform_list');

			all_enables.each(function(platty){
				$(platty).style.color = '#000000';
			});

			imgs.each(function(platty){

				if(platty.id != 'platform_template'){
					$(platty).style.opacity = 0.25;
				}
			});

			$('top_game_title').innerHTML = data_retrieved.data_title;

			pf.each(function(platty){
				// turn on disable
				$('disable_'+platty[0]).style.color = '#FF0000';
				$('enable_'+platty[0]).style.color = '#999999';
				//$('enable_'+platty[0]).removeAttribute('onclick');
				//turn on full alpha
				$('image_'+platty[0]).style.filter = 'alpha(opacity=100)';
				$('image_'+platty[0]).style.opacity = 1;
				$('image_'+platty[0]).style.cursor = 'pointer';
				$('image_'+platty[0]).style.cursor = 'hand';
				//$('image_'+platty[0]).setAttribute('onclick','clickPlatform("'+platty[0]+'")');
				$('platform_'+platty[0]).onclick = function() { clickPlatform(platty[0]); }
				//parentNode.innerHTML = parentNode.innerHTML;
			});
		},
		onFailure: function(){ alert('Something went wrong...') }
	});
}

function saveData(){
	var qs = '';
	var pf = $H(data_retrieved.platforms);
	if(pf !=''){
		pf.each(function(platty){
			qs = $H($H(platty).value);
			qs.merge($H({data_id:$F('data_id')}));
			new Ajax.Request('/api/save_data.php', {
			  method: 'post',
			  parameters: qs,
			  onSuccess: function(response){

			    }
			  });

		});
	}
	new Ajax.Request('/api/save_data.php', {
		method: 'post',
		parameters: $H(data_retrieved),
		onSuccess: function(response){
			var result = response.responseText.split('|');
			if(result[0]=='new'){
				$('data_id').value = result[1];
				data_retrieved.data_id = result[1];
				new Insertion.Bottom('al_list_box', '<a onclick="getData('+result[1]+');return false;" class="al al_'+$F('title').substring(0,1).toLowerCase()+'" style="cursor: pointer;">'+$F('title')+'</a>');
			}


		}
	  });
}

function addInstance(category_id){
	if($F('start_time')!='0000-00-00 00:00:00'&&$F('start_time')!=''&&$F('start_time')!=null){
		$('category_id').value = category_id;
		new Ajax.Request('/api/add_instance.php',{
			parameters: $('edit_form').serialize(true),
		  	onSuccess: function(response){
		      getData($('data_id').value);
		    }
		});
	} else {
		alert('Although you may change the data for any individual platform at any time, you must set a default "template" date to provide a valid date for the new platform.\n\nPlease enter a default release date before continuing to add a platform.');
	}
}

function setPeriod(quarter){
    var stime = '';
    var etime = '';
    var year = '20'+''+$('period_year').value;
    var t = ' 00:00:00';
    switch(quarter){
        case 1: stime = year+'-01-01'+t; etime = year+'-03-31'+t; break;
        case 2: stime = year+'-04-01'+t; etime = year+'-06-30'+t; break;
        case 3: stime = year+'-07-01'+t; etime = year+'-09-30'+t; break;
        case 4: stime = year+'-10-01'+t; etime = year+'-12-31'+t; break;
        case 5: stime = year+'-01-01'+t; etime = year+'-06-30'+t; break;
        case 6: stime = year+'-07-01'+t; etime = year+'-12-31'+t; break;
    }

    $('start_time_display').innerHTML = makeLongDateString(stime);
    $('end_time_display').innerHTML = makeLongDateString(etime);

    $('start_time').value = stime;
    $('end_time').value = etime;


    if($('current_platform').value == '' || $('current_platform').value == 'template'){
    	data_retrieved.data_start_time = stime;
    	data_retrieved.data_end_time = etime;
    } else {
    	data_retrieved.platforms[$('current_platform').value].instance_start_time = stime;
    	data_retrieved.platforms[$('current_platform').value].instance_end_time = etime;
    }
    add_log('start_time','start_time');
    saveData();

}

function makeLongDateString(date_string){
	if(date_string == null)return false;
	date_string = date_string.replace(' 00:00:00','');
	var date_array = date_string.split('-');
	var day = parseInt(date_array[0],10);
	var month = parseInt(date_array[1],10) - 1;
    var display_date = new Date();
    display_date.setFullYear(day, month, date_array[2]);
    return monthArrayLong[display_date.getMonth()]+' '+display_date.getDate()+', '+display_date.getFullYear();
}

function toSimpleTime(dupe){
    $('date_periods').style.display = 'none';
    $('input_field_advance').style.display = 'none';
    $('simple_button').style.display = 'none';
    $('advanced_button').style.display = 'block';
    $('end_time').value = $F('start_time');
    $('end_time_display').innerHTML = makeLongDateString($('end_time').value);
    $('start_time_calendar').style.display = 'block';
}

function toAdvancedTime(){
    $('date_periods').style.display = 'block';
    $('input_field_advance').style.display = 'block';
    $('simple_button').style.display = 'block';
    $('advanced_button').style.display = 'none';
    $('start_time_calendar').style.display = 'block';
}

function hideTime(){
	$('date_periods').style.display = 'none';
    $('input_field_advance').style.display = 'none';
    $('simple_button').style.display = 'none';
    $('advanced_button').style.display = 'none';
    $('start_time_calendar').style.display = 'none';

    $('start_time_display').innerHTML = 'To make changes to release dates please edit the individual platform.';
}

function popUpLoad(platform){
	var pos = Position.page($('box_image'));
	window.open('api/admin_upload.php', 'upload image', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizeable=0,width=400,height=170,top='+(pos[1]+110)+',left='+(pos[0]+140)+'');
}
</script>

<div id="platform_list">


<div class="platform_box" style="margin-right: -2px">
	<img src="/images/admin_system_template.jpg" id="platform_template" style="filter:alpha(opacity=100);opacity: 1; cursor: hand;cursor:pointer;" onclick="clickPlatform('template')" border="0" border="0">
</div>

<?php
foreach($category_array as $plat){
?>
<div class="platform_box">
	<div class="platform_off" id="disable_<?=$plat['category_short']?>" onclick="deleteInstance(<?=$plat['category_id']?>);" style="color: #000000;">disable</div>
	<div id="platform_<?=$plat['category_short']?>" class="platform_image_box"><img src="/images/admin_system_<?=$plat['category_short']?>.jpg" id="image_<?=$plat['category_short']?>" border="0" onclick=""></div>
	<div class="platform_on" id="enable_<?=$plat['category_short']?>" onclick="addInstance(<?=$plat['category_id']?>)">enable</div>
</div>
<?php
}


?>

<div style="clear:both"></div></div>

<div id="inputFormForm">
<h4>Editing Game <span id="top_game_title"></span></h4>

<?php
if(!empty($msg)){ ?>
<div class="msgs"><?
foreach($msg as $m){
	print "<div class='msg'>".$m."</div>";
}
?>
</div>
<? }

$month_array = Site::getMonthsArray();
?>
<?php
if($edit_type=='i'&& $edit_type != ''){ ?><p>If data is not different from the main game information, please leave it blank. This will help keep your data neat and consistent.</p><?php }
else print "To change the data for a specific platform, such as price, description or release date, please click the platform edit button on the right.<br><br>";
?>



<form id="edit_form" method="post">
<input type="hidden" name="id" id="id" value="<?=$id?>">
<input type="hidden" name="instance_id" id="instance_id" value="<?=($edit_type=='i'?$id:0)?>">
<input type="hidden" name="data_id" id="data_id" value="">
<input type="hidden" name="edit_type" id="edit_type" value="<?=$edit_type?>">
<input type="hidden" name="category_id" id="category_id" value="">
<input type="hidden" name="current_platform" id="current_platform" value="">
<input type="hidden" name="main_form" value="1">
<?php

/*
Lots to do here. Lots of scary javascript
*/
?>
<?php
if($edit_type=='i'){
	$time = strtotime($content['times'][0]['start_time']);
	$end_time = strtotime($content['times'][0]['end_time']);
} else {
	$time = strtotime($release_time);
	$end_time = strtotime($release_end_time);
}
?>



<?php if($edit_type=='d'){ ?>
<div class="formDark">
<label for="title">Title:</label><input class="form_version" type="text" id="title" name="title" value="<?=(isset($content[$etf.'_title'])?$content[$etf.'_title']:'')?>"><span class="text_version" id="title_text_version"><?=(isset($content[$etf.'_title'])?$content[$etf.'_title']:'')?></span>
</div>


<div class="formDark">
<label for="website">Website:</label><input class="form_version" type="text" id="website" name="website" value="<?=isset($content['website'])?$content['website']:''?>"><span class="text_version" id="website_text_version"><?=isset($content['website'])?$content['website']:''?></span>
</div>

<div class="formDark">
<label for="genre_id">Genre:</label><select class="form_version" id="genre_id" name="genre_id"><?php
foreach($genres as $k => $v){ ?><option value="<?=$k?>"<?=(isset($content['genre_id'])&&$k==$content['genre_id']?' selected':'')?>><?=$v?></option><?php } ?></select><span class="text_version" id="genre_id_text_version"><?=isset($content['genre_id'])?$genres[$content['genre_id']]:''?></span>
<?php } ?>
</div>

<div class="formLight" id="box_image"><div id="revert_image" class="revert_div" onclick="revert_to_template('image');">revert to template</div>
<label for="image">Image:</label>
	
<div style="float: left; width: 330px;padding: 0px; " >
	<div id="image_outer" style="padding: 0px; margin-right: 10px; height: 150px; width: 107px; float: left;"></div>
	<div onclick="popUpLoad();" style="cursor: pointer; cursor: hand;">:: Click to replace</div>
<input type="hidden" id="image" name="image" value="">

</div>
<div style="clear: both;"></div>
</div>

<div class="formLight" id="box_release">
	<label for="release_day" style="float: left; ">Release:</label>
	<div style="float: left; width: 390px; ">
	<div style="height: 20px; padding: 0px;">

		<div id="start_time_display"></div>
		<img id="start_time_calendar" src="/images/calendar.png" style="float: left; margin-top: 3px; margin-left: 3px; cursor: hand; cursor: pointer;" onclick="displayDatePicker('start_time', this, 'ymd', '-');return false;">
		<div id="input_field_advance" style="display: none; padding: 0px; float: left;">
		<div style="float: left; padding: 5px; color: white;">to</div>
		<div id="end_time_display"></div>
		<img src="/images/calendar.png" style="float: left; margin-top: 3px; margin-left: 3px; cursor: hand; cursor: pointer;" onclick="displayDatePicker('end_time', this, 'ymd', '-');return false;">
		</div>
		<div id="advanced_button" onclick="toAdvancedTime();">advanced</div>
		<div id="simple_button" style="display: none;" onclick="toSimpleTime();">simple</div>
		<br>
	</div>

	<div id="date_periods" style="width: 320px; display: none;padding: 0px;margin: 0px;">
        <div class="letter" style="width: 50px;"><select name="period_year" id="period_year" style="padding:0px;"><option>07</option><option>08</option><option>09</option></select></div>
        <div class="letter" style="width: 50px;">Quarter:</div>
        <div class="number" onclick="setPeriod(1);">1</div>
        <div class="number" onclick="setPeriod(2);">2</div>
        <div class="number" onclick="setPeriod(3);">3</div>
        <div class="number" onclick="setPeriod(4);">4</div>
        <div class="letter" style="width: 50px;">Half</div>
        <div class="number" onclick="setPeriod(5);">1</div>
        <div class="number" onclick="setPeriod(6);">2</div>
        <div style="clear: both;padding:0px;margin:0px;"></div>
    </div>
    </div>

	<input type="hidden" id="start_time" name="start_time" value="">
	<input type="hidden" id="end_time" name="end_time" value="">
	<div style="clear:both;padding:0px;margin:0px;"></div>
	<div class="message">:: if you want to list your release date between a certain date / month / year, please select advanced mode.</div>
</div>

<div class="formLight" id="box_rating_id"><div id="revert_rating_id" class="revert_div" onclick="revert_to_template('rating_id');">revert to template</div>
<label for="rating_id">Rating:</label><select id="rating_id" name="rating_id" style="font-size: 9px;"><?php
foreach($ratings as $k => $v){ ?><option value="<?=$k?>"<?=((isset($content[$etf.'_rating_id'])&&$k==$content[$etf.'_rating_id'])||(!isset($content[$etf.'_rating_id'])||$content[$etf.'_rating_id']==''||$content[$etf.'_rating_id']==0)&&$v=='TBD'?' selected':'')?>><?=$v?></option><?php } ?></select>
</div>

<div class="formLight" id="box_description"><div id="revert_description" class="revert_div" onclick="revert_to_template('description');">revert to template</div>
<label for="description">Description:</label><textarea id="description" name="description"></textarea>
</div>

<div class="formLight" id="box_price"><div id="revert_price" class="revert_div" onclick="revert_to_template('price');">revert to template</div>
<label for="price">RRP:</label><input type="text" id="price" name="price" value="">
</div>

<div class="formLight" id="box_developer"><div id="revert_developer" class="revert_div" onclick="revert_to_template('developer');">revert to template</div>
<label for="developer">Developer:</label><input type="text" id="developer" name="developer" value="">
</div>

<div class="formLight" id="box_publisher"><div id="revert_publisher" class="revert_div" onclick="revert_to_template('publisher');">revert to template</div>
<label for="publisher">Publisher:</label><input type="text" id="publisher" name="publisher" value="">
</div>
<div style="clear: both;"></div>
<img style="float: right;cursor: pointer; cursor: hand; margin-bottom: 10px;" src="/images/button_save.gif" onclick="saveData();return false;">

<div style="clear: both;"></div>
</form>

<form id="log_form" onsubmit="return false;">
<input type="hidden" name="changed_fields" id="changed_fields">
<div id="change_log"></div>
<img style="float: right;cursor: pointer; cursor: hand;" src="/images/button_saveandbroadcast.gif" onclick="save_log();return false;">
</form>


<div>

</div>
</div>


<div id="disable_div" style="position:absolute;z-index:100; width:100px; height: 100px; background-color: 20px;"></div>

<script>
disable_div();
wipe_form();
var changes_made = false;
var change_log = $('change_log');
var changed_fields = [];
var translate_fields = {title:'Game Title', website:'Website', description:'Description', price:'Price', image:'Box Art', developer:'Developer', publisher:'Publisher', rating_id:'Rating', start_time:'Release Date', end_time:'Release Date', genre_id:'Genre'};



function save_log(){
    var all_fields = $A(document.getElementsByTagName('input'));
    var final_fields = [];

    all_fields.each(function(f) {
        if(f.type == 'checkbox' && f.checked){
            final_fields.push(translate_fields[f.value]);
        }
    });
    final_fields = final_fields.uniq().join(',');

    new Ajax.Request('/api/save_change.php', {
		method: 'post',
		parameters: {fields: final_fields, title: $('title').value, data_id: $('data_id').value },
		onSuccess: function(response){
		}
	});
    change_log.innerHTML = '';
}

function revert_to_template(box_id){
	$('box_'+box_id).style.backgroundColor = '#999999';
	data_retrieved.platforms[$('current_platform').value]['instance_'+box_id] = '';
	$(box_id).value = data_retrieved['data_'+box_id];
	$('revert_'+box_id).style.display = 'none';
	var image_file = (data_retrieved.data_image == null || data_retrieved.data_image == '' ? 'boxshot_template.jpg' : data_retrieved.data_image);
	setImage(image_file);
	saveData();
}

var Checks = {
	change_field: function(event) {
		changes_made = true;
		if($('current_platform').value!='template'&&$('current_platform').value!=''){
			plat_colour = platform_colours[$('current_platform').value].split(',')[1];
			$('box_'+Event.element(event).id).style.backgroundColor = plat_colour;
		}

	    add_log(event);

    	if($('current_platform').value == ''){
	    	//if(Event.element(event).id != 'website' && Event.element(event).id != 'genre_id'){
	    		prefix = 'data_';
	    	//}
	    	var data_location = data_retrieved;
	    } else {
	    	var data_location = data_retrieved.platforms[$('current_platform').value];
	    	$('revert_'+Event.element(event).id).style.display = 'block';
	    	prefix = 'instance_';
	    }
	    var location2 = prefix+Event.element(event).id;
	    data_location[location2] = $(Event.element(event).id).value;


	    saveData();
	}
};

function manual_log(text){
    //
}

function add_log(event, text){
	var new_div = document.createElement('div');

	if(typeof(event)=='object'){
		var log_text = translate_fields[Event.element(event).id];
		var value = Event.element(event).id;
	} else {
		var log_text = translate_fields[text];
		var value = event;
	}

	var text_node = document.createTextNode('Changes made to ' + log_text);
    var c_box = document.createElement('input');
    var prefix = '';

    c_box.setAttribute('type','checkbox');
    c_box.setAttribute('checked','true');
    c_box.setAttribute('value', value);
    new_div.appendChild(c_box);
    new_div.appendChild(text_node);
    changed_fields.push(value);
    change_log.appendChild(new_div);

    change_log.scrollTop = change_log.offsetHeight;
}

var form_fields = ['title', 'website', 'genre_id', 'description', 'price', 'developer', 'publisher', 'rating_id', 'start_time', 'end_time'];

form_fields.each(function(f) {
    Event.observe(f, 'change', Checks.change_field.bindAsEventListener(Checks));
});
</script>
<div style="clear: both;"></div>
<?php if(isset($_GET['data_id'])){ ?>
<script>
getData(<?=$_GET['data_id']?>);
</script>
<?php }?>