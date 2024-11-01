<?php

/*
 * Plugin Name: VP Slider
* Plugin URI: https://wordpress.org/plugins/vp-slider/
* Description: VP Slider is simple lightweight plugin. It is easy to use. This plugin you can use any where via shortcode. 
* Version: 1.0
* Author: Maruf Arafat
*/

function Virtual_Practices_carosel_slider() {
	wp_enqueue_script('jquery');
	wp_enqueue_style( 'Virtual_Practices_iatcs_stylesheet ', plugins_url('css/owl.carousel.css', __FILE__), true, 1.0);
	wp_enqueue_style( 'Virtual_Practices_iatcs_theme ', plugins_url('css/owl.theme.css', __FILE__), true, 1.0);
    wp_enqueue_script( 'Virtual_Practices_iatcs_plugin ', plugins_url( '/js/owl.carousel.js', __FILE__ ), array('jquery'), 1.0, false);
}

add_action('init','Virtual_Practices_carosel_slider');

function Virtual_Practices_carosel_slider_custom_post() {
	register_post_type( 'vp_iatcs',
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
		'rewrite' => array( 'slug' => 'vp_iatcs' ),
		'supports' => array( 'title', 'custom-fields','thumbnail','excerpt' )
		)
	);
}

add_action( 'init', 'Virtual_Practices_carosel_slider_custom_post' );


function Virtual_Practices_carosel_slider_costom_taxonomy() {

	register_taxonomy(
		'vp_iatcs_category',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'vp_iatcs',                  //post type name
		array(
			'hierarchical'          => true,
			'label'                         => 'VP Category',  //Display name
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'vp_iatcs_category', // This controls the base slug that will display before each term
				'with_front'    => true // Don't display the category base before
				)
			)
		);
	}

add_action( 'init', 'Virtual_Practices_carosel_slider_costom_taxonomy'); 


function Virtual_Practices_slider_shortcode($atts){
	extract( shortcode_atts( array(
		'id' => 'vp',
		'ppp' => '2',
		'cat' => '',
		'autoPlay' => 'true',
		'stopOnHover' => 'true',
		'navigation' => 'false',
		'pagination' => 'false',
		'responsive' => 'true',
		'title' => '18',
		'sec' => '200',
	), $atts, 'vp_slider' ) );
	
    $q = new WP_Query(
        array('posts_per_page' => '-1', 'post_type' => 'vp_iatcs', 'vp_iatcs_category' => $cat)
        );		
		
	$list = '
	<script type="text/javascript">
		jQuery(document).ready(function() {
		  	jQuery("#vp-slider-'.$id.'").owlCarousel({
		  		slideSpeed : '.$sec.',
				items : '.$ppp.',
				itemsDesktop : [1199,3],
				itemsDesktopSmall : [979,3],
				autoHeight : true,

				autoPlay : '.$autoPlay.',
				stopOnHover : '.$stopOnHover.',

				responsive: '.$responsive.',
				responsiveRefreshRate : 200,
				responsiveBaseWidth: window,

				pagination : '.$pagination.',
				paginationNumbers: true,

				transitionStyle : true,

				navigationText : ["prev","next"],
				navigation : '.$navigation.',
		  	});
		});
	</script>
	<style>
		.owl-carousel .owl-wrapper-outer{margin-left: -10px;}
		div#vp-slider-'.$id.' div.item{border: 1px solid #B4ADAD;margin: 10px;}
		div#vp-slider-'.$id.' div.item img{width:100%;}
		div#vp-slider-'.$id.' div.item h2{text-align: center;font-size:'.$title.'px}
		div#vp-slider-'.$id.' div.item p{text-align: center;padding:10px;}

	</style>
	<div id="vp-slider-'.$id.'">';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$portfolio_subtitle = get_post_meta($idd, 'portfolio_subtitle', true);
		
		$full_vp_iatcs = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'vp_iatcs_crop_img' );
		$list .= '<div class="item">
		<img src="'.$full_vp_iatcs[0].'" alt="'.get_the_title().'">
		<h2>'.get_the_title().'</h2>
		<p>'.get_the_excerpt().'</p>
		</div>';        
	endwhile;
	$list.= '</div>';
	wp_reset_query();
	return $list;
}
add_shortcode('VP_Slider', 'Virtual_Practices_slider_shortcode');	

add_theme_support( 'post-thumbnails', array( 'vp_iatcs') );


add_image_size( 'vp_iatcs_crop_img', 300, 300, true );



?>