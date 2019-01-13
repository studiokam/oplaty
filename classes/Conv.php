<?php 
class Conv {
	public static function objToArray($con) {
        $json = json_encode($con);
        return json_decode($json, true);
	}
}