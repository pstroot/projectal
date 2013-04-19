<?PHP

error_reporting(E_ALL);
ini_set('display_errors', '1');

$path = $_GET["path"];

$Open = opendir ("$path");
while ($Files = readdir ($Open)){
$Filename = "$path" . $Files;
		if (is_dir ($Filename)){
		print ("<a href=?path=$Filename/><B>$Files</b></a><br>");
		} else {
		print ("$Files<br>");
		}
}
closedir($Open);
	
?>                   