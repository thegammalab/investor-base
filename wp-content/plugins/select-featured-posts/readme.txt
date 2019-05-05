=== Select Featured Posts ===
Contributors: mediology,kapilchugh
Tags: featured post, featured, post, wordpress, ajax
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 0.2

A plugin which allows you to select/unselect posts and updates the same using Ajax.

== Description ==

This allows you to mark the selected post as featured post and hence enables editors, publishers, bloggers etc to feature relavent content on the site.

In most content publishing scenarios, where there are a large set of contributors, certain posts require to be featured in relevant content sections of your WP site, so that they draw the maximim attention span from the readers. This Plugin allows the editors or administrators to mark a set of posts as featured posts. Then using a simple functional call in your theme, you can dynamically display the featured posts.  if you want to place it separatly.

**Select Featured Posts plugin is also available as a widget.**

We would be keen you hear your feedback and requests for any enhancements. Please send your feedback to wordpress@mediologysoftware.com.

== Installation ==

1. Upload the `select-featured-posts` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Posts -> Posts screen if you have wordpress 3.0 and for older versions go to Posts ->  Edit.
4. Check and uncheck posts to make it featured/unfeatured
5. To use it in theme add this before query_posts or get_posts

  `if (function_exists('featured_posts')) {
    featured_posts('add');
  }`

  If query_posts or get_posts is not there in your theme then you can add above code and query_posts before while loop
  For eg.

  <pre>
  if (function_exists('featured_posts')) {
    featured_posts('add');
  }
  query_posts('posts_per_page=10');
    while ( have_posts() ) : the_post();
    //Your Code
  endwhile; // end of the loop.
  if (function_exists('featured_posts')) {
    featured_posts('remove');
  }
  </pre>


6. To remove the featured post from the query post buffer once the same have been displayed add this code

  `if (function_exists('featured_posts')) {
    featured_posts('remove');
  }`

For eg.

  `if (function_exists('featured_posts')) {
    featured_posts('add');
  }
  query_posts('category_name=Entertainment');
  // Hence now that the featured posts have been displayed clear them so that they don't get repeated.
  if (function_exists('featured_posts')) {
    featured_posts('remove');
  }`
So it will give featured post of Entertainment Category.

OR

7. If you want to get Featured post IDs only then use this

  `if (function_exists('get_featured_posts')) {
    $limit = 5;
    $featured_posts = get_featured_posts($limit);
  }`

8. If your theme supports widgets then go to Appearance -> Widgets and just Drag and Drop Select Featured Post Widget.
9. In widget you can specify total no of posts or Featured Post of a Category.

== Frequently Asked Questions ==
Please send us your questions to wordpress@mediologysoftware.com. We will feature the frequently asked questions and answers here.

== Screenshots ==
1. Select Featured post checkbox in the Admin panel
2. Widget Display.


== Changelog ==

= 0.2 =
* Removed Notices and updated documentation

= 0.1 =
* Initial release
