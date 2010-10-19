<?php
$dsn = $usr = $pwd = $user = $key = $secret = '';
if($_SERVER['HTTP_HOST'] != 'localhost'){
	$user = 'ricardo.martins@lbslocal.com';
	$key = '36gcCCI_nv1kQrWTGjnXCBfgVjlCT8KITrkOp_HcopA~'; // fill with your public key
	$secret = 'q5_prXzkYCfpr9EB8k4br5ec8uE~'; // fill with your secret key
}else{
	$user = 'ricardo.martins@lbslocal.com';
	$key = '36gcCCI_nv2NKa5aEz4RA_9tJYkrnnPS__o_fgRn4uE~'; // fill with your public key
	$secret = 'WDqV7pv0VZLum53kTuCJN6Ji5vc~'; // fill with your secret key
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
	$dsn = 'mysql:dbname=ricardomartins05;host=mysql.ricardomartins.info';
	$usr = 'ricardomartins05';
	$pwd = 'apontador';
}