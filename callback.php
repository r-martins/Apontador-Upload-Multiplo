<?php
//var_dump($_REQUEST);
error_reporting(-1);
include_once "classes/OAuth.php";
include_once "classes/url_reader.php";
require_once "config.php";

$consumer = new OAuthConsumer($key, $secret, NULL);
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

$token = $_REQUEST["oauth_token"];
$verifier = $_REQUEST["oauth_verifier"];
if ((!$token) || (!$verifier)) {
	die('Token e verifier em branco.');
}



// Passo 3: Passa o token e verificador para o Apontador, que vai validar o callback
//          e devolver o token de acesso definitivo
$endpoint = "http://api.apontador.com.br/v1/oauth/access_token?oauth_verifier=$verifier";
$parsed = parse_url($endpoint);
$params = array();
parse_str($parsed['query'], $params);
$acc_req = OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $endpoint, $params);
$acc_req->sign_request($signature_method, $consumer, NULL);
//parse_str(file_get_contents($acc_req), $access_token);
//var_dump($acc_req);//exit;
$reader = new Url_Reader((string)$acc_req);

if($reader->success()){
    parse_str($reader->get(),$access_token);
//echo $access_token['oauth_token'];
    setcookie('oauth_token', $access_token['oauth_token'], time()+2592000 , '/' ) or die('seu navegador não aceita cookies');
    setcookie('oauth_token_secret', $access_token['oauth_token_secret'], time()+2592000, '/');
    setcookie('user_id', $access_token['user_id'], time()+2592000, '/');
	
    $urlredir = 'index.php';
    $urlredir .= (isset($_GET['lbsid']))?'?lbsid='.$_GET['lbsid']:'';
    header('Location:' . $urlredir);
}else{
    throw new Exception(sprintf('Falha ao buscar auth token e token secret em %s. %s', (string)$acc_req,  $reader->get_errors()));
}

//var_dump($access_token);