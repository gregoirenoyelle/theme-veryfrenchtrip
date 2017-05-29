<?php

//* Minimum custom body class
function minimum_add_body_class( $classes ) {
	$classes[] = 'minimum';
		return $classes;
}

//* Add widget support for homepage if widgets are being used
add_action( 'genesis_meta', 'minimum_front_page_genesis_meta' );
function minimum_front_page_genesis_meta() {

	if ( is_home() && is_plugin_active('advanced-custom-fields-pro/acf.php') ) {

		//* Remove entry meta in entry footer and Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		//* Add Genesis grid loop
		add_action( 'genesis_loop', 'vft_loop_home' );

		//* Remove entry footer functions
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

		//* Force full width content layout
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
	}


}


//* Loop Flexible ACF
function vft_loop_home() {

	// Conditionnel pour flexible ACF
	if( have_rows('gn_flex_h', 'option') ) :

		 // loop through the rows of data
		while ( have_rows('gn_flex_h', 'option') ) : the_row();

		$output = '';

			// ZONE Text Slogan
			if( get_row_layout() == 'gn_z_text_hero' ):
				$ft = get_sub_field('taille_texte', 'option');
				$output .= '<section class="bloc-accueil bloc-accueil-hero clearfix">';
				$output .= sprintf ('<h2 style="font-size:%spx; text-align: center;">%s</h2>', $ft, get_sub_field('zone_texte', 'option') );
				$output .= '</section>';

			// ZONE Editeur Colonne
			elseif( get_row_layout() == 'gn_z_editeur_grid' ):
				$output .= '<section class="bloc-accueil bloc-accueil-editor clearfix">';
				if ( get_sub_field('bloc_title', 'option') ) :
					$output .= sprintf('<h4 class="widget-title widget-title-home">%s</h4>' , get_sub_field('bloc_title', 'option') );
				endif;
				if ( have_rows('repeat_edit', 'option') ) :
					while ( have_rows('repeat_edit', 'option') ) : the_row();
						$output .= sprintf( '<article class="editor-bloc entry" style="width:%s%%" itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">%s</article>', get_sub_field('width_bloc', 'option'), get_sub_field('editeur', 'option') );
					endwhile;
				else :
					$output .=  '<p>Aucune rangée n\'est disponible</p>';
				endif; // END if ( have_rows('repeat_edit', 'option')
				$output .= '<br class="clearfix">';
				$output .= '</section>';

			// ZONE Podcast en Une
			elseif( get_row_layout() == 'gn_z_featured' ):
				$titre_widget = get_sub_field('bloc_title', 'option');
				// Affichage du dernier podcast
				$args = array(
					'post_type' => 'podcast',
					'posts_per_page' => 1
				);
				$query = new WP_Query($args);

				$output .= '<section class="bloc-accueil bloc-accueil-featured clearfix">';

				if ( $titre_widget ) :
					$output .= sprintf('<h4 class="widget-title widget-title-home">%s</h4>', $titre_widget);
				endif;
				while ( $query->have_posts() ) : $query->the_post();
					$permalink = get_permalink();
					$class = get_post_class();
					$img_id = get_field('visuel_panoramique');
					$img = wp_get_attachment_image( $img_id, 'full');
					$output .= sprintf('<article class="%s" itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">', join(' ', $class) );
					if ( $img ) {
							$output .= sprintf('<a href="%s">%s</a>', $permalink, $img ) ;
					}

					$output .= sprintf( '<h2 class="entry-title"><a href="%s">%s</a></h2>', $permalink, get_the_title() ) ;
					$output .= sprintf( '<p>%s<br><a href="%s">[ Lire la suite ]</a></p>', get_the_excerpt(), $permalink );
					$output .= '</article>';
				endwhile; wp_reset_postdata();

				$output .= '</section>';

			// ZONE Boucle de Podcast
			elseif( get_row_layout() == 'gn_z_query_content' ):
				$titre = get_sub_field('bloc_title', 'option');
				$posts = get_sub_field('number_post', 'option');
				$offset = get_sub_field('number_offset', 'option');
				$taxo = get_sub_field('taxo_podcast', 'option');
				$display_archive = get_sub_field('display_archive', 'option');
				$archive_text = get_sub_field('text_archive', 'option');
				$archive_link = get_sub_field('link_archive', 'option');
				$args = array(
					'post_type' => 'podcast',
					'posts_per_page' => $posts,
					'offset' => $offset,
					'tax_query' => array(
						array(
							'taxonomy' => 'series',
							'field' => 'ID',
							'terms' => $taxo
						),
					),
				);
				$query = new WP_Query($args);
				$output .= '<section class="bloc-accueil bloc-accueil-loop clearfix">';
				if ( $titre ) :
					$output .= sprintf( '<h4 class="widget-title widget-title-home"></h4>', $titre );
				endif;
				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) : $query->the_post();
						$permalink = get_permalink();
						$class = get_post_class();
						$output .= sprintf( '<article class="%s" itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">', join(' ', $class) );
						if ( has_post_thumbnail() ) {
							$output .= sprintf( '<a href="%s">%s</a>', $permalink, get_the_post_thumbnail('', 'medium') );
						}
						$output .= sprintf( '<h2 class="entry-title"><a href="%s">%s gougou</a></h2>', $permalink, get_the_title() ) ;
						$output .= sprintf( '<p>%s<br><a href="%s">[ Lire la suite ]</a></p>', get_the_excerpt(), $permalink );
						$output .= '</article>';
					endwhile; wp_reset_postdata();

					if ( $display_archive ) :
						$output .= sprintf( '<p class="lien-archive"><a href="%s">%s</a></p>', $archive_link, $archive_text  );
					endif;

				else :
					$output .= '<p>Oups ! pas de contenu avec cette taxonomie mon ami(e)</p>';
				endif;
				$output .= '</section>';

			endif;

		echo $output;

		endwhile;

	else :

		echo '<p>Aucun champ flexible n\'est actif sur cette page</p>';

	endif; // END if( have_rows('gn_flex_h')



}

//* Run the Genesis loop
genesis();
