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
	// acf check
	if ( function_exists('get_field') )	 :

		// Conditionnel pour flexible ACF
		if( have_rows('gn_flex_h', 'option') ) :

			 // loop through the rows of data
			while ( have_rows('gn_flex_h', 'option') ) : the_row();

			$output = '';

				// ZONE Text Slogan
				if( get_row_layout() == 'gn_z_text_hero' ):
					$ft = get_sub_field('taille_texte', 'option');
					$output .= "<section class='bloc-accueil bloc-accueil-hero clearfix'>";
					$output .= "<h2 style='font-size:{$ft}px; text-align: center;'>" . get_sub_field('zone_texte', 'option') . "</h2>";
					$output .= "</section>";

				// ZONE Editeur Colonne
				elseif( get_row_layout() == 'gn_z_editeur_grid' ):
					$output .= "<section class='bloc-accueil bloc-accueil-editor clearfix'>";
					if ( get_sub_field('bloc_title', 'option') ) :
						$output .= "<h4 class='widget-title widget-title-home'>" . get_sub_field('bloc_title', 'option') . "</h4>";
					endif;
					if ( have_rows('repeat_edit', 'option') ) :
						while ( have_rows('repeat_edit', 'option') ) : the_row();
							$w = get_sub_field('width_bloc', 'option');
							$output .= "<article class='editor-bloc entry' style='width:{$w}%;' itemscope='itemscope' itemtype='http://schema.org/BlogPosting' itemprop='blogPost'>";
							$output .= get_sub_field('editeur', 'option');
							$output .= "</article>";
						endwhile;
					else :
						$output .=  "<p>Aucune rangée n'est disponible";
					endif; // END if ( have_rows('repeat_edit', 'option')
					$output .= "<br class='clearfix'>";
					$output .= "</section>";

				// ZONE Contenu en Une
				elseif( get_row_layout() == 'gn_z_featured' ):
					$id = get_sub_field('featured_post', 'option');
					$type = get_sub_field('type_post', 'option');
					$link = get_permalink( $id );
					$thumbnail = get_the_post_thumbnail( $id, 'medium', array('class' => 'aligncenter') );
					$args = array(
						'post_type' => $type,
						'post__in' => array($id)
					);
					$query = new WP_Query($args);
					// aff_v($query);
					$output .= "<section class='bloc-accueil bloc-accueil-featured clearfix'>";
					if ( get_sub_field('bloc_title', 'option') ) :
						$output .= "<h4 class='widget-title widget-title-home'>" . get_sub_field('bloc_title', 'option') . "</h4>";
					endif;
					while ( $query->have_posts() ) : $query->the_post();
						$post_ID = get_the_ID();
						$class = get_post_class();
						$output .= "<article class='" . join(' ', $class) . "' itemscope='itemscope' itemtype='http://schema.org/BlogPosting' itemprop='blogPost'>";
						
						if( 'podcast' == $type ) {
							if( get_field('visuel_panoramique', $post_ID) ){
								$output .= '<a href="'. $link .'"><img src="'. get_field('visuel_panoramique', $post_ID) .'" class="aligncenter" alt="'. esc_attr( get_the_title() ) .'"></a>';
							}
						}else{
							$thumbnail = get_the_post_thumbnail( $post_ID, 'medium', array('class' => 'aligncenter') );
							if ( $thumbnail ) {
								$output .= "<a href='{$link}'>" . $thumbnail . "</a>";
							}
						}

						$output .= "<h2 class='entry-title'><a href='{$link}'>" . get_the_title() . "</a></h2>";
						$output .= "<p>" . get_the_excerpt() . "<br><a href='{$link}'>[ Lire la suite ]</a></p>";
						$output .= "</article>";
					endwhile; wp_reset_postdata();
					$output .= "</section>";

				// ZONE Boucle de contenu
				elseif( get_row_layout() == 'gn_z_query_content' ):
					$type = get_sub_field('type_post', 'option');
					$posts = get_sub_field('number_post', 'option');
					$offset = get_sub_field('number_offset','option');
					$archi_link = get_sub_field('link_archive', 'option');
					$archi_text = get_sub_field('text_archive', 'option');
					if ( $type == 'post' ) :
						$taxo = get_sub_field('taxo_post', 'option');
					else :
						$taxo = get_sub_field('taxo_podcast', 'option');
					endif;
					$display_archive = get_sub_field('display_archive', 'option');
					$archive_text = get_sub_field('text_archive', 'option');
					$archive_link = get_sub_field('link_archive', 'option');
					$args = array(
						'post_type' => $type,
						'posts_per_page' => $posts,
						'offset' => $offset,
						'tax_query' => array(
							array(
								'taxonomy' => $taxo->taxonomy,
								'field' => 'slug',
								'terms' => $taxo->slug
							),
						),
					);
					$query = new WP_Query($args);
					// aff_v($taxo);
					// aff_v($query); exit;
					$output .= "<section class='bloc-accueil bloc-accueil-loop clearfix'>";
					if ( get_sub_field('bloc_title', 'option') ) :
						$output .= "<h4 class='widget-title widget-title-home'>" . get_sub_field('bloc_title', 'option') . "</h4>";
					endif;
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) : $query->the_post();
							$post_ID = get_the_ID();
							$link = get_permalink($post_ID);
							
							$class = get_post_class();
							$output .= "<article class='" . join(' ', $class) . "' itemscope='itemscope' itemtype='http://schema.org/BlogPosting' itemprop='blogPost'>";
							$thumbnail = get_the_post_thumbnail( $post_ID, 'medium', array('class' => 'aligncenter') );
							if ( $thumbnail ) {
								$output .= "<a href='{$link}'>" . $thumbnail . "</a>";
							}
							$output .= "<h2 class='entry-title'><a href='{$link}'>" . get_the_title() . "</a></h2>";
							$output .= "<p>" . get_the_excerpt() . "<br><a href='{$link}'>[ Lire la suite ]</a></p>";
							$output .= "</article>";
						endwhile; wp_reset_postdata();
						if ( get_sub_field('display_archive', 'option') ) :
							$output .= "<p class='lien-archive'><a href='{$archi_link}'>{$archi_text}</a></p>";
						endif;

					else :
						$output .= "<p>Oups ! pas de contenu avec cette taxonomie mon ami(e)</p>";
					endif;
					$output .= "</section>";

				endif;

			echo $output;

			endwhile;

		else :

			echo "<p>Aucun champ flexible n'est actif sur cette page</p>";

		endif; // END if( have_rows('gn_flex_h')

	else :
		echo "<p>Merci d'activer le plugin Advanced Custom Field</p>";
	endif; // END if ( function_exists('get_field')
}

//* Run the Genesis loop
genesis();
