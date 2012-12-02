<?php


// Boats list to display
$BOATS = array(
	/*
	 Exemple :
	'BOAT_NAME' => array('navigator' => 'PLAYER_FIRSTNAME', 'id' => ID),
	*/
);


define(PAGE_TITLE, 'Classement Vendée Globe - Virtual Regatta');

define(ERROR_MSG_MULTIPLES_TIMES_UPDATE, 
		'Les classements récupérés ne sont pas tous établis à la même heure.<br /><br />' . 
		'<a href="?a=refreshCache">Régénérez le classement général</a>');


define(VR_PROFIL_URL, 'http://www.virtualregatta.com/player.php?id_player=');
define(BOAT_RANKING_SEARCH_URL, 'http://vr-annexe.akroweb.fr/vg12.php');
define(LINE_CLASSNAME, 'boat');
define(SEARCH_FIELD_NAME, 'vrbateaucherche');


define(CACHE_LIFETIME, 900); // Time in seconds (ex 900s = 15min)
define(CACHE_FILE, 'cache/rankings.frg.html');


?>