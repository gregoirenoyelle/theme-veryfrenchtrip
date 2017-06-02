<?php

///////////////////////
// FILTRES GENESIS
// APPLIQUÉ SUR ARCHIVES
// TAXO ET CPT
///////////////////////

function gn_genesis_filter_cpt_taxo_base() {
//* forcer full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
//* Enlever les meta dans le header
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

//* Enlever le contenu
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

//* Enlever l'image à la une
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
}

function gn_genesis_filter_cpt_taxo_footer() {
//* Enlever les méta dans le footer
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
}



///////////////////////
// COMPTEUR SUR POST
///////////////////////
function gn_cpt_counter_jq(){
add_action('wp_head','gn_script');
function gn_script(){ ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	var postCampeur = jQuery('.type-gnpost_wordcampeur');
	var i = 1;
	postCampeur.each(function(){
		jQuery(this)
			.addClass('home-featured-'+i+' home-featured-widget widget-area featured-content')
			.removeClass('gnpost_wordcampeur');
		i++;
		if ( i > 4 ) {
			i = 1;
		}
	});
}); // document ready
</script>
<?php }
}

///////////////////////
// MAQUEUR WIDGET
// HOME
// VERY FRENCH TRIP V1
// THEME MINIMUM PRO
///////////////////////

// MARQUEUR OUVERTURE
function gn_widget_open($home_featured_number = 1) { ?>
	<div class="home-featured-<?php echo $home_featured_number; ?> home-featured-widget widget-area">
		<section class="widget featured-content featuredpage">
			<div class="widget-wrap">
				<h4 class="widget-title widgettitle entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
				<article <?php post_class() ?>>

<?php }

// MARQUEUR OUVERTURE
function gn_widget_close() { ?>
				</article><!-- / .article -->
			</div><!-- / widget-wrap -->
		</section><!-- / .featured-content -->
	</div><!-- / .widget-area -->
<?php }


///////////////////////
// JETPACK mettre après le contenu
///////////////////////

// genesis_after_entry_content
add_action( 'genesis_meta', 'vft_filtre_partage' );
function vft_filtre_partage() {
	remove_filter( 'the_excerpt', 'sharing_display', 19 );
	remove_filter( 'the_content', 'sharing_display', 19 );

	add_action( 'genesis_after_entry_content', 'vft_change_place_partage' );
	function vft_change_place_partage(){

		if ( function_exists( 'sharing_display' ) ) {
			echo sharing_display();
		}

	}

}




