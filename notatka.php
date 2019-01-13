<?php 
require_once 'core/init.php';

$id_raty = Input::get('id');

if (Session::exists('flash_success')) {
	$flash_success = Session::flash('flash_success');
}
if (Session::exists('flash_error')) {
	$flash_error = Session::flash('flash_error');
}

if (Input::get('notatka')) 
{
	$note = Input::get('notatka');
	$raty = new Raty();
	$zmiana_statusu = $raty->notatka($id_raty, Input::get('notatka'));

	Session::flash('flash_success', 'Notatka zapisana');
	Redirect::to('notatka.php?id='.$id_raty);

} else {
	$stmt = $db->get('raty', array('id', '=', $id_raty));
	$note = $stmt->first()->notatka;
}

$stmt = $db->get('raty', array('id', '=', $id_raty));
$raty_from_db = $stmt->results();


if (!$raty_from_db) {

	Redirect::to('lista.php');
	exit();
} 

echo $twig->render('notatka.twig', compact('name', 'note', 'flash_success', 'flash_error'));
?>