<?php 
require_once 'core/init.php';

// sprawdzenie jaki wybrano miesiac 
if (Input::get('date')) {	
	$values = explode('.',Input::get('date'));
	$month = $values[0];
	$year = $values[1];
} else {
	$month = date('m');
	$year = date('Y');
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

		Redirect::to('index.php');

	}

$months = new Month();
$polish_month = $months->polish_month($month, $year);




// sprawdzenie czy jest cos "po terminie"
$dzis = strtotime(date('Y-m-d', time()));
$stmt = $db->get('raty', array('data_raty', '<', $dzis), ' AND status = 0');
$po_termnie = $stmt->count();

// wybranie rat do opłaty w tym miesicu
$starts = strtotime($year.'-'.$month.'-01 00:00:01');
$ends = strtotime($year.'-'.$month.'-'.date('t', $starts).'23:59:50');

$stmt = $db->query("SELECT * FROM raty WHERE data_raty > $starts AND data_raty < $ends ORDER BY data_raty");
$raty = $stmt->results();
$raty_array = Conv::objToArray($raty);


// kwota pozostała w danym miesiacu do oplaty
$pozostalo = '';
foreach ($raty_array as $row) {
	if ($row['status'] == 0) {
		$pozostalo = $row['kwota'] + $pozostalo;
	}
	
}

$platnosci = new Platnosc();
$raty_display = [];
// nazwa platnosci
foreach ($raty_array as $key => $value) {

	$value['nazwa'] = $platnosci->name($value['id_platnosci']);
	$value['rat_start'] = $platnosci->repeat_numbers($value['id_platnosci']);
	$value['pozostalo'] = $platnosci->pozostalo($value['id_platnosci'], $value['nr_raty']);
	$timeleft = $value['data_raty'] - time() + 86400;
	$value['dni'] = round((($timeleft/24)/60)/60);
	
	$raty_display[] = $value;
}

// echo "<pre>";
// print_r( $raty_display );
// echo "<br>";
echo $twig->render('index.twig', compact('name', 'raty_display', 'polish_month', 'po_termnie', 'pozostalo', 'error'));
