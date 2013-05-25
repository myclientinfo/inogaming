<div id="<?=$div_id?>" class="leftDiv">

	<div id="<?=$div_id?>_header" class="leftDivHeader"><span><?=$div_title?></span></div>
	<div class="leftDivContent">
	
	<?php 
	if(is_array($content)){
	echo '<ul>';
		foreach($content as $value){ ?>
			<li><?=(isset($value['link'])?'<a href="'.$value['link'].'">':'')?>
			<?=$value['title']?><?=(isset($value['link'])?'</a>':'')?></li>
	<?php 
		}
	echo '</ul>';
	}
	else {
		echo $content;
	}
	?>
	
	</div>
</div>