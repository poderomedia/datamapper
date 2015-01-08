<?php
	get_header();

	$entities_count = wp_count_posts('entity');
?>

	<section class="section-featured">
			<div class="mask-image"></div>
			<a href="#" class="ctrl-down"><i class="icon icon-32 flaticon stroke down-2"></i></a>
			<div class="row-table row-table-bottom">
				<div class="row-cell">

					<div class="row-module">
						<div class="row">
							<div class="col16">
								<div class="heading heading-center">
									<hr />
									<h1><?php echo get_field('site_intro_title', 'option'); ?></h1>
									<h2><?php echo get_field('site_intro_description', 'option'); ?></h2>
								</div> 
								<form>
									<i class="icon icon-32 flaticon stroke zoom-2"></i>
									<input type="text" class="input" placeholder="Buscar perfiles..." />
									<input type="submit" class="input-submit" value="Buscar" />
								</form>
								<!--<div class="form-advanced">
									<a href="#">Búsqueda avanzada</a>
								</div>-->
								<ul class="share-social clearfix">
									<li><div class="fb-like" data-href="https://chequeado.com/justiciapedia" data-layout="button_count" data-action="recommend" data-show-faces="false" data-share="false"></div></li>
									<li><a href="https://twitter.com/intent/tweet?button_hashtag=justiciapedia&text=La%20Plataforma%20de%20la%20familia%20judicial" class="twitter-hashtag-button" data-lang="es" data-related="chequeado" data-url="http://chequeado.com/justiciapedia">Tweet #justiciapedia</a></li>
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
		
		<section class="row-module row-white section-steps">
			<div class="row clearfix">
				<div class="col4 prefix1">
					<span><i class="icon icon-32 flaticon stroke pin-1"></i></span>
					<h6 style="text-align: center;"><strong>1)</strong> Búsqueda de datos</h6>
					<p style="text-align: center;">Para esta base sólo utilizamos datos abiertos.</p>
				</div>
				<div class="col4 prefix1">
					<span><i class="icon icon-32 flaticon stroke pin-1"></i></span>
					<h6 style="text-align: center;"><strong>2)</strong> Análisis de las relaciones</h6>
					<p style="text-align: center;">En virtud de las conexiones que puedan afectar la ética de las profesiones.</p>
				</div>
				<div class="col4 prefix1">
					<span><i class="icon icon-32 flaticon stroke pin-1"></i></span>
					<h6 style="text-align: center;"><strong>3)</strong> Apertura</h6>
					<p style="text-align: center;">Datos abiertos y participación de la comunidad.</p>
				</div>
			</div>
		</section>

		<section class="row-module">
			<div class="row clearfix">
				<div class="col16">

					<ul class="visible-m right">
						<li class="dropdown placement-right">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-default">Destacados <i class="icon icon-16 flaticon stroke down-1"></i></a>
							<ul class="dropdown-menu">
								<li><a href="#">Recientes</a></li>
								<li><a href="#">Lo más visto</a></li>
							</ul>
						</li>
					</ul>

					<nav class="hidden-m filters right clearfix">
						<a href="#" class="active">Destacados</a>
						<!--<a href="#">Recientes</a>
						<a href="#">Lo más visto</a>-->
					</nav>

					<div class="heading">
						<h3>Perfiles</h3>
					</div> 
				</div>
				<div class="people-list clearfix">
					<?php
						$featured_args = array(
							'post_type'         => 'entity',
							'meta_key'          => 'entity_prop_featured',
							'meta_value'        => 1,
							'posts_per_page'    => 7,
							'orderby'           => 'date',
							'order'             => 'desc'
						);
						
						$featured = query_posts($featured_args);
						
						$i = 1;
						foreach($featured as $item) :
							
						$entity_type = current(get_the_terms($item->ID, 'entity_type'));
						$profile_type = get_field('entity_person_profile_type', $item->ID);

						if($entity_type->slug == 'person') {
							$profile_positions = get_field('entity_person_positions', $item->ID);
							$positions_count = count($profile_positions);
						}
					?>
					<div class="col4">
						<div class="people">
							<a href="<?php echo get_permalink($item->ID); ?>">
								<figure>
									<img src="<?php echo w_entity_avatar($item->ID); ?>" alt="<?php echo get_the_title($item->ID); ?>" />
								</figure>
								<h4><?php echo get_the_title($item->ID); ?></h4>
								<?php if($entity_type->slug == 'person') : ?>
								<h5><?php echo w_translate_terms($profile_type->slug); ?><?php if($positions_count > 0) : ?> <span>+<?php echo $positions_count; endif; ?></span></h5>
								<?php endif; ?>
								<!--<hr />
								<h6>Other title</h6>-->
							</a>
						</div>
					</div>
					<?php endforeach; ?>
					<div class="col4">
						<div class="more">
							<a href="#">
								<h6><?php echo $entities_count->publish; ?>+<span>Perfiles</span></h6>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="row-module row-white">
			<div class="row clearfix">
				<div class="col16">
					<div class="heading heading-center">
						<i class="icon icon-32 flaticon stroke pin-1"></i>
						<h3>¿Qué es Justiciapedia y cómo funciona?</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodte mpor incididunt.</p>
						<div class="heading-actions">
							<a href="#" class="btn btn-primary"><i class="icon icon-16 flaticon stroke plus-1"></i> Más información</a>
						</div>
					</div>   
				</div>
			</div>
		</section>

<?php
	get_footer();
?>