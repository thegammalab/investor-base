<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://tidyhive.com
 * @since      1.0.0
 *
 * @package    Thfp
 * @subpackage Thfp/public/partials
 */
?>

<article class="thfp__item" style="background-color: '#999'; background-image: url(<?php echo get_the_post_thumbnail_url(); ?>); background-size: cover;">
	<div class="thfp-feat__content">
		<div class="thfp-feat__contentBox">
			<span class="thfp-entry__cat"><a href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a></span>
			<h2 class="thfp-entry__title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
			<a class="thfp-entry__button" href="<?php echo get_the_permalink(); ?>"><?php _e('Read More', 'thfp'); ?></a>
		</div>
	</div>
</article>
