<?php


// Boats list to display
$BOATS = array(
	/*
	 Exemple :
	'BOAT_NAME' => array('navigator' => 'PLAYER_FIRSTNAME', 'id' => ID),
	*/
);


define(PAGE_TITLE, 'Classement Vend&eacute;e Globe - Virtual Regatta');

define(ERROR_MSG_MULTIPLES_TIMES_UPDATE, 
		'Les classements r&eacute;cup&eacute;r&eacute;s ne sont pas tous &eacute;tablis &agrave; la m&ecirc;me heure.<br /><br />' . 
		'<a href="?a=refreshCache">Reg&eacute;n&eacute;rez le classement g&eacute;n&eacute;ral</a>');

define(ERROR_MSG_BOATS_LIST, 
		'<p>La liste des bateaux est erron&eacute;e, v&eacute;rifiez que vous avez bien configur&eacute; le fichier core/config.php.</p>' .
		'<p>La variable "BOATS" doit &ecirc;tre remplie selon le mod&egrave;le suivant (en rempla&ccedil;ant les valeurs en majuscules) :' .
		'<code>\'BOAT_NAME\' => array(\'navigator\' => \'PLAYER_FIRSTNAME\', \'id\' => ID),</code></p>');

define(VR_PROFIL_URL, 'http://www.virtualregatta.com/player.php?id_player=');
define(BOAT_RANKING_SEARCH_URL, 'http://vr-annexe.akroweb.fr/vg12.php');
define(LINE_CLASSNAME, 'boat');
define(SEARCH_FIELD_NAME, 'vrbateaucherche');


define(CACHE_LIFETIME, 900); // Time in seconds (ex 900s = 15min)
define(CACHE_FILE, 'cache/rankings.frg.html');


?>