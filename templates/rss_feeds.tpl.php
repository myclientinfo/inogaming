
<br>

<script>
function updatePanel(e){
    var decode_to = {any_time: 'any time', today: 'today', one_week: 'one week from now', one_month: 'this time next month', next_month: 'the end of next month'};
    var decode_from = {any_time: 'any time', today: 'today', one_week: ' one week ago', one_month: 'this time last month', next_month: 'the start of next month'};
    var fields = $('rss_order_by','rss_platform','rss_to','rss_from');
    var panel = $('commentary_panel');
    var platforms = {<?php foreach($platform_array as $k => $cat){?><?php echo $k!=1?', ':'' ?><?php echo $cat['category_id']?>: '<?php echo strtoupper($cat['category_short']) ?>'<?php }?>}
    var platform_id = fields[1].value;
    var commentary_text = '';

    commentary_text = 'All titles ';
    if(platform_id!='all'){
        commentary_text += ' for ' + platforms[platform_id];
    }
    commentary_text += ' from '+decode_from[fields[3].value];
    commentary_text += ' to '+decode_to[fields[2].value];
    commentary_text += ' and sorted by '+fields[0].value;
    panel.innerHTML = commentary_text;
}

function updateFeedLink(){
    var link_text = 'http://www.vurp.com/api/feed.rss?';
    var link_variables = '';
    if($('rss_platform').value!='all'){
        link_variables += '&platform=' + $('rss_platform').value;
    }
    if($('rss_from').value!='any_time'){
        link_variables += '&from=' + $('rss_from').value;
    }
    if($('rss_to').value!='any_time'){
        link_variables += '&to=' + $('rss_to').value;
    }
    if($('rss_order_by').value!='release'){
        link_variables += '&order_by=' + $('rss_order_by').value;
    }
    if(link_variables.length>1){
        link_variables = link_variables.substr(1);
    }
    $('feed_link_text').innerHTML = link_text + link_variables;
    $('feed_link_text').value = link_text + link_variables;
}

function resetCustom(){
    $('commentary_panel').innerHTML = '';
    $('feed_link_text').innerHTML = '';
    $('feed_link_text').value = '';
    $('rss_from').selectedIndex = 1;
    $('rss_to').selectedIndex = 0;
    $('rss_platform').selectedIndex = 0;
    $('rss_order_by').selectedIndex = 0;
}

function showHide(){
    $('custom_box').style.display = $('custom_box').style.display == 'none' ? 'block' : 'none';
}
</script>
<div class="block">
	<a href="/api/feed.rss"><img src="/images/icon_rss.gif" border="0" align="left"></a> <div>Vurp Release List Content - All</div>
	<div style="clear: both;"></div>
</div>
<!--
<div class="block">
	<a href="/api/changes.rss"><img src="/images/icon_rss.gif" border="0" align="left"></a> <div>Vurp Change History - All</div>
	<div style="clear: both;"></div>
</div>

<div class="block">
	<div>Vurp single platform -
	<?php foreach($platform_array as $k => $cat){?><?php echo $k!=1?', ':'' ?><a href="/api/feed.rss?platform=<?php echo $cat['category_id']?>" style="font-weight: bold; text-decoration: none;"><?php echo trim($cat['category_short']) ?></a><?php }?></div>
	<div style="clear: both;"></div>
</div>-->
<div id="custom_rss_container" style="<?php if(!isset($_GET['spaz'])){ ?>display: none;<?php } ?>margin-top: 15px;">
	<div onclick="showHide()" style="cursor: hand; cursor: pointer; font-weight: bold;">:: custom rss ::</div>
	<div id="custom_box" style="display: none; margin-top: 10px;">
	<form action="" method="post" onsubmit="return false;">
	<select name="rss_from" id="rss_from" style="float: left; font-size: 8pt; width: 50px;" onchange="updatePanel(this);updateFeedLink();"><option value="today" selected>now</option><option value="any_time">any time</option><option value="one_week">one week</option><option value="one_month">one month</option><option value="next_month">next month</option></select>
	<div style="width: 15px; float: left;text-align: center;">to</div>
	<select name="rss_to" id="rss_to" style="float: left; font-size: 8pt; width: 50px;" onchange="updatePanel(this);updateFeedLink();"><option value="today">now</option><option value="any_time">any time</option><option value="one_week">one week</option><option value="one_month">one month</option><option value="next_month">next month</option></select>
	<select name="rss_platform" id="rss_platform" style="float: left; font-size: 8pt; width: 50px;" onchange="updatePanel(this);updateFeedLink();"><option>all</option><?php foreach($platform_array as $k => $cat){?><option value="<?php echo $cat['category_id']?>"><?php echo $cat['category_short'] ?></option><?php } ?></select>
	<select name="rss_order_by" id="rss_order_by" style="float: left; font-size: 8pt; width: 50px;" onchange="updatePanel(this);updateFeedLink();"><option>title</option><option>release</option><option>platform</option><option>publisher</option></select>
    <input type="submit" value="clr" onclick="resetCustom();return false;" style="border: 1px solid black; width: 25px; font-size: 8pt;">
	</form>
	<div id="commentary_panel" style="margin-top: 10px;"></div>
	<div id="feed_link" style="margin-top: 10px;">
        <textarea id="feed_link_text" name="feed_link_text" style="width: 250px; height: 30px;"></textarea>
	</div>
	</div>
</div>