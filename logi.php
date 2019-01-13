<?php 
require_once 'core/init.php';

$stmt = $db->query("SELECT * FROM logi ORDER BY data DESC");

$logi = $stmt->results();
$logi_array = Conv::objToArray($logi);

echo $twig->render('logi.twig', compact('logi_array', 'name'));
?>