<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo $_SERVER['REQUEST_URI'];
$uri = explode("/", $_SERVER['REQUEST_URI']);
echo($uri[1]);
foreach($_REQUEST as $key => $value){
  echo $key . " : " . $value . "<br />\r\n";
}
echo "Files:<br>";
foreach($_FILES as $key => $value){
  echo $key . " : " . $_FILES[$key]['name'] . "<br />\r\n";
}
//$_FILES['video']['name']
?> 