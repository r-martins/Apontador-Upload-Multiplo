<?php
error_reporting(-1);
include_once "classes/OAuth.php";
include_once "classes/url_reader.php";
require_once "config.php";

$user = 'ricardo.martins@lbslocal.com';



$lbsid = 'C40649834B4B1Z4B12';

$url_request = "http://api.apontador.com.br/v1/oauth/request_token"; // url pra pedir o oauth_token e oauth_secret (antes de autorizar)
$url_authorize = "http://api.apontador.com.br/v1/oauth/authorize"; // url pra pedir autorizacao


$options = array('consumer_key' => $key, 'consumer_secret' => $secret);


$method = "GET";
$params = null;


try
{
	$consumer = new OAuthConsumer($key, $secret, NULL);
	$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

	
	// Passo 1: Pedir o par de tokens inicial (oauth_token e oauth_token_secret) para o Apontador
	$req_req = OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $url_request, array());
	$req_req->sign_request($signature_method, $consumer, NULL);
	//a classe $req_req ao ser transformada em string nos devolve o url para pedir a chave
	$reader = new Url_Reader((string)$req_req);
	if($reader->success()){
	    parse_str($reader->get());
	}else{
	    throw new Exception(sprintf('Falha ao buscar auth token e token secret em %s. - %s'), (string)$req_req, $reader->get_errors());
	}

	//redireciona pro apontador pedindo autorizacao
	$oauth_callback = "$callbackurl?&key=$key&secret=$secret&token=$oauth_token&token_secret=$oauth_token_secret&endpoint=" . urlencode($url_authorize);
	$auth_url = $url_authorize . "?oauth_token=$oauth_token&oauth_callback=" . urlencode($oauth_callback) . "";
//	var_dump($key,$secret,$auth_url);exit;
	header("Location: $auth_url");
}
catch(OAuthException2 $e)
{
	var_dump($e);
}

