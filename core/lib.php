<?php

require_once('config.php');
$update_time = array();


/**
 * Retreive rankings of all boats added in config.php and refresh cache if needed
 * @return String HTML code
 */
function get_all_rankings() {
	$expire_time = time() - CACHE_LIFETIME;
	if (file_exists(CACHE_FILE) && filemtime(CACHE_FILE) > $expire_time) {
        $output = file_get_contents(CACHE_FILE);
	}
	else {
		$output = _generate_all_rankings();
		_refresh_cache($output);
	}
	
	return $output;
}


/**
 * Regenerate cache file with given content
 * @param String $data Content to cache
 */
function _refresh_cache($data) {
	if (!file_exists(CACHE_FILE)) {
		touch(CACHE_FILE);
	}
	file_put_contents(CACHE_FILE, $data);
}


/**
 * Generate HTML code with ranking of each boat declared in config.php
 * @return String HTML code
 */
function _generate_all_rankings() {
	global $BOATS;
	$boats_output = array();
	foreach($BOATS as $boat_name => $parameters) {
		$boat = _search_boat_ranking($boat_name);
		if (!empty($boat['output'])) {
			$boats_output[$boat['position']] = $boat['output'];
		}
	}

	global $update_time;
	if (sizeof($update_time) > 1) {
		return '<div class="error"><h1>Erreur</h1><p>' . ERROR_MSG_MULTIPLES_TIMES_UPDATE . '</p></div>';
	}

	ksort($boats_output);

	$page_title = PAGE_TITLE . ' &agrave; ' . $update_time[0];
	
	$i = 0;
	foreach ($boats_output as $position => $boat_output) {
		$i++;
		$table_content .= (strlen($table_content) > 0) ? "\n			" : '';
		// Add position in this list
		$table_content .= preg_replace('/<tr( title=(.*?))?>(\s*)<td>/', '<tr$1>$3<td>' . $i . "</td>\n\t\t\t\t<td>", $boat_output);
	}

	return <<<EOT
<header>
		<h1>$page_title</h1>
	</header>

	<table class="ranking">
		<thead>
			<tr>
				<th>#</th>
				<th><abbr title="Sans Option(s)">SO</abbr></th>
				<th>&Eacute;volution</th>
				<th><abbr title="Virtual Regatta">VR</abbr></th>
				<th>Bateau</th>
				<th>Distance</th>
				<th>&Eacute;cart</th>
				<th>Profils</th>
			</tr>
		</thead>
		<tbody>
			$table_content
		</tbody>
	</table>

EOT;
}


/**
 * Search a boat ranking from vr-annexe.akroweb.fr
 * @param String $boat Boat name
 * @return String HTML code
 */
function _search_boat_ranking($boat) {
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, BOAT_RANKING_SEARCH_URL);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_HEADER, false);
	curl_setopt($c, CURLOPT_POST, true);
	curl_setopt($c, CURLOPT_POSTFIELDS, array(SEARCH_FIELD_NAME => "$boat"));
	$output = curl_exec($c);

	return _extract_boat_ranking($boat, $output);
}


/**
 * Extract the expected line in a ranking table and modify this HTML code
 * @param String $boat Boat name
 * @param String $req_output Source code of a ranking webpage
 * @return String HTML code
 */
function _extract_boat_ranking($boat, $req_output) {
	// Save update time
	preg_match('/Mise\s&agrave;\sjour\s:\s([0-9]{2}:[0-9]{2})/', $req_output, $matches);
	global $update_time;
	if (!in_array($matches[1], $update_time)) {
		$update_time[] = $matches[1];
	}

	// Extract the expected row of this boat
	preg_match('/<tr class="boat">(.*?)<\/tr>/is', $req_output, $matches);

	// Get position of this boat in ranking
	preg_match('/<tr class="boat">\s*<td (.*?)>(.*?)<\/td>/is', $matches[0], $position);
	$boat_position = $position[2];

	// Remove country
	$output = preg_replace('/\s*<td class="cent">(<img src="img\/flags(.*?))*<\/td>\s*/', '', $matches[0]);

	// Remove 'cent' class
	$output = preg_replace('/<td class="cent">/', '<td>', $output);
	
	// Merge 'fleche' and 'score' cells
	$output = preg_replace('/<td class="fleche">(.*?)<\/td>\s*<td class="score">(.*?)<\/td>/', '<td>$2 $1</td>', $output);

	// Add original domain name in links URL
	$output = preg_replace('/<a href="/', '<a href="http://vr-annexe.akroweb.fr/', $output);
	
	// Add a title with the navigator's identity and Remove class attribute
	global $BOATS;
	$new_tr_tag = (strlen($BOATS[$boat]['navigator']) > 0) ? '<tr title="' . $BOATS[$boat]['navigator'] . '">' : '<tr>';
	$output = preg_replace('/<tr class="boat">/', $new_tr_tag, $output);
	
	// Add a title on the ranking link
	$output = preg_replace('/<td>(\s*)<a href="(.*?)"/', '<td>$1<a title="Profil du joueur sur \'L\'Annexe des SO\'" href="$2"', $output);

	// Add links to the profile pages of current boat
	$link_to_VR_profile = '<a href="http://www.virtualregatta.com/player.php?id_player=' . $BOATS[$boat]['id'] . 
							'" title="Profil du joueur sur VirtualRegatta"><img alt="Logo Virtual Regatta" src="statics/img/VirtualRegatta.ico" /></a>';
	$link_to_annexe_profile = '<a $1><img alt="Logo L\'Annexe des SO" src="statics/img/VR-Annexe.ico" /></a>';
	$new_links = "\n\t\t\t\t\t" . $link_to_annexe_profile . '&nbsp;&nbsp;' . "\n\t\t\t\t\t" . $link_to_VR_profile . "\n\t\t\t\t";
	$output = preg_replace('/<a (.*?)><img src="img\/ancre\.png"\/><\/a>/', $new_links, $output);

	// Modify path images an add alt attribute
	$output = preg_replace('/<img src="img\//', '<img src="statics/img/', $output);
	$output = preg_replace('/<img (.*?)flecher/', '<img alt="Places perdues" $1flecher', $output);
	$output = preg_replace('/<img (.*?)flechev/', '<img alt="Places gagn&eacute;es" $1flechev', $output);

	// Add thousands separator
	$output = preg_replace_callback('/(<td>)([0-9]{4,})/', '_add_thousands_separator', $output);

	// Remove HTM comments
	$output = preg_replace('/<!--(.*?)-->/', '', $output);
	
	// Remove empty lines
	$output = preg_replace('/\n(\s*?)\n/', "\n", $output);
	
	// Format HTML code
	$output = preg_replace('/<\/td><td/', "</td>\n\t\t\t\t<td", $output);
	$output = preg_replace('/\s{20}<td/', "\t\t\t\t<td", $output);
	$output = preg_replace('/\s{16}<\/tr/', "\t\t\t</tr", $output);


	return array(
		'position' => $boat_position,
		'output' => $output
	);
}


/**
 * Add blank thousands separator on a given number
 * @param float $number
 * @return String Given number with thousands separator
 */
function _add_thousands_separator($number) {
	return $number[1] . number_format($number[2], 0, ',', ' ');
}

?>