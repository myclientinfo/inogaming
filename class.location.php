<?php

class Location{
	
	var $country;
	var $state;
	var $city;
	var $location_type;
	var $debug_on = true;
	
	function Location($location){
		$this->setLocation($location);
	}
	
	function setLocation($location){
		
		if($location_data = $this->getCity($location)){
		} else if($location_data = $this->getState($location)) {
		} else if($location_data = $this->getState($location)) {
		} else {
			$location_data = array('city' => NULL, 'state' => NULL, 'country' => NULL, 'location_type' => NULL);
		}
		
		foreach($location_data as $k => $v){
			$this->$k = $v;
		}
	
	}
	
	function getCity($location){
		$query = 'SELECT city, state, country, "city" as location_type FROM cities AS ci
			JOIN states AS s ON ci.state_id = s.state_id
			JOIN countries AS co ON co.country_id = s.country_id
			WHERE city = \''.$location.'\'';
		
		$result = mysql_query($query);
		
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) return $row;
		} else {
			return false;
		}
	}
	
	function getState($location){
		$query = 'SELECT NULL as city, state, country, "state" as location_type FROM states AS s
			JOIN countries AS co ON co.country_id = s.country_id
			WHERE state = \''.$location.'\'';
			
		$result = mysql_query($query);
		
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) return $row;
		} else {
			return false;
		}
	}
	
	function getCountry($location){
		$query = 'SELECT NULL as city, NULL as state, country, "country" as location_type FROM countries AS c
			WHERE state = \''.$location.'\'';
			
		$result = mysql_query($query);
		
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) return $row;
		} else {
			return false;
		}
	}
	
	function isValid(){
		$location_type = $this->location_type;
		
		if(!is_null($this->$location_type) && strlen($this->$location_type) >= 1) return true;
		else return false;
		
		
	}
	
	function getLocation(){
		$location_type = $this->location_type;
		return array($location_type => $this->$location_type);
	}
	
	function getCities(){
		$query = 'SELECT * FROM cities WHERE active = 1';
			
		$result = mysql_query($query);
		
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[$row['city_id']] = $row['city'];
		} else {
			return false;
		}
		return $array;
	}
	
	function getCapitalLocations(){
        $query = 'SELECT s.state, s.state_id, s.state_short, c.city, c.city_id, c.capital FROM cities AS c LEFT JOIN states AS s ON s.state_id = c.state_id ORDER by state ASC, city ASC';
        $array = array();
        $result = mysql_query($query);
        
        while($row = mysql_fetch_assoc($result)){
        	if($row['capital']==1) $array['capital'][$row['state_id']] = $row;
            else $array['else'][$row['state_id']][] = $row;
		}
		sort($array['capital']);
        return $array;
    }
	
	function getStates(){
		$query = 'SELECT * FROM states WHERE active = 1';
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[$row['state_id']] = $row['state'];
		return $array;
	}
	
}

?>