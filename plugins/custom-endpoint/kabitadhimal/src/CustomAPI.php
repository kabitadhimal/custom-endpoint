<?php
namespace Np\Com\Kabitadhimal;
class CustomAPI {

    const POST_TYPE = 'post';
 
    public function __construct(){

        add_action('rest_api_init',[$this, 'register_rest_api_route']);
    }

    public function register_rest_api_route() {
        /* 
        * Registering a route 
        * This tells the API to respond to a given request with our function. 
        *
        * WP endpont: http://your-site/wp-json/wp/v2/posts/
        *
        * http://your-site/wp-json/custom-endpoint/v1/posts/
        */
        register_rest_route('custom-endpoint/v1','posts',[
            'method' => 'GET',
            'callback' => [$this,'custom_endpoint_posts']
        ]);
    
        /*
        * Your custom end pont : http://your-site/wp-json/custom-endpoint/v1/posts/133
        */
        register_rest_route('custom-endpoint/v1','posts/(?P<id>[\d]+)',[
            'method' => 'GET',
            'callback' => [$this,'custom_endpoint_post_id']
        ]);

        //Post by slug
        register_rest_route('custom-endpoint/v1','posts/(?P<slug>[a-zA-Z0-9-]+)',[
            'method' => 'GET',
            'callback' => [$this,'custom_endpoint_post_by_slug']
        ]);
        
        /*
        * Display post by particular category id
        * WP endpont: http://your-site/wp-json/wp/v2/post-by-category/CID
        */
        register_rest_route('custom-endpoint/v1','post-by-category/(?P<category_id>\d+)',[
            'method' => 'GET',
            'callback' => [$this,'custom_endpoint_post_by_category']
        ]);

        /*
        * Display all the post
        * WP endpont: http://your-site/wp-json/wp/v2/post-by-category/
        */
        register_rest_route('custom-endpoint/v1','post-by-category',[
            'method' => 'GET',
            'callback' => [$this,'custom_endpoint_post_by_category']
        ]);
    }

    public function custom_endpoint_posts($request) {
        $params = wp_parse_args( $request->get_params(), [
            'posts_per_page' => '',
            'current_page' => '',
            'title' => '',
            'category' => null
            ] );


        $args = [];

        if(isset($params['posts_per_page']) && !empty($params['posts_per_page'])) {
            $args['posts_per_page'] = $params['current_page'];   
        }

        if(isset($params['current_page']) && !empty($params['current_page'])) {
            $args['paged'] = $params['current_page'];   
        }


        if(isset($params['category']) && !empty($params['category'])) {
            $args['cat'] = intval($request['category']);
        }

        if(isset($params['category']) && !empty($params['category'])) {
            $args['s'] = $params['title'];
        }

        $args['post_type'] =  self::POST_TYPE ;
        $query = new \WP_Query( $args );
        $data = [];
        $i = 0;
        if($query->max_num_pages){
            $data['max_num_pages'] = $query->max_num_pages; 
        }
        while ( $query->have_posts() ) : 
            $query->the_post(); 
            $data['posts'][$i]['id'] = get_the_ID();
            $data['posts'][$i]['title'] = get_the_title();
            $data['posts'][$i]['date'] = get_the_date();
            $data['posts'][$i]['excerpt'] = get_the_excerpt();
            $data['posts'][$i]['content'] = get_the_content();
            $data['posts'][$i]['permalink'] = get_permalink();
            $data['posts'][$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            $data['posts'][$i]['featured_image']['medium'] = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $data['posts'][$i]['featured_image']['large'] = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $i++;
        endwhile;
        wp_reset_query();

    return $data;
    }


    /*
    * Post by Slug 
    * 
    * http://your-site/wp-json/custom-endpoint/v1/posts/post-format-video-youtube
    */

    public function custom_endpoint_post_by_slug($request) {

        $args = [
        'name' => $request['slug'],
        'post_type' =>  self::POST_TYPE 
        ];

        $post = get_posts($args);
        $data = [];
        if($post) {
            $data['id'] = $post[0]->ID;
            $data['title'] = $post[0]->post_title;
            $data['content'] = $post[0]->post_content;
            $data['slug'] = $post[0]->post_name;
            $data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0], 'thumbnail');
            $datad['featured_image']['medium'] = get_the_post_thumbnail_url($post[0], 'medium');
            $data['featured_image']['large'] = get_the_post_thumbnail_url($post[0], 'large');
        }
        return $data;
    }

    /*
    * Post by ID 
    *  http://your-site/wp-json/custom-endpoint/v1/posts/1161
    */

    public function custom_endpoint_post_id($request) {

        $args = [
            'p' => $request['id'],
            'post_type' => self::POST_TYPE 
        ];


        $post = get_posts($args);
        $data = [];
        if($post) {
            $data['id'] = $post[0]->ID;
            $data['title'] = $post[0]->post_title;
            $data['content'] = $post[0]->post_content;
            $data['slug'] = $post[0]->post_name;
            $data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0], 'thumbnail');
            $data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0], 'medium');
            $data['featured_image']['large'] = get_the_post_thumbnail_url($post[0], 'large');
        }

        return $data;

    }

    public function custom_endpoint_post_by_category($request){

        if(isset($request['category_id']) && !empty($request['category_id'])) {
            $args['cat'] = $request['category_id'];
        }
        /*
        * Update the post Type
        */
        $args['post_type'] =  self::POST_TYPE ;
        $query = new \WP_Query( $args );
        $data = [];
        $i = 0;
        if($query->max_num_pages){
            $data['max_num_pages'] = $query->max_num_pages; 
        }
        while ( $query->have_posts() ) : 
            $query->the_post(); 
            $data['posts'][$i]['id'] = get_the_ID();
            $data['posts'][$i]['title'] = get_the_title();
            $data['posts'][$i]['date'] = get_the_date();
            $data['posts'][$i]['excerpt'] = get_the_excerpt();
            $data['posts'][$i]['content'] = get_the_content();
            $data['posts'][$i]['permalink'] = get_permalink();
            $data['posts'][$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            $data['posts'][$i]['featured_image']['medium'] = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $data['posts'][$i]['featured_image']['large'] = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $i++;
        endwhile;
        wp_reset_query();

        return $data;

    }
}