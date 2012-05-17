<?php
$dsn = $usr = $pwd = $user = $key = $secret = $callbackurl = '';
if($_SERVER['HTTP_HOST'] != 'localhost'){
	//PROD APP
	$user = 'XXX'; //fill with your email (used by your app)
	$key = 'XXXX'; // fill with your public key
	$secret = 'xxxxx'; // fill with your secret key
	$callbackurl = "http://apontador.ricardomartins.info/upload_multiplo/callback.php"; //url de retorno
}else{
	//DEV APP
	$user = 'ricardo.martins@lbslocal.com';
	$key = 'XXXX'; // fill with your public key
	$secret = ''; // fill with your secret key
	$callbackurl = "http://localhost/apontador/upload_multiplo/callback.php"; //url de retorno
}

if($_SERVER['HTTP_HOST'] == 'localhost'){
	//DEV APP
	define('APP_URL', 'http://localhost/apontador/upload_multiplo/');
	//bd
	$dsn = 'mysql:dbname=apontador;host=localhost';//db name and mysql host
	$usr = 'apontador'; //db user
	$pwd = 'apontador'; //db pass
}else{
	//PROD APP
	define('APP_URL', 'http://apontador.ricardomartins.info/upload_multiplo/');
	//bd
	$dsn = 'mysql:dbname=DB_NAME_HERE;host=MYSQL_HOST_HERE';
	$usr = '';
	$pwd = '';
}

