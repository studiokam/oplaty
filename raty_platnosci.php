<?php 
require_once 'core/init.php';


$platnosc_id = Input::get('id');
$rata_logi = Input::get('logi');

//////////////////////
// zmiana statusu raty
//////////////////////

if (Input::get('zaplacono')) 
	{
		$zmiana = Input::get('zaplacono');
		$rata_id = Input::get('id_raty');
		$raty = new Raty();
		$zmiana_statusu = $raty->status_change($rata_id, $zmiana, $user_id, $platnosc_id);
		Redirect::to('raty_platnosci.php?id='.$platnosc_id);

	}

$stmt = $db->get('raty', array('id_platnosci', '=', $platnosc_id));
$raty_from_db = $stmt->results();

$stmt2 = $db->get('logi', array('id_raty', '=', $rata_logi), ' ORDER BY data DESC');
$logi_from_db = $stmt2->results();

if (!$raty_from_db) {

	Redirect::to('lista.php');
	exit();
} 

$platnosc = new Platnosc();
$nazwa_platnosci = $platnosc->name($platnosc_id);

$raty_array = Conv::objToArray($raty_from_db);
$logi_array = Conv::objToArray($logi_from_db);
// echo "<pre>";
// print_r($logi_array);

echo $twig->render('raty_platnosci.twig', compact('raty_array', 'nazwa_platnosci', 'rata_logi', 'logi_array', 'error', 'name'));
?>