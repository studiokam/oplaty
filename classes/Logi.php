<?php 
class Logi {
	private $_db;
	
	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function setLog($platnosc_id = '', $rata_id = '', $user_id, $tresc )
	{

 		$fields_logi = array(
			'id_platnosci' => $platnosc_id,
			'id_raty' => $rata_id,
			'data' => time(),
			'dodal' => $user_id,
			'tresc' =>$tresc
 		);

		if (!$this->_db->insert('logi', $fields_logi)) {
			throw new Exception('Problem z logiem.');
		}
		
	}


}