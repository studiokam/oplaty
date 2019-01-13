<?php
session_start();

// wyswietlanie błędów ( 0 dla blokowania)
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'lr'
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);


//autoload klas, autmatyczne ładowanie tylko tej klasy która jest wywoływana
spl_autoload_register(function($class) 
{
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

require 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);


$db = DB::getInstance();


$url = $_SERVER['PHP_SELF'];

if (!strpos($url, "zaloguj") != FALSE) {
	if (Session::exists('user')) {
	  	$user_id = Session::get('user');
	} else {
	  	Redirect::to('zaloguj.php');
	}

	$stmt_name = $db->get('users', array('id', '=', $user_id));
	$name = $stmt_name->results()[0]->username;
}
