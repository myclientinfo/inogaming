<?php
include_once('api_config.php');

Data::deleteInstance($_POST['data_id'], $_POST['platform']);
?>