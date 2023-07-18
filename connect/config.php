<?php

/**
 * Class Db
 *
 * This class provides a simple way to connect
 * to a MySQL db thatperform common database queries.
 * It includes constants for the db username, and password.
 *
 *
 *
 * The class has three private properties: $dsn, $dbuser, and $dbpass. These properties store the database connection details.
 * There is a public property $conn, which represents the PDO connection object.
 * The constructor __construct() is executed automatically when an object of the Db class is created.
 * Inside the constructor, the $dsn, $dbuser, and $dbpass properties are assigned their respective values.
 * The try-catch block is used to handle any exceptions that might occur during the database connection.
 * Within the try block, a new PDO instance is created by passing the $dsn, $dbuser, and $dbpass values to the PDO constructor. This establishes the database connection.
 * If an exception occurs, such as a connection error, the PDOException object catches the exception, and an error message is displayed using the getMessage() method.
 *
 * @author Stephozz
 *
 */

class Db
{
    private $dns;
    private $dbuser;
    private $dbpass;

    public $conn;

    public function __construct()
    {
 
        // https://www.phptutorial.net/php-pdo
        $this->dns = "mysql:host=localhost;dbname=ticketingapp";
        $this->dbuser = "root";
        $this->dbpass = 'tiger';
        try {
            $this->conn = new PDO($this->dns, $this->dbuser, $this->dbpass);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
