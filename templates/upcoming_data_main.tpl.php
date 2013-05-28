<?php
if(isset($_GET['platform'])){
	print '<img style="margin-bottom: 10px;" src="/images/header_'.$_GET['platform'].'.jpg">';
} else {
?>
<div id="main_message">
<div style="float: left; width: 494px;  ">
<h4 style="margin-top: 0px; margin-bottom: 10px;">GamersEvents</h4>

Welcome to the beta launch of GamersEvents. There may be a few minor issues as we add more and more content, and we will fix them as we find them. If you find any major errors, please feel free to send them to <a href="mailto:matt@australiangamer.com">the developer</a>.<br><br>

GamersEvents is your only destination for video games events in Australia. Whether you're looking for something to do on the weekend, trying to find competitions to further your pro-gaming career, or have a LAN event you wish to promote this is where you need to be.<br><br>

There is no cost at all, whether you're managing an event or looking for something to do.
</div>
<div style="float: left; border: 0px solid black; width: 175px; border-left-width: 1px; padding-left: 15px;">
<h4 style="margin-top: 0px; margin-bottom: 10px;">State Search</h4>

<a href="/act/">Australian Capital Territory</a><br>
<a href="/nsw/">New South Wales</a><br>
<a href="/nt/">Northern Territory</a><br>
<a href="/qld/">Queensland</a><br>
<a href="/tas/">Tasmania</a><br>
<a href="/sa/">South Australia</a><br>
<a href="/vic/">Victoria</a><br>
<a href="/wa/">Western Australia</a><br>

</div>
<div style="clear: both;"></div>
</div>
<?php } ?>
	<div style="margin-bottom: 8px;">
<div class="main_nav_button" id="prev_1" onclick="clickDays('-');" style="float: left; visibility: hidden;cursor: pointer; cursor: hand;">
&laquo; Previous
</div>
<div class="main_nav_button" id="next_1" onclick="clickDays('+');" style="float: right; cursor: pointer; cursor: hand;">
Next &raquo;
</div>
<div style="clear: both;"></div>
</div>
<?php
$i=1;
foreach($content as $k => $value){
?>
<div class="daybox" id="daybox_<?=$i?>" style="display: <?=($i<=3||isset($_GET['platform'])?'block':'none')?>;">
<div class="mainUpcomingDay">
<div class="mainUpcomingDate"><?=Site::getDateString($k, DAY_MONTH_YEAR)?></div>
<div class="mainUpcomingDayData">
<?php
foreach($value as $d){
	$description = $d['instance_description']!=''?$d['instance_description']:$d['data_description'];
	if(strlen($description)>127){
		$description = substr($description,0,130).'...';
	}
		?>
<b class="mainUpcomingTitle"><a href="/<?=strtolower($d['state_short'])?>/<?=strtolower($d['city'])?>/<?=Site::createLinkFromTitle($d['data_title'])?>/<?php echo $d['instance_id'] ?>_<?=Site::createLinkFromTitle($d['instance_title'])?>.html"><?=$d['data_title']?></a></b> (<?=$d['category']?>)<br>
<?=($description!='')?$description.'<br>':''?>
<br>

<?php
}
$i++;
?>
	</div><br>
</div>


</div>




<?php
  //  }
}
?>
<?php
if(!isset($_GET['platform'])){
	?>
	<div>
<div class="main_nav_button" id="prev_2" onclick="clickDays('-');" style="float: left; visibility: hidden;cursor: pointer; cursor: hand;">
&laquo; Previous
</div>
<div class="main_nav_button" id="next_2" onclick="clickDays('+');" style="float: right; cursor: pointer; cursor: hand;">
Next &raquo;
</div>
<div style="clear: both;"></div>
</div>


	<?php
}
?>

	<script language="Javascript" src="/js/prototype.js"></script>
	<script>
<?php 
if(isset($_GET['state'])){
	?>
		var state_colours = {template:'#FFCC33,#FFE59A',
						ps3:'#ee0d0d,#f68686',
						psp:'#cc0066,#e57fb2',
						ps2:'#b92c2c,#dc9595',
						gba:'#003399,#7f99cc',
						pc:'#ff9900,#ffcc7f',
						x360:'#339900,#99cc7f',
						nds:'#0066cc,#7fb2e5',
						wii:'#0099ff,#7fccff'

						};

	function setColours(state){
		var lights = document.getElementsByClassName('mainUpcomingDayData', 'main_content');
		var darks = document.getElementsByClassName('mainUpcomingDate', 'main_content');
		var state_colour = state_colours[state];

		state_colour = state_colour.split(',');

		lights.each( function(box){
			box.style.backgroundColor = state_colour[1];
		});
		darks.each( function(box){
			box.style.backgroundColor = state_colour[0];
		});
	}

	setColours('<?php echo (isset($_GET['platform'])?$_GET['platform']:'template')?>');
<?php
}
?>

    var day = 1;
    var dpc = 3;
    var total_days = '';

	function clickDays(pm){
    	var last_day = day + dpc - 1;
        var first_day = day;
        var first_of_next = day + dpc;
        var first_of_previous = day - dpc;
        var last_of_previous = day - 1;

    	var day_array = [];
        allDaysOff();
        if(pm == '+'){
        	day = first_of_next;
        	var i = first_of_next;
            while(i <= last_day + dpc){
        	    day_array.push(i);
        	    i++;
            }
        } else {
            if(day <= dpc ) return false;
            day = first_of_previous;
            var i = last_of_previous;
            while(i >= first_of_previous){
        	    day_array.push(i);
        	    i--;
            }
        }
        var change_array = $('daybox_'+day_array[0],'daybox_'+day_array[1],'daybox_'+day_array[2]);
        change_array.each( function(box){
			box.show();
		});

		if(day == 1){
    	    $('prev_1').style.visibility = 'hidden';
    	    $('prev_2').style.visibility = 'hidden';
    	} else {
        	if($('prev_1').style.visibility == 'hidden'){
                $('prev_1').style.visibility = 'visible';
                $('prev_2').style.visibility = 'visible';
            }
        }

        if(total_days - day < dpc){
            $('next_1').style.visibility = 'hidden';
            $('next_2').style.visibility = 'hidden';
        } else {
        	if($('next_1').style.visibility == 'hidden'){
                $('next_1').style.visibility = 'visible';
                $('next_2').style.visibility = 'visible';
            }
        }

        setHeights();

    }

    function allDaysOff(){
        var all_days = $$('div.daybox');
        all_days.each( function(box){
			box.hide();
		});
		if(total_days == '') total_days = all_days.length;
    }
</script>
