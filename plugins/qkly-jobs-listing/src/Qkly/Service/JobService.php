<?php

namespace Qkly\Service;

use GuzzleHttp\Client;
use Symfony\Component\VarExporter\VarExporter;

class JobService{

    private $endPoint;

    public function __construct($endPoint)
    {
        $this->endPoint = $endPoint;
       // $this->endPoint = 'https://qkly-jobboard-production-api.azurewebsites.net/';

    }

    protected function prepareDataForSearch($params = [], $currentPage = 1, $itemsPerPage = 10)
    {
        $data = [
            'advancedSearchField' => [
               // "companyId" => "97fa129d-3b38-4788-a813-ed633a5102ca",
                "industryIds" => [],
                "jobTitleIds" => [],
                "jobTitles" => [],
                "jobPositionIds" => [],
                "skillIds" => [],
                "experience" => [],
                "education" => [],
                "languageIds" => [],
                "benefitIds" => [],

                /*  "isStateSearch" => true,
                  "state"=>"Utah",*/
                "IsFreeFormAddressSearch" => false
            ],
            'searchFilter' => [
                "page" => $currentPage,
                "resultPerPage" => $itemsPerPage,
                // "searchText" =>"wordpress"
            ]
        ];

        if(isset($params['searchText'])){
            $data['searchFilter']['searchText'] = filter_var($params['searchText'], FILTER_SANITIZE_STRING);
        }

        if(isset($params['location'])){
            $location = filter_var($params['location'], FILTER_SANITIZE_STRING);
            $data['advancedSearchField']['isStateSearch'] = (bool) ($location);
            $data['advancedSearchField']['state'] = $location;
        }
        if(isset($params['companyId'])){
            //$companyID = filter_var($params['companyId'], FILTER_SANITIZE_STRING);
           // $data['advancedSearchField']['companyId'] = "97fa129d-3b38-4788-a813-ed633a5102ca";
            $data['advancedSearchField']['companyId'] = $params['companyId'];
        }

        if(isset($params['isRemote'])){
            $data['advancedSearchField']['isRemote'] = (bool) $params['isRemote'];
        }

        if(isset($params['showSalary'])){
            $data['advancedSearchField']['isRemote'] = (bool) $params['isRemote'];
        }

        return $data;
    }

    public function getJobs($params = [],  $currentPage = 1){
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->endPoint
        ]);
        $options = get_option('qkly_option_name');
        //var_dump($options);
        $jobsPerPage = isset( $options['jobs_per_page'] ) && !empty($options['jobs_per_page']) ? intval( $options['jobs_per_page'] ) : 10 ;

        $data = $this->prepareDataForSearch($params, $currentPage, $jobsPerPage);

        $jobListResponse = null;
        $requestUri = "/api/v1/Job/getall";
        $method = 'POST';

        try {
            $request = $client->request($method, $requestUri,
                [
                    'headers'=> [
                        'Content-Type' => 'application/json-patch+json',
                        'accept' => 'text/plain'
                    ],
                   'body' => json_encode($data),
                    //'debug' => true
                ]
            );


            //throw new \Exception("this is not working");
            $jobListResponse = json_decode($request->getBody());


           // var_dump($jobListResponse);
            $jobListResponse->inputs = [
                'currentPage' => $currentPage,
                'data' => $params
            ];
           //var_dump($jobListResponse);
            //file_put_contents($this->jobJsonFile , $request->getBody());
        }catch (\Exception $exception){
           // var_dump($exception->getMessage());
        }
        if($jobListResponse){
            //
        }

        return $jobListResponse;
    }

    public function getLocation($location) {
        //$_GET['location']
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->endPoint
        ]);

        $locationResponse = null;
        try {
            $request = $client->request('GET', "/api/v1/company/location/list/{$location}",
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
            $locationResponse = json_decode($request->getBody());
            //var_dump($request->getBody());
            //wp_send_json($locationResponse);

            //file_put_contents($this->jobJsonFile , $request->getBody());
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
        }

        if($locationResponse){
            //
        }
        return $locationResponse;

        
    }
}