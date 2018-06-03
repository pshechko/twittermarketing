<?php

namespace TwitterModel;

class Region{
    private static $tableName = 'regions';
    public $id;
    public $label;
    public $name;
    public $circles;

    public function __construct($id, $label, $name, $circles){
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->circles = $circles;
    }

    public static function getTableName(){
        global $wpdb;
        return $wpdb->prefix.self::$tableName;
    }

    public static function getById($id){
        global $wpdb;
        $tableName = self::getTableName();
        $regionFromDB = $wpdb->get_row("SELECT * FROM {$tableName} WHERE id={$id}");
        $regionCircles = Circle::getByRegionId($id);

        return new Region(
            $regionFromDB->id,
            $regionFromDB->label,
            $regionFromDB->name,
            $regionCircles
        );
    }

    public static function getAll(){
        global $wpdb;
        $tableName = self::getTableName();
        $regionsFromDB = $wpdb->get_results("SELECT * FROM {$tableName}");
        $regions = [];
        foreach($regionsFromDB as $region){
            $regionCircles = Circle::getByRegionId($region->id);
            $regions []= new Region(
                $region->id,
                $region->label,
                $region->name,
                $regionCircles
            );
        }
        return $regions;
    }
}