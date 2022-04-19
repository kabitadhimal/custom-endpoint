<?php
/*
Plugin Name: Qkly Jobs Listing
Plugin URI:  https://devfinity.io/contact-us/
Description: List jobs posted in Qkly
Version: 1.0.7
Requires at least: 5.0
Requires PHP: 7.2
Author: Devfinity
Author URI: https://devfinity.io/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: qjl
*/

namespace Qkly;

include 'src/vendor/autoload.php';

use Qkly\JobSettings;
use Qkly\Service\JobService;

new JobSettings();
new JobPageSetup(getPluginUrl(), getJobManager());

function getPluginUrl(){
    return plugins_url('',__FILE__);
}

function getJobService(){
    static $jobsService;
    if(!$jobsService){
        $endPoint = JobSettings::getEndPoint();
        $jobsService = new JobService($endPoint);
    }
    return $jobsService;
}

function getJobManager(){
    static $jobManager;
    if(!$jobManager){
        $pluginUri = getPluginUrl();
       // $endPoint = JobSettings::getEndPoint();
        $jobsService = getJobService();
        $jobManager = new JobManager($pluginUri, $jobsService);
    }
    return $jobManager;
}

add_shortcode('qkly_job_list', function(){
    ob_start();
    getJobManager()->loadForm();
    return ob_get_clean();
});