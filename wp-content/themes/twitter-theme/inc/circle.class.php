<?php

namespace TwitterModel;

class Circle{
    private static $tableName = 'circles';
    public $id;
    public $region_id;
    public $latitude;
    public $longtitude;
    public $radius;

    public function __construct($id, $region_id, $latitude, $longtitude, $radius){
        $this->id = $id;
        $this->region_id = $region_id;
        $this->latitude = $latitude;
        $this->longtitude = $longtitude;
        $this->radius = $radius;
    }

    public static function getTableName(){
        global $wpdb;
        return $wpdb->prefix.self::$tableName;
    }

    public static function getById($id){
        global $wpdb;
        $tableName = self::getTableName();
        $circleFromDB = $wpdb->get_row("SELECT * FROM {$tableName} WHERE id={$id}");

        return new Circle(
            $circleFromDB->id,
            $circleFromDB->region_id,
            $circleFromDB->latitude,
            $circleFromDB->longtitude,
            $circleFromDB->radius
        );
    }

    public static function getByRegionId($regionId){
        global $wpdb;
        $tableName = self::getTableName();
        $circlesFromDB = $wpdb->get_results("SELECT * FROM {$tableName} WHERE region_id={$regionId}");
        $circles = [];
        foreach($circlesFromDB as $circle)
            $circles []= new Circle(
                $circle->id,
                $circle->region_id,
                $circle->latitude,
                $circle->longtitude,
                $circle->radius
            );
        return $circles;
    }

    public static function getAll(){
        global $wpdb;
        $tableName = self::getTableName();
        $circlesFromDB = $wpdb->get_results("SELECT * FROM {$tableName}");
        $circles = [];
        foreach($circlesFromDB as $circle)
            $circles []= new Circle(
                $circle->id,
                $circle->region_id,
                $circle->latitude,
                $circle->longtitude,
                $circle->radius
            );
        return $circles;
    }

}