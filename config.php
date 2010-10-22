<?php
$dsn = $usr = $pwd = $user = $key = $secret = $callbackurl = '';
if($_SERVER['HTTP_HOST'] != 'localhost'){
	$user = 'ricardo.martins@lbslocal.com';
	$key = ''; // fill with your public key
	$secret = ''; // fill with your secret key
	$callbackurl = ""; //url de retorno
}else{
	$user = 'ricardo.martins@lbslocal.com';
	$key = ''; // fill with your public key
	$secret = ''; // fill with your secret key
	$callbackurl = ""; //url de retorno
}

if($_SERVER['HTTP_HOST'] == 'localhost'){
	define('APP_URL', 'http://localhost/apontador/upload_multiplo/');
	//bd
	$dsn = 'mysql:dbname=apontador;host=localhost';
	$usr = 'apontador';
	$pwd = 'apontador';
}else{
	define('APP_URL', 'http://apontador.ricardomartins.info/upload_multiplo/');
	//bd
	$dsn = 'mysql:dbname=DBNAME;host=DBHOST';
	$usr = '';
	$pwd = '';
}