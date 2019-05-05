=== Plugin Name ===
Contributors: orahmax, orahmax2
Donate link: https://tidyhive.com
Tags: featured, featured posts, widget, posts, post, slider, carousel, posts slider, featured posts slider
Requires at least: 3.0.1
Tested up to: 4.7
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Set featured posts with single click, no page refresh from admin panel.

== Description ==

This plugin adds the ability for the user to mark a post to be a featured post with just one click without the need of editing an individual post. The plugin also includes a widget which can display all the posts which have been marked featured.

= Usage =

* Go to your admin dashboard page.
* Open all posts page which displays the list of posts in table.
* A column name 'Featured posts' will be there with a star icon in each row.
* Click the star to make that post as featured.

Dashboard->Posts>All Posts


= Instructions =

Q1. How to display feature posts?

Plugin comes with a widget, which can be added to sidebar

Q2. How to display featured posts in any desired section of theme(Developers)?

In order to display featured posts in your theme, use the following code to fetch the posts.

`$featured_posts = Thfp_public::thfp_get_featured_posts();`

The above code return WP_Query object which you can use in a loop. Above static function accepts a parameter(int) which limits the number of posts which it can return.

Show the posts with the help of custom loop:-

`if($featured_posts->have_posts()):
	while($featured_posts->have_posts()): $featured_posts->the_post();
		//Your code here
	endwhile;
endif;`

You can also use your own wp_query. Make sure you use 'tag' key to be 'featured'.

`$args = array(
	'post_status'	=>	'publish',
	'tag'			=>	'featured',
	//Use any other needed args
);
$featured_posts = new WP_Query($args);`

and then use the loop to display posts.

== Installation ==

1. Upload `thfp.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
