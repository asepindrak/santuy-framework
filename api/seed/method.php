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


   public  function seed($model, $seed)
   {
      $modelName = $model->name;
      foreach ($seed as $item) {


         $index = 0;


         $queryStr = "INSERT INTO " . $modelName . " ( ";

         foreach ($item as $key => $value) {
            if ($index > 0 && $index < count((array)$item)) {
               $queryStr .= ", ";
            }
            $queryStr .= $key . " ";
            $index++;
         }

         $queryStr .= " ) ";
         $queryStr .= " VALUES ( ";
         $index = 0;
         foreach ($item as $key => $value) {

            if ($index > 0 && $index < count((array)$item)) {
               $queryStr .= ", ";
            }
            $queryStr .= "?";

            $index++;
         }


         $queryStr .= " ) ";


         $queryArr = [];
         foreach ($item as $key => $value) {
            if ($key == "password") {
               array_push($queryArr, md5($value));
            } else {
               array_push($queryArr, $value);
            }
         }

         $this->con->executeQuery($queryStr, $queryArr);
      }
   }
}