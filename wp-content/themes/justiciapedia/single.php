<?php
	get_header();

	the_post();

	$entity_type = current(get_the_terms($post->ID, 'entity_type'));
	$instance = 'entity_' . $entity_type->slug . '_';
	/* Fields */
	$positions = get_field('entity_person_positions', $post->ID);
	$profile_type_card = get_field('entity_person_profile_type', $post->ID);
	$twitter_url = get_field('entity_person_twitter', $post->ID);
	$documents = get_field_object($instance . 'files', $post->ID);

	if($entity_type->slug == 'person') {
		$long_profile = get_field('entity_person_profile', $post->ID);
		$short_profile = get_field('entity_person_profile', $post->ID);
		$shortname = get_field('entity_person_shortname', $post->ID);

		$controversies = get_field_object($instance . 'controversies', $post->ID);
	}
	else {
		$long_profile = get_field('entity_organization_profile', $post->ID);
		$short_profile = get_field('entity_organization_profile', $post->ID);
		$shortname = '';
		$controversies = '';
	}
?>

	<section class="row-module section-profile">
			<div class="row clearfix">

				<aside class="profile-aside col4">
					
					<div class="profile-header">
						<figure>
							<img src="<?php echo w_entity_avatar($post->ID); ?>" alt="<?php the_title(); ?>" />
						</figure>
						<?php if(!empty($shortname)) : ?>
						<h2><?php echo $shortname; ?></h2>
						<?php else : ?>
						<h2><?php the_title(); ?></h2>
						<?php endif; ?>
						<h3><?php echo w_translate_terms($profile_type_card->slug); ?></h3>
					</div>

					<div class="collapsed-group">

						<div class="collapsed-heading">
							<a href="#" class="btn-collapse collapsed" data-toggle="collapse" data-target="#aside-info"><i></i> Información personal</a>
						</div>

						<div id="aside-info" class="collapsed-body collapse navbar-collapse">
							<ul class="profile-info">
								<?php if($entity_type->slug == 'person') : ?>
								<li>
									<label>Cargos</label>
									<ul class="profile-positions">
									<?php
										foreach($positions as $position) :
									?>
									<li>
									<?php
										echo $position['position'];
									?>
									</li>
									<?php
										endforeach;
									?>
									</ul>
								</li>
								<?php endif; ?>
								<!--<li>
									<label>Afiliación política</label>
									Independiente
								</li>
								<li>
									<label>Sitio Web</label>
									<a href="#">www.johnapplessed.com</a>
								</li>-->
								<?php
									if(!empty($twitter_url)) :
										$twitter_username = explode('twitter.com/', $twitter_url);
								?>
								<li>
									<label>Twitter</label>
									<a href="<?php echo $twitter_url; ?>" target="_blank">@<?php echo $twitter_username[0]; ?></a>
								</li>
								<?php endif; ?>
								<?php
									if(!empty($short_profile)) :
										$short_profile = substr($short_profile, 0, 150);
								?>
								<li class="short-profile">
									<p><?php echo $short_profile; ?>… <a href="#">Leer más &rsaquo;</a></p>
								</li>
							<?php endif; ?>
							</ul>
						</div>
						
						<div class="collapsed-heading">
							<a href="#" class="btn-collapse collapsed" data-toggle="collapse" data-target="#aside-nav"><i></i> Opciones</a>
						</div>
						
						<div id="aside-nav" class="collapsed-body collapse navbar-collapse">
							<nav>
								<a href="#" class="btn btn-success"><i class="icon icon-16 flaticon stroke plus"></i> Recibir actualizaciones</a>
								<!--<ul>
									<li><a href="#"><i class="icon icon-16 flaticon stroke pin-1"></i> Línea de acontecimientos</a></li>
								</ul>-->
								<h6>Colaborar</h6>
								<ul class="moar">
									<li><a href="#"><i class="icon icon-16 flaticon stroke share-1"></i> Enviar Datos</a></li>
									<li><a href="#"><i class="icon icon-16 flaticon stroke share-3"></i> Sugerir Conexiones</a></li>
									<li><a href="#"><i class="icon icon-16 flaticon stroke info-2"></i> Reportar Error</a></li>
								</ul>
								<ul class="moar">
									<li><a href="https://www.donaronline.org/chequeado-com/donantes-fieles" target="_blank"><i class="icon icon-16 flaticon stroke heart"></i> Doná acá</a></li>
								</ul>
							</nav>
						</div>
					</div>
				</aside><!--/aside-->

				<div class="col12">
					<div class="content">

						<nav class="filters profile-nav clearfix">
							<a href="#profile-connections" class="active">Conexiones</a>
							<?php if(!empty($long_profile)) : ?>
							<a href="#profile-profile">Perfil</a>
							<?php endif; ?>
							<?php
								if($controversies['value'] and count($controversies['value']) > 0) :
							?>
							<a href="#profile-controversies">Controversias</a>
							<?php endif; ?>
							<?php
								if($documents['value'] and count($documents['value']) > 0) :
							?>
							<a href="#profile-documents">Documentos</a>
							<?php
								endif;
							?>
							<!--<a href="#">Noticias</a>-->
						</nav>
						
						<div class="profile-pane" id="profile-connections" style="display: block;">
							<div class="wiremap">
								<div class="col10">
									<iframe src="<?php bloginfo('url'); ?>/viz/?profile_id=<?php echo $post->ID; ?>&profile_type=<?php echo $entity_type->slug; ?>" frameborder="0" scrolling="no" width="700" height="350"></iframe>
								</div>
							</div>

							<!-- conexiones -->
							<div class="col10">
								<?php
									$conn = get_field_object('entity_person_family', $post->ID);

									if($conn['value'] and count($conn['value']) > 0) :
								?>
								<div class="heading-min">
									<h6>Familia</h6>
								</div>
								<table width="100%">
									<tbody>
										<?php
											foreach($conn['value'] as $item) :
												$rel1 = $item['relation'];

												$rel2 = $item['name'][0]->post_title;
												$rel2_link = get_permalink($item['name'][0]->ID);
												$overview = $item['connection_overview'];
										?>
										<tr>
											<td><?php echo w_translate_terms($rel1); ?></td>
											<td>de <a href="<?php echo $rel2_link; ?>"><?php echo $rel2; ?></a></td>
											<?php if(!empty($overview)) : ?>
											<td><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $overview; ?>"><i class="icon icon-16 flaticon stroke question-mark" style="display: inline-block; vertical-align: text-bottom;"></i> Relación</a></td>
											<?php endif; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php endif; ?>

								<?php
									$conn = get_field_object('entity_person_friends', $post->ID);

									if($conn['value'] and count($conn['value']) > 0) :
								?>
								<div class="heading-min">
									<h6>Amigos y conocidos</h6>
								</div>
								<table width="100%">
									<tbody>
										<?php
											foreach($conn['value'] as $item) :
												$rel1 = $item['name'][0]->post_title;
												$rel1_link = get_permalink($item['name'][0]->ID);

												$rel2 = $item['in'][0]->post_title;
												$rel2_link = get_permalink($item['in'][0]->ID);
												$overview = $item['connection_overview'];
										?>
										<tr>
											<td>Cercano/a</td>
											<td>de <a href="<?php echo $rel1_link; ?>"><?php echo $rel1; ?></a></td>
											<td><?php if(!empty($rel2)) : ?>en <a href="<?php echo $rel2_link; ?>"><?php echo $rel2; ?></a><?php endif; ?></td>
											<?php if(!empty($overview)) : ?>
											<td><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $overview; ?>"><i class="icon icon-16 flaticon stroke question-mark" style="display: inline-block; vertical-align: text-bottom;"></i> Relación</a></td>
											<?php endif; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php endif; ?>

								<?php
									$conn = get_field_object('entity_person_education', $post->ID);

									if($conn['value'] and count($conn['value']) > 0) :
								?>
								<div class="heading-min">
									<h6>Educación</h6>
								</div>
								<table width="100%">
									<tbody>
										<?php
											foreach($conn['value'] as $item) :
												$rel1 = $item['relation'];

												$career = $item['career'];

												$rel2 = $item['name'][0]->post_title;
												$rel2_link = get_permalink($item['name'][0]->ID);

												$when = w_determine_time($item['date_from'], $item['date_to']);

												$overview = $item['connection_overview'];
										?>
										<tr>
											<td><?php echo w_translate_terms($rel1); ?> <?php if(!empty($career)) : ?>de <?php echo $career; ?><?php endif; ?></td>
											<?php if(!empty($rel2)) : ?>
											<td>en <a href="<?php echo $rel2_link; ?>"><?php echo $rel2; ?></a></td>
											<?php endif; ?>
											<?php if($when != '—') : ?>
											<td><?php echo $when; ?></td>
											<?php endif; ?>
											<?php if(!empty($overview)) : ?>
											<td><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $overview; ?>"><i class="icon icon-16 flaticon stroke question-mark" style="display: inline-block; vertical-align: text-bottom;"></i> Relación</a></td>
											<?php endif; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php endif; ?>

								<?php
									$conn = get_field_object('entity_person_business', $post->ID);

									if($conn['value'] and count($conn['value']) > 0) :
								?>
								<div class="heading-min">
									<h6>Empresas y propiedad</h6>
								</div>
								<table width="100%">
									<tbody>
										<?php
											foreach($conn['value'] as $item) :
												$rel1 = $item['relation'];

												$rel2 = $item['name'][0]->post_title;
												$rel2_link = get_permalink($item['name'][0]->ID);

												$when = w_determine_time($item['date_from'], $item['date_to']);

												$overview = $item['connection_overview'];
										?>
										<tr>
											<td><?php echo w_translate_terms($rel1); ?></td>
											<?php if(!empty($rel2)) : ?>
											<td>en <a href="<?php echo $rel2_link; ?>"><?php echo $rel2; ?></a></td>
											<?php endif; ?>
											<?php if($when != '—') : ?>
											<td><?php echo $when; ?></td>
											<?php endif; ?>
											<?php if(!empty($overview)) : ?>
											<td><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $overview; ?>"><i class="icon icon-16 flaticon stroke question-mark" style="display: inline-block; vertical-align: text-bottom;"></i> Relación</a></td>
											<?php endif; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php endif; ?>

								<?php
									$conn = get_field_object('entity_person_organizations', $post->ID);

									if($conn['value'] and count($conn['value']) > 0) :
								?>
								<div class="heading-min">
									<h6>Organizaciones</h6>
								</div>
								<table width="100%">
									<tbody>
										<?php
											foreach($conn['value'] as $item) :
												$rel1 = $item['relation'];

												$rel2 = $item['name'][0]->post_title;
												$rel2_link = get_permalink($item['name'][0]->ID);

												$when = w_determine_time($item['date_from'], $item['date_to']);

												$overview = $item['connection_overview'];
										?>
										<tr>
											<td><?php echo w_translate_terms($rel1); ?></td>
											<?php if(!empty($rel2)) : ?>
											<td>en <a href="<?php echo $rel2_link; ?>"><?php echo $rel2; ?></a></td>
											<?php endif; ?>
											<?php if($when != '—') : ?>
											<td><?php echo $when; ?></td>
											<?php endif; ?>
											<?php if(!empty($overview)) : ?>
											<td><a href="#" data-toggle="tooltip" data-placement="top" title="<?php echo $overview; ?>"><i class="icon icon-16 flaticon stroke question-mark" style="display: inline-block; vertical-align: text-bottom;"></i> Relación</a></td>
											<?php endif; ?>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php endif; ?>
							</div>
							<!-- / conexiones -->
						</div>

						<div class="profile-pane" id="profile-profile">
							<h3 class="pane-title">Perfil</h3>

							<?php if(!empty($long_profile)) : ?>
							<!-- text -->
							<div class="text">
								<?php
									echo get_field($instance . 'profile', $post->ID);
								?>
							</div>
							<!-- / text -->
							<?php else : ?>
							<div class="empty-data">
								No hay reseña para este perfil.
							</div>
							<?php endif; ?>
						</div>
					 	
					 	<div class="profile-pane" id="profile-controversies">
					 		<h3 class="pane-title">Controversias</h3>

					 		<?php
					 			$controversies = get_field_object($instance . 'controversies', $post->ID);

					 			if($controversies['value'] and count($controversies['value']) > 0) :
					 				foreach($controversies['value'] as $item) :
					 		?>

							<div class="archive">
								<article style="margin-bottom: 30px;">
									<time><?php echo $item['date']; ?></time>
									<h4><?php echo $item['title']; ?></h4>
									<p><?php echo $item['overview']; ?></p>
								</article>
							</div>
							<?php endforeach; else : ?>
							<div class="empty-data">
								No hay controversias para este perfil.
							</div>
							<?php endif; ?>
					 	</div>
						
						<div class="profile-pane" id="profile-documents">
							<h3 class="pane-title">Documentos y Archivos</h3>

					 		<?php
					 			$documents = get_field_object($instance . 'files', $post->ID);
					 			
					 			if($documents['value'] and count($documents['value']) > 0) :
					 		?>
							<div class="archive">
								<table>
									<thead>
										<tr>
											<th style="text-align: left; font-size: 14px; font-weight: bold; paddiing-bottom: 7px;">Nombre</th>
											<th style="text-align: left; font-size: 14px; font-weight: bold; paddiing-bottom: 7px;">Descripción</th>
											<th style="text-align: left; font-size: 14px; font-weight: bold; paddiing-bottom: 7px;">Descarga</th>
										</tr>
									</thead>

									<tbody>
										<?php foreach($documents['value'] as $item) : ?>
										<tr>
											<td style="padding: 15px 0; border: 0;"><a href="<?php echo wp_get_attachment_url($item['file']); ?>" target="_blank"><?php echo $item['title']; ?></a></td>
											<td style="padding: 15px 0; border: 0;"><?php echo $item['description']; ?></td>
											<td style="padding: 15px 0; border: 0;"><a href="<?php echo wp_get_attachment_url($item['file']); ?>" target="_blank">Descargar &darr;</a></td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<?php else : ?>
							<div class="empty-data">
								No hay documentos para este perfil.
							</div>
							<?php endif; ?>
					 	</div>

					</div>
				</div><!--/col12-->

			</div>
		</section><!--/section-profile-->

<?php
	get_footer();
?>