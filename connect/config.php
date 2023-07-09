<?php

/**
 * Class Db
 *
 * This class provides a simple way to connect
 * to a MySQL db thatperform common database queries.
 * It includes constants for the db username, and password.
 *
 * @author Stephozz
 *
 */

class Db
{
    private $dsn;
    private $dbuser;
    private $dbpass;

    public $conn;

    public function __construct()
    {
 
        // https://www.phptutorial.net/php-pdo
        $this->dsn = "mysql:host=localhost;dbname=ticketingapp";
        $this->dbuser = "root";
        $this->dbpass = 'tiger';
        try {
            $this->conn = new PDO($this->dsn, $this->dbuser, $this->dbpass);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

?>
