<?php
include_once('api_config.php');

echo Data::addSubscriber($_POST['data_id'], $_POST['email']);
?>