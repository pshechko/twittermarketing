<?php

namespace TwitterModel;

class Region{
    private static $tableName = 'regions';
    public $id;
    public $label;
    public $name;
    public $circles;
    public $tweetNum;
    public $percentage;

    public function __construct($id, $label, $name, $percentage = 0){
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->percentage = $percentage;

        $regionCircles =Circle::getByRegionId($id);
        $this->circles = $regionCircles;

        $this->tweetNum = 0;
        foreach($regionCircles as $circle){
            $this->tweetNum += count($circle->tweets);
        }



       
    }

    public static function getTableName(){
        global $wpdb;
        return $wpdb->prefix.self::$tableName;
    }

    public static function getById($id){
        global $wpdb;
        $tableName = self::getTableName();
        $regionFromDB = $wpdb->get_row("SELECT * FROM {$tableName} WHERE id={$id}");
        //$regionCircles = Circle::getByRegionId($id);

        return new Region(
            $regionFromDB->id,
            $regionFromDB->label,
            $regionFromDB->name
        );
    }

    public static function getAll(){
        global $wpdb;
        $tableName = self::getTableName();
        $regionsFromDB = $wpdb->get_results("SELECT * FROM {$tableName}");
        $regions = [];

        foreach($regionsFromDB as $region){
            $regions []= new Region(
                $region->id,
                $region->label,
                $region->name
            );
        }

        $maxTweetCount = 0;
        foreach ($regions as $region)
            if($region->tweetNum > $maxTweetCount)
                $maxTweetCount = $region->tweetNum;
        
        if($maxTweetCount === 0)
            return $regions;

        foreach ($regions as $region)
            $region->percentage = round($region->tweetNum * 100 / $maxTweetCount);

        return $regions;
    }
}