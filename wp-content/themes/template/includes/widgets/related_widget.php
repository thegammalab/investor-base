<?php
/**
 * Adds Foo_Widget widget.
 */
class IB_Rel_Widget_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'ib_rel_widget', // Base ID
			esc_html__( 'IB Related Widget', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'A Foo Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	 public function widget( $args, $instance ) {
	 	echo $args['before_widget'];
		$results = apply_filters('tdf_get_posts',"post",3,0,array("search"=>array(),"order"=>"rand"));
		?>
		<div class="other_news_widget">
		  <h4>
				<img src="<?=get_bloginfo("template_directory");?>/assets/images/other_news.png" alt="">
				<?php	if ( ! empty( $instance['title'] ) ) {	echo apply_filters( 'widget_title', $instance['title'] ); }	?>
			</h4>
		  <div class="widget_content">
		    <?php foreach($results["items"] as $i=>$item){ ?>
		      <div class="row align-items-center">
		        <div class="col-4 pr-0">
		          <?=get_the_post_thumbnail($item["post_id"],"small_crop");?>
		        </div>
		        <div class="col-8">
		          <h5><a href="<?=$item["post_permalink"];?>" class="fs90"><?=$item["post_title"];?></a></h5>
		        </div>
		      </div>
		      <?php if($i!=2){ echo '<hr />';} ?>
		    <?php } ?>
		  </div>
		</div>

	 	<?php
	 	echo $args['after_widget'];
	 }

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_ib_rel_widget() {
    register_widget( 'IB_Rel_Widget_Widget' );
}
add_action( 'widgets_init', 'register_ib_rel_widget' );
