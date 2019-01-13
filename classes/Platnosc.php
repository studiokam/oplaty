<?php 
class Platnosc {
	private $_db,
			$_data;
	
	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function name($id)
	{
		$data = $this->_db->get('platnosci', array('id', '=', $id));
		$this->_data = $data->first()->name;
		return $data->first()->name;
	}

	public function repeat_numbers($id)
	{
		$data = $this->_db->get('platnosci', array('id', '=', $id));
		$this->_data = $data->first()->repeat_numbers;
		return $data->first()->repeat_numbers;
	}

	public function pozostalo($id, $nr_raty)
	{
		$data = $this->_db->get('platnosci', array('id', '=', $id));
		$this->_data = $data->first();

		$razem = $this->_data->repeat_numbers;
		return $razem - $nr_raty + 1;

	}


	public function data()
	{
		return $this->_data;
	}

	public function create($fields = array()) {
		if (!$this->_db->insert('platnosci', $fields)) {
			throw new Exception('Problem z utworzeniem konta');
		}
	}


	public function update($fields = array(), $id) 
	{

		if (!$this->_db->update('accounts', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

}