<?php 
require_once 'core/init.php';


$platnosc_id = Input::get('id');
$logi = new Logi();

// sprawdzenie adresu czy jest przycisk "delete" i wyświetlenie innej treści na stronie 
$action = Input::get('action');
if ($action) {
	$delete = 'ok';
}

// usuniecie rat do płatnosci i płatnosci
$delete_ok = Input::get('delete_ok');
if ($delete_ok) {

	if (!$db->delete('raty', array('id_platnosci', '=', $platnosc_id))) {
		throw new Exception('Problem z wykasowaniem rat do płatnosci');
	}
	if (!$db->delete('platnosci', array('id', '=', $platnosc_id))) {
		throw new Exception('Problem z wykasowaniem płatnosci');
	}


	Session::flash('flash_success', 'Poprawnie usunięto płatność.');
	Redirect::to('lista.php');
}


// pobranie starej nazwy płatności
$stmt_old = $db->get('platnosci', array('id', '=', $platnosc_id));
$platnosci_from_db_old_name = $stmt_old->first()->name;
$platnosci_from_db_old_kwota = $stmt_old->first()->price;

// zmiana nazwy płatnosci
if (Input::get('name')) 
{
	$tresc = 'Zmieniono nazwę dla płatności. Pierwotna nazwa: '. $platnosci_from_db_old_name .', Nowa nazwa: '. Input::get('name') .'';

	$zmiana = Input::get('name');
	if (!$db->update('platnosci', $platnosc_id, array('name' => Input::get('name')))) {
		throw new Exception('Problem ze zmian nazwy płatnosci');
	}

	$logi->setLog($platnosc_id,'', $user_id, $tresc);

	Session::flash('flash_success', 'Poprawnie zmieniono nazwę');

}

// zmiana kwoty płatnosci
if (Input::get('price')) 
{
	$tresc = 'Zmieniono kwotę dla płatności. Pierwotna kwota: '. $platnosci_from_db_old_kwota .', Nowa kwota: '. Input::get('price') .'';

	$zmiana = Input::get('price');
	if (!$db->update('platnosci', $platnosc_id, array('price' => Input::get('price')))) {
		throw new Exception('Problem ze zmian kwoty płatnosci');
	}

	// zmiana kwoty dla wszystkich przyszłych rat płatności
	$teraz = time();
	$stmt_update_get = $db->get('raty', array('data_raty', '>', $teraz), ' AND id_platnosci = '.$platnosc_id.'');
	$raty_do_up = $stmt_update_get->results();
	$raty_do_up_array = Conv::objToArray($raty_do_up);

	foreach ($raty_do_up_array as $row) {
		$stmt_update_get = $db->update('raty', $row['id'], array('kwota' => Input::get('price')));
	}


	$logi->setLog($platnosc_id,'', $user_id, $tresc);
	Session::flash('flash_success', 'Poprawnie zmieniono nazwę');

}


if (Session::exists('flash_success')) {
	$flash_success = Session::flash('flash_success');
}
if (Session::exists('flash_error')) {
	$flash_error = Session::flash('flash_error');
}



$stmt = $db->get('platnosci', array('id', '=', $platnosc_id));
$platnosci_from_db = $stmt->first();

$stmt2 = $db->get('logi', array('id_platnosci', '=', $platnosc_id), ' ORDER BY data DESC');
$logi_from_db = $stmt2->results();

if (!$platnosci_from_db) {

	Redirect::to('lista.php');
	exit();
} 

$platnosc_array = Conv::objToArray($platnosci_from_db);
$logi_array = Conv::objToArray($logi_from_db);

echo $twig->render('edytuj.twig', compact('platnosc_array', 'logi_array', 'delete', 'flash_success', 'flash_error', 'name'));
?>