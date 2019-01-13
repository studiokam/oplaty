<?php 
require_once 'core/init.php';

$stmt = $db->query("SELECT * FROM platnosci ORDER BY name");
// echo "<pre>";
// print_r($stmt);

$platnosci = $stmt->results();

if (Session::exists('flash_success')) {
	$flash_success = Session::flash('flash_success');
}
if (Session::exists('flash_error')) {
	$flash_error = Session::flash('flash_error');
}


$platnosci_array = Conv::objToArray($platnosci);


echo $twig->render('lista.twig', compact('platnosci_array', 'flash_success', 'flash_error', 'name'));
?>
