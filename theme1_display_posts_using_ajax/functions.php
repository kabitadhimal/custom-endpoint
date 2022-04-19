<?php
use Np\Com\Kabitadhimal\Pagination;

require_once get_template_directory().'/vendor/autoload.php';
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function getEvents($currentPage){
    $client = new GuzzleHttp\Client();
    $requestUri = "http://your-site/wp-json/custom-endpoint/v1/posts/";
    $method = "GET";
        try {
            $request = $client->request('GET', $requestUri,
                [
                    'headers'=> [
                        'Content-Type' => 'application/json-patch+json',
                        'accept' => 'text/plain'
                    ],
                    //'debug' => true
                    'query' => ['current_page' => $currentPage]
                ]
            );

            $postResponse = json_decode($request->getBody());
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }

        return $postResponse;
}

function getEventsHtml($posts) {
    echo ' <div class="row mb-2">';
    foreach($posts as $posts) {
        ?>
            <div class="col-md-6">
            <div class="row g-0 border">
                <div class="col p-4 d-flex flex-column ">
                <div class="mb-1 text-muted"><?=$posts->date?></div>
                <p class="card-text mb-auto"><?=$posts->excerpt?></p>
                <a href="<?=$posts->permalink?>" class="stretched-link">Continue reading</a>
                </div>
                <?php if( isset($posts->featured_image->thumbnail)): ?>
                <div class="col-auto d-none d-lg-block">
                    <img src="<?=$posts->featured_image->thumbnail?>" />
                </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
<?php
        
    }
    echo '</div>';
}

add_action( 'wp_ajax_events', 'ajaxEventListsHandler');
add_action( 'wp_ajax_nopriv_events',  'ajaxEventListsHandler');

function ajaxEventListsHandler() {
    $currentPage = $_GET['current_page'];
    $data = getEvents($currentPage);
    ob_start();
    getEventsHtml( $data->posts );
        $eventsHtml = ob_get_contents();
    ob_end_clean();
    $pagination = Pagination::paginate( $data->max_num_pages,  $currentPage);
    $response = [
        'content' => $eventsHtml,
        'pagination' => $pagination
    ];
    wp_send_json($response);
}