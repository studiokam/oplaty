<?php 
require_once 'core/init.php';


$id_raty = Input::get('id');
$logi = new Logi();


$stmt = $db->get('raty', array('id', '=', $id_raty));
$rata_kwota = $stmt->first()->kwota;
$id_platnosci = $stmt->first()->id_platnosci;

$platnosci = new Platnosc();
$nazwa = $platnosci->name($id_platnosci);

//////////////////////
// zmiana statusu raty

if (Input::get('price')) 
{
	$zmiana = Input::get('price');
	$raty = new Raty();
	$zmiana_statusu = $raty->zmiana_raty($id_raty, $zmiana, $user_id, $id_platnosci);
	Redirect::to('edytuj_rate.php?id='.$id_raty);
}

// logi
$stmt2 = $db->get('logi', array('id_raty', '=', $id_raty), ' ORDER BY data DESC');
$logi_from_db = $stmt2->results();
$logi_array = Conv::objToArray($logi_from_db);

// echo "<pre>";
// print_r( $logi_array );
echo "<br>";

echo $twig->render('edytuj_rate.twig', compact('name', 'logi_array', 'rata_kwota', 'nazwa', 'error'));
