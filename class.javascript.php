<?php

class Javascript{

	var $is_admin;
	var $admin_javascript;
	var $date_string = '<script language="Javascript" src="/js/date_picker.js"></script>';

	function Javascript($is_admin = false){
		if($this->hasAdmin()){
			$this->is_admin = $is_admin;
		}
	}

	function hasAdmin(){
		$current_file = $_SERVER['PHP_SELF'];

		$array = array('/admin_edit.html', '/admin_edit.php','/admin.html', '/admin.php','/admin_list.html','admin_list.php');
		if(in_array($current_file, $array)){
			return true;
		}
		return false;
	}

	function getJavascriptString(){
		$javascript_string = '';
		if($this->is_admin){
			$javascript_string .= $this->date_string;
		}
		return $javascript_string;
	}






}

?>