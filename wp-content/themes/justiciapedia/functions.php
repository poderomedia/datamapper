<?php
	
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	
	/*
	Misc
	*/
	function w_title($delimiter = '—') {
		global $post;
		
		if(is_home()) {
			return get_bloginfo('name');
		}
		elseif(is_single() || is_page()) {
			return get_the_title($post->ID) . ' ' . $delimiter . ' ' . get_bloginfo('name');
		}
		elseif(is_archive() || is_post_type_archive() || is_category()) {
			return single_cat_title('', false) . ' ' . $delimiter . ' ' . get_bloginfo('name');
		}
		elseif(is_search()) {
			return get_search_query() . ' ' . $delimiter . ' ' . get_bloginfo('name');
		}
		else {
			return get_bloginfo('name');
		}
	}

	/*
	Register Post Type
	*/
	function w_post_type_entities() {
		$labels = array(
			'name'               => 'Entities',
			'singular_name'      => 'Entity',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Entity',
			'edit_item'          => 'Edit Entity',
			'new_item'           => 'New Entity',
			'all_items'          => 'All Entities',
			'view_item'          => 'View Entity',
			'search_items'       => 'Search Entities',
			'not_found'          => 'No Entities found',
			'not_found_in_trash' => 'No Entities found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Entities'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			//'rewrite'            => false,
			'rewrite'			 => array('slug' => 'profiles', 'with_front' => true),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 4,
			'supports'           => array('title', 'author', 'revisions'),
			//'taxonomies'         => array('category')
		);

		register_post_type('entity', $args);
	}

	function w_post_type_colaborations() {
		$labels = array(
			'name'               => 'Colaborations',
			'singular_name'      => 'Colaboration',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Colaboration',
			'edit_item'          => 'Edit Colaboration',
			'new_item'           => 'New Colaboration ',
			'all_items'          => 'All Colaborations',
			'view_item'          => 'View Colaboration',
			'search_items'       => 'Search Colaborations',
			'not_found'          => 'No colaborations found',
			'not_found_in_trash' => 'No colaborations found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Colaborations'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array('title', 'editor', 'author', 'revisions')
		);

		register_post_type('colaboration', $args);
	}

	/*
	Register Taxonomy
	*/
	function w_taxonomy_entities() {
		$args = array(
			'query_var'     => true,
			'hierarchical'  => true,
			'has_archive'   => true,
			'show_ui'		=> true,
			'show_admin_column' => true,
			'query_var'		=> true,
			'rewrite'       => array('slug' => 'entity', 'width_front' => true),
			'labels'        => array(
				'name'      => 'Entity Types',
				'singular'  => 'Entity Type',
				'all_items' => 'All Entity Types',
				'edit_item' => 'Edit Entity Type',
				'view_item' => 'View Entity Type',
				'update_item' => 'Update Entity Type',
				'add_new_item' => 'Add New Entity Type',
				'new_item_name' => 'New Entity Type Name',
				'search_items' => 'Search Entity Types',
				'popular_items' => 'Popular Entity Types',
				'add_or_remove_items' => 'Add or Remove Entity Types',
				'not_found' => 'No Entity Types Found'
			)
		);

		register_taxonomy('entity_type', 'entity', $args);
	}

	function w_taxonomy_profiles() {
		$args = array(
			'query_var'     => true,
			'hierarchical'  => true,
			'has_archive'   => true,
			'show_ui'		=> true,
			'show_admin_column' => true,
			'query_var'		=> true,
			'rewrite'       => array('slug' => 'type', 'width_front' => true),
			'labels'        => array(
				'name'      => 'Profile Types',
				'singular'  => 'Profile',
				'all_items' => 'All Profile Types',
				'edit_item' => 'Edit Profile Type',
				'view_item' => 'View Profile Type',
				'update_item' => 'Update Profile Type',
				'add_new_item' => 'Add New Profile Type',
				'new_item_name' => 'New Profile Type Name',
				'search_items' => 'Search Profile Types',
				'popular_items' => 'Popular Profile Types',
				'add_or_remove_items' => 'Add or Remove Profile Types',
				'not_found' => 'No Profile Types Found'
			)
		);

		register_taxonomy('profile_type', 'entity', $args);
	}

	/*
	Register Menus
	*/
	function w_register_menus() {
		register_nav_menus(array(
			'main-nav' => __('Main Nav')
		));
	}

	/*
	Theme Support
	*/
	add_theme_support('post-thumbnails');

	/*
	Misc Functions
	*/
	add_image_size('avatar190', 190, 190, true);
	add_image_size('news297', 297, 205, true);

	function w_admin_head() {
		echo '<style type="text/css">
		//.location_map-container { width: 0; height: 0; overflow: hidden; }
		#acf-entity_organization_students { display: none; }
		#acf-entity_organization_employees { display: none; }
		#acf-entity_organization_members { display: none; } { display: none; }
		</style>

		<script type="text/javascript">
		jQuery(document).ready(function() {
		jQuery(\'#acf-field-entity_candidate_type\').on(\'change\', function() {
		jQuery(\'#location_map, .location_map-container\').css(\'width\', \'98%\');

		//$(\'.location_map-container\').css({ height: \'auto\', width: \'auto\' });
		});
		});

		</script>';
	}

	function w_remove_menus() {
		remove_menu_page('edit-comments.php');
	}

	/*
	Filters
	*/
	

	/*
	Actions
	*/
	add_action('init', 'w_post_type_entities');
	add_action('init', 'w_post_type_colaborations');
	add_action('init', 'w_taxonomy_entities');
	add_action('init', 'w_taxonomy_profiles');
	add_action('init', 'w_register_menus');

	add_action('admin_head', 'w_admin_head');
	add_action('admin_menu', 'w_remove_menus');

	/*
	================= END CORE =================
	*/

	/* New Get Connections */
	function w_get_conns($post_id, $entity_type = 'person') {
		$conns = array();

		if($entity_type == 'person') {
			// Get the data
			$family = get_field_object('entity_person_family', $post_id);
			$family = $family['value'];

			$friends = get_field_object('entity_person_friends', $post_id);
			$friends = $friends['value'];

			$education = get_field_object('entity_person_education', $post_id);
			$education = $education['value'];

			$business = get_field_object('entity_person_business', $post_id);
			$business = $business['value'];

			$organization = get_field_object('entity_person_organization', $post_id);
			$organization = $organization['value'];

			// Go over the data
			if(!empty($family)) {
				foreach($family as $item) {
					$conflict = false;

					if($item['conflict'] != 'none') {
						$conflict = true;
					}

					array_push($conns, array(
						'post_id' => $item['name'][0]->ID,
						'name' => $item['name'][0]->post_title,
						'conflict' => $conflict,
						'conflict_overview' => ($conflict == true ? $item['conflict_overview'] : false)
					));
				}
			}

			if(!empty($friends)) {
				foreach($friends as $item) {
					$conflict = false;

					if($item['conflict'] != 'none') {
						$conflict = true;
					}

					array_push($conns, array(
						'post_id' => $item['name'][0]->ID,
						'name' => $item['name'][0]->post_title,
						'conflict' => $conflict,
						'conflict_overview' => ($conflict == true ? $item['conflict_overview'] : false)
					));
				}
			}

			if(!empty($education)) {
				foreach($education as $item) {
					$conflict = false;

					if($item['conflict'] != 'none') {
						$conflict = true;
					}

					array_push($conns, array(
						'post_id' => $item['name'][0]->ID,
						'name' => $item['name'][0]->post_title,
						'conflict' => $conflict,
						'conflict_overview' => ($conflict == true ? $item['conflict_overview'] : false)
					));
				}
			}

			if(!empty($business)) {
				foreach($business as $item) {
					$conflict = false;

					if($item['conflict'] != 'none') {
						$conflict = true;
					}

					array_push($conns, array(
						'post_id' => $item['name'][0]->ID,
						'name' => $item['name'][0]->post_title,
						'conflict' => $conflict,
						'conflict_overview' => ($conflict == true ? $item['conflict_overview'] : false)
					));
				}
			}

			if(!empty($organization)) {
				foreach($organization as $item) {
					$conflict = false;

					if($item['conflict'] != 'none') {
						$conflict = true;
					}

					array_push($conns, array(
						'post_id' => $item['name'][0]->ID,
						'name' => $item['name'][0]->post_title,
						'conflict' => $conflict,
						'conflict_overview' => ($conflict == true ? $item['conflict_overview'] : false)
					));
				}
			}
		}

		return $conns;
	}

	function w_determine_time($from, $to) { // true = show today or past / false = show is or was
		$n_from = explode('-', $from);
		$n_to = explode('-', $to);
		
		if(!empty($from) && !empty($to)) {
			return 'Desde ' . $n_from[0] . ' — Hasta ' . $n_to[0];
		}
		elseif(empty($from) && !empty($to)) {
			return '(Pasado) Hasta ' . $n_to[0];
		}
		elseif(!empty($from) && empty($to)) {
			return 'Desde ' . $n_from[0] . ' — Presente';
		}
		elseif(empty($from) && empty($to)) {
			return '—';
		}
	}

	function w_translate_terms($term) {
		$es = array(
			'in' => 'en',
			'with' => 'con',
			'is' => 'es',
			'of' => 'de',
			'yes' => 'Sí',
			'no' => 'No',
			'other' => 'Otro',
			'work' => 'Laboral',
			'avowed' => 'Declarado',
			'personal' => 'Personal',
			'single' => 'Soltero/a',
			'relationship' => 'En una relación',
			'fiance' => 'Prometido/a',
			'married' => 'Esposo/a',
			'separated' => 'Separado/a',
			'divorced' => 'Divorciado/a',
			'widowed' => 'Viudo/a',
			'anulled' => 'Anulado/a',
			'parent' => 'Padre/Madre',
			'child' => 'Hijo/a',
			'sibiling' => 'Hermano/a',
			'nephew' => 'Sobrino/a',
			'uncle' => 'Tío/a',
			'cousin' => 'Primo/a',
			'grandparent' => 'Abuelo/a',
			'grandchild' => 'Nieto/a',
			'stepbrother' => 'Hermanastro/a',
			'stepparent' => 'Padrastro/Madrastra',
			'stepchild' => 'Hijastro/a',
			'brotherinlaw' => 'Cuñado/a',
			'parentinlaw' => 'Suegro/a',
			'childinlaw' => 'Nuero/a',
			'friend' => 'Cercano/a',
			'student' => 'Estudiante',
			'degree' => 'Grado',
			'owner' => 'Dueño',
			'partner' => 'Socio',
			'seniorpartner' => 'Socio mayoritario',
			'shareholder' => 'Accionista',
			'member' => 'Miembro',
			'close' => 'Cercano',
			'financer' => 'Financista',
			'president' => 'Presidente/a',
			'vicepresident' => 'Vicepresidente/a',
			'secretary' => 'Secretario',
			'counselor' => 'Consejero/a',
			'researcher' => 'Investigador/a',
			'director' => 'Director/a',
			'coordinator' => 'Coordinador/a',
			'donor' => 'Donante',
			'founder' => 'Fundador',
			'boardpresident' => 'Presidente del directorio',
			'boardvicepresident' => 'Vicepresidente del directorio',
			'treasurer' => 'Tesorero',
			'rector' => 'Rector',
			'vicechancellor' => 'Vicerrector',
			'dean' => 'Decano',
			'vicedean=> Vicedecano' => 'vicedean=> Vicedecano',
			'academicdirector' => 'Director Académico',
			'headofdepartment' => 'Jefe de departamento',
			'professor' => 'Profesor titular/catedrático/académico',
			'consultant' => 'Consultor/a',
			'boardmember' => 'Miembro del directorio',
			'chiefexecutiveofficer' => 'Gerente general',
			'financeandadministrativemanager' => 'Gerente de administración y finanzas',
			'businessmanager' => 'Gerente comercial',
			'salesmanager' => 'Gerente de ventas',
			'operationsmanager' => 'Gerente de operaciones',
			'technologymanager' => 'Gerente de tecnología',
			'humanresourcesmanager' => 'Gerente de Recursos Humanos',
			'marketingmanager' => 'Gerente de Marketing',
			'communicationsmanager' => 'Gerente de comunicaciones',
			'executivepresident' => 'Presidente Ejecutivo',
			'advisor' => 'Asesor',
			'lobbyist' => 'Lobbista',
			'member' => 'Miembro',
			'close' => 'Cercano a',
			'financer' => 'Financista',
			'chairman' => 'Presidente/a',
			'vicepresident' => 'Vicepresidente/a',
			'prosecretary' => 'Prosecretario/a',
			'treasurer' => 'Tesorero/a',
			'donor' => 'Donante',
			'founder' => 'Fundador/a',
			'President' => 'Presidente/a',
			'chiefofstaff' => 'Jefe de gabinete',
			'advisor' => 'Asesor/a',
			'minister' => 'Ministro/a',
			'undersecretary' => 'Subsecretario/a',
			'intendent' => 'Intendente/a',
			'core' => 'Consejero /a regional',
			'governor' => 'Gobernador/a',
			'mayor' => 'Alcalde',
			'councilor' => 'Concejal/a',
			'deputy' => 'Diputado/a',
			'senator' => 'Senador/a',
			'judge' => 'Juez/a',
			'prosecutor' => 'Fiscal',
			'aaff' => 'FFAA',
			'general' => 'General',
			'vicepresident' => 'Vicepresidente/a',
			'mayorsecretary' => 'Secretario Municipal',
			'director' => 'Director/a',
			'coordinator' => 'Coordinador/a',
			'seremi' => 'Secretario Regional Ministerial (SEREMI)',
			'ambassador' => 'Embajador/a',
			'professes' => 'Profesa la religión',

			'judge' => 'Juez',
			'lawyer' => 'Abogado',
			'counselor' => 'Consejero',
			'politician' => 'Político',
			'prosecutor' => 'Fiscal'
		);
		
		if(array_key_exists($term, $es)) {
			return $es[$term];
		}
		else {
			return $term;
		}
	}

	function w_entity_avatar($post_id) {
		if(has_term('person', 'entity_type', $post_id)) {
			$field = 'entity_person_avatar';
		}
		else {
			$field = 'entity_organization_avatar';
		}

		$avatar = wp_get_attachment_image_src(get_field($field, $post_id), 'avatar190');

		$output = $avatar[0];

		return $output;
	}

?>