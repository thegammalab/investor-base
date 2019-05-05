<?php 
class Thfp_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'thfp_widget',
			'description' => 'This Widget shows a list of featured posts.',
		);
		parent::__construct( 'thfp_widget', 'Tidyhive - Featured Posts', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget . $before_title . $title . $after_title;

		$args = array( 'tag' => 'featured', 'posts_per_page' => 5, 'post_status' => 'publish' );
		$featured_posts = new WP_Query($args);

		if($featured_posts->have_posts()):
			echo '<ul>';
			while($featured_posts->have_posts()): $featured_posts->the_post();
				echo '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
			endwhile;
			echo '</ul>';
		endif;

		echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		if($instance){
			$title = $instance['title'];
		}else{
			$title = '';
		}
		?>
		<p>
			<lable for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'thfp' ); ?></lable>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;
		//Fields
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'Thfp_widget' );
});