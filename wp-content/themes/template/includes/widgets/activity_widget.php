<?php
/**
* Adds Foo_Widget widget.
*/
class IB_Act_Widget_Widget extends WP_Widget {

	/**
	* Register widget with WordPress.
	*/
	function __construct() {
		parent::__construct(
			'ib_act_widget', // Base ID
			esc_html__( 'IB Activity Widget', 'text_domain' ), // Name
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
		global $wpdb;
		$exchange_code = $_SESSION["exchange_code"];

		echo $args['before_widget'];
		?>
		<div class="widgetone">
			<h4>
				<img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/market.png" alt="">
				<?php	if ( ! empty( $instance['title'] ) ) {	echo apply_filters( 'widget_title', $instance['title'] ); }	?>
			</h4>
			<ul class="nav nav-pills nav-justified" id="widgettab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="win-tab" data-toggle="tab" href="#win" role="tab" aria-controls="win" aria-selected="true">winners</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="lose-tab" data-toggle="tab" href="#lose" role="tab" aria-controls="lose" aria-selected="false">losers</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="contact" aria-selected="false">active</a>
				</li>
			</ul>
			<div class="tab-content" id="widgettabContent">
				<div class="tab-pane fade show active" id="win" role="tabpanel" aria-labelledby="win-tab">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Symbol</th>
								<th scope="col">Last price</th>
								<th scope="col">Change</th>
								<th scope="col">% change</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key` = '".$exchange_code."_changepercent' ORDER BY CAST(`meta_value` as SIGNED) DESC LIMIT 0,5");
							foreach($results as $item){
								$priceinfo = get_post_meta($item->post_id,$exchange_code."_pricedata",true);
								$change = get_post_meta($item->post_id,$exchange_code."_change",true);
								if($change>=0){
									$change_class = "green";
								}else{
									$change_class = "red";
								}
								$changepercent = get_post_meta($item->post_id,$exchange_code."_changepercent",true);
								if($changepercent>=0){
									$changepercent_class = "green";
								}else{
									$changepercent_class = "red";
								}
								?>
								<tr>
									<th scope="row"><a style="color:#000;" href="<?=get_permalink($item->post_id);?>"><?=get_post_meta($item->post_id,$exchange_code."_symbol",true);?><span><?=get_the_title($item->post_id);?></span></a></th>
									<td><?=number_format($priceinfo["last"],2);?></td>
									<td class="<?=$change_class;?>"><?=number_format($change,2)?></td>
									<td class="<?=$changepercent_class;?>"><?=round($changepercent,3);?>%</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tab-pane fade" id="lose" role="tabpanel" aria-labelledby="lose-tab">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Symbol</th>
								<th scope="col">Last price</th>
								<th scope="col">Change</th>
								<th scope="col">% change</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key` = '".$exchange_code."_changepercent' AND `meta_value`!='' ORDER BY CAST(`meta_value` as SIGNED) ASC LIMIT 0,5");
							foreach($results as $item){
								$priceinfo = get_post_meta($item->post_id,$exchange_code."_pricedata",true);
								$change = get_post_meta($item->post_id,$exchange_code."_change",true);
								if($change>=0){
									$change_class = "green";
								}else{
									$change_class = "red";
								}
								$changepercent = get_post_meta($item->post_id,$exchange_code."_changepercent",true);
								if($changepercent>=0){
									$changepercent_class = "green";
								}else{
									$changepercent_class = "red";
								}
								?>
								<tr>
									<th scope="row"><a style="color:#000;" href="<?=get_permalink($item->post_id);?>"><?=get_post_meta($item->post_id,$exchange_code."_symbol",true);?><span><?=get_the_title($item->post_id);?></span></a></th>
									<td><?=number_format($priceinfo["last"],2);?></td>
									<td class="<?=$change_class;?>"><?=number_format($change,2)?></td>
									<td class="<?=$changepercent_class;?>"><?=round($changepercent,3);?>%</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Symbol</th>
								<th scope="col">Last price</th>
								<th scope="col">Volume</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key` = '".$exchange_code."_sharevolume' ORDER BY CAST(`meta_value` as SIGNED) DESC LIMIT 0,5");
							foreach($results as $item){
								$priceinfo = get_post_meta($item->post_id,$exchange_code."_pricedata",true);
								?>
								<tr>
									<th scope="row"><a style="color:#000;" href="<?=get_permalink($item->post_id);?>"><?=get_post_meta($item->post_id,$exchange_code."_symbol",true);?><span><?=get_the_title($item->post_id);?></span></a></th>
									<td><?=number_format($priceinfo["last"],2);?></td>
									<td class="green"><?=number_format($priceinfo["sharevolume"]);?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
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
function register_ib_act_widget() {
	register_widget( 'IB_Act_Widget_Widget' );
}
add_action( 'widgets_init', 'register_ib_act_widget' );
