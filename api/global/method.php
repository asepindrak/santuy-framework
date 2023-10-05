<?php

namespace Generate\Api;

require_once '../database/connection.php';


use Generate\Api\Connection as Con;

class GlobalApi
{
   public $con;
   public $contentType;


   public function __construct()
   {
      $this->con = new Con();
      $this->contentType = 'Content-Type: application/json';
   }


   public  function getAll($model)
   {
      $this->con->start();
      $result = $this->con->executeQuery("select * from " . $model, []);
      $this->con->commit();

      $data = [];
      while ($item = $result->fetch_assoc()) {
         array_push($data, $item);
      }
      $response = array(
         'message' => 'Get List Successfully.',
         'data' => $data
      );

      http_response_code(200);
      header($this->contentType);
      echo json_encode($response);
   }

   public function getId($model, $id)
   {
      $this->con->start();
      $result = $this->con->executeQuery("select * from " . $model . " where id = " . $id . " limit 1", []);
      $this->con->commit();

      $data = [];
      while ($item = $result->fetch_assoc()) {
         array_push($data, $item);
      }
      $response = array(
         'message' => 'Get Successfully.',
         'data' => $data
      );
      http_response_code(200);
      header($this->contentType);
      echo json_encode($response);
   }

   public function createData($model, $data)
   {
      $this->con->start();
      $result = $this->con->executeQuery("insert into " . $model . " (name, username, password) VALUES (?, ?, ?)", ["adens", "adens", md5("adens")]);
      $this->con->commit();

      $data = [];
      while ($item = $result->fetch_assoc()) {
         array_push($data, $item);
      }
      $response = array(
         'message' => 'Added Successfully.',
         'data' => $data
      );
      http_response_code(201);
      header($this->contentType);
      echo json_encode($response);
   }

   public function updateData($model, $data)
   {
      $this->con->start();
      $this->con->executeQuery("update " . $model . " (name, username, password) VALUES (?, ?, ?)", ["adens", "adens", md5("adens")]);
      $this->con->commit();

      $response = array(
         'message' => 'Updated Successfully.',
         'data' => $data
      );
      http_response_code(200);
      header($this->contentType);
      echo json_encode($response);
   }

   public function deleteData($model, $id)
   {
      $this->con->start();
      $this->con->executeQuery("delete from " . $model . " where id = " . $id, []);
      $this->con->commit();

      $response = array(
         'message' => 'Deleted Successfully.',
      );
      http_response_code(200);
      header($this->contentType);
      echo json_encode($response);
   }
}
