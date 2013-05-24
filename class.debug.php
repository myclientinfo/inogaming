<?php

class Debug{

	var $debug_on = false;
	
	function Debug($debug_on){
		if($debug_on) print "Debug Mode On";
		
	}
	
	function debugStatus($debug_on){
		$this->debug_on = $debug_on;
	}
	
	function printr($value){
		if(!$this->debug_on)return false;
		print "<pre style='background-color: white; color: black; border: 1px solid black; text-align: left;'>";
		print_r($value);
		print "</pre>";
	
	}
	

}

?>