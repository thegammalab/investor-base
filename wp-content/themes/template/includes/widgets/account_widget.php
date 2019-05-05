<?php
/**
* Adds Foo_Widget widget.
*/
class IB_Acc_Widget_Widget extends WP_Widget {

	/**
	* Register widget with WordPress.
	*/
	function __construct() {
		parent::__construct(
			'ib_acc_widget', // Base ID
			esc_html__( 'IB Account Widget', 'text_domain' ), // Name
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
		echo $args['before_widget'];
		?>
		<div class="widgettwo">
			<h4>
				<img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/phone.png" alt="">
				<?php	if ( ! empty( $instance['title'] ) ) {	echo apply_filters( 'widget_title', $instance['title'] ); }	?>
			</h4>
			<ul class="nav nav-pills nav-justified" id="widgettab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="watchlist-tab" data-toggle="tab" href="#watchlist" role="tab" aria-controls="win" aria-selected="true">Watchlist</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="portfolio-tab" data-toggle="tab" href="#portfolio" role="tab" aria-controls="portfolio" aria-selected="false">POrtfolio</a>
				</li>
			</ul>
			<div class="tab-content" id="widgettabContent">
				<div class="tab-pane fade show active" id="watchlist" role="tabpanel" aria-labelledby="watchlist-tab">
					<?php
					$my_watchlist = get_my_watchlist();
					$watchlist_items = array(99999999999);
					foreach($my_watchlist as $w_itm){
						$watchlist_items[]=$w_itm["post_id"];
					}
					$results = apply_filters('tdf_get_posts',"companies",25,get_query_var("paged"),array("search"=>array("pid"=>$watchlist_items),"order"=>"title_asc"));
					if(count($results["items"])){
						?>
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
								<?php foreach($results["items"] as $item){
									$w_it = ($my_watchlist[$item["post_id"]]);
									$priceinfo = get_post_meta($item["post_id"],$w_it["exchange"]."_pricedata",true);
									if($priceinfo["change"]>=0){
										$val_class = "green";
									}else{
										$val_class = "red";
									}
									?>
									<tr>
										<th scope="row"><a style="color:#000;" href="<?=$item["post_permalink"]."/".$w_it["exchange"];?>"><?=$w_it["code"];?><span><?=$item["post_title"];?></span></a></th>
										<td><?=$priceinfo["last"];?></td>
										<td class="<?=$val_class;?>"><?=number_format($priceinfo["change"],2);?></td>
										<td class="<?=$val_class;?>"><?=round($priceinfo["changepercent"],3);?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					<?php }else{ ?>
						<h3 class="no_results pt-3 light_nores" style="text-align:center; color: rgba(149, 163, 179, 0.2);">Sorry, no results</h3>
					<?php } ?>
				</div>
				<div class="tab-pane fade" id="portfolio" role="tabpanel" aria-labelledby="portfolio-tab">
					<?php
					$portfolio = apply_filters('tdf_get_portfolio_ids',UID);
					$portfolio_ids = array(99999999999);
					foreach($portfolio as $f=>$v){
						$portfolio_ids[]=$f;
					}

					$results = apply_filters('tdf_get_posts',"companies",25,get_query_var("paged"),array("search"=>array("pid"=>$portfolio_ids),"order"=>"title_asc"));
					if(count($results["items"])){
						?>
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
								<?php foreach($results["items"] as $item){
									$w_it = ($portfolio[$item["post_id"]]);
									$priceinfo = get_post_meta($item["post_id"],$w_it["exchange"]."_pricedata",true);
									if($priceinfo["change"]>=0){
										$val_class = "green";
									}else{
										$val_class = "red";
									}
									?>
									<tr>
										<th scope="row"><a style="color:#000;" href="<?=$item["post_permalink"]."/".$w_it["exchange"];?>"><?=$w_it["code"];?><span><?=$item["post_title"];?></span></a></th>
										<td><?=$priceinfo["last"];?></td>
										<td class="<?=$val_class;?>"><?=number_format($priceinfo["change"],2);?></td>
										<td class="<?=$val_class;?>"><?=round($priceinfo["changepercent"],3);?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					<?php }else{ ?>
						<h3 class="no_results pt-3 light_nores" style="text-align:center; color: rgba(149, 163, 179, 0.2);">Sorry, no results</h3>
					<?php } ?>
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
function register_ib_acc_widget() {
	register_widget( 'IB_Acc_Widget_Widget' );
}
add_action( 'widgets_init', 'register_ib_acc_widget' );
