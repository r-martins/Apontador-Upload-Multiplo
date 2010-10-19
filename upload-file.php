<?php
error_reporting(-1);
require_once 'config.php';
include 'classes/url_reader.php';
require_once 'classes/ApontadorApiLib.php';
header("Content-type: text/html; charset=utf-8;");
//upload de fotos
$url = 'http://api.apontador.com.br/v1/search/places/byaddress?state=SP&city=Sao%20Paulo&street=Av.%20Paulista&number=100&term=Little%20Best%20Friend&type=json';
$url = 'http://api.apontador.com.br/v1/places/C404577420635Z6357/photos/new';

$uploaddir = './uploads/'; 
$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
$size=$_FILES['uploadfile']['size'];
if($size>1048576)
{
	echo "error file size > 1 MB";
	unlink($_FILES['uploadfile']['tmp_name']);
	exit;
}


$method = 'PUT';
$data = base64_encode(file_get_contents($_FILES['uploadfile']['tmp_name']));
$oauth_token = $_GET['oauth_token'];
$oauth_token_secret = $_GET['oauth_token_secret'];

$PLACEID = 'C404577420635Z6357';
$PLACEID = $_GET['lbsid'];

$resultado = apontadorChamaApi("PUT", "places/$PLACEID/photos/new", array(
	"type"    => "json",
	"content" => $data
	), $oauth_token, $oauth_token_secret);
var_dump($resultado);


//soma 1 foto na tb de upload_foto apenas para fins de estatistica
try {
    $db = new PDO($dsn, $usr, $pwd);
    $db->exec("UPDATE upload_foto set qtd_fotos = qtd_fotos+1 WHERE id = 0" . $_REQUEST['user_id'] . " LIMIT 1");
} catch (PDOException $e) {
//    echo 'Connection failed: ' . $e->getMessage();
}

/*
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
  echo "success"; 
} else {
	echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
}
*/

