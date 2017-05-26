<?php

remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );


add_action('genesis_before_entry', 'gn_breadcrumb_wordcampeur');
function gn_breadcrumb_wordcampeur() {
	global $post;

	echo '<div class="breadcrumb taxo-wordcampeur">';
	echo '<strong>Filtres de navigation</strong>&nbsp;: <br />';
	echo get_the_term_list($post->ID, 'gntx_pays', 'Pays&nbsp;: ', ' ', '<br />' );
	echo get_the_term_list($post->ID, 'gntx_evenement', 'Conférence&nbsp;: ', ' ', '<br />' );
	// if ( !get_the_term_list($post->ID, 'gntx_evenement') ) :
	// si nécessaire un conditionnel pour éviter le doublon. Pas sûr
	echo get_the_term_list($post->ID, 'gntx_spectateur', 'French Trip&nbsp;: ', ' ', '' );
	// endif;
	echo '</div>';


} // gn_content_wordcampeur

// CONDTIONNEL ACF
if ( function_exists('get_field') ) {

	add_action('genesis_entry_content', 'gn_content_wordcampeur_base', 50);
	function gn_content_wordcampeur_base() {
		gn_meta_wordcampeur_image('wordcampeur', 250);
		gn_meta_wordcampeur_texte();

	} // gn_content_wordcampeur

	add_action('genesis_entry_content', 'gn_content_wordcampeur_sup', 60);
	function gn_content_wordcampeur_sup() {
	if ( get_field('gn_publier_bio') )	:
		echo '<h3 class="bio-wordcampeur">Bio</h3>';
		if ( in_array('biofr', get_field('gn_publier_bio') ) ) :
		echo '<section class="bio-french">';
		the_field('gn_bio');
		endif;
		echo '</section>';
		if ( in_array('bioeng', get_field('gn_publier_bio') ) ) :
		echo '<section class="bio-english">';
			the_field('gn_bio_eng');
		echo '</section>';
		endif;
	endif;
	} // gn_content_wordcampeur

}

// CONDTIONNEL ACF

add_action('genesis_entry_content', 'gn_content_wordcampeur_articles', 70);
function gn_content_wordcampeur_articles() {
$gn_user_id = get_the_author_meta('ID');
$gn_user_posts = count_user_posts( $gn_user_id );
	if ( $gn_user_posts != 0 ) :
		$gn_loop_post = new WP_Query (
			array(
				'author' => $gn_user_id,
				'posts_per_page' => -1
			)
		); // fin WP_Query

		?>
		<h3>Articles récents</h3>
		<ul>
		<?php
		while ( $gn_loop_post->have_posts() ) : $gn_loop_post->the_post(); ?>

			<li><a href="<?php the_permalink();?>"><?php the_title(); ?></a></li>
		<?php endwhile;
		wp_reset_postdata();
		?>
		</ul>
	<?php endif;

}


genesis();