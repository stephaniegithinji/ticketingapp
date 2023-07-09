<?php

require_once  '../../connect/config.php';


class Client extends Db
{
    public function user_exists($email){
        $sql = "SELECT email FROM users WHERE email = :email";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
    }
}