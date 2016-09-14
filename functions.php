<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Swank Theme' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/swank/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'swank_enqueue_scripts' );
function swank_enqueue_scripts() {

	wp_enqueue_script( 'swank-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); 
	wp_enqueue_style( 'swank-google-fonts', '//fonts.googleapis.com/css?family=Old+Standard+TT:400,400italic,700|Montserrat:400,700', array(), CHILD_THEME_VERSION );

}

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 640,
	'height'          => 200,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Enqueue Styles and Scripts
add_action( 'wp_enqueue_scripts', 'genesis_sample_scripts' );
function genesis_sample_scripts() {
	$minnified = '.min';
	//* Should we load minified scripts? Also enqueue live reload to allow for extensionless reloading with 'grunt watch'.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) {
		$minnified = '';
		wp_enqueue_script( 'live-reload', '//localhost:35729/livereload.js', array(), CHILD_THEME_VERSION, true );
	}
	//* Add Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts' );
	//* Add Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Crimson+Text:400,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts' );
	//* Remove default CSS
	wp_dequeue_style( 'genesis-sample-theme' );
	//* Add compiled CSS
	wp_register_style( 'genesis-sample-styles', get_stylesheet_directory_uri() . '/style' . $minnified . '.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'genesis-sample-styles' );
	//* Add compiled JS
	wp_enqueue_script( 'genesis-sample-scripts', get_stylesheet_directory_uri() . '/js/project' . $minnified . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
}

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add new image sizes 
add_image_size( 'circles', 200, 200, TRUE );
add_image_size( 'portfolio-featured', 300, 200, TRUE );
add_image_size( 'sidebar', 290, 150, TRUE );

//* Add Top Bar Above Header
add_action( 'genesis_site_title', 'swank_top_bar' );
function swank_top_bar() {
 
	echo '<div class="top-bar"><div class="wrap">';
 
	genesis_widget_area( 'top-bar-left', array(
		'before' => '<div class="top-bar-left">',
		'after' => '</div>',
	) );

	genesis_widget_area( 'top-bar-right', array(
		'before' => '<div class="top-bar-right">',
		'after' => '</div>',
	) );
 
	echo '</div></div>';
 
}

//* Remove the entry meta in the entry footer
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'swank_post_info_filter' );
function swank_post_info_filter($post_info) {

	$post_info = '[post_date] by [post_author_posts_link] [post_categories] [post_comments]';
	return $post_info;

}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_footer', 'genesis_do_subnav' );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'swank_secondary_menu_args' );
function swank_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;
}

//* Change Avatar Size
add_filter( 'genesis_comment_list_args', 'swank_comment_list_args' );
function swank_comment_list_args( $args ) {

	return array( 'type' => 'comment', 'avatar_size' => 100, 'callback' => 'genesis_comment_callback' );

}

//* Add Support for Comment Numbering
add_action ('genesis_before_comment', 'afn_numbered_comments');
function afn_numbered_comments () {

    if (function_exists('gtcn_comment_numbering'))
    echo gtcn_comment_numbering($comment->comment_ID, $args);

}

//* Change the number of portfolio items to be displayed (props Bill Erickson) 
add_action( 'pre_get_posts', 'swank_portfolio_items' );
function swank_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

//* Create portfolio custom post type 
add_action( 'init', 'portfolio_post_type' );
function portfolio_post_type() {
    register_post_type( 'portfolio',
        array(
            'labels' => array(
                'name' => __( 'Portfolio' ),
                'singular_name' => __( 'Portfolio' ),
            ),
            'exclude_from_search' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'public' => true,
            'rewrite' => array( 'slug' => 'portfolio' ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'genesis-seo' ),
        )
    );
}

//* Customize the credits 
add_filter('genesis_footer_creds_text', 'swank_footer_creds_filter');
function swank_footer_creds_filter( $creds ) {

    $creds = 'Copyright [footer_copyright] &middot; <a href="http://mtois.com"> MTOIS Site</a> By, <a href="https://riverbirchindustries.com">River Birch Industries</a>';
    return $creds;

}

//* Register Widget Areas
genesis_register_sidebar( array(
	'id'          => 'top-bar-left',
	'name'        => __( 'Top Bar Left', 'swank' ),
	'description' => __( 'This is the left side of your top bar.', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'          => 'top-bar-right',
	'name'        => __( 'Top Bar Right', 'swank-' ),
	'description' => __( 'This is the right side of your top bar.', 'swank' ),
) );

genesis_register_sidebar( array(
    'id'          => 'portfolioblurb',
    'name'        => __( 'Portfolio Blurb', 'swank' ),
    'description' => __( 'This is a widget area that can be shown above your portfolio', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'         => 'home-slider',
	'name'       => __( 'Home Page Slider Widget', 'swank' ),
	'description' => __( 'This is the slider widget on your home page', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'         => 'under-home-slider',
	'name'       => __( 'Under Home Page Slider Widget', 'swank' ),
	'description' => __( 'This is underneath the slider widget on your home page', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'          => 'featured-circles',
	'name'        => __( 'Home Page Featured Post Circles', 'swank' ),
	'description' => __( 'This is the top section of your home page', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-featured-area',
	'name'        => __( 'Home Featured Widget Area', 'swank' ),
	'description' => __( 'This is the featured posts section of your home page.', 'swank' ),
) );

//* Reposition the primary navigation menu

remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

//* REGISTER NEW SUB FOOTER AREA

genesis_register_sidebar( array(
	'id'          => 'sub-footer',
	'name'        => __( 'Sub Footer', 'swank' ),
	'description' => __( 'This is the sub footer widget.', 'swank' ),

) );

//Add hero image above post/page content
 
// Create new image size for our hero image
add_image_size( 'hero-image', 1400, 400, TRUE ); // creates a hero image size
 
// Hook after header area
add_action( 'genesis_after_header', 'bw_hero_image' );

function bw_hero_image() {
// If it is a page and has a featured thumbnail, but is not the front page do the following...
    if (has_post_thumbnail() && is_page() ) {
    	// Get hero image and save in variable called $background
    	$image_desktop = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'hero-image' );
    	$image_tablet = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'large' );
    	$image_mobile = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'medium' );

    	$bgdesktop = $image_desktop[0];
        $bgtablet = $image_tablet[0];
        $bgmobile = $image_mobile[0];

// You can change above-post-hero to any class you want and adjust CSS styles
    	$featured_class = 'above-post-hero';

 ?> 
<div class='<?php echo $featured_class; ?>'></div>
<style>
	<?php echo ".$featured_class "; ?> {background-image:url( <?php echo $bgmobile; ?>);height:206px;}
		
		@media only screen and (min-width : 480px) {       
        <?php echo ".$featured_class "; ?> {background-image:url(<?php echo $bgtablet;?>);height:316px;}
		}
		@media only screen and (min-width : 992px) {       
        <?php echo ".$featured_class "; ?> {background-image:url(<?php echo $bgdesktop;?>);height:475px;}
		}
</style>
<?php
    } 
}

//SINGLE POST Add hero image above post/page content
 
// SINGLE POST Create new image size for our hero image
add_image_size( 'hero-image', 1400, 400, TRUE ); // creates a hero image size
 
// Hook after header area
add_action( 'genesis_after_header', 'single_hero_image' );

function single_hero_image() {
$url = "http://mtois.staging.wpengine.com/wp-content/uploads/2015/08/Blog-Banner.jpg";
// If it is a page and has a featured thumbnail, but is not the front page do the following...
    if ( is_single() ) {
    	// Get hero image and save in variable called $background
    	
    	$image_desktop = $url;
    	$image_tablet = $url;
    	$image_mobile = $url;

    	$bgdesktop = $url;
        $bgtablet = $url;
        $bgmobile = $url;

// SINGLE POST You can change above-post-hero to any class you want and adjust CSS styles
    	$featured_class = 'single-above-post-hero';

 ?> 
<div class='<?php echo $featured_class; ?>'></div>
<style>
	<?php echo ".$featured_class "; ?> {background-image:url( <?php echo $bgmobile; ?>);height:206px;}
		
		@media only screen and (min-width : 480px) {       
        <?php echo ".$featured_class "; ?> {background-image:url(<?php echo $bgtablet;?>);height:316px;}
		}
		@media only screen and (min-width : 992px) {       
        <?php echo ".$featured_class "; ?> {background-image:url(<?php echo $bgdesktop;?>);height:605px;}
		}
</style>
<?php
    } 
}

//REMOVE WRAP FOR FULL-WIDTH COLORS
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

//* Remove page title for multiple pages (requires HTML5 theme support)
//* Change '3645' and '4953' to match your needs
add_action( 'get_header', 'child_remove_page_titles' );
function child_remove_page_titles() {
    $pages = array( 6,9,10,11,13 );
    if ( is_page( $pages ) ) {
        remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    }
}

//REMOVE AND REPOSITION SHARDADDY ICONS
function remove_jetpack_share_button() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
}
 
add_action( 'loop_start', 'remove_jetpack_share_button' );

add_action('the_content', 'before_post_content');
 
function before_post_content( $content ) {
    if ( function_exists( 'sharing_display' ) )
    return sharing_display( '', true ) . $content;
}

//REMOVE PAGE TITLE
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

//* Register before post genesis_after_header widget area
genesis_register_sidebar( array(
	'id'            => 'before-post',
	'name'          => __( 'Before Post', 'Swank SASS' ),
	'description'   => __( 'This is a widget area that can be placed before all single posts', 'Swank SASS' ),
) );

//* Hook before post widget area genesis_after_header
add_action( 'genesis_after_header', 'sp_before_post_widget' );
	function sp_before_post_widget() {
	if ( is_singular( 'post' ) )
		genesis_widget_area( 'before-post', array(
			'before' => '<div class="before-post widget-area">',
			'after' => '</div>',
	) );
}

//* Customize search form input box text
add_filter( 'genesis_search_text', 'sp_search_text' );
function sp_search_text( $text ) {
	return esc_attr( 'Search...' );
}
