<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tidyhive.com
 * @since      1.0.0
 *
 * @package    Thfp
 * @subpackage Thfp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Thfp
 * @subpackage Thfp/public
 * @author     Mohit Chawla <mohitchawla@hotmail.co.in>
 */
class Thfp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Thfp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Thfp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thfp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Thfp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Thfp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thfp-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Runs a query to fetch all featured posts
	 * @return [type] [description]
	 */
	public static function thfp_get_featured_posts( $count = -1 ) {
		$args = array(
			'post_status' 			=> 	'publish',
			'tag'					=>	'featured',
			'posts_per_page'		=>	$count,
			'ignore_sticky_posts'	=>	true,
			);

		return new WP_Query($args);
	}

	/**
	 * Code to display featured posts slider
	 * @since  1.5.0
	 */
	public static function thfp_featured_posts_slider(){
		$featured_posts = self::thfp_get_featured_posts();


		if( isset($featured_posts) && $featured_posts->have_posts() ){
			if($featured_posts->post_count > 1){
				wp_enqueue_script('owl-js', plugin_dir_url(__FILE__) . '/js/owl.carousel.min.js', array('jquery') );
				wp_enqueue_style('owl-css', plugin_dir_url(__FILE__) . '/css/owl.min.css');
				?>

				<script>
					var $ = jQuery.noConflict();
					$(document).ready(function(){
						$('.thfp-feat__slider').owlCarousel({
							items: 1,
							nav: true,
							loop: true,
							dots: true,
							navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>' ]
						});
					});
				</script>
			<?php
			}
			echo '<div class="thfp-feat__slider">';
			while( $featured_posts->have_posts() ){
				$featured_posts->the_post();
				
				$categories = get_the_category();
				$category_link = get_category_link($categories[0]->term_id);
				
				include('partials/thfp-public-display.php');
			}
			echo "</div>";
		}
	}

}
