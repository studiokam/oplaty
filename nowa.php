<?php
require_once 'core/init.php';

if (Input::exists()) {

	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'name' => 'Nazwa płatności',			// nazwa do wyświetlania błędu
			'required' => true,			// wymagane
			'min' => '2',
			'unique' => 'platnosci'			// unikalna nazwa w tabeli users
			),
		'price' => array(
			'name' => 'Kwota',
			'required' => true,
			'numeric' => true
			),
		'start_date' => array(
			'name' => 'Data najbliższej płatności',
			'required' => true
			),
		'cycle' => array(
			'name' => 'Cykl',
			'required' => true,
			),
		'repeat_numbers' => array(
			'name' => 'Ile razy',
			'numeric' => true,
			'max' => '3'
			)
		));


	if ($validation->passed()) {

		try {
			$platnosc = new Platnosc();
			$platnosc->create(array(
				'name' => Input::get('name'),
				'add_date' => time(),
				'start_date' => strtotime(Input::get('start_date')),
				'who_add' => $user_id,
				'price' => Input::get('price'),
				'repeat_numbers' => Input::get('repeat_numbers'),
				'cycle' => Input::get('cycle')
			));

			$stmt = $db->get('platnosci', array('name', '=', Input::get('name')));
			$last_id = $stmt->first()->id;

			$raty = new Raty();

			$limit = $raty->limit(Input::get('cycle'), Input::get('repeat_numbers'));
			$data = strtotime(Input::get('start_date').' 23:00:00');

			for ($i=1; $i <= $limit; $i++) { 		
							
				$raty->create(array(
					'id_platnosci' => $last_id,
					'nr_raty' => $i,
					'data_raty' => $data,
					'kwota' => Input::get('price'),
					'notatka' => '',
					'status' => '0'
				));

				$data = strtotime($raty->cykl(Input::get('cycle')), $data);
			}

			Session::flash('flash_success', 'Dodano nowa płatność.');
			Redirect::to('lista.php');

		} catch (Exception $e) {
			die($e->getMessage());
		}
		
	} else {
		foreach ($validation->errors() as $error_add) {
			$error[] = $error_add;
		}
	}
}

$data = array (

	'name' => Input::get('name'),
	'start_date' => Input::get('start_date'),
	'price' => Input::get('price'),
	'repeat_numbers' => Input::get('repeat_numbers'),
	'cycle' => Input::get('cycle')

);

$meta_css = array (
	'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'
);
$meta_js = array (
	'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
	'js/datepicker.js'
);
$selectValues = array('miesiac'=>'Co miesiąc', 'raz'=>'Jednorazowo', 'tydzien'=>'Co tydzień', 'rok'=>'Co rok');

echo $twig->render('nowa.twig', compact('data', 'error', 'meta_css', 'meta_js', 'selectValues', 'name'));
?>
