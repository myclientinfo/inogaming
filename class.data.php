<?php

class Data{

	var $data_id;
	var $instance_id;
	var $data_info;
	var $instance_info;

	function Data($data_id, $instance_id){
		 if($instance_id > 0) {
			$data_id = $this->getDataIdFromInstanceId($instance_id);
			$this->data_info = $this->getSingleData($data_id, 0);
			if(isset($this->data_info['instances'])) $this->instance_info = $this->instanceInfoFromData($this->data_info, $instance_id);
			else
			$this->data_id = $this->data_info['data_id'];
			$this->instance_id = $instance_id;
		} elseif($data_id > 0){
			$this->data_info = $this->getSingleData($data_id, 0);
			$this->data_id = $data_id;
			$this->instance_id = NULL;
		}

		if(!isset($_SESSION['category_array'])){
			$_SESSION['category_array'] = $this->getCategories();
		}

	}

	function instanceInfoFromData($data_info, $instance_id){
		foreach($data_info['instances'] as $k => $i){
			if($i['instance_id'] == $instance_id) return $i;
		}
		return false;
	}

	function addSubscriber($data_id, $email){
		$query = 'INSERT INTO email_updates(data_id, email) VALUES ('.$data_id.', "'.$email.'")';
		$result = mysql_query($query);
		if($result) return 'success';
		else return mysql_error();

	}

	function saveFormData(){
		if( (int)$_POST['data_id'] == 0){
			$query = 'INSERT INTO data(owner_id, title, description, website, create_time)
				VALUES('.$_SESSION['owner_id'].', "'.$_POST['data_title'].'", "'.$_POST['data_description'].'", "'.$_POST['data_website'].'", NOW())';
		} else {
			$query = 'UPDATE data SET
				title = "'.$_POST['data_title'].'",
				description = "'.$_POST['data_description'].'",
				website = "'.$_POST['data_website'].'"
					WHERE data_id = '.$_POST['data_id'];

		}

		$result = mysql_query($query);

		if((int)$_POST['data_id']==0 ) return mysql_insert_id();
		else return $_POST['data_id'];
	}


	function getContact($instance_id){
        $query = 'SELECT * FROM contact WHERE instance_id = "'.$instance_id.'"';
        $result = mysql_query($query);
		$array = array();
        while($row = mysql_fetch_assoc($result)) $array = $row;

        return $array;
    }

	function saveOwner(){
		if( $_POST['owner_id'] == 0 ){
			$query = 'INSERT INTO owners(company_name, contact_name, username, password, email, phone, mobile) VALUES("'.$_POST['company_name'].'", "'.$_POST['contact_name'].'", "'.$_POST['username'].'", "'.$_POST['password'].'", "'.$_POST['email'].'", "'.$_POST['phone'].'", "'.$_POST['mobile'].'")';
		} else {
			$query = 'UPDATE owners SET company_name = "'.$_POST['company_name'].'",
						contact_name = "'.$_POST['contact_name'].'",
						username = "'.$_POST['username'].'",
						password = "'.$_POST['password'].'",
						email = "'.$_POST['email'].'",
						phone = "'.$_POST['phone'].'",
						mobile = "'.$_POST['mobile'].'",
						url = "'.$_POST['url'].'"
						WHERE owner_id = '.$_POST['owner_id'];
		}
		$result = mysql_query($query);
	}

	function saveImageFilename($data_id, $instance_id, $filename){
    	if($instance_id){
			$query = 'UPDATE instances SET image = "'.$filename.'" WHERE instance_id = '.$instance_id;
    	} else {
        	$query = 'UPDATE data SET image = "'.$filename.'" WHERE data_id = '.$data_id;
    	}
    	$result = mysql_query($query);
	}

	function uploadImage($instance_id){

		$image = $_FILES['image_file'];
		$relative_location = '/images/logos/';
		$location = $_SERVER['DOCUMENT_ROOT'].$relative_location;

		$new_filename = $_POST['data_id'].'_'.$instance_id.'.jpg';

		$max_width = 150;
		$max_height = 150;

		list($width, $height) = getimagesize($_FILES['image_file']['tmp_name']);
		$ratio = $height/$width;

		if ($width > $max_width) {
			$res_width = $max_width;
			$res_height = $res_width * $ratio;
		}
		else {
			$res_width = $max_width;
			$res_height = $height;
		}
		if ($res_height > $max_height) {
			$res_height = $max_height;
			$res_width = $res_height/$ratio;
		}

		$source = imagecreatefromjpeg($_FILES['image_file']['tmp_name']);
		$new_image = imagecreatetruecolor($res_width, $res_height);
		imagecopyresampled($new_image, $source, 0, 0, 0, 0, $res_width, $res_height, $width, $height);

		imagejpeg($new_image, $location.$new_filename);
		return $new_filename;

	}

	function getData(){
		if(empty($this->instance_info)){
			return $this->data_info;
		}else{
			return $this->instance_info;
		}
	}

	function getLatestChanges(){
		$query = 'SELECT c.change_type_id, c.data_id, c.text, MAX(publish_time) as last_published, d.title FROM changes AS c
					LEFT JOIN data AS d ON d.data_id = c.data_id
					GROUP BY d.data_id
					ORDER BY last_published DESC
					LIMIT 15';
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}

	function getRssChanges($data_id = false, $from = false, $to = false, $limit = false){
		$query = 'SELECT c.change_type_id, c.data_id, c.text, publish_time, d.title FROM changes AS c
					LEFT JOIN data AS d ON d.data_id = c.data_id
					WHERE 1';
        if($data_id) $query .=   ' AND c.data_id = '.$data_id;
        if($from) $query .= ' AND LEFT(c.publish_time, 10) >= '.$from;
        if($to) $query .= ' AND LEFT(c.publish_time, 10) <= '.$to;
		$query .= ' ORDER BY publish_time DESC';
		if($limit) $query .= ' LIMIT '.$limit;
        $array = array();
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}

	function getTime($time_id){
		$query = 'SELECT *, title as time_title, description as time_description FROM times WHERE time_id = '.$time_id;
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array = $row;
		return $array;
	}

	function getTimes($instance_id){
		$query = 'SELECT *, title as time_title, description as time_description FROM times WHERE instance_id = '.$instance_id;
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}

	function getDataForOwner($owner_id){
		$query = 'SELECT title, data_id, description FROM data WHERE owner_id = '.$owner_id.' ORDER BY title ASC';
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}

	function getRandomInstance(){
		$query = 'SELECT i.instance_id, i.data_id FROM instances AS i JOIN times AS t ON i.instance_id = t.instance_id WHERE i.description != "" AND t.start_time > NOW()';
		$result = mysql_query($query);

		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		if(!empty($array)){ $rand_number = mt_rand(0, count($array)-1);
			return $array[$rand_number];
		} else return false;
	}

	function getRandomData(){
		$query = 'SELECT d.data_id, d.title, d.image, d.html as data_html, d.description as data_description, d.data_id, d.title as data_title,
						c.category, c.category_short as platform
						FROM data AS d
						LEFT JOIN instances AS i ON i.data_id = d.data_id
						LEFT JOIN categories AS c ON c.category_id = i.category_id
						JOIN times AS t ON t.instance_id = i.instance_id
						WHERE 1 AND t.start_time >= NOW()
						GROUP BY d.data_id';

		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;

		if(!empty($array)){ $rand_number = mt_rand(0, count($array)-1);
			return $array[$rand_number];
		} else return $array;
	}

	function getExportForOwner($owner_id){
		$query = 'SELECT i.instance_id as vurp_id, d.title as title, d.price as t_price, i.price, p.category_short as platform, t.start_time, t.end_time FROM instances AS i
		            JOIN data AS d ON d.data_id = i.data_id
		            JOIN categories AS p ON p.category_id = i.category_id
		            JOIN times AS t ON t.instance_id = i.instance_id
		            WHERE d.owner_id = '.$owner_id.' ORDER BY title ASC';


		$result = mysql_query($query);
		if(!$result){
    		print mysql_error()."<br>";
    		print $query;
		}
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}

	function confirm(){
		$query = 'UPDATE email_updates SET confirm = 1 WHERE data_id = '.$_GET['t'].' AND MD5(email) = "'.$_GET['cid'].'"';
		$result = mysql_query($query);
	}

	function sendConfirm($data_id, $email){
		$message = "Please confirm that you wish to receive these emails by clicking on the following link: ";
		$link = '<a href="http://www.vurp.com/confirm.html?t='.$data_id.'&cid='.md5($email).'">Confirm your subscription</a>';
		$message = $message . $link;

		mail($email, 'Please confirm your subscription', $message);
	}

	function saveLog($type = 1){
		$field_array = explode(',',$_POST['fields']);
		$count_fields = count($field_array);
		$i = 1;

		//$text = 'Changes have been made to ';
		$text = '';
		foreach($field_array as $f){
			if($i != 1 && $i != $count_fields ) $text .= ', ';
			elseif( $i == $count_fields && $count_fields > 1) $text .= ' and ';
			$text .= trim($f);
			$i++;
		}
		//$text .= ' for '.$_POST['title'];
		$text .= '';
		$query = 'INSERT INTO changes(data_id, change_type_id, text, publish_time) VALUES('.$_POST['data_id'].','.$type.' ,"'.$text.'", NOW())';
		$result = mysql_query($query);

		if(!$result){
			$change_id = mysql_insert_id();
			Data::runUpdateEmail($change_id);
		} else {
			print mysql_error();
		}
	}

	function runUpdateEmail($change_id){
		$query = 'SELECT c.text, c.change_type_id, e.email, d.title FROM changes AS c
					JOIN email_updates AS e ON c.data_id = e.data_id
					JOIN data AS d ON d.data_id = c.data_id
					WHERE c.data_id = '.$change_id.'
					AND e.confirm = 1
					ORDER BY e.email';
	}

	function saveContact($instance_id){
		$query = 'DELETE FROM content WHERE instance_id = '.$instance_id;
		$result = mysql_query($query);

		$query = 'INSERT INTO contact(instance_id, name, phone, mobile, email, fax) VALUES('.$_POST['instance_id'].', "'.$_POST['contact_name'].'", "'.$_POST['contact_phone'].'", "'.$_POST['contact_mobile'].'", "'.$_POST['contact_email'].'", "'.$_POST['contact_fax'].'")';
		$result = mysql_query($query);
	}

	function getInstances($data_id){
		$query = 'SELECT instance_id, title AS instance_title, address AS instance_address, description AS instance_description FROM instances AS i
			WHERE data_id = '.$data_id;
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}

	function getInstance($instance_id){
		$query = 'SELECT instance_id, venue AS instance_venue, address AS instance_address, category_id AS instance_category_id, title AS instance_title, description AS instance_description, price AS instance_price, map AS instance_map FROM instances AS i
			WHERE instance_id = '.$instance_id;

		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array = $row;
		return $array;
	}

	function saveInstance(){
		if((int)$_POST['instance_id'] != 0){
			$query = 'UPDATE instances SET description = "'.$_POST['instance_description'].'",
				data_id = '.$_POST['data_id'].',
				city_id = '.$_POST['city_id'].',
				title = "'.$_POST['instance_title'].'",
				venue = "'.$_POST['instance_venue'].'",
				address = "'.$_POST['instance_address'].'",
				category_id = '.$_POST['instance_category_id'].',
				map = "'.$_POST['instance_map'].'",
				price = "'.$_POST['instance_price'].'"
				WHERE instance_id = '.$_POST['instance_id'];
		} else {
			$query = 'INSERT INTO instances(description, data_id, title, category_id, map, price, venue, address, city_id)
				VALUES("'.$_POST['instance_description'].'", '.$_POST['data_id'].', "'.$_POST['instance_title'].'", '.$_POST['instance_category_id'].', "'.$_POST['instance_map'].'", "'.$_POST['instance_price'].'", "'.$_POST['instance_venue'].'", "'.$_POST['instance_address'].'", '.$_POST['city_id'].')';
		}
		$result = mysql_query($query);
		if(!$result){
			print $query."<br>";
			print mysql_error().'';
			die();
		}
		if((int)$_POST['instance_id']==0 ) return mysql_insert_id();
		else return $_POST['instance_id'];
	}

	function saveTime(){
		if((int)$_POST['time_id'] != 0){
			$query = 'UPDATE times SET instance_id = "'.$_POST['instance_id'].'", title = "'.$_POST['time_title'].'", description = "'.$_POST['time_description'].'", start_time = "'.$_POST['start_time'].'", end_time = "'.$_POST['end_time'].'" WHERE time_id = '.$_POST['time_id'];
		}else{
			$query = 'INSERT INTO times( instance_id, title, description, start_time, end_time) VALUES("'.$_POST['instance_id'].'", "'.$_POST['time_title'].'", "'.$_POST['time_description'].'", "'.$_POST['start_time'].'", "'.$_POST['end_time'].'")';
		}
		$result = mysql_query($query);
		if((int)$_POST['time_id']==0 ) return mysql_insert_id();
		else return $_POST['time_id'];
	}

	function saveData(){
		if(!isset($_POST['data_id'])||$_POST['data_id']==''||$_POST['data_id']==0){
			$query = 'INSERT INTO data (owner_id, title) VALUES ('.$_SESSION['owner_id'].', "'.$_POST['data_title'].'")';
			//print $query."\n";
			$result = mysql_query($query);
			if(!$result){
				print mysql_error();
			} else {
				print 'new|';
				print mysql_insert_id();
			}
		} else {
			if(isset($_POST['instance_id'])){
				$query = 'UPDATE instances SET rating_id = "'.$_POST['instance_rating_id'].'",
								description = "'.$_POST['instance_description'].'",
								price = "'.$_POST['instance_price'].'",
								image = "'.$_POST['instance_image'].'",
								publisher = "'.$_POST['instance_publisher'].'",
								developer = "'.$_POST['instance_developer'].'"
								WHERE instance_id = '.$_POST['instance_id'];

				$time_query = 'UPDATE times SET start_time = "'.$_POST['instance_start_time'].'", end_time = "'.$_POST['instance_end_time'].'" WHERE instance_id = '.$_POST['instance_id'];
				$result = mysql_query($time_query);
				if(!$result){
					print mysql_error();
				}
			} elseif(isset($_POST['data_id'])) {
				$query = 'UPDATE data SET rating_id = "'.$_POST['data_rating_id'].'",
				title = "'.$_POST['data_title'].'",
				category_id = "'.$_POST['data_genre_id'].'",
				website = "'.$_POST['data_website'].'",
				image = "'.$_POST['data_image'].'",
				start_time = "'.$_POST['data_start_time'].'",
				end_time = "'.$_POST['data_end_time'].'",
				description = "'.$_POST['data_description'].'",
				price = "'.$_POST['data_price'].'",
				publisher = "'.$_POST['data_publisher'].'",
				developer = "'.$_POST['data_developer'].'"
				WHERE data_id = '.$_POST['data_id'];
				print 'update|'.$_POST['data_id'];
			}
			$result = mysql_query($query);
			if(!$result){
				print mysql_error();
			}
		}
	}

	function getPress($data_id){
        $query = 'SELECT *.*, t.type FROM press AS p
                    LEFT JOIN press_type AS t ON t.press_type_id = p.press_type_id
                    WHERE p.data_id = '. $data_id;

		$array = array();

        $result = mysql_query($query);

        while($row = mysql_fetch_assoc($result)) $array[$row['type']][] = $row;
		return $array;

    }

    function getDataIdFromInstanceId($instance_id){
    	$query = 'SELECT data_id FROM instances WHERE instance_id = ' . $instance_id;

		$result = mysql_query($query);

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) return $row['data_id'];
		} else {
			return false;
		}


    }

	function isInstance(){
		if(!empty($this->instance_info)) return true;
	}

	function deleteTimesForInstance($instance_id){
	    $query = 'DELETE FROM times WHERE instance_id = ' . $instance_id;
	    $result = mysql_query($query);
	}

	function deleteTime($time_id){
		$query = 'DELETE FROM times WHERE time_id = ' . $time_id;
		$result = mysql_query($query);
	}

	function deleteInstance($instance_id){
		$query = 'DELETE FROM instances WHERE instance_id = ' . $instance_id;
		$result = mysql_query($query);
		Data::deleteTimesForInstance($instance_id);
	}

	function deleteData($data_id){
		$query = 'select instance_id from instances where data_id = '.$data_id;
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)) Data::deleteInstance($row['instance_id']);
		$query = 'delete from data where data_id = '.$data_id;
		$result = mysql_query($query);
	}

	function formCreateData(){
		$use_array = array('category_id', 'owner_id', 'title', 'description', 'website', 'publisher', 'developer', 'price', 'ag_id');
		$field_string = '';
		$value_string = '';
		$i = 0;
		$ag_id = $this->getAgId($_POST['title']);

	    foreach($use_array as $val){
	    	$field_string .= ($i!=0?', ':''). $val;

	    	if($val == 'ag_id') $value_string .= ','.$ag_id;
	    	else $value_string .= ($i!=0?', ':''). "'".$_POST[$val]."'";

	    	$i++;
	    }

		$query = 'INSERT INTO data(' . $field_string . ') VALUES(' . $value_string . ')';

		$result = mysql_query($query);
	    $data_id = mysql_insert_id();
	    $this->data_info = $this->getSingleData($data_id, '0');
	    return $data_id;
	}

	function getAgId($str){
		$str = urlencode($str);
		$ag_id = file_get_contents('http://www.australiangamer.com/bindid.php?str='.$str.'&access=myaosabj');
		return $ag_id;
	}

	function formUpdateData(){

	    $use_array['d'] = array('category_id', 'title', 'description', 'image', 'website', 'publisher', 'developer', 'price', 'category_id', 'rating_id');
	    $use_array['i'] = array('title','description','image','price', 'publisher', 'developer', 'rating_id');
	    $i = 0;
	    $query = 'UPDATE '. ($_POST['edit_type']=='d'?'data':'instances') . ' SET ';
	    foreach($_POST as $k => $v){
	        if(in_array($k, $use_array[$_POST['edit_type']])){
	            $query .= ($i!=0?', ':'').$k.' = \''.$v.'\'';
	            $i++;
	        }
	    }
	    $query .= ' WHERE '.($_POST['edit_type']=='d'?'data':'instance').'_id = '.$_POST['id'];

	    $result = mysql_query($query);
	   	if(!$result)print mysql_error();

	   	$this->data_info = $this->getSingleData($this->data_id, '0');
	   	if($_POST['edit_type']=='i') $this->instance_info = $this->instanceInfoFromData($this->data_info, $_POST['id']);
	}

	function formCreateInstance($data_id, $category_id){
	    $start_time = isset($_POST['start_time'])&&$_POST['start_time']!=''?$_POST['start_time']:'0000-00-00 00:00:00';
	    $end_time = isset($_POST['end_time'])&&$_POST['end_time']!=''?$_POST['end_time']:'0000-00-00 00:00:00';

	    $query = 'INSERT INTO instances(data_id, category_id) VALUES('.$data_id.', '.$category_id.')';
	    $result = mysql_query($query);
	    $instance_id = mysql_insert_id();
	    Data::createTime($instance_id, $start_time, $end_time);
	    //$this->data_info = $this->getSingleData($_POST['id'], '0');
	}

	function createTime($instance_id, $start_time = '0000-00-00 00:00:00', $end_time = '0000-00-00 00:00:00', $title = ''){
	    $query = 'INSERT INTO times(instance_id, start_time, end_time, title) VALUES('.$instance_id.', \''.$start_time.'\', \''.$end_time.'\', \''.$title.'\')';
	    $result = mysql_query($query);
	    if($result){
			return mysql_insert_id();
		} else {
			print mysql_error();
		}

	}

	function getFirstTime($time_array){
		foreach($time_array as $time){
			$array[] = $time['instance_start_time'];
		}
		sort($array);
		return $array[0];
	}

	function getDataIdFromTitle($title){
		$query = "SELECT data_id FROM data WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(title),' ',''),':',''),'+','plus'),'&','and'),'!',''),\"'\",''),'(',''),')',''),',',''),'?',''),'#',''),'@','') = REPLACE(LOWER('".$title."'),'_','')";
		$result = mysql_query($query);
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) return $row['data_id'];
		} else {
			return false;
		}
	}

	function getFullCategories(){
		$query = 'SELECT * FROM categories WHERE active = 1 ORDER BY category_id';
		$result = mysql_query($query);
		$array = array();
		while($row = mysql_fetch_assoc($result)) $array[$row['category_id']] = $row;

		return $array;
	}


	function getSingleSafeData($data_id){
		$query = 'SELECT d.category_id AS data_genre_id, g.genre AS genre, d.start_time as data_start_time, d.end_time as data_end_time,
					d.rating_id as data_rating_id, d.image as data_image, d.developer as data_developer, d.publisher as data_publisher,
					d.description as data_description, d.price as data_price, d.website as data_website, d.data_id, d.title as data_title
					FROM data AS d
					LEFT JOIN genres AS g ON d.category_id = g.genre_id
					WHERE 1
					AND d.data_id = ' . $data_id;

		$result = mysql_query($query);

		if($result){
			if(mysql_num_rows($result)>0){
				while($row = mysql_fetch_assoc($result)) return $row;
			}
		} else {
			print mysql_error();
		}

	}

	function getSingleData($data_id, $instance_id){

		$query = 'SELECT t.time_id, t.start_time as instance_start_time, t.end_time as instance_end_time, t.title as time_title, t.description as time_description,
					d.html as data_html, d.category_id AS data_genre_id, g.genre AS genre,
					d.start_time as data_start_time, d.end_time as data_end_time,
					dr.rating_image as data_rating_image, d.rating_id as data_rating_id,
					dr.rating as data_rating, d.image as data_image, d.developer as data_developer,
					d.publisher as data_publisher, d.description as data_description, d.price as data_price,
					d.website as data_website, d.data_id, d.title as data_title, i.instance_id,
					i.title as instance_title, c.category AS instance_category,
					i.category_id as instance_category_id, i.venue as instance_venue, i.address as instance_address,
					c.category_short as instance_category_short, c.category_id as instance_category_id,
					i.rating_id as instance_rating_id, ir.rating_image as instance_rating_image,
					ir.rating as instance_rating, i.description as instance_description,
					i.html as instance_html, i.image as instance_image, i.price as instance_price,
					i.publisher as instance_publisher, i.developer as instance_developer, ci.city as city, ci.city_id,
					st.state as state, st.state_short FROM times AS t
				LEFT JOIN instances AS i ON i.instance_id = t.instance_id
				LEFT JOIN data AS d ON d.data_id = i.data_id
				LEFT JOIN categories AS c ON c.category_id = i.category_id
				LEFT JOIN genres AS g ON d.category_id = g.genre_id
				LEFT JOIN cities AS ci ON ci.city_id = i.city_id
				LEFT JOIN states AS st ON st.state_id = ci.state_id
				LEFT JOIN ratings AS dr ON d.rating_id = dr.rating_id
				LEFT JOIN ratings AS ir ON i.rating_id = ir.rating_id
					WHERE 1';
		if($instance_id != 0) $query .= ' AND i.instance_id = ' . $instance_id;
		if($data_id != 0) $query .= ' AND d.data_id = ' . $data_id;

		$result = mysql_query($query);

		$allowed_array = array( 'time' => array('instance_end_time','instance_start_time','rsvp_time','time_title', 'time_id', 'time_description'),
								'instance'=> array('instance_end_time','instance_start_time','instance_id','instance_title','instance_description','instance_html','instance_image','instance_price','city', 'state', 'instance_category','instance_category_short','instance_category_id', 'instance_publisher', 'instance_developer', 'instance_rating_id', 'instance_rating_image', 'instance_rating', 'instance_venue', 'city_id', 'state_short', 'instance_address'),
								'data' => array('data_id','data_description','data_start_time','data_end_time','data_html','data_image','data_end_time','data_start_time','release_time', 'data_website','data_title', 'data_genre_id', 'genre', 'data_publisher', 'data_developer', 'data_rating_id', 'data_rating_image', 'data_rating', 'data_price')
									);

		$data_array = array();

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $temp_array[$row['instance_id']][] = $row;

			$instance_array = array();
			foreach($temp_array as $instance_id => $instance){
				$times_array = array();
				foreach($instance as $key => $time){
					$instance_array = $time;
					$time_array = $time;
					$data_array = $time;
					$looptype = 'time';
					foreach($time_array as $k => $v){
						if(!in_array($k,$allowed_array[$looptype]))unset($time_array[$k]);
					}
					$times_array[] = $time_array;
				}
				$looptype = 'instance';
				foreach($instance_array as $k => $v){
					if(!in_array($k,$allowed_array[$looptype]))unset($instance_array[$k]);
				}
				$instance_array['times'] = $times_array;
				$instances_array[$instance_array['instance_id']] = $instance_array;

			}

			$looptype = 'data';
			foreach($data_array as $k => $v){
				if(!in_array($k,$allowed_array[$looptype]))unset($data_array[$k]);
			}
			$data_array['instances'] = $instances_array;

		} else {
			return Data::getSingleSafeData($data_id);
		}
		return $data_array;
	}

	function allDataToDateData($data_array){
		$array = array();
		foreach($data_array as $row){
			//if($row['start_time'] == $row['end_time']){
				$array[substr($row['start_time'],0,10)][] = $row;
			/*
			} else {
				$sdate = substr($row['start_time'],0,10);
				$edate = substr($row['end_time'],0,10);
				$s_array = explode('-', $sdate);
				$e_array = explode('-', $edate);
				$smd = $s_array[1].'-'.$s_array[2];
				$emd = $e_array[1].'-'.$e_array[2];
				$yr = $s_array[0];

				if($smd == '01-01' && $emd == '03-31'){
					$array[$yr.'-03-50'][] = $row;
				} elseif($smd == '04-01' && $emd == '06-30'){
					$array[$yr.'-06-50'][] = $row;
				} elseif($smd == '07-01' && $emd == '09-30'){
					$array[$yr.'-09-50'][] = $row;
				} elseif($smd == '10-01' && $emd == '12-31'){
					$array[$yr.'-12-50'][] = $row;
				} elseif($smd == '01-01' && $emd == '06-30'){
					$array[$yr.'-06-60'][] = $row;
				} elseif($smd == '07-01' && $emd == '12-31'){
					$array[$yr.'-12-60'][] = $row;
				} elseif($smd == '01-01' && $emd == '12-31'){
					$array[$yr.'-12-70'][] = $row;
				} else {
					$array[$yr.'-'.$e_array[1].'-40'][] = $row;
				}
			}
			*/
		}
		return $array;
	}

	function timeDataToDateData($data_array){
		$array = array();
		foreach($data_array as $row){
			if(substr($row['instance_start_time'],0,10) == substr($row['instance_end_time'],0,10)){
				$array[substr($row['instance_start_time'],0,10)][] = $row;
			} else {
				$sdate = substr($row['instance_start_time'],0,10);
				$edate = substr($row['instance_end_time'],0,10);
				$s_array = explode('-', $sdate);
				$e_array = explode('-', $edate);
				$smd = $s_array[1].'-'.$s_array[2];
				$emd = $e_array[1].'-'.$e_array[2];
				$yr = $s_array[0];

				if($smd == '01-01' && $emd == '03-31'){
					$array[$yr.'-03-50'][] = $row;
				} elseif($smd == '04-01' && $emd == '06-30'){
					$array[$yr.'-06-50'][] = $row;
				} elseif($smd == '07-01' && $emd == '09-30'){
					$array[$yr.'-09-50'][] = $row;
				} elseif($smd == '10-01' && $emd == '12-31'){
					$array[$yr.'-12-50'][] = $row;
				} elseif($smd == '01-01' && $emd == '06-30'){
					$array[$yr.'-06-60'][] = $row;
				} elseif($smd == '07-01' && $emd == '12-31'){
					$array[$yr.'-12-60'][] = $row;
				} elseif($smd == '01-01' && $emd == '12-31'){
					$array[$yr.'-12-70'][] = $row;
				} else {
					$array[$yr.'-'.$e_array[1].'-40'][] = $row;
				}
			}
		}
		return $array;
	}

	function getAllData($location='', $start='', $finish='', $group = false, $order = 'start_time ASC', $number='', $sort = false, $arbit = ''){

		$query = 'SELECT '.($group?'MIN(t.start_time) as start_time, MAX(t.end_time) as end_time': 't.start_time, t.end_time').', d.html as data_html, d.description as data_description,
						d.data_id, d.title as data_title, c.category, c.category_short as platform, i.instance_id, i.title as instance_title, i.description as instance_description, i.html as instance_html,
						ci.city as city, st.state as state, st.state_short, d.publisher FROM times AS t
				LEFT JOIN instances AS i ON i.instance_id = t.instance_id
				LEFT JOIN categories AS c ON c.category_id = i.category_id
				LEFT JOIN data AS d ON d.data_id = i.data_id
				LEFT JOIN cities AS ci ON ci.city_id = i.city_id
				LEFT JOIN states AS st ON st.state_id = ci.state_id
					WHERE 1';

		if(is_array($location)) $query .= ' AND '.key($location).' = \''.$location[key($location)].'\'';
		if($start != '') $query .= " AND t.start_time >= '".$start."'";
		if($finish != '') $query .= " AND t.end_time <= '".$finish."'";
		if($arbit != '')$query .= " AND ". $arbit;
		if($group) $query .= ' GROUP BY '.$group;
		if($order) $query .= ' ORDER BY '.$order;
		if($number != '') $query .= " LIMIT ".$number."";
echo $query;
		$result = mysql_query($query);
		$data_array = array();
		if(!$result){
    		print "".mysql_error()."<br>
    		".$query."<br>";
    	}

		if(mysql_num_rows($result)==0) return $data_array;

$allowed_array = array( 'time' => array('end_time','start_time','rsvp_time','time_title', 'time_description'),
								'instance'=> array('instance_id','instance_title','instance_description','instance_html','instance_image','instance_price','city', 'state', 'instance_category','instance_category_id', 'instance_publisher', 'instance_developer', 'instance_rating_id', 'instance_rating_image', 'instance_rating', 'short_state'),
								'data' => array('data_id','data_description','data_html','data_image','data_price','release_time', 'website','data_title', 'genre_id', 'genre', 'data_publisher', 'data_developer', 'data_rating_id', 'data_rating_image', 'data_rating')
									);
		if($sort){


			if(mysql_num_rows($result) > 0){

				while($row = mysql_fetch_assoc($result)) $temp_array[$row['data_id']][$row['instance_id']][] = $row;

				$instance_array = array();

    			foreach($temp_array as $instance_id => $instance){

    				$times_array = array();

    				foreach($instance as $key => $time){
    					$instance_array = $time;
    					$time_array = $time;
    					$data_array = $time;
    					$looptype = 'time';
    					foreach($time_array as $k => $v){
    						if(!in_array($k,$allowed_array[$looptype]))unset($time_array[$k]);
    					}
    					$times_array[] = $time_array;
    				}
    				$looptype = 'instance';
    				foreach($instance_array as $k => $v){
    					if(!in_array($k,$allowed_array[$looptype]))unset($instance_array[$k]);
    				}
    				$instance_array['times'] = $times_array;
    				$instances_array[$instance_array['instance_id']] = $instance_array;
    			}

    			$looptype = 'data';
    			foreach($data_array as $k => $v){
    				if(!in_array($k,$allowed_array[$looptype]))unset($data_array[$k]);
    			}
    			$data_array['instances'] = $instances_array;

			} else {
				return false;
			}
		} else {


			if(mysql_num_rows($result) > 0){
				while($row = mysql_fetch_assoc($result)){
					$data_array[] = $row;
				}
			} else {
				return false;
			}
		}

		return $data_array;
	}






	function getNew($number = 10){

		$query = "SELECT * FROM data ORDER BY create_time DESC LIMIT ".$number;

		$result = mysql_query($query);

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[] = $row;
		} else {
			return false;
		}

		return $array;




	}


	function getLatestReleases($limit = false){
		$query = 'SELECT t.*, g.title as title, g.title as data_title, p.category_short as platform FROM times AS t
				JOIN instances AS i ON i.instance_id = t.instance_id
				JOIN data AS g ON g.data_id = i.data_id
				JOIN categories AS p ON p.category_id = i.category_id
				WHERE t.end_time = t.start_time
				AND t.end_time < NOW()
				AND t.end_time != "0000-00-00 00:00:00"
				AND t.end_time = t.start_time
				ORDER BY t.start_time DESC';
		if($limit) $query .= ' LIMIT '.$limit;
		$result = mysql_query($query);
		if(!$result){
    		print mysql_error()."<br><br>";
    		print $query;
        }
		$array=array();
		while($row = mysql_fetch_assoc($result)) $array[] = $row;
		return $array;
	}


	function getDataByType($location = ''){
		$category_array = $this->getCategories();
		$i=0;
		$query = '';

		$today = Site::getTime('start_day', '', 'MYSQL');

		foreach($category_array as $k => $v){

			if($i != 0) $query .= 'UNION';
				$query .= '(SELECT MIN(t.start_time) as start_time, MAX(t.end_time) as end_time, ci.city, d.html as data_html, d.description as data_description,
										d.data_id, d.title as data_title, i.instance_id, i.title as instance_title, i.description as instance_description, i.html as instance_html,
				            c.category_id, c.category,
				            ci.city as city, st.state as state, st.state_short FROM times AS t
								LEFT JOIN instances AS i ON i.instance_id = t.instance_id
								LEFT JOIN data AS d ON d.data_id = i.data_id
				        JOIN categories AS c ON c.category_id = i.category_id
								LEFT JOIN cities AS ci ON ci.city_id = i.city_id
								LEFT JOIN states AS st ON st.state_id = ci.state_id
				WHERE i.category_id = '.$k.'
					AND t.start_time >= \''.$today.'\'
				GROUP BY i.instance_id
				LIMIT 1)';

				$i++;
		}
		$result = mysql_query($query);

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[$row['category']] = $row;
		} else {
			return false;
		}
		return $array;

	}

	function getDataByDate($location = ''){
		$query = 'SELECT MIN(start_time) as start_time, MAX(end_time) as end_time, left(start_time,10) as date, d.html as data_html, d.description as data_description,
										d.data_id, d.title as data_title, i.instance_id, i.title as instance_title, i.description as instance_description, i.html as instance_html,
				            c.category_id, c.category,
				            ci.city as city, st.state as state FROM times AS t
								LEFT JOIN instances AS i ON i.instance_id = t.instance_id
								LEFT JOIN data AS d ON d.data_id = i.data_id
				        JOIN categories AS c ON c.category_id = i.category_id
								LEFT JOIN cities AS ci ON ci.city_id = i.city_id
								LEFT JOIN states AS st ON st.state_id = ci.state_id
				WHERE 1
				    AND start_time = end_time
				    AND start_time >= \''.Site::getTime('start_day', '', 'MYSQL').'\'
				GROUP BY i.instance_id';

		$result = mysql_query($query);

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[$row['date']][] = $row;
		} else {
			return false;
		}

		return $array;

	}



	function getCategories(){
		if(isset($_SESSION['category_array'])) return $_SESSION['category_array'];
		else {

			$query = 'SELECT * FROM categories WHERE active = 1 ORDER BY category_id';
			$result = mysql_query($query);

			if(mysql_num_rows($result) > 0){
				while($row = mysql_fetch_assoc($result)) $array[$row['category_id']] = $row['category'];
			} else {
				return false;
			}

			return $array;
		}
	}


	function getGenres(){
		$query = 'SELECT * FROM genres WHERE active = 1 ORDER BY genre';
		$result = mysql_query($query);

		$array = array(0=>'Unknown');

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[$row['genre_id']] = $row['genre'];
		} else {
			return false;
		}
		return $array;
	}

	function getOwners(){
		$query = 'SELECT owner_id, company_name FROM owners WHERE owner_status_id >= 1 ORDER BY company_name';
		$result = mysql_query($query);

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[$row['owner_id']] = $row['company_name'];
		} else {
			return false;
		}
		return $array;
	}

	function getDataOwnerInfo($data_id){
		$query = 'SELECT o.* FROM owners AS o LEFT JOIN data AS d ON d.owner_id = o.owner_id WHERE d.data_id = "'.$data_id.'"';

		$result = mysql_query($query);

		if($result){
			while($row = mysql_fetch_assoc($result)) return $row;
		} else {
    		print "<!-- $query\n\n".mysql_error()." -->";
			return false;
		}
	}

	function getRatings(){
		$query = 'SELECT rating_id, rating FROM ratings WHERE active = 1 ORDER BY rating_id';
		$result = mysql_query($query);

		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) $array[$row['rating_id']] = $row['rating'];
		} else {
			return false;
		}
		return $array;
	}

	function getReleaseTime(){
		$i=0;
		if(!isset($this->data_info['instances'])) return false;
		$first_instance = current($this->data_info['instances']);

		$current_time = $first_instance['times'][0]['start_time'];
		if(count($this->data_info['instances'])==1)return $current_time;
		foreach($this->data_info['instances'] as $k => $v){
			if( $i==0 ) $time = $v['times'][0]['start_time'];
			if( $time == $v['times'][0]['start_time'] ) $time = $v['times'][0]['start_time'];
			else return false;
			$i++;
		}
		return $time;
	}

	function getReleaseEndTime(){
		$i=0;
		if(!isset($this->data_info['instances'])) return false;
		$first_instance = current($this->data_info['instances']);

		$current_time = $first_instance['times'][0]['end_time'];
		if(count($this->data_info['instances'])==1)return $current_time;
		foreach($this->data_info['instances'] as $k => $v){
			if( $i==0 ) $time = $v['times'][0]['end_time'];
			if( $time == $v['times'][0]['end_time'] ) $time = $v['times'][0]['end_time'];
			else return false;
			$i++;
		}
		return $time;
	}


}

?>