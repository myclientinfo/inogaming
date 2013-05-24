<?php

class Auth{

    function Auth(){
        //
    }

    function isLoggedIn(){
        if(Auth::checkSession() && Auth::checkLocalCookie()){
            Auth::setLocalCookie($_SESSION['owner_id']);
            //$GLOBALS['debug']->printr('session and cookie');
            return true;
        } elseif(Auth::checkSession() && !Auth::checkLocalCookie()) {
	        //$GLOBALS['debug']->printr('session, but no cookie');
            Auth::setLocalCookie($_SESSION['owner_id']);
            return true;
        } elseif(!Auth::checkSession() && Auth::checkLocalCookie()) {
        	//$GLOBALS['debug']->printr('cookie, but no session');
        	$owner_id = $_COOKIE['vurp_owner_id'];
            Auth::setLocalCookie($owner_id);
            $auth_array = Auth::loadOwner($owner_id);
            Auth::setSession($auth_array);
            return true;
        } else {
        	//$GLOBALS['debug']->printr('neither session nor cookie');
            return false;
        }
    }

    function checkLocalCookie(){
        if(isset($_COOKIE['vurp_owner_id'])) return true;
        else return false;
    }

    function checkSession(){
        if(isset($_SESSION['owner_id'])) return true;
        else return false;
    }

    function setSession($auth_array){
        $_SESSION['owner_id'] = $auth_array['owner_id'];
        $_SESSION['owner_info'] = $auth_array;
    }

    function loadOwner($owner_id){
        $query = 'SELECT * FROM owners WHERE owner_id = '.$owner_id.' LIMIT 1';
        $result = mysql_query($query);
        if(mysql_num_rows($result)>0){
            while($row = mysql_fetch_assoc($result))return $row;
        } else return false;
    }

    function logOut(){
    	setcookie ( 'vurp_owner_id', '', time()-3600);
    	$_COOKIE = array();
    	$_SESSION = array();
    }

    function verifyOwner(){
        $un = $_POST['username'];
        $pw = $_POST['password'];
        if($un==''||$pw==''){
        	return false;
        }

        $query = 'SELECT * FROM owners WHERE username = "'.$un.'" AND password = "'.$pw.'" LIMIT 1';
        //$GLOBALS['debug']->printr($query);
        $result = mysql_query($query);
        if(mysql_num_rows($result)>0){
            while($row = mysql_fetch_assoc($result)){
            	//$GLOBALS['debug']->printr($row);
            	return $row;
            }
        } else return false;
    }

    function setLocalCookie($owner_id){
        $expire_time = time()+60*60*24*30;
        //setcookie ( 'vurp_owner_id', $owner_id, $expire_time, '/', '.vurp.com' );
        setcookie ( 'vurp_owner_id', $owner_id, $expire_time);
    }

    function refreshLocalCookie($owner_id){
        $expire_time = time()+60*60*24*30;
        //setcookie ( 'vurp_owner_id', $owner_id, $expire_time, '/', '.vurp.com' );
        setcookie ( 'vurp_owner_id', $owner_id, $expire_time);
    }
}
?>