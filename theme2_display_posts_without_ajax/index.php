<?php
get_header();
require_once get_template_directory().'/vendor/autoload.php';
use Np\Com\Kabitadhimal\Pagination;
$client = new GuzzleHttp\Client();
$requestUri = "http://localhost/testwordpress/wp-json/custom-endpoint/v1/posts/";

$currentPage = isset($_GET['currentPage']) && !empty($_GET['currentPage']) ? $_GET['currentPage'] : 1;

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

    echo ' <div class="row mb-2">';
    foreach($postResponse->posts as $posts) {
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
    echo Pagination::paginate( $postResponse->max_num_pages,  $currentPage);
get_footer();