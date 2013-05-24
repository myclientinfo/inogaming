<?php

define('SECONDS_IN_HOUR', 3600);
define('SECONDS_IN_DAY', 86400);
define('SECONDS_IN_WEEK', 604800);
define('TEMPLATE_PATH', 'templates/');

define('DAY_MONTH_YEAR', 'j F Y');

class Site{




	function getPlatformColours(){
		return array(
						'ps3'=>array('#ee0d0d','#f68686'),
						'psp'=>array('#cc0066','#e57fb2'),
						'ps2'=>array('#b92c2c','#dc9595'),
						'gba'=>array('#003399','#7f99cc'),
						'pc'=>array('#ff9900','#ffcc7f'),
						'x360'=>array('#339900','#99cc7f'),
						'nds'=>array('#0066cc','#7fb2e5'),
						'wii'=>array('#0099ff','#7fccff')
					);
	}

	function searchArray($array, $field, $value, $return = false){
		foreach($array as $k => $v){
			if($v[$field]==$value){
				if(!$return) return $v;
				else return $v[$return];
			}
		}
		return false;
	}

	function getTime($type, $time = '', $return_format = 'PHP'){
		if($time == '') $time = time();

		if($type == 'week_from_time'){
			$new_time = $time + SECONDS_IN_WEEK;
		} elseif($type == 'start_this_week') {
			$day_of_week = date('N', $time);
			$day = date('j', $time);
			$start_day_of_week = $day - ($day_of_week - 1);
			$new_time = mktime(0, 0, 1, date('n', $time), $start_day_of_week, date('Y', $time));
		} elseif($type == 'end_this_week') {
			$day_of_week = date('N', $time);
			$day = date('j', $time);
			$end_day_of_week = $day + (7 - $day_of_week);
			$new_time = mktime(24,59,59,date('n', $time), $end_day_of_week, date('Y', $time));
		} elseif($type == 'plus_three_weeks'){
			$new_time = $time + (SECONDS_IN_WEEK * 3);
		} elseif($type == 'end_this_month'){
			// todo
		} elseif($type == 'start_this_month'){
			// todo
		} elseif($type == 'start_day'){
			$new_time = mktime(0,0,0,date('n', $time), date('j', $time), date('Y', $time));
		}

		if($return_format == 'PHP') return $new_time;
		else return date('Y-m-d H:i:s', $new_time);
	}

	function createLinkFromTitle($title){
    	$title = str_replace(' ','_', $title);
    	$title = str_replace(':','', $title);
    	$title = str_replace('/','', $title);
    	$title = str_replace('!','', $title);
    	$title = str_replace(')','', $title);
    	$title = str_replace('(','', $title);
    	$title = str_replace('?','', $title);
    	$title = str_replace('#','', $title);
    	$title = str_replace('@','', $title);
    	$title = str_replace(',','', $title);
    	$title = str_replace('.','', $title);
    	$title = str_replace('&','and', $title);
    	$title = str_replace('+','plus', $title);
    	$title = str_replace("'",'', $title);
        return strtolower($title);
    }

	function getTimeString($start, $end = 0, $short = false, $standard = false){
		
		if($end == '0000-00-00 00:00:00') $end = 0;
		$string = '';
		$start_mod = strtotime($start);
		$end_mod = strtotime($end);

		if(date('Ymd',$start_mod)==date('Ymd',$end_mod)){
			if($short) return date('j F', $start_mod);
			elseif($standard) return date('d/m/Y', $start_mod);
			else return date('l, jS \of F, Y', $start_mod);
		} else {
    		if(date('Ym',$start_mod)==date('Ym',$end_mod)){
	    		$string = date('F Y',$start_mod);
    		} else {
	    		if(date('md',$start_mod)== '0101' && date('md',$end_mod) == '0331'){
    				$string = 'Q1 '.date('y',$end_mod);
    			} elseif(date('md',$start_mod)== '0401' && date('md',$end_mod) == '0630'){
    				$string = 'Q2 '.date('y',$end_mod);
    			} elseif(date('md',$start_mod)== '0701' && date('md',$end_mod) == '0930'){
    				$string = 'Q3 '.date('y',$end_mod);
    			} elseif(date('md',$start_mod)== '1001' && date('md',$end_mod) == '1231'){
    				$string = 'Q4 '.date('y',$end_mod);
    			} elseif(date('md',$start_mod)== '0101' && date('md',$end_mod) == '0630'){
    				$string = 'H1 '.date('y',$end_mod);
    			} elseif(date('md',$start_mod)== '0107' && date('md',$end_mod) == '1231'){
    				$string = 'H2 '.date('y',$end_mod);
    			} elseif(date('md',$start_mod)== '0101' && date('md',$end_mod) == '1231'){
    				$string = date('Y',$end_mod);
    			} else {
	    			$start_string = date('l, jS \of F', $start_mod);
	    			$end_string = ' to '. date('l, jS \of F', $end_mod);
	    			$string = $start_string.$end_string;
    			}
    		}
		}

		return $string;

	}
	
	function getItinararyTitleString($array){
		//$GLOBALS['debug']->printr($array);
		$time_array = array();
		foreach($array as $time){
			$time_array[] = substr($time['instance_start_time'], 0, 10);
			$time_array[] = substr($time['instance_end_time'], 0, 10);
		}
		
		array_unique($time_array);
		sort($time_array);
		
		if(count($time_array) == 1) return date(DAY_MONTH_YEAR, strtotime($time_array[0]));
		else return date(DAY_MONTH_YEAR, strtotime($time_array[0])) . ' to ' . date(DAY_MONTH_YEAR, strtotime(end($time_array)));
		
		//$GLOBALS['debug']->printr($time_array);
		
	}

	function getDateString($date, $format){
		$time_array = explode('-',$date);

		if($time_array[2]>31){
			if($time_array[2]==40){
				$months = Site::getMonthsArray();
				$month = $months[(int)$time_array[1]];
				return 'In '.$month;
			} elseif($time_array[2]==50) {
				if($time_array[1]==3) return 'Quarter 1';
				if($time_array[1]==6) return 'Quarter 2';
				if($time_array[1]==9) return 'Quarter 3';
				if($time_array[1]==12) return 'Quarter 4';
			} elseif($time_array[2]==60) {
				if($time_array[1]==6) return 'Half 1';
				if($time_array[1]==12) return 'Half 2';
			} elseif($time_array[2]==70) {
				return 'In '.$time_array[0];
			}
		}
		$date = strtotime($date);
		return date($format, $date);

	}

	function comparePlatforms($platforms, $field){

    }

	function getDataReleaseString($platforms){
    	$s_array = array();
    	$e_array = array();
    	foreach($platforms as $p){
        	$s_array[] = $p['instance_start_time'];
        	$e_array[] = $p['instance_end_time'];
        }

        $s_count = count(array_unique($s_array));
        $e_count = count(array_unique($e_array));
        if($s_count == 1 && $e_count == 1){
            return Site::getTimeString($s_array[0], $e_array[0]);
        } else {
            return 'See specific platforms';
        }
    }

	function getMonthsArray(){
		$array = array('','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		unset($array[0]);
		return $array;
	}
}

?>