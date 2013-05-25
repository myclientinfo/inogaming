<div id="rightAd" style="text-align: center; margin: 2px;">
<?php
    if (@include(getenv('DOCUMENT_ROOT').'/ads/phpadsnew.inc.php')) {
        if (!isset($phpAds_context)) $phpAds_context = array();
        $phpAds_raw = view_raw ('', 3, '', '', '0', $phpAds_context);
        echo $phpAds_raw['html'];
    }
?>
</div>
