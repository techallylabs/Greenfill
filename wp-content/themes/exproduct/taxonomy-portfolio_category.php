<?php
/*** The template for displaying portfolio categories. ***/

get_header();

$exproduct_layout = exproduct_get_option('portfolio_settings_sidebar_type', '2');
$exproduct_sidebar = exproduct_get_option('portfolio_settings_sidebar_content', 'sidebar-1');

if ( ! is_active_sidebar($exproduct_sidebar) ) $exproduct_layout = '1';

$exproduct_portfolio_perrrow = exproduct_get_option('portfolio_settings_perrow', '2');
if ( $exproduct_portfolio_perrrow == '3' ) {
	$exproduct_add_class_port_col = 'col-md-4 col-sm-4 col-xs-6';
}
elseif ( $exproduct_portfolio_perrrow == '4' ) {
	$exproduct_add_class_port_col = 'col-md-3 col-sm-4 col-xs-6';
}
else {
	$exproduct_add_class_port_col = 'col-md-6 col-sm-6 col-xs-6';
}

$exproduct_portfolio_perrow = exproduct_get_option('portfolio_settings_perrow', '2');
$exproduct_portfolio_css_animation = ( exproduct_get_option('css_animation_settings_portfolio', '') != '' ) ? ' wow '.exproduct_get_option('css_animation_settings_portfolio', '') : '';
$exproduct_portfolio_type = exproduct_get_option('portfolio_settings_type', 'type_without_icons');
$exproduct_portfolio_loadmore = exproduct_get_option('portfolio_settings_loadmore', esc_html__('Load more', 'exproduct' ) );

$exproduct_portfolio_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

?>

<!-- ========================== -->
<!-- BLOG - CONTENT -->
<!-- ========================== -->
<section class="page-section">
	<div class="container">
		<div class="row">

			<?php exproduct_show_sidebar( 'left', $exproduct_layout, $exproduct_sidebar ); ?>

			<div class="<?php if ( $exproduct_layout == 1 ) : ?>col-lg-12 col-md-12<?php else : ?>col-lg-9 col-md-8<?php endif; ?> col-sm-12 col-xs-12 left-column sidebar-type-<?php echo esc_attr($exproduct_layout); ?>">

				<div id="portfolio-category-section" class="portfolio-list-section portfolio-perrow-<?php echo esc_attr($exproduct_portfolio_perrow); ?>">

				<?php $exproduct_portfolio_category_description = get_term_field( 'description', $exproduct_portfolio_term->term_id, 'portfolio_category' );
				if( !is_wp_error( $exproduct_portfolio_category_description ) && $exproduct_portfolio_category_description != '' ) :
				?>
					<div class="section-heading text-center">
						<div class="section-subtitle"><?php echo wp_kses_post($exproduct_portfolio_category_description);?></div>
						<div class="design-arrow"></div>
					</div>

				<?php
				endif;

					$exproduct_portfolio_categories = get_objects_in_term( $exproduct_portfolio_term->term_id, 'portfolio_category');

					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

					$args = array(
								'post_type' => 'portfolio',
								'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
								'post__in' => $exproduct_portfolio_categories,
								'paged' => $paged
							);

					$wp_query = new WP_Query( $args );

					if ( $wp_query->have_posts() ) : ?>

						<div class="row portfolio-masonry-holder list-works clearfix">
						<?php
						while ( $wp_query->have_posts() ) :
							$wp_query->the_post();

							$exproduct_portfolio_post_type = ( class_exists( 'RW_Meta_Box' ) && rwmb_meta('post_types_select') != '' ) ? rwmb_meta('post_types_select') : 'image';

							$cats = wp_get_object_terms(get_the_id(), 'portfolio_category');
							$exproduct_cat_slugs = '';
							if ( ! empty($cats) ) {
								foreach ( $cats as $cat ) {
									$exproduct_cat_slugs .= $cat->slug . " ";
								}
							}
							$exproduct_portfolio_thumbnail = get_the_post_thumbnail(get_the_ID(), 'exproduct-portfolio-thumb', array('class' => 'img-responsive'));
							$exproduct_portfolio_thumbnail_full = get_the_post_thumbnail_url(get_the_ID(), 'full');

							// potfolio category list linked
							$exproduct_portfolio_linked_list_cats = exproduct_get_post_terms( array( 'taxonomy' => 'portfolio_category', 'items_wrap' => '%s' ) );

							if ( $exproduct_portfolio_type == 'type_without_icons' || $exproduct_portfolio_type == 'type_without_space' ) : ?>

									<div class="<?php echo esc_attr($exproduct_add_class_port_col); ?> item <?php echo esc_attr($exproduct_portfolio_css_animation); ?> <?php echo esc_attr($exproduct_cat_slugs); ?>" id="post-<?php echo esc_attr(get_the_ID()); ?>">
										<div class="portfolio-item">
											<div class="portfolio-image">
												<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><?php echo wp_kses_post($exproduct_portfolio_thumbnail); ?></a>
												<div class="gallery-item-hover">
                                                    <a href="<?php echo esc_url( $exproduct_portfolio_thumbnail_full ); ?>" class="fancybox">
                                                        <span class="item-hover-icon"><i class="fa fa-search"></i></span>
                                                    </a>
                                                    <a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>">
                                                        <span class="item-hover-icon"><i class="fa fa-link"></i></span>
                                                    </a>
                                                </div>
												<div class="portfolio-item-body">
													<div class="name"><?php echo wp_kses_post( get_the_title() ); ?></div>
													<div class="under-name"><?php echo wp_kses_post( $exproduct_portfolio_linked_list_cats ); ?></div>
												</div>
											</div>
										</div>
									</div>

							<?php
							else : ?>

									<div class="<?php echo esc_attr($exproduct_add_class_port_col); ?> item <?php echo esc_attr($exproduct_portfolio_css_animation); ?> <?php echo esc_attr($exproduct_cat_slugs); ?>" id="post-<?php echo esc_attr(get_the_ID()); ?>">
										<div class="portfolio-item">
											<div class="portfolio-image">
												<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><?php echo wp_kses_post($exproduct_portfolio_thumbnail); ?></a>
												<div class="portfolio-item-body center-body">
													<ul>
														<?php
														if ( $exproduct_portfolio_post_type == 'image' ) :
															$exproduct_portfolio_gallery = ( class_exists( 'RW_Meta_Box' ) ) ? rwmb_meta('portfolio_images', 'type=image&size=full') : '';
															$exproduct_portfolio_full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'full', false);
															$exproduct_portfolio_full_image_link = $exproduct_portfolio_full_image[0];
															?>
															<li><a href="<?php echo esc_url($exproduct_portfolio_full_image_link); ?>"  rel="prettyPhoto[pp_gal_<?php echo esc_attr(get_the_id());?>]"><span class="icon icon-Search"></span></a></li>
															<?php
															if ( $exproduct_portfolio_gallery ) :
																foreach ( $exproduct_portfolio_gallery as $key => $slide ) :
																	if ( $key > 0 ) :
																	?>
																		<div class="portfolio-gallery-none">
																			<a href="<?php echo esc_url($slide['url']); ?>" rel="prettyPhoto[pp_gal_<?php echo esc_attr($post->ID); ?>]" ><img src="<?php echo esc_url($slide['url']); ?>" width="<?php echo esc_attr($slide['width']); ?>" height="<?php echo esc_attr($slide['height']); ?>" alt="<?php echo esc_attr($slide['alt']); ?>" title="<?php echo esc_attr($slide['title']); ?>"/></a>
																		</div>
																	<?php
																	endif;
																endforeach;
															endif;
														 ?>
														<?php
														endif; ?>
														<?php
														if ( $exproduct_portfolio_post_type == 'video' ) :
															$exproduct_portfolio_video_href = ( class_exists( 'RW_Meta_Box' ) ) ? get_post_meta( get_the_ID(), 'portfolio_video_href', true ) : '';
															if ( $exproduct_portfolio_video_href != '' ) :
																$exproduct_portfolio_video_width = ( class_exists( 'RW_Meta_Box' ) ) ? rwmb_meta('portfolio_video_width') : '';
																$exproduct_portfolio_video_height = ( class_exists( 'RW_Meta_Box' ) ) ? rwmb_meta('portfolio_video_height') : '';
																?>
																<li><a href="<?php echo esc_url($exproduct_portfolio_video_href.'?width='.esc_attr($exproduct_portfolio_video_width).'&amp;height='. esc_attr($exproduct_portfolio_video_height)) ?>" rel="prettyPhoto[pp_video_<?php echo esc_attr(get_the_id());?>]"><span class="icon icon-Media"></span></a></li>
															<?php
															endif;
														endif;
														?>
															<li><a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"><span class="icon icon-Info"></span></a></li>
														<?php



														?>
													</ul>
												</div>
											</div>
											<div class="portfolio-item-footer">
												<div class="name"><?php echo wp_kses_post( get_the_title() ); ?></div>
												<div class="under-name"><?php echo wp_kses_post($exproduct_portfolio_linked_list_cats); ?></div>
											</div>
										</div>
									</div>

							<?php
							endif;

						endwhile; ?>
						</div>

						<?php
						if ( get_next_posts_link( '', $wp_query->max_num_pages ) ) {

							echo '
								<div class="row">
									<div class="col-md-12 text-center">
										<div class="portfolio-pagination">
											<span data-current="'.esc_attr($paged).'" data-max-pages="'.esc_attr($wp_query->max_num_pages).'" class="load-more">' . get_next_posts_link( wp_kses_post($exproduct_portfolio_loadmore), $wp_query->max_num_pages) . '</span>
										</div>
										<div class="portfolio-pagination-loading">
											<a href="javascript: void(0)" class="btn btn-default">'. esc_html__("Loading...", "exproduct") .'</a>
										</div>
									</div>
								</div>
							';
						}
						?>

					<?php
					endif;
				?>
				</div>

			</div>

			<?php exproduct_show_sidebar( 'right', $exproduct_layout, $exproduct_sidebar ); ?>

		</div>
	</div>
</section>

<?php get_footer(); ?>