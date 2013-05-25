
<?php

?>
<ul style="padding-left: 15px;margin: 0px;">
<?
foreach($content as $ch){
$ch['text'] = str_replace($ch['title'],'<a href="/games/'.str_replace(' ','_', $ch['title']).'.html">'.$ch['title'].'</a>',$ch['text']);
?>
<li style=""><?=$ch['text']?> changed for <a href="/games/<?=Site::createLinkFromTitle($ch['title'])?>/"><?=$ch['title']?></a><br></li>
<?
}
?>
</ul>