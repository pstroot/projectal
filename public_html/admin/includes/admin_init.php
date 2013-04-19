<?
session_start();

if(!isset($pathToRoot)){
	$pathToRoot = "";
}

$cityID = "65"; // 65 is the city ID for Minneapolis

require($pathToRoot . "../includes/configure.php");


/* INITIALIZE DATABASE */
/* require the query_factory clsss based on the DB_TYPE */
require($pathToRoot . '../includes/classes/class.base.php');
require($pathToRoot . '../includes/functions/sessions.php');
require($pathToRoot . '../includes/classes/db/' .DB_TYPE . '/query_factory.php');

$db = new queryFactory();
if (!$db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, USE_PCONNECT, false)) {
  if (file_exists($pathToRoot . 'zc_install/index.php')) {
    header('location: ' . $pathToRoot . '../zc_install/index.php');
    exit;
  } elseif (file_exists($down_for_maint_source)) {
    if (defined('HTTP_SERVER') && defined('DIR_WS_CATALOG')) {
      header('location: ' . HTTP_SERVER . DIR_WS_CATALOG . $down_for_maint_source );
    } else {
      header('location: ' . $down_for_maint_source );
//    header('location: mystoreisdown.html');
    }
    exit;
  } else {
    exit;
  }
}

function curPageURL() {	
	$_SERVER['FULL_URL'] = 'http';
	$script_name = '';
	if(isset($_SERVER['REQUEST_URI'])) {
		$script_name = $_SERVER['REQUEST_URI'];
	} else {
		$script_name = $_SERVER['PHP_SELF'];
		if($_SERVER['QUERY_STRING']>' ') {
			$script_name .=  '?'.$_SERVER['QUERY_STRING'];
		}
	}
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
		$_SERVER['FULL_URL'] .=  's';
	}
	$_SERVER['FULL_URL'] .=  '://';
	if($_SERVER['SERVER_PORT']!='80')  {
		$_SERVER['FULL_URL'] .=
		$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$script_name;
	} else {
		$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$script_name;
	}
	
	return $_SERVER['FULL_URL'];
}



function fixSpecialChars($str){
	$str = str_replace("'","’",$str);
	return $str;
}
?>
