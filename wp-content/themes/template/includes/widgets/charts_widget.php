<?php
/**
 * Adds Foo_Widget widget.
 */
class IB_Chart_Widget_Widget extends WP_Widget
{

	/**
	 * Register widget with WordPress.
	 */
	function __construct()
	{
		parent::__construct(
			'ib_chart_widget', // Base ID
			esc_html__('IB Charts Widget', 'text_domain'), // Name
			array('description' => esc_html__('A Foo Widget', 'text_domain'),) // Args
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
	public function widget($args, $instance)
	{
		echo $args['before_widget'];
		$results = apply_filters('tdf_get_posts', "post", 3, 0, array("search" => array(), "order" => "rand"));
		?>
	<script src="<?php echo get_bloginfo("template_directory"); ?>/assets/js/charts.js"></script>

	<div class="widgetone">
		<h4>
			<img src="<?= get_bloginfo("template_directory"); ?>/assets/images/other_news.png" alt="">
			<?php if (!empty($instance['title'])) {
				echo apply_filters('widget_title', $instance['title']);
			}	?>
		</h4>
		<div class="" style="z-index: 100; position: relative; padding:15px 10px 1px; background: #fff; box-shadow: 0 0 25px rgba(149, 163, 179, 0.2);">
			<!-- Start of QuoteMedia.com Mini Market Indices Code -->
			<style>
				.qm-wrap-minimarketindices h2 {
					display: none;
				}
			</style>
			<div class="qm-wrap-minimarketindices">
				<div data-qmod-tool="minimarketindices" data-qmod-params='{symbol:YHOO}' class="qtool"></div>
			</div>
			<!-- LOADER  102396-->
			<script id="qmod" type="application/javascript" src="//qmod.quotemedia.com/js/qmodLoader.js" data-qmod-wmid="102396" data-qmod-env="app" async data-qmod-version=""></script>
			<!-- END QMOD CODE -->
			<!-- TradingView Widget BEGIN -->
			<!-- <div class="tradingview-widget-container" style="z-index:100;">
  												<div class="tradingview-widget-container__widget"></div>
  												<div class="tradingview-widget-copyright d-none"><a href="https://www.tradingview.com/markets/indices/" rel="noopener" target="_blank"><span class="blue-text">Indices</span></a> by TradingView</div>
  												<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
  												{
  												"showChart": true,
  												"locale": "en",
  												"largeChartUrl": "",
  												"width": "400",
  												"height": "660",
  												"plotLineColorGrowing": "rgba(33, 150, 243, 1)",
  												"plotLineColorFalling": "rgba(33, 150, 243, 1)",
  												"gridLineColor": "rgba(233, 233, 234, 1)",
  												"scaleFontColor": "rgba(131, 136, 141, 1)",
  												"belowLineFillColorGrowing": "rgba(5, 122, 205, 0.12)",
  												"belowLineFillColorFalling": "rgba(5, 122, 205, 0.12)",
  												"symbolActiveColor": "rgba(225, 239, 249, 1)",
  												"tabs": [
    												{
      												"title": "Indices",
      												"symbols": [
        												{
          												"s": "INDEX:SPX",
          												"d": "S&P 500"
        												},
        												{
          												"s": "INDEX:DOWI",
          												"d": "Dow 30"
        												},
        												{
          												"s": "INDEX:DAX",
          												"d": "DAX Index"
        												},
        												{
          												"s": "TSX:TSX"
        												}
      												],
      												"originalTitle": "Indices"
    												}
  												]
												}
  												</script>
												</div> -->
			<!-- TradingView Widget END -->
			<?php /*
				<div class="" style="position:relative; z-index:100;">
					<div class="carousel_bg" style="height:auto;">
						<div id="firstpage_smallcarousel" class="">
							<?php for($i=0;$i<6;$i++){ ?>
		<div class="item">
			<div class="row">
				<?php for($j=0;$j<3;$j++){ ?>
				<div class="col-sm-12 py-3">
					<script type="text/javascript">
						var config<?=$j.$i;?> = {
							type: 'line',
							data: {
								labels: ['', '', '', '', '', '', '', '', '', '', '', '', '', ''],
								datasets: [{
									borderColor: "#95a3b3",
									pointRadius: 0,
									pointHoverRadius: 0,
									data: [
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
										randomScalingFactor(),
									],
									fill: false,
								}]
							},
							options: {
								responsive: true,
								legend: {
									display: false,
								},
								title: {
									display: false,
								},
								tooltips: {
									enabled: false,
								},
								scales: {
									xAxes: [{
										display: false,

									}],
									yAxes: [{
										display: false,

									}]
								}
							}
						};
						jQuery(document).ready(function() {
							var ctx<?=$j.$i;?> = document.getElementById('canvas<?=$j.$i;?>').getContext('2d');
							window.myLine<?=$j.$i;?> = new Chart(ctx<?=$j.$i;?>, config<?=$j.$i;?>);
						})
					</script>
					<div class="row m-0">
						<div class="col-6 p-0">
							<div class="actions_name">S&P/TSX</div>
							<div style="width:60px; height:25px;">
								<canvas id="canvas<?=$j.$i;?>"></canvas>
							</div>
						</div>
						<div class="col-6 p-0">
							<div class="money_total">14,937.00</div>
							<div class="increase_decrease">-245.64 (-1.62%)</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>

	</div>
	<a class="carousel-control-prev" href="#firstpage_smallcarousel" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#firstpage_smallcarousel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#firstpage_smallcarousel').owlCarousel({
			loop: true,
			margin: 0,
			nav: false,
			dots: false,

			responsive: {
				0: {
					items: 1
				},
				240: {
					items: 1
				},
				480: {
					items: 2
				},
				769: {
					items: 3
				},
				992: {
					items: 1
				},
				1200: {
					items: 2
				}
			}
		});

		jQuery('.carousel-control-next').click(function() {
			jQuery('#firstpage_smallcarousel').trigger('next.owl.carousel');
			return false;
		});

		jQuery('.carousel-control-prev').click(function() {
			jQuery('#firstpage_smallcarousel').trigger('prev.owl.carousel');
			return false;
		});
	});
</script>
*/
			?>
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
public function form($instance)
{
	$title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'text_domain');
	?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
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
public function update($new_instance, $old_instance)
{
	$instance = array();
	$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

	return $instance;
}
} // class Foo_Widget

// register Foo_Widget widget
function register_ib_chart_widget()
{
	register_widget('IB_Chart_Widget_Widget');
}
add_action('widgets_init', 'register_ib_chart_widget');
