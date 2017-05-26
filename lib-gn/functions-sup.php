<?php
// functions sup du theme


///////////////////////
// ENQUEUE SCRIPT CSS
///////////////////////

// ENQUEUE JS
///////////////////////
function gn_enqueue_js() {
	wp_register_script( 'vft-js', get_stylesheet_directory_uri() . '/lib-gn/js/scripts-vft.js', array('jquery'), '1.0', true );

	if ( !is_admin() ) {
		wp_enqueue_script( 'vft-js' );
	}
}
add_action( 'wp_enqueue_scripts', 'gn_enqueue_js' );



///////////////////////
// GENESIS HOOK COMMUNS
///////////////////////

add_action( 'get_header', 'gn_remove_cat_posttype_podcast' );
function gn_remove_cat_posttype_podcast() {
	if ( 'podcast' == get_post_type() ) {
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
}


// perso: footer changer Copyright test
add_filter('genesis_footer_creds_text', 'perso_texte_copyright');
function perso_texte_copyright($creds) {
$creds = 'Copyright '.'[footer_copyright]' . ' :: Création: <a href="http://www.gregoirenoyelle.com/" target="_blank">Grégoire Noyelle</a> :: Base: <a href="http://www.wordpress.org" target="_blank">WordPress</a> , <a href="http://www.studiopress.com/features" target="_blank">Genesis Framework</a>
 :: '. '[footer_loginout redirect="http://veryfrenchtrip.com/"]';
return $creds;
}

///////////////////////
// MODIFICATION DU THÈME
///////////////////////

// Enlever le CPT Portfolio
remove_action( 'init', 'minimum_portfolio_post_type' );


// Image Size vignette wordcampeur
add_image_size( 'wordcampeur-s', 150, 150, TRUE );
add_image_size( 'wordcampeur', 250, 250, TRUE );



// UNREGISTAR SIDEBAR
unregister_sidebar( 'site-tagline-right' );
unregister_sidebar( 'home-featured-1' );
unregister_sidebar( 'home-featured-2' );
unregister_sidebar( 'home-featured-3' );
unregister_sidebar( 'home-featured-4' );

///////////////////////
// FONCTIONS PAR THÈME
///////////////////////
require_once( CHILD_DIR.'/lib-gn/snippets/genesis-snippets.php' );
require_once( CHILD_DIR.'/lib-gn/snippets/acf-snippets.php' );
require_once( CHILD_DIR.'/lib-gn/snippets/query-sup.php' );
require_once( CHILD_DIR.'/lib-gn/snippets/utilitaires.php' );




///////////////////////
// ACF CONTENT
///////////////////////



 // FONCTION DEBUG
// sur page WordCampeur Perso
// conditionnel is_single('11')
////////////////////////

// add_action('get_header', 'gn_debug');
function gn_debug() {
	if ( "gnpost_wordcampeur" == get_post_type() && is_single() ) {
		add_action('genesis_entry_content', 'gn_debug_test', 60);
		function gn_debug_test() {
			echo '<pre>';
				var_dump(gn_meta_wordcampeur_tableau());
			echo '</pre>';

		} // gn_debug_test
	} // if sur type content

} // function gn_debug()


///////////////////////
// EDITOR STYLE
///////////////////////

function gn_ajouter_styles_editeur() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'init', 'gn_ajouter_styles_editeur' );


///////////////////////
// PODCAST
///////////////////////
add_action( 'init', 'gn_add_support_cpt_podcast' );
function gn_add_support_cpt_podcast() {
	add_post_type_support( 'podcast', array('genesis-seo','genesis-cpt-archives-settings') );
}

///////////////////////
// IDEASTREAM
///////////////////////
add_action( 'init', 'gn_add_support_cpt_ideastream' );
function gn_add_support_cpt_ideastream() {
	add_post_type_support( 'ideas', array('genesis-cpt-archives-settings') );
}


///////////////////////
// ADMIN MENU
///////////////////////
$site_url = get_bloginfo('url');
// test menu admin
add_action('admin_bar_menu', 'gn_add_toolbar_items', 300);
function gn_add_toolbar_items($admin_bar){
	if ( !is_super_admin() )
		return;
	$admin_bar->add_menu( array(
		'id'    => 'options-site',
		'title' => 'Options du Thème',
		'href'  => '#',
		'meta'  => array(
			'title' => __('Options'),
		),
	));
	$admin_bar->add_menu( array(
		'id'    => 'options-accueil',
		'parent' => 'options-site',
		'title' => 'Page d\'Accueil',
		'href'  => $site_url . '/wp-admin/admin.php?page=theme-page-accueil',
		'meta'  => array(
			'title' => __('Page d\'Accueil'),
			'class' => 'options-accueil'
		),
	));
	$admin_bar->add_menu( array(
		'id'    => 'options-entete',
		'parent' => 'options-site',
		'title' => 'Entête',
		'href'  => $site_url . '/wp-admin/admin.php?page=theme-reglage-base',
		'meta'  => array(
			'title' => __('Entête'),
			'class' => 'options-entete'
		),
	));
}


// enlever bar admin pour non admin
function gn_remove_admin_bar_for_non_amdin() {
	if( !is_user_logged_in() )
		add_filter( 'show_admin_bar', '__return_false' );
}
add_action('wp', 'gn_remove_admin_bar_for_non_amdin');

///////////////////////
// CSS ADMIN
///////////////////////

add_action('admin_head', 'gn_admin_css_page_options_acf');

function gn_admin_css_page_options_acf() { ?>
  <style>
  @media only screen and (max-width: 3000px) {
		.options_page_theme-page-accueil #post-body.columns-2 #postbox-container-1 {
		  position: fixed;
		  top: 95px;
		  right: 310px;
		}
	}
	@media only screen and (max-width: 850px) {
		.options_page_theme-page-accueil #post-body.columns-2 #postbox-container-1 {
		  position: relative;
		}
	}
  </style>
<?php }


///////////////////////
// RETIRER SRCSET
///////////////////////
// voir https://make.wordpress.org/core/2015/11/10/responsive-images-in-wordpress-4-4/#comment-28662
function vft_disable_srcset( $sources ) {
return false;
}
add_filter( 'wp_calculate_image_srcset', 'vft_disable_srcset' );


///////////////////////
// CHANGER FEED POUR PODCAST
///////////////////////
function vft_ssp_posts_in_feed ($n) {
	return 9999; // The number of episodes in RSS feed
}

add_filter('ssp_feed_number_of_posts', 'vft_ssp_posts_in_feed');