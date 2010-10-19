<?php
require_once 'config.php';

try {
    $db = new PDO($dsn, $usr, $pwd);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
$term = $_GET['term'];
$term = utf8_decode(addslashes(urldecode($term)));

$sql = 'SELECT nome,uf FROM tb_cidades WHERE lower(nome) like \'' . strtolower($term) . '%\' ORDER BY prioridade DESC, nome ASC LIMIT 10 ';
$cidades = $db->query($sql);

$cities = array();
foreach($cidades as $k => $v){
    $cities[$k]['id'] = $v['nome'] . ',' . $v['uf'];
    $cities[$k]['label'] = $v['nome'] . ',' . $v['uf'];
    $cities[$k]['value'] = $v['nome'] . ',' . $v['uf'];
}

echo json_encode($cities);