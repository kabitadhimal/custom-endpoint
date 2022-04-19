<?php

namespace Qkly;
use Qkly\Service\JobService;
use Symfony\Component\HttpFoundation\Request;

class JobManager
{
    private $templatePath;
    private $pluginUri;

    /**
     * @var JobService
     */
    private $jobsService;

    public function __construct( $pluginUri, JobService $jobsService)
    {
        $this->templatePath = __DIR__.'/../partial';
        $this->pluginUri = $pluginUri;
        $this->jobsService = $jobsService;
    }

    public function getInputsFromGlobal(&$inputs = [])
    {
        $request = Request::createFromGlobals();
        $inputs['searchText'] = $request->get('searchText');
        $inputs['location'] = $request->get('location');
        $inputs['isRemote'] = $request->get('isRemote');
        $inputs['isSalary'] = $request->get('isSalary');
        $inputs['currentPage'] = $request->get('currentPage', 1);
        $inputs = $this->filterInputs($inputs);
    }

    public function filterInputs(array $inputs = [])
    {

            $inputs['searchText'] = ( isset($inputs['searchText']) && $inputs['searchText'] != "" ) ?
                sanitize_text_field( $inputs['searchText'] ) :"";

            $inputs['location'] = ( isset($inputs['location']) && $inputs['location'] != "" ) ?
                sanitize_text_field( $inputs['location'] ) : "";

            $inputs['isSalary'] = ( isset($inputs['isSalary']) && $inputs['isSalary'] != "" ) ?
                wp_validate_boolean( $inputs['isSalary'] ) : "";

            $inputs['isRemote'] = ( isset($inputs['isRemote']) && $inputs['isRemote'] != "" ) ?
                wp_validate_boolean( $inputs['isRemote']  ) : "";

            $inputs['currentPage'] = ( isset($inputs['currentPage']) && $inputs['currentPage'] != "" ) ?
                                    filter_var( $inputs['currentPage'] , FILTER_VALIDATE_INT) : "";

        return $inputs;
    }

    public function loadForm()
    {
        $inputs = [];
        $this->getInputsFromGlobal($inputs);
        $assetUrl = $this->pluginUri;
        include $this->templatePath.'/form.php';
    }

    public function processForm()
    {
        $inputs = [];
        $this->getInputsFromGlobal($inputs);

        $options = get_option('qkly_option_name');

        $inputs['companyId'] = ( isset( $options['company_id'] ) && !empty( $options['company_id'] ) ) ?
            sanitize_text_field( $options['company_id'] ) : "";

        /*
         * if the client tries to access the job from another website
         */
        $serverName = isset( $_SERVER['SERVER_NAME'] ) && !empty( $_SERVER['SERVER_NAME'] ) ?
            sanitize_text_field( $_SERVER['SERVER_NAME'] ) : "";
        $companyHost = ( isset( $_COOKIE['companyHost'] ) && !empty( $_COOKIE['companyHost'] ) ) ?
            sanitize_text_field( $_COOKIE['companyHost'] ) : "";
        $companyID = ( isset( $_COOKIE['companyID'] ) && !empty( $_COOKIE['companyID'] ) ) ?
            sanitize_text_field( $_COOKIE['companyID'] ) : "";

        if( ( isset($companyHost) && $serverName != $companyHost ) &&  isset($companyID)) {
            $inputs['companyId'] = $companyID ;
        }

        $jobs = $this->jobsService->getJobs( $inputs, $inputs['currentPage'] );
        return $jobs;
    }

    private function createSlug($str, $delimiter = '-'){

        $str = preg_replace('#[0-9 ]*#', '', $str);
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;

    }

    function trimWords($text, $num_words = 55, $more = null) {
        $text          = strip_tags( $text );
        $num_words     = (int) $num_words;

        $words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
        $sep         = ' ';
        if ( count( $words_array ) > $num_words ) {
            array_pop( $words_array );
            $text = implode( $sep, $words_array );
            $text = $text . $more;
        } else {
            $text = implode( $sep, $words_array );
        }
        return $text;
    }

    public function getHtml($jobs)
    {
        if(!$jobs)  return '';

        /*
         * Grid for job listing container
         */
        $option = get_option('qkly_option_name');
        $gridType = $option['layout_display'];
        $qklyListClass ="";
        $qklyCardClass = "";
        if( isset($gridType) && ($gridType == "single-col") ) {
            $qklyListClass = "qkly-single-list";
          //  $qklyCardClass = "qkly-single-card";
        }

        ob_start();
        $count =1;
        foreach ($jobs as $job) {
            include __DIR__ . '/../partial/jobs.php';
            $count++;
        }
        return ob_get_clean();
    }

}