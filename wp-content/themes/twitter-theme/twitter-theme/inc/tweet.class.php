<?php
namespace TwitterModel;

class Tweet{
    private static $tableName = 'tweets';
    public $id;
    public $tweetId;
    public $circleId;
    public $content;
    public $meta;
    public $date;

    public function __construct($tweetId, $circleId, $content, $meta, $date){
        $this->tweetId = $tweetId;
        $this->circleId = $circleId;
        $this->content = $content;
        $this->meta = $meta;
        $this->date = $date;
    }

    public function insert(){
        global $wpdb;
        $tableName = self::getTableName();
        if($this->exists())
            return;
        $wpdb->insert($tableName, [
            'tweet_id' => $this->tweetId,
            'circle_id' => $this->circleId,
            'content' => $this->content,
            'meta' => $this->meta,
            'date' => $this->date
        ]);
    }

    public static function getByTweetId($tweetId){
        global $wpdb;
        $tableName = self::getTableName();
        return $wpdb->get_row("SELECT * FROM {$tableName} WHERE tweet_id={$tweetId}");
    }

    public function exists(){
        return !empty(self::getByTweetId($this->tweetId));
    }

    public static function getTableName(){
        global $wpdb;
        return $wpdb->prefix.self::$tableName;
    }

    public static function getAPITweetsFromCircle($circle, $search, $count = 100){
        global $twitterConnection; 
        $radius = $circle->radius/1000;
        $tweets = $twitterConnection->get("search/tweets", [
            "q" => $search, 
            "include_entities" => true,
            "geocode" => "{$circle->latitude},{$circle->longtitude},{$radius}km",
            "count" => $count
        ]);

        if(!count($tweets->statuses))
            return [];

        $tweets = array_map(function($tweet) use ($circle){
            return new Tweet( 
                $tweet->id,
                $circle->id,
                $tweet->text,
                json_encode($tweet),
                date('Y-m-d G:i:s', strtotime($tweet->created_at))
             );
        },$tweets->statuses);

        return $tweets;
    }

    public static function updateFromApi($search = 'iphone', $count = 100){
        global $twitterConnection;
        global $regions;
        foreach ($regions as $region){
            foreach($region->circles as $circle){
                $tweets = self::getAPITweetsFromCircle($circle, $search, $count);
                foreach($tweets as $tweet)
                    //var_dump(is_null($tweet));
                    $tweet->insert();
            }
        }
    }

    public static function getTweetsForRegion($regionId){
        global $wpdb;
        $tweetTableName = self::getTableName();
        $circlesTableName = Circle::getTableName();
        $regionTableName = Region::getTableName();

        return $wpdb->get_results("SELECT tweet.* FROM {$tweetTableName} tweet
        JOIN {$circlesTableName} circle
            ON tweet.circle_id = circle.id
        JOIN {$regionTableName} region
            ON circle.region_id = region.id
        WHERE region.id = {$regionId}");
    }

    public static function getTweetsForCirle($circleId){
        global $wpdb;
        $tableName = self::getTableName();
        return $wpdb->get_results("SELECT * FROM {$tableName} WHERE circle_id={$circleId}");
    }
}