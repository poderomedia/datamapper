<?php
	
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	
	function normalize_text($string) {
		$pattern = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ');
		$replace  = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N');
		
		return str_replace($pattern, $replace, $string);
	}
	
	if(!isset($_GET['profile_type']) OR empty($_GET['profile_type'])) {
		$profile_type = 'person';
	}
	else {
		$profile_type = $_GET['profile_type'];
	}

	$profile_id = $_GET['profile_id'];

	$query = w_get_conns($profile_id, $profile_type);

	$term = current(get_the_terms($profile_id, 'entity_type'));
	$json = array(
		'root' => 'entity_0',
		'nodes' => array(
			0 => array(
				'id' => 'entity_0',
				'name' => get_the_title($profile_id),
				'numcn' => count($query),
				'type' => $term->slug,
			)
		),
		'links' => array()
	);

	$i = 1;

	foreach($query as $row) {
		$term = current((array) get_the_terms($row['post_id'], 'entity_type'));	
		
		$json['nodes'][$i] = array(
			'id' => 'entity_' . $i, 
			'name' => $row['name'],
			'url' => get_permalink($row['post_id']), 
			'type' => $term->slug,
			'numcn' => 1,
			'has_conflict' => $row['conflict'],
			'description' => $row['conflict_overview'],
			'profile_id' => $row['post_id']
		);

		$json['links'][] = array('from' => 'entity_0', 'to' => 'entity_' . $i);

		$i++;
	}

	$visualization = json_encode($json);
	
	echo $visualization;
?>