<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header(); ?>


<?php

?>

<?php if ( is_home() && ! is_front_page() && ! empty( single_post_title( '', false ) ) ) : ?>
	<header class="page-header alignwide">
		<h1 class="page-title"><?php single_post_title(); ?></h1>
	</header><!-- .page-header -->
<?php endif; ?>

<div class="post-content">

</div>

<div class="pagination"></div>

<?php
/*

$client = new GuzzleHttp\Client();

$requestUri = "http://localhost/testwordpress/wp-json/w1/v1/posts/";
$method = "GET";

try {
    $request = $client->request('GET', $requestUri,
        [
            'headers'=> [
                'Content-Type' => 'application/json-patch+json',
                'accept' => 'text/plain'
            ]
            //'debug' => true
        ]
    );

   // var_dump($this->endPoint);
    //throw new \Exception("this is not working");
    $postResponse = json_decode($request->getBody());
    //var_dump($request->getBody());
    //wp_send_json($locationResponse);

    //file_put_contents($this->jobJsonFile , $request->getBody());
}catch (\Exception $exception){
    var_dump($exception->getMessage());
}

foreach($postResponse->posts as $posts) {
    var_dump($posts->id);
    var_dump($posts->featured_image->thumbnail);
}
*/

?>



<script>
    function get_events(currentPage, successCallback){
        console.log('2.fetch events');
        var ajaxUrl = "<?=admin_url( 'admin-ajax.php' )?>";
        var data = {
        'action': 'events',
        'current_page': currentPage
            };

        jQuery.ajax({
            url : ajaxUrl,
            data : data,
            success: function(response) {
              //  console.log('3.fetch events');
                jQuery( ".post-content" ).html(response.content);
                jQuery( ".pagination" ).html(response.pagination);
                successCallback();
            }
        });
    }

    function getEventSuccessHandler(){
      //  console.log('4.assign events');
        jQuery('ul.pagination li a').click(function(e) {
            console.log(jQuery(this).text());
            e.preventDefault();
            get_events(jQuery(this).text(), getEventSuccessHandler)
        });
    }

    /*
     * Asynchronus Query
     */

  jQuery(document).ready(function($){
      console.log('1.start');
        get_events(1, getEventSuccessHandler);
  });
</script>
<?php
 get_footer();