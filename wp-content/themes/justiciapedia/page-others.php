<?php
	get_header();
	the_post();

	$instance = substr($post->post_name, 0, -1);
	$term = get_term_by('slug', $instance, 'profile_type');
	
	$query = array(
		'post_type'         => 'entity',
		'meta_key'          => 'entity_person_profile_type',
		'meta_value'        => $term->term_id,
		'posts_per_page'    => 10,
		'orderby'           => 'date',
		'order'             => 'desc'
	);

	$query = query_posts($query);
?>

		<section class="row-module row-white">
			<div class="row clearfix">
				<div class="col16">          
					<div class="section-title">
						<div class="visible-m btn-group right clearfix">
							<a href="#" class="btn only-icon btn-default active"><i class="icon icon-16 flaticon stroke paragraph-justify-1"></i> List</a>
							<a href="#" class="btn only-icon btn-default"><i class="icon icon-16 flaticon stroke grid-1"></i> Grid</a>
						</div>
						<h1><?php the_title(); ?></h1>
						<label><?php echo $term->count; ?> perfiles en esta categoría</label>
					</div>
				</div>
			</div>
		</section>

		<section class="row-module">
			<!--<div class="section-options">
				<div class="row">
					<div class="col16">
						<ul class="filters-options clearfix">
							<li class="hidden-m right">
								<div class="btn-group clearfix">
									<a href="#" class="btn only-icon btn-default active"><i class="icon icon-16 flaticon stroke paragraph-justify-1"></i> List</a>
									<a href="#" class="btn only-icon btn-default"><i class="icon icon-16 flaticon stroke grid-1"></i> Grid</a>
								</div>
							</li>
							<li class="dropdown">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Dropdown toggle <i class="icon icon-16 flaticon stroke down-1"></i></a>
								<ul class="dropdown-menu">
									<li><a href="#">Dropdown option</a></li>
									<li><a href="#">Dropdown option</a></li>
									<li><a href="#">Dropdown option</a></li>
									<li><a href="#">Dropdown option</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Dropdown toggle <i class="icon icon-16 flaticon stroke down-1"></i></a>
								<ul class="dropdown-menu">
									<li><a href="#">Dropdown</a></li>
									<li><a href="#">Dropdown option</a></li>
									<li><a href="#">Option</a></li>
									<li><a href="#">Dropdown option lorem #2</a></li>
								</ul>
							</li>
							<li><a href="#" class="btn btn-primary">Submit</a></li>
						</ul>
					</div>
				</div>    
			</div>-->

			<div class="row clearfix">
				<div class="col16">
					<div class="content archive">
						<?php
							foreach($query as $item) :
								if($entity_type->slug == 'person') {
									$profile_positions = get_field('entity_person_positions', $item->ID);
									$positions_count = count($profile_positions);
								}

								$entity_type = current(get_the_terms($item->ID, 'entity_type'));
								$profile_type = get_field('entity_person_profile_type', $item->ID);

								if($entity_type->slug == 'person') {
									$long_profile = get_field('entity_person_profile', $item->ID);
								}
								else {
									$long_profile = get_field('entity_organization_profile', $item->ID);
								}
						?>
						<article class="col14">
							<h4><a href="<?php get_permalink($item->ID); ?>"><?php echo get_the_title($item->ID); ?></a></h4>
							<?php if($entity_type->slug == 'person') : ?>
							<h5><?php echo w_translate_terms($profile_type->slug); ?><?php if($positions_count > 0) : ?> <span>+<?php echo $positions_count; endif; ?></span></h5>
							<?php endif; ?>
							<p><?php echo substr($long_profile, 0, 200); ?>…</p>
						</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>

<?php
	get_footer();
?>