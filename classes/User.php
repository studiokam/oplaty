<?php
class User {
	private $_db,
			$_data,
			$_sessionName,
			$_isLoggedIn;

	public function __construct($user = null) {
		$this->_db = DB::getinstance();

		$this->_sessionName = Config::get('session/session_name');

		if (!$user) {
			if (Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName); 

				if ($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					//
				}
			}
		} else {
			$this->find($user);
		}
	}


	public function find($user = null) {
		if ($user) {
			$data = $this->_db->get('users', array('username', '=', $user));

			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($username = null, $password = null) {
		

		if (!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			$user = $this->find($username);
			if ($user) {
				if ($this->data()->password === $password) {
					Session::put($this->_sessionName, $this->data()->id);					
					return true;
				} 
			}
		}

		return false;
	}


	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function logout() {

		Session::delete($this->_sessionName);
	}

	public function data() {
		return $this->_data;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
}