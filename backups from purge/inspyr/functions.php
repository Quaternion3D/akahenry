<?php /*
Theme Name: (in)SPYR
Theme URI: http://in.spyr.me
Author: Spyr Media
Author URI: http://spyr.me
*/

require_once(get_template_directory() . '/lib/init.php');
require_once('includes/inspyr_admin/admin_options.php');
require_once('includes/widgets/subscribe_widget.php');
require_once('includes/inspyr_flexslider/inspyr_flexslider.php');

define('CHILD_THEME_NAME','(in)SPYR');
define('CHILD_THEME_URL','http://in.spyr.me');


/*** Mobile Viewport Meta */
function add_mobile_viewport_meta() { ?>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />
<?php
	}
add_action('wp_head','add_mobile_viewport_meta');


/*** HTML5 Doctype */
remove_action('genesis_doctype','genesis_do_doctype');
add_action('genesis_doctype',create_function('','echo "<!DOCTYPE html><html>\n<head>\n<meta charset=\"UTF-8\">\n";'));


/*** Remove Edit Link */
add_filter('genesis_edit_post_link','__return_false');


/*** Enable Page Excerpts
add_post_type_support('page','excerpt'); */


/*** Unregister Layouts */
genesis_unregister_layout('sidebar-content');
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');
genesis_unregister_layout('sidebar-content-sidebar');


/** Unregister Secondary Nav */
//function inspyr_unregister_nav() { unregister_nav_menu('secondary'); }
//add_action('init','inspyr_unregister_nav');





/*** Enqueue Scripts */
function inspyr_enqueue_scripts() {
	$theme_dir = get_bloginfo('stylesheet_directory');
  /*** Scripts
	wp_enqueue_script('inspyr-flexslider',$theme_dir . '/includes/inspyr_flexslider/js/jquery.flexslider-min.js',array('jquery'));
	wp_enqueue_script('inspyr-selectbox',$theme_dir . '/includes/js/jquery.selectbox-min.js',array('jquery'));
	wp_enqueue_script('inspyr-fitvids',$theme_dir . '/includes/js/jquery.fitvids.js',array('jquery'));
	wp_enqueue_script('inspyr-flexsliderinit',$theme_dir . '/includes/js/inspyr_flexslider_init.js',array('inspyr-flexslider')); */
  /*** Combined Scripts */
	wp_enqueue_script('inspyr-scripts',$theme_dir . '/includes/js/inspyr-min.js',array('jquery'));
	}
add_action('wp_enqueue_scripts','inspyr_enqueue_scripts');


/*** Enqueue Styles */
function inspyr_enqueue_styles() {
	global $inspyr_theme_var;
	if ($inspyr_theme_var->theme_parent == 'tribe') { wp_enqueue_style('inspyr-tribe','http://fonts.googleapis.com/css?family=Josefin+Slab:300,600,700',false); }
	if ($inspyr_theme_var->theme_parent == 'merit') { wp_enqueue_style('inspyr-merit','http://fonts.googleapis.com/css?family=Fredericka+the+Great',false); }	
	}
add_action('init','inspyr_enqueue_styles');


/*** IE CSS */
function add_ie_css() { ?>
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory') ?>/style-ie8.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory') ?>/style-ie7.css" /><![endif]-->
<?php }
add_action('wp_head','add_ie_css');


/*** Custom Logo */
function inspyr_custom_logo() {
	global $wp_version;
	if (version_compare($wp_version,'3.4','>=')) {
		$args = array(
			'default-image'          => '',
			'random-default'         => false,
			'width'                  => 0,
			'height'                 => 0,
			'flex-height'            => true,
			'flex-width'             => true,
			'default-text-color'     => '',
			'header-text'            => false,
			'uploads'                => true,
			'wp-head-callback'       => 'inspyr_logo_style',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
			);
		add_theme_support('custom-header',$args);
		}
	}
add_action('init','inspyr_custom_logo');

function inspyr_logo_style() {
	$output = sprintf( '#header #title a { background: url(%s) 0 0 no-repeat;height:' . get_custom_header()->height . 'px;width:' . get_custom_header()->width . 'px; }',esc_url(get_header_image()));
	printf( '<style type="text/css">%s</style>', $output );
	}


/*** Header Widget Area */
unregister_sidebar('header-right');
genesis_register_sidebar(array('id'=>'header-right','name'=>__('(in)SPYR Header Right','genesis'),'description'=>__('Suggested Usage: Custom Menu, Search','inspyr'),));


/*** Feature Area */
genesis_register_sidebar(array('id'=>'subscribe-area','name'=>__('(in)SPYR Subscribe Area','inspyr'),'description' => __('Suggested Usage: (in)SPYR Subscribe &nbsp; &nbsp; (visible on homepage and top of interior sidebar)','inspyr'),));
genesis_register_sidebar(array('id'=>'slider-area','name'=>__('(in)SPYR Slider Area','inspyr'),'description' => __('Suggested Usage: (in)SPYR FlexSlider &nbsp; &nbsp; &nbsp; (only visible on homepage)','inspyr'),));
function inspyr_feature_area() {
	if (is_front_page()) {
		if (get_query_var('paged') >= 2) { return; }
		genesis_widget_area('subscribe-area',array('before'=>'<div class="subscribe-area widget-area">',));
		genesis_widget_area('slider-area',array('before'=>'<div class="slider-area widget-area">',));
		}
	}
add_action('genesis_before_content_sidebar_wrap','inspyr_feature_area',20);


/*** Above Content Widget */
genesis_register_sidebar(array('id'=>'callout-area','name'=>__('(in)SPYR Callout Area','inspyr'),'description' => __('This callout area displays above the content. (only visible on homepage)','inspyr'),));
function inspyr_callout_area() {
	if (is_front_page()) {
		if (get_query_var('paged') >= 2) { return; }
		genesis_widget_area('callout-area',array('before'=>'<div class="callout-area widget-area">',));
		}
	}
add_action('genesis_before_loop','inspyr_callout_area',20);


/*** Unregister Secondary Sidebar */
unregister_sidebar('sidebar-alt');


/*** Filter Widget Title */
function inspyr_filter_widget_title ($title) {
	if ($title <> '') {
		return '<span>' . $title . '</span>';
		}
	}
add_filter('widget_title','inspyr_filter_widget_title');


/*** Custom Post Byline */
function custom_post_info($post_info) {
	$post_info = 'By [post_author_posts_link]';
	if (!is_single()) { $post_info .= '<a href="' . get_comments_link() . '" class="leave-comment">Leave a Comment</a>'; }
	$post_info .= '<div class="post-date"><span class="month">[post_date format="M"]</span><span class="day">[post_date format="j"]</span>[post_comments zero="0" one="1" more="%"]</div>';
	return $post_info;
	}
add_filter('genesis_post_info','custom_post_info',40);
add_filter('genesis_post_info','do_shortcode',40);


/*** After Post Byline */
function inspyr_post_meta($post_meta) {
	if (is_single()) {
		$post_meta = '[post_tags before="Tags: "] [post_categories before="Categories: "]';
		return $post_meta;
		}
	}
add_filter('genesis_post_meta','inspyr_post_meta',40);
add_filter('genesis_post_meta','do_shortcode',40);


/*** Post Navigation */
function inspyr_post_nav() {
	if (is_single()) { ?>
<div class="post-nav">
	<div class="prev-post-nav"><?php previous_post_link('%link','Prev'); ?></div>
	<div class="next-post-nav"><?php next_post_link('%link','Next'); ?></div>
	<div class="clear"></div>
	</div>
<?php
		}
	}
add_action('genesis_after_post_content','inspyr_post_nav',20);


/*** Change More Link */
function change_more_link($link) {
	$offset = strpos($link, '"') + 1;
	if ($offset) { $end = strpos($link, '" ',$offset); }
	if ($end) { $link = substr($link,$offset, $end-$offset); }
	$to_return = '<a href="'. $link . '" class="more-link"><span><i>&laquo; </i>read more<i> &raquo;</i></span></a>';
	return $to_return;
	}
add_filter('the_content_more_link','change_more_link');
add_filter('get_the_content_more_link','change_more_link');
//add_filter('excerpt_more','change_more_link');


/*** Image Sizes */
add_image_size('inspyr-featured-page',55,90,true);
add_image_size('inspyr-post-thumbnail',960,265,true);
add_image_size('inspyr-rss-post-thumbnail',600,200,true);
function custom_image_sizes($sizes) {
	$addsizes = array("inspyr-post-thumbnail" => __("(in)SPYR Wide Banner"));
	$newsizes = array_merge($sizes,$addsizes);
	return $newsizes;
	}
add_filter('image_size_names_choose','custom_image_sizes');


/*** Add Post Image */
function add_post_image() { if (!is_single()) { ?><a href="<?php the_permalink(); ?>" class="wp-post-image-anchor"><?php } the_post_thumbnail('inspyr-post-thumbnail'); if (!is_single()) { ?></a><?php } }
add_action('genesis_before_post_content','add_post_image');


/*** Add Post Image To RSS */
function insertThumbnailRSS($content) {
	global $post;
	$content = str_replace('<img ','<img style="height:auto;max-width:100%;" ',$content);
	if (has_post_thumbnail($post->ID)) { $content = '<div>' . get_the_post_thumbnail($post->ID,'inspyr-rss-post-thumbnail') . '</div>' . $content; }
	return $content;
	}
add_filter('the_excerpt_rss','insertThumbnailRSS');
add_filter('the_content_feed','insertThumbnailRSS');


/*** Clear #inner */
function clear_content_div() { ?><div class="clear"></div><?php }
add_action('genesis_after_content_sidebar_wrap','clear_content_div');


/*** Filter Search Text */
function custom_search_text($text) { return esc_attr('search'); }
add_filter('genesis_search_text','custom_search_text');


/*** Sidebar Subscribe Widget */
function inspyr_sidebar_subscribe() {
	global $inspyr_theme_var;
	if (!is_front_page() && !$inspyr_theme_var->options['inspyr_subscribe_homepage_only']) {
		genesis_widget_area('subscribe-area',array('before'=>'<div class="subscribe-area widget-area">',));
		}
	}
add_action('genesis_before_sidebar_widget_area','inspyr_sidebar_subscribe');


/*** Footer Widgets
add_theme_support('genesis-footer-widgets',3 ); */


/*** Footer */
function register_footer_nav() { register_nav_menus(array("footer_nav" => "Footer Navigation Menu")); }
add_action('init','register_footer_nav');

function inspyr_footer() {
	global $inspyr_theme_var;
	$show_footer_social = false;
	if ($inspyr_theme_var->options['inspyr_theme_twitter'] <> '') {
		$inspyr_footer_twitter = '<a href="' . $inspyr_theme_var->options['inspyr_theme_twitter'] . '" target="_blank">Follow on<br /><span>Twitter</span></a>';
		$show_footer_social = true;
		}
	if ($inspyr_theme_var->options['inspyr_theme_facebook'] <> '') {
		$inspyr_footer_facebook = '<a href="' . $inspyr_theme_var->options['inspyr_theme_facebook'] . '" target="_blank">Connect on<br /><span>Facebook</span></a>';
		$show_footer_social = true;
		}
	if ($inspyr_theme_var->options['inspyr_theme_googleplus'] <> '') {
		$inspyr_footer_googleplus = '<a href="' . $inspyr_theme_var->options['inspyr_theme_googleplus'] . '" target="_blank">Connect on<br /><span>Google+</span></a>';
		$show_footer_social = true;
		} ?>
	<div id="footer-title"><a href="<?php echo bloginfo('url'); ?>" title="<?php echo bloginfo('name'); ?>"><?php echo bloginfo('name'); ?></a></div><?php
	if ($show_footer_social) { ?>
	<div id="footer-social"><?php echo $inspyr_footer_twitter;echo $inspyr_footer_facebook;echo $inspyr_footer_googleplus; ?></div>
<?php	} ?>
	<div id="footer-nav"><?php wp_nav_menu(array( 'theme_location' => 'footer_nav','fallback_cb' => 'false' )); ?></div>
	<div class="copyright">Copyright &copy; <?php echo date('Y'); ?></div><?php
	}
remove_action('genesis_footer','genesis_do_footer');
add_action('genesis_footer','inspyr_footer');


/*** Shortcodes */
add_filter('widget_text','do_shortcode');

function add_inspyr_icon_shortcode($atts) {
	extract(shortcode_atts(array('network' => '','url' => '','text' => ''),$atts));
	if ($text == '') { $text = strtoupper($network); }
	$to_return = '<a href="' . $url . '" target="_blank" class="inspyr_icon sm_' . $network .'"><span><i></i>' . $text . '</span></a>';
	return $to_return;
	}
add_shortcode('inspyr_icon','add_inspyr_icon_shortcode');

function add_inspyr_responsive_video_shortcode($atts,$content="") { return '<div class="inspyr_video">' . $content . '</div>'; }
add_shortcode('inspyr_video','add_inspyr_responsive_video_shortcode');


/*** (in)SPYR Switcher
require_once('includes/inspyr_switcher/inspyr_switcher.php'); */



/** Reposition the primary navigation menu */
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

//support woocommerce
add_theme_support( 'genesis-connect-woocommerce' );

/**
 * WooCommerce
 *
 * Unhook sidebar
*/
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

//remove related products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );



/** JIGOSHOP **/

//remove product description
function mytheme_remove_product_description_heading( $current_desc ) {
    return '';
}
add_filter( 'jigoshop_product_description_heading', 'mytheme_remove_product_description_heading' );

//remove product additional info
remove_action( 'jigoshop_product_tabs', 'jigoshop_product_attributes_tab' , 20 );
remove_action( 'jigoshop_product_tab_panels', 'jigoshop_product_attributes_panel' , 20 );


/**
 * WooCommerce
 *
 * Unhook/Hook the WooCommerce Wrappers------WHAT ARE THE WOOCOMMERCE WRAPPERS????
 

function responsive_child_theme_setup() {
    remove_action('woocommerce_before_main_content', 'responsive_woocommerce_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'responsive_woocommerce_wrapper_end', 10);

    add_action('woocommerce_before_main_content', 'responsive_child_woocommerce_wrapper', 10);
    add_action('woocommerce_after_main_content', 'responsive_child_woocommerce_wrapper_end', 10);
 
    function responsive_child_woocommerce_wrapper() {
      echo '<div id="content-woocommerce" class="grid col-940">';
    }
 
    function responsive_child_woocommerce_wrapper_end() {
      echo '</div><!-- end of #content-woocommerce -->';
    }
}

add_action( 'after_setup_theme', 'responsive_child_theme_setup' );
*/