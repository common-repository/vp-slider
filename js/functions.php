 <?php
 /*
 * Plugin Name: VP Carousel Slider
 * Plugin URI: http://wordpress.org/test
 * Description: A brief description of the Plugin.
 * Version: 1.0
 * Author: Maruf Arafat
 * Author URI: http://marufarafat.me
 */

function wp_image_and_text_carosel_slider() {
	wp_enqueue_script('jquery');
	wp_enqueue_style( 'wp_iatcs_stylesheet ', plugins_url('css/owl.carousel.css', __FILE__), true, 1.0);
	wp_enqueue_style( 'wp_iatcs_theme ', plugins_url('css/owl.theme.css', __FILE__), true, 1.0);
    wp_enqueue_script( 'wp_iatcs_plugin ', plugins_url( '/js/owl.carousel.js', __FILE__ ), array('jquery'), 1.0, false);
}

add_action('init','wp_image_and_text_carosel_slider');

function wp_image_and_text_carosel_slider_active_script () {?>

<?php
}
add_action('wp_footer','wp_image_and_text_carosel_slider_active_script');

function wp_image_and_text_carosel_slider_custom_post() {
	register_post_type( 'wp_iatcs',
		array(
			'labels' => array(
				'name' => __( 'VP Slider' ),
				'singular_name' => __( 'Single Slide' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Slide' ),
				'edit_item' => __( 'Edit Slide' ),
				'new_item' => __( 'New Slide' ),
				'view_item' => __( 'View Slide' ),
				'not_found' => __( 'Sorry, we couldn\'t find the Slide you are looking for.' )
			),
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'menu_position' => 20,
		'has_archive' => true,
		'hierarchical' => false, 
		'capability_type' => 'page',
		'rewrite' => array( 'slug' => 'wp_iatcs' ),
		'supports' => array( 'title', 'custom-fields','thumbnail','editor' )
		)
	);
}

add_action( 'init', 'wp_image_and_text_carosel_slider_custom_post' );

function wp_image_and_text_carosel_slider_costom_taxonomy() {

	register_taxonomy(
		'wp_iatcs_category',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'wp_iatcs',                  //post type name
		array(
			'hierarchical'          => true,
			'label'                         => 'OWL Category',  //Display name
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'wp_iatcs_category', // This controls the base slug that will display before each term
				'with_front'    => true // Don't display the category base before
				)
			)
		);
	}

add_action( 'init', 'wp_image_and_text_carosel_slider_costom_taxonomy');   

function wp_image_and_text_carosel_slider_shortcode($atts){
	extract( shortcode_atts( array(
		'ppp' => '4',
		'cat' => '',
		'activ' => '',
	), $atts, 'pricing_table' ) );
	
    $q = new WP_Query(
        array('posts_per_page' => $ppp, 'post_type' => 'wp_iatcs', 'wp_iatcs_category' => $cat)
        );		
add_image_size( 'wp_iatcs_crop_img', 300, 300, true );
		
	$list = '
	<script type="text/javascript">
		jQuery(document).ready(function() {
		  jQuery("#owl-example").owlCarousel({
		      autoPlay: 3000, 
		      items : 2,
		      itemsDesktop : [1199,3],
		      itemsDesktopSmall : [979,3]
		  });
		});
	</script>
	<style>
		

	</style>
	<div id="owl-example">';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$portfolio_subtitle = get_post_meta($idd, 'portfolio_subtitle', true);
		
		$full_wp_iatcs = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'wp_iatcs_crop_img' );
		$list .= '<div class="item"><img src="'.$full_wp_iatcs[0].'" alt="'.get_the_title().'"></div>';        
	endwhile;
	$list.= '</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('owl', 'wp_image_and_text_carosel_slider_shortcode');	

add_theme_support( 'post-thumbnails', array( 'wp_iatcs') );


add_image_size( 'wp_iatcs_crop_img', 300, 300, true );


