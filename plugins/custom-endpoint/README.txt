=== Custom Endpont ===
Contributors: Kabita, kabitadhimal
Donate link: 
Tags: custom endpoint, WordPress REST API, custom routes, URL mappings
Requires at least: 5.0
Tested up to:  5.7.2
Requires PHP: 7.2
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin creates the custom end point for post.

== Description ==

We can get the json formated data of 'post_type' => post. And we can update the post type in class CustomAPI.PHP
- listing post
    http://your-site/wp-json/wp/v2/posts
    
- listing based on post id 
    http://your-site/wp-json/custom-endpoint/v1/posts/133

- listing post based on category id
    http://your-site/wp-json/wp/v2/post-by-category/CID

- filter post based on different criteria : category/title/current page
    http://your-site/wp-json/wp/v2/posts/?posts_per_page=5&current_page=2&title=abc&category=7

