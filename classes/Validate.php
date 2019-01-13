<?php 
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items=array()) {
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				
				$value = trim($source[$item]);
				$item = escape($item);

				if ($rule === 'required' && empty($value)) {
					$this->addError("{$rules['name']} - wypełnienie pola jest wymagane");
				} else if(!empty($value)) {
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value) {
								$this->addError("{$rules['name']} musi mieć min {$rule_value} znaków.");
							}
						break;

						case 'max':
							if (strlen($value) > $rule_value) {
								$this->addError("{$rules['name']} może mieć max. {$rule_value} znaki.");
							}
						break;

						case 'not_numeric':
							if (is_numeric($value)) {
								$this->addError("{$rules['name']} musi posiadać przynajmniej jedna literę.");
							}
						break;

						case 'numeric':
							if (!is_numeric($value)) {
								$this->addError("{$rules['name']} musi być cyfra");
							}
						break;

						case 'matches':
							if ($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$rules['name']}");
							}
						break;

						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if ($check->count()) {
								$this->addError("{$rules['name']} już istniej, podaj inna.");
							}
						break;
						
						default:
							# code...
						break;
					}
				}
			}
		}

		
		if ($source['cycle'] === 'raz' && $source['repeat_numbers'] != 1) {
			$this->addError("{$rules['name']} - przy wybraniu platności jednorazowej w opcji CYKL, ilość powtórzeń musi mieć wartość równą 1.");
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}