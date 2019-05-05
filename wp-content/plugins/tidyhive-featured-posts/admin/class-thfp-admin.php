<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tidyhive.com
 * @since      1.0.0
 *
 * @package    Thfp
 * @subpackage Thfp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Thfp
 * @subpackage Thfp/admin
 * @author     Mohit Chawla <mohitchawla@hotmail.co.in>
 */
class Thfp_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/thfp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/thfp-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add new column to posts admin table
	 * @param  array $defaults List of columns
	 * @return array           List of columns with addition
	 */
	function thfp_columns_head($defaults) {
	    $defaults['featured_post'] = __('Featured Post', $this->plugin_name);
	    return $defaults;
	}

	/**
	 * Show the Icon in custom column based on post tag
	 * @param  string $column_name Admin post column
	 * @param  int $post_ID     Id of post in row
	 * @return [type]              [description]
	 */
	function thfp_columns_content($column_name, $post_ID) {
	    if ($column_name == 'featured_post') {
	        $post_tags = wp_get_post_tags( $post_ID, array( 'fields' => 'names' ) );
	        if ($post_tags) {
	        	if(in_array('featured', $post_tags))
	            	echo '<a id="'.$post_ID.'" data-featured="1" href="#" class="featured-post__icon">
	            			<span class="dashicons dashicons-star-filled"></span>
	            	</a>';
	            else
	            	echo '<a id="'.$post_ID.'" data-featured="0" href="#" class="featured-post__icon">
	            		<span class="dashicons dashicons-star-empty"></span>
	            	</a>';
	        }else{
	        	echo '<a id="'.$post_ID.'" data-featured="0" href="#" class="featured-post__icon">
	            		<span class="dashicons dashicons-star-empty"></span>
	            	</a>';
	        }
	    }
	}

	/**
	 * Set Post tags
	 */
	function thfp_set_tag() {

		$feat_arr = array('featured');
		$tags_arr = wp_get_post_tags( $_POST['postId'], array( 'fields' => 'names' ) );
		
		if( $_POST['isFeatured'] == "0" ){
			$final_arr = array_merge($tags_arr, $feat_arr);
		}else{
			$final_arr = array_diff($tags_arr, $feat_arr);
		}

		wp_set_post_tags( $_POST['postId'], $final_arr );
		
		wp_die();
	}

	/**
	 * Adds a Link to admin panel which displays all featured posts
	 * 
	 */
	function thfp_featured_posts_link() {
	    global $submenu;
	    $link_to_add = 'edit.php?tag=featured';

	    $submenu['edit.php'][] = array( __('Featured Posts', $this->plugin_name), 'edit_posts', $link_to_add );
	}

}
