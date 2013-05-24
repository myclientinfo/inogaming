<?php

class RSS{
	
	var $XML;
	var $rssArray;
	
	function RSS($loc=false){
		if($loc){
			$this->XML = file_get_contents($loc);
			$this->rssArray = $this->parseXMLintoarray($this->XML);
		}
	}
	
	
	
	function getItems($number = false){
		$itemArray = $this->rssArray['rss']['channel']['item'];
		if(!$number || $number >= count($itemArray)) return $itemArray;
		else{
			for($i=0;$i<$number;$i++){
				$array[] = $itemArray[$i];
			}
			return $array;
		}
	}
	
	function readxmlfile($xmlfile){ // reads XML file in and returns it
		$xmlstream =fopen($xmlfile,r);
		$xmlraw=fread($xmlstream,1000000);
		fclose($xmlstream);
		return $xmlraw;
	}

	function parseXMLintoarray ($xmldata){ // starts the process and returns the final array
		//print $xmldata;
		$xmlparser = xml_parser_create();
		xml_parser_set_option($xmlparser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($xmlparser, $xmldata, $arraydat);
		xml_parser_free($xmlparser);
		$semicomplete = $this->subdivide($arraydat);
		$complete = $this->correctentries($semicomplete);
		return $complete;
	}

	function subdivide ($dataarray, $level = 1){
		foreach ($dataarray as $key => $dat){
			if ($dat['level'] === $level && $dat['type'] === "open"){
				$toplvltag = $dat['tag'];
			} elseif ($dat['level'] === $level && $dat['type'] === "close" && $dat['tag']=== $toplvltag){
				$newarray[$toplvltag][] = $this->subdivide($temparray,($level +1));
				unset($temparray,$nextlvl);
			} elseif ($dat['level'] === $level && $dat['type'] === "complete"){
				
				//modified by Matt to stop it breaking
				if (isset($newarray[$dat['tag']]) && is_array($newarray[$dat['tag']])){
					$newarray[$dat['tag']][] = $dat['value'];
				} elseif (isset($newarray[$dat['tag']]) && !is_array($newarray[$dat['tag']])){
					$newarray[$dat['tag']] = array($newarray[$dat['tag']], $dat['value']);
				} else {
					$newarray[$dat['tag']]=(isset($dat['value'])?$dat['value']:'');
				}
				
			} elseif ($dat['type'] === "complete"||$dat['type'] === "close"||$dat['type'] === "open"){
				$temparray[]=$dat;
			}
		}
		return $newarray;
	}
	
	function correctentries($dataarray){
		if (is_array($dataarray)){
			$keys =  array_keys($dataarray);
			if (count($keys)== 1 && is_int($keys[0])){
				$tmp = $dataarray[0];
				unset($dataarray[0]);
				$dataarray = $tmp;
			}
			$keys2 = array_keys($dataarray);
			foreach($keys2 as $key){
				$tmp2 = $dataarray[$key];
				unset($dataarray[$key]);
				$dataarray[$key] = $this->correctentries($tmp2);
				unset($tmp2);
			}
		}
		return $dataarray;
	}
	
	function arrayIntoXML( $Variable, $VariableName, $forceVariable = '', $hasSub = false) {
		if(!isset($xml))$xml = '';
		$VariableName = str_replace(' ','_',$VariableName);
		if ( gettype($Variable) == "array" ) {
			
			if(isset($forceVariable) && $forceVariable==''){
				$xml .= "<{$VariableName}>\n";
				$forceSub = false;
			}
			else $forceSub = true;
			
			foreach ( $Variable as $key => $value ) {
				
				if ( gettype($Variable[$key]) == "array" ) {
				
					$keys = array_keys($Variable[$key]);
					$nonNum = true;
					
					foreach($keys as $temp){
						if(!is_numeric($temp))$nonNum = false;
					}
					
					if($forceSub){
						$xml .= RSS::arrayIntoXML($Variable[$key], $VariableName);
					}
					else{
						if($nonNum) $xml .= RSS::arrayIntoXML($Variable[$key], $key, $VariableName);
						else $xml .= RSS::arrayIntoXML($Variable[$key], $key);
					}
					
				}
				else{
					if($forceSub) $xml .= "\t<{$VariableName}>{$value}</{$VariableName}>\n";
					else $xml .= "\t<{$key}>{$value}</{$key}>\n";
				}
			}
			if(isset($forceVariable) && $forceVariable=='') $xml .= "</{$VariableName}>\n";
		} else {
			$xml = "<{$key}>{$value}</{$key}>";
		}
		return $xml;
	}

} 

?>