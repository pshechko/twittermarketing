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
        $this->date = strtotime($date);
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

    public static function getTweetsFromCircle($circle){
        global $twitterConnection; 
        $radius = $circle->radius/1000;
        $tweets = $twitterConnection->get("search/tweets", [
            "q" => $search, 
            "include_entities" => true,
            "geocode" => "{$circle->latitude},{$circle->longtitude},{$radius}km",
            "count" => 5 
        ]);
       // $tweets = json_decode($tweets);
        return $tweets->statuses;
    }

    public static function updateFromApi($search = 'iphone', $count = 100){
        global $twitterConnection;
        global $regions;
        foreach ($regions as $region){
            foreach($region->circles as $circle){
                $radius = $circle->radius/1000;
                $tweets = $twitterConnection->get("search/tweets", [
                    "q" => $search, 
                    "include_entities" => true,
                    "geocode" => "{$circle->latitude},{$circle->longtitude},{$radius}km",
                    "count" => 5 
                ]);
            }
        }
    }
}