<?php
namespace Qkly;

class JobPageSetup
{
    /**
     * @var JobManager
     */
    private $jobManager;
    private $pluginUrl;

    public function __construct($pluginUrl, JobManager $jobManager)
    {
        $this->jobManager = $jobManager;
        $this->pluginUrl = $pluginUrl;

        add_action( 'wp_enqueue_scripts', [$this, 'setup'] );

        add_action( 'wp_ajax_jobs', [$this, 'ajaxJobListsHandler']);
        add_action( 'wp_ajax_nopriv_jobs', [$this, 'ajaxJobListsHandler']);

        add_action( 'wp_ajax_qkly_location', [$this, 'ajaxLocationSearchHandler']);
        add_action( 'wp_ajax_nopriv_qkly_location', [$this, 'ajaxLocationSearchHandler']);

    }

    public function setup()
    {
        $version = '1.5';
        wp_enqueue_style(
            'qkly-main-css',
            $this->pluginUrl."/assets/css/main.css",
            "",
                $version
        );
        wp_enqueue_style(
            'qkly-job-list-css',
            $this->pluginUrl."/assets/css/joblist.css",
            "",
                $version
        );
        wp_enqueue_style(
            'qkly-icon-css',
            $this->pluginUrl."/assets/css/icons.css",
            "",
                $version
        );
        wp_enqueue_style(
            'qkly-custom-css',
            $this->pluginUrl."/assets/css/custom.css",
            "",
                $version
        );

        wp_enqueue_style(
            'qkly-google-font-montserrat',
            "https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap",
            "",
            $version
        );

        wp_enqueue_style(
            'qkly-google-font-sans',
            "https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap",
            "",
            $version
        );

        wp_enqueue_script(
            'qkly-js-modal',
            $this->pluginUrl."/assets/js/qkly-js-modal.js",
           '',
            $version,
            false
        );
        wp_enqueue_script(
            'qkly-job-listing',
            $this->pluginUrl."/assets/js/job-listing.js",
            [ 'jquery'],
            $version,
            false
        );

        wp_enqueue_script(
            'qkly-typehead-bundle',
            $this->pluginUrl."/assets/js/vendors/type-head/typeahead.bundle.js",
            '',
            $version,
            false
        );

        wp_enqueue_script(
            'qkly-typehead-client',
            $this->pluginUrl."/assets/js/typeahead.js",
            '',
            $version,
            true
        );
        wp_localize_script(
            'qkly-typehead-client',
            'qklyParams',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            )
        );

    }

    public function ajaxLocationSearchHandler()
    {
        $location = sanitize_text_field( $_GET['location'] );
        $jobService = getJobService();
        $locationResponse = $jobService->getLocation($location);

        wp_send_json($locationResponse);
    }

    public function ajaxJobListsHandler()
    {
        $jobResult = $this->jobManager->processForm();
        $jobsHtml = $this->jobManager->getHtml($jobResult->jobList);
        $pagination = Pagination::paginate( $jobResult->totalPage,  $jobResult->inputs['currentPage']);

        $response = [
            'content' => $jobsHtml,
            'pagination' => $pagination
        ];
        wp_send_json($response);

    }

}