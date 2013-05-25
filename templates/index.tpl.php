<?
header("content-type:text/html;charset=utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head><meta http-equiv="content-type" content="text/html;charset=utf-8">
	<title><?=$title?></title>
	<meta name="description" content="Gamers Events is the number one listing for video game and gaming events all round Australia - Expos, LAN's, tours, shows, parties, and more!">
	<meta name="keywords" content="australian, gamer, events, gaming, australia, videogames, expo, lan, tours, show, party, exhibition, video games">
	<link rel="stylesheet" href="/js/style.css">
	<?=$script?>
	<script type="text/javascript" src="/js/prototype.js"></script>
	<script language="JavaScript">
	var actual_left_height;
	function setHeights(){
		if($('bodyLeft').offsetHeight < $('bodyRight').offsetHeight){
			$('bodyLeft').style.height = $('bodyRight').offsetHeight+'px';
		}
		if($('bodyLeft').offsetHeight > $('bodyRight').offsetHeight){
			console.log('this');
			$('bodyLeft').style.height = actual_left_height+'px';
			
		}
	}

	var newwindow;
	function popUp(url)
	{
		newwindow=window.open('/popup.php?pic=' + url, 'popUp', 'width=100,height=100');
		if (window.focus) {newwindow.focus()}
	}
	onload = function() {
		actual_left_height = $('bodyLeft').offsetHeight;
		setHeights();
		
	}
	
	window.onresize = function(){
		position_logo();	
}
	//Event.observe(window, 'resize', position_logo);
	
	var position_logo = function(){
		var header_position = $('header_top').cumulativeOffset();
		$('logo_overlay').style.left = (header_position[0] - 30) + 'px';
		$('logo_overlay').style.display = 'block';
	}
	
	</script>
    
</head>

<body<?=$body_attributes?>>

<?=$body_content?>
<div align="center" id="footerContainer">
<div class="footerHeader">
</div>
<br />
<a href="http://www.vurp.com/index.html" class="footerLinks" style="padding-left: 0px;">home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="http://www.vurp.com/upcoming_events.html" class="footerLinks">upcoming events</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="http://www.vurp.com/recent_events.html" class="footerLinks">recent events</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="http://www.vurp.com/links.html" class="footerLinks">links</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="http://www.vurp.com/about_us.html" class="footerLinks">about us</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="http://www.vurp.com/contactus.html" class="footerLinks">contact us</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="http://www.vurp.com/admin.html" class="footerLinks" style="padding-right: 0px; border-right-width: 0px;">log in</a>
<br /><br />
<span class="footerCopy">GamersEvents.com &copy; <?php echo date('Y') ?></span>
</div>
<br /><br />
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2187522-5";
urchinTracker();
</script>
</body>


</html>