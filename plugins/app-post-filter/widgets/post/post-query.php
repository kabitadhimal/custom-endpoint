<?php
/*$term = get_queried_object();
$inputCatSlug = "";
if( isset( $term ) && !empty( $term )) {
    $inputCatSlug = $term->slug;
    $inputCatName = $term->name;
}*/


//build query
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = [ 'post_type' => 'post', 'paged' => $paged, 'post_status'=> 'publish','posts_per_page' => 4];

if( isset( $_GET['category'] )  && ( $_GET["category"] != "all" ) ) {
    $args['category_name'] = $_GET['category'];
}

//search
$inputSearch = (isset($_GET['search']) && $_GET['search'] != '') ? $_GET['search'] : "";
if($inputSearch){
    $args['s'] = $inputSearch;
}
$query = new WP_Query( $args );