<?php 
class Month {
	function polish_month($month, $year)
	{
		
		$months = array(
			'Styczeń' => '1', 
			'Luty' => '2', 
			'Marzec' => '3',
			'Kwiecień' => '4',
			'Maj' => '5',
			'Czerwiec' => '6',
			'Lipiec' => '7',
			'Sierpień' => '8',
			'Wrzesień' => '9',
			'Październik' => '10',
			'Listopad' => '11',
			'Grudzień' => '12'
			); // uzupełnij analogicznie tablicę
		$m = array_search($month, $months);
		
		$datas = strtotime($year.'-'.$month.'-01');

		// domyślnie pokazywane sa 3 miesice (obecny, poprzedni i następny), ale limituje to do max 2 miesięcy wstecz
		$miesiace_wybor = array(
			
			array(
				'mont_pl' => array_search($month, $months),
				'mont' => date('m', $datas),
				'year' => date('Y', $datas),
				'select' => 'selected'
			),
			array(
				'mont_pl' => array_search(date('m', strtotime('+1 month',$datas)), $months),
				'mont' => date('m', strtotime('+1 month',$datas)),
				'year' => date('Y', strtotime('+1 month',$datas))
			)
			
		);

		if ($datas < strtotime('-1 month',strtotime(date('Y-m').'-01'))) {
			
		} else {
			array_unshift($miesiace_wybor, array(
				'mont_pl' => array_search(date('m', strtotime('-1 month',$datas)), $months),
				'mont' => date('m', strtotime('-1 month',$datas)),
				'year' => date('Y', strtotime('-1 month',$datas))
			));
		}

		return $miesiace_wybor;
	}

}