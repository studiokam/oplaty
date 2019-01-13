<?php 
class Raty {
	private $_db,
			$_data;
	
	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function setAcc($id)
	{
		$data = $this->_db->get('raty', array('id', '=', $id));
		$this->_data = $data->first();
		
	}

	public function data()
	{
		return $this->_data;
	}

	public function create($fields = array()) {
		if (!$this->_db->insert('raty', $fields)) {
			throw new Exception('Problem z utworzeniem konta');
		}
	}

	public function status_change($rata_id, $zmiana, $user_id, $platnosc_id)
	{

		$stmt = $this->_db->get('platnosci', array('id', '=', $platnosc_id));
		$dane_platnosci = $stmt->first();

		$stmt2 = $this->_db->get('raty', array('id', '=', $rata_id));
		$dane_raty = $stmt2->first();

		switch ($zmiana) {
			case 'ok':
				$zmiana ='1';
				$tresc = '- Zaktualizowano status platnosci "'. $dane_platnosci->name .'" nr. raty '. $dane_raty->nr_raty .' ('. $rata_id .'), ustawiono na: opłacono';
				break;

			case 'back':
				$zmiana = '0';
				$tresc = '- Zaktualizowano status platnosci "'. $dane_platnosci->name .'" nr. raty '. $dane_raty->nr_raty .' ('. $rata_id .'), ustawiono na: do opłacenia';
				break;

		}

		$fields = array(
			'status' => $zmiana
 		);

 		$fields_logi = array(
			'id_platnosci' => $platnosc_id,
			'id_raty' => $rata_id,
			'data' => time(),
			'dodal' => $user_id,
			'tresc' =>$tresc
 		);

		if (!$this->_db->update('raty', $rata_id, $fields)) {
			throw new Exception('There was a problem updating.');
		}

		if (!$this->_db->insert('logi', $fields_logi)) {
			throw new Exception('There was a problem updating.');
		}

	}

	public function zmiana_raty($rata_id, $zmiana, $user_id, $id_platnosci)
	{

		

		$stmt2 = $this->_db->get('raty', array('id', '=', $rata_id));
		$dane_raty = $stmt2->first();

		$tresc = 'Zmiana kwoty raty na '.$zmiana. ' PLN';

		$fields = array(
			'kwota' => $zmiana
 		);

 		$fields_logi = array(
			'id_platnosci' => $id_platnosci,
			'id_raty' => $rata_id,
			'data' => time(),
			'dodal' => $user_id,
			'tresc' =>$tresc
 		);

		if (!$this->_db->update('raty', $rata_id, $fields)) {
			throw new Exception('There was a problem updating.');
		}

		if (!$this->_db->insert('logi', $fields_logi)) {
			throw new Exception('There was a problem updating.');
		}

	}

	public function notatka($rata_id, $notatka)
	{


		$stmt2 = $this->_db->get('raty', array('id', '=', $rata_id));
		$dane_raty = $stmt2->first();



		$fields = array(
			'notatka' => $notatka
 		);


		if (!$this->_db->update('raty', $rata_id, $fields)) {
			throw new Exception('There was a problem updating.');
		}


	}

	public function limit($cykl, $ile_razy) {
		if ($ile_razy > 0) {
				return $ile_razy;
			} else {
				switch ($cykl) {
					case 'raz':
						return '1';
						break;

					case 'tydzien':
						return '250';
						break;

					case 'miesiac':
						return '120';
						break;

					case 'rok':
						return '10';
						break;

				}
			}
		
	}

	public function cykl($cykl) {
		switch ($cykl) {

			case 'tydzien':
				return '+7 days';
				break;

			case 'miesiac':
				return '+1 month';
				break;

			case 'rok':
				return '+1 year';
				break;

		}
	}

	public function update($fields = array(), $id) 
	{

		if (!$this->_db->update('accounts', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

}