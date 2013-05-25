<div id="leftAd" style="text-align: center;">
<br>
<img src="http://www.australiangamer.com/images/250x250_ad_header.gif" border="0" /><br>
<?php
    if (@include(getenv('DOCUMENT_ROOT').'/ads/phpadsnew.inc.php')) {
        if (!isset($phpAds_context)) $phpAds_context = array();
        $phpAds_raw = view_raw ('', 2, '', '', '0', $phpAds_context);
        echo $phpAds_raw['html'];
    }
?>
<br>
<br>
</div>