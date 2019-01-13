<?php 
require_once 'core/init.php';


$id_raty = Input::get('id');


$stmt = $db->get('raty', array('data_raty', '<', time()), ' AND status = 0');
$wyniki = $stmt->results();
$wyniki_array = Conv::objToArray($wyniki);


//////////////////////
// zmiana statusu raty

if (Input::get('price')) 
{
	$zmiana = Input::get('price');
	$raty = new Raty();
	$zmiana_statusu = $raty->zmiana_raty($id_raty, $zmiana, $user_id, $id_platnosci);
}


$platnosc_id = Input::get('id');

//////////////////////
// zmiana statusu raty
//////////////////////

if (Input::get('zaplacono')) 
{
	$zmiana = Input::get('zaplacono');
	$rata_id = Input::get('id_raty');
	$raty = new Raty();
	$zmiana_statusu = $raty->status_change($rata_id, $zmiana, $user_id, $platnosc_id);
	Redirect::to('poterminie.php');

}

// kwota pozostała w danym miesiacu do oplaty
$pozostalo = '';
foreach ($wyniki_array as $row) {
	if ($row['status'] == 0) {
		$pozostalo = $row['kwota'] + $pozostalo;
	}
	
}


$platnosci = new Platnosc();
$raty_display = [];
// nazwa platnosci
foreach ($wyniki_array as $key => $value) {

	$value['nazwa'] = $platnosci->name($value['id_platnosci']);
	$value['pozostalo'] = $platnosci->pozostalo($value['id_platnosci'], $value['nr_raty']);
	$timeleft = $value['data_raty'] - time() + 86400;
	$dni_po_terminie = round((($timeleft/24)/60)/60);
	$value['dni'] = substr($dni_po_terminie, 1);
	
	
	$raty_display[] = $value;
}

// echo "<pre>";
// print_r( $raty_display );
// echo "<br>";

echo $twig->render('poterminie.twig', compact('name', 'raty_display', 'pozostalo'));
