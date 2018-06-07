<?php

define(THEME_DIR, get_template_directory());
define(THEME_URL, get_template_directory_uri());

require(THEME_DIR . '/inc/circle.class.php');
require(THEME_DIR . '/inc/region.class.php');
require(THEME_DIR . '/vendor/autoload.php');

use \Abraham\TwitterOAuth\TwitterOAuth;

require(THEME_DIR . '/inc/tweet.class.php');

$twitterConnection = new  TwitterOAuth(
    TWITTER_CONSUMER_KEY,
    TWITTER_CONSUMER_SECRET,
    TWITTER_ACCESS_TOKEN,
    TWITTER_ACCESS_TOKEN_SECRET
);

$regions = \TwitterModel\Region::getAll();
$regionsWithTweets = array_filter($regions, function ($region) {
    return $region->tweetNum > 0;
});

usort($regionsWithTweets, function ($a, $b) {
    return $a->tweetNum < $b->tweetNum;
});


//var_dump (\TwitterModel\Region::getAll());

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');
    wp_register_script('class-marker', THEME_URL . "/assets/js/class.marker.js", ['jquery']);
    wp_register_script('class-circle', THEME_URL . "/assets/js/class.circle.js", ['jquery']);
    wp_register_script('map-script', THEME_URL . "/assets/js/map-script.js", ['class-marker', 'class-circle', 'google-charts']);
    wp_register_script('google-charts', THEME_URL . "/assets/js/google-charts-loader.js");
    wp_register_script('custom-charts-script', THEME_URL . "/assets/js/custom-charts.js", ['map-script']);
    wp_register_script('bootstrap-script', THEME_URL . "/assets/js/bootstrap.min.js", ['jquery']);

    $markerPattern = file_get_contents(THEME_DIR . '/assets/map-patterns/marker-pattern.svg');
    $cirlePattern = file_get_contents(THEME_DIR . '/assets//map-patterns/circle-pattern.html');
    $mapPattern = file_get_contents(THEME_DIR . '/assets/map-patterns/map-pattern.svg');
    //$regions = file_get_contents(THEME_DIR.'/assets/json/regions.json');
    global $regions, $regionsWithTweets;
    $regionsObj = [];
    foreach ($regions as $region) {
        $regionsObj[$region->id] = $region;
    }

    wp_localize_script('class-marker', 'classMarkerArgs', ['markerPattern' => $markerPattern]);
    wp_localize_script('class-circle', 'classCircleArgs', ['circlePattern' => $cirlePattern]);
    wp_localize_script('map-script', 'mapScriptArgs', [
        'mapPattern' => $mapPattern,
        'regions' => $regionsObj,
        'regionsWithTweets' => $regionsWithTweets
    ]);

    wp_enqueue_script('google-charts');
    wp_enqueue_script('class-marker');
    wp_enqueue_script('class-circle');
    wp_enqueue_script('map-script');
    wp_enqueue_script('custom-charts-script');
});

add_action('wp_print_styles', function () {
    wp_enqueue_style('bootstrap', THEME_URL . "/assets/css/bootstrap.min.css");
    wp_enqueue_style('main', THEME_URL . "/assets/css/main.css", ['bootstrap']);
});

/*add_action("init", function(){
    
    global $wpdb;

	$table_name = $wpdb->prefix . 'tweets';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
        tweet_id BIGINT(30) NOT NULL,
        content longtext,
        meta longtext,
		date datetime,
		PRIMARY KEY  (id)
	);";

$wpdb->get_results($sql);
var_dump($wpdb->last_result); die();

});*/

add_action("initt", function () {
    global $wpdb;
    $regions = json_decode(file_get_contents(THEME_DIR . '/assets/json/regions.json'), true);
    // var_dump(file_get_contents(THEME_DIR.'/assets/json/regions.json')); die();
    foreach ($regions as $id => $regionInfo) {
        /*$wpdb->insert(
            $wpdb->prefix."regions",['id'=>$id, 'label'=>$regionInfo['label']]
        );*/
        foreach ($regionInfo['circles'] as $circle) {
            $wpdb->insert(
                $wpdb->prefix . "circles", ['region_id' => $id, 'latitude' => $circle['lat'], 'longtitude' => $circle['long'], 'radius' => $circle['radius']]
            );

            // var_dump($wpdb->last_error); die();
        }


    }
});

add_action('init', function () {
    global $regions;
    var_dump(3);die();
    \TwitterModel\Tweet::updateFromApi('iphone', 30);
});