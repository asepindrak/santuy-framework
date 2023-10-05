<?php

namespace Generate\Api;

require_once '../database/connection.php';


use Generate\Api\Connection as Con;

class Migration
{
   public $con;
   public $contentType;


   public function __construct()
   {
      $this->con = new Con();
      $this->contentType = 'Content-Type: application/json';
   }


   public  function createTable($model)
   {
      $modelName = $model->name;
      $query = "CREATE TABLE IF NOT EXISTS " . $modelName . " ( ";

      foreach ($model->columns as $field) {
         $query .= $field->name . " " . $field->dataType . ", ";
      }

      $query .= "trash INT DEFAULT 0, ";
      $query .= "createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, ";
      $query .= "createdBy TIMESTAMP DEFAULT NULL";
      $query .= " )  ENGINE=INNODB;";

      $this->con->migrate($query);
   }


   public  function truncateTable($model)
   {
      $modelName = $model->name;
      //check if table exist
      $checkTable = $this->con->migrate("SELECT COUNT(TABLE_NAME) as count FROM information_schema.TABLES  WHERE TABLE_SCHEMA LIKE '" . $this->con->db . "' AND TABLE_TYPE LIKE 'BASE TABLE' AND TABLE_NAME = '" . $modelName . "';");

      $data = [];
      while ($item = $checkTable->fetch_assoc()) {
         array_push($data, $item);
      }
      if (count($data) > 0) {
         $countTable = $data[0]["count"];
         if ($countTable > 0) {
            //check if column exist
            foreach ($model->columns as $field) {
               $checkColumn = $this->con->migrate("SHOW COLUMNS FROM " . $modelName . " LIKE '" . $field->name . "';");
               $dataColumn = [];
               while ($itemColumn = $checkColumn->fetch_assoc()) {
                  array_push($dataColumn, $itemColumn);
               }
               if (count($dataColumn) == 0) {
                  $dataType = $field->dataType;

                  $this->con->migrate("ALTER TABLE " . $modelName . " ADD COLUMN " . $field->name . " " . $dataType . ";");
               }
            }
         }
      }
   }
}
