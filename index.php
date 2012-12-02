<?php

$time_start = microtime(true);
require_once('core/lib.php');


// Controler
switch($_GET['a']) {

	// Boat informations
	// WIP
	/*
	case 'boat':
		//$doc_title = 'Classement du bateau : ' . $_GET['boat'];
		$content = search_boat_ranking($_GET['boat']);
		break;
	*/

	case 'refreshCache':
		if (file_exists(CACHE_FILE)) {
			unlink(CACHE_FILE);
		}
		header('Location:.');
		break;

	// Display complete ranking
	default:
		$content = get_all_rankings();
		break;
}


// Execution time
$time = (microtime(true) - $time_start) * 1000;
$time = ($time > 1000) ? round($time / 1000, 3) . ' s' : round($time, 3) . ' ms';

// HTML template
require_once('statics/template.html.php');


?>