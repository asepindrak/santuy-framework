<?php

namespace Generate\Api;

use Dotenv;
use mysqli;

class Connection
{
    public $mysqli;
    public $https;
    public $db;
    public function __construct()
    {
        require_once '../vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable("../");
        $dotenv->load();
        $host = $_ENV['HOST'];
        $db = $_ENV['DATABASE'];
        $user = $_ENV['USERNAME'];
        $pass = $_ENV['PASSWORD'];
        $this->https = $_ENV['HTTPS'];
        $this->db = $db;
        $this->mysqli = new mysqli($host, $user, $pass, $db);
        if ($this->mysqli->connect_errno) {
            exit();
        }
    }

    public function executeQuery($query, $params)
    {
        try {
            $statement = $this->mysqli->prepare($query);
            if (!$statement) {
                $this->rollback();
            }


            if (!$statement->execute($params)) {
                $this->rollback();
            }
            $result = $statement->get_result();
            return $result;
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }

    public function migrate($query)
    {
        try {
            $result = $this->mysqli->query($query);

            return $result;
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }

    public function start()
    {
        try {
            $this->mysqli->query("START TRANSACTION");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function commit()
    {
        try {
            $this->mysqli->query("COMMIT");
            $this->mysqli->close();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
    }

    public function rollback()
    {
        try {
            $this->mysqli->query("ROLLBACK");
            $this->mysqli->close();
            http_response_code(400);

            header("HTTP/1.0 400 Bad Request");
            return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}