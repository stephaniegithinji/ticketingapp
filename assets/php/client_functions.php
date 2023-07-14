<?php

require_once __DIR__ .  '/../../connect/config.php';



class Client extends Db
{
	/**
	 * Check if a user with the specified email  exists in the users table
	 *
	 * @param string $email The service number of the user to check
	 * @return int The number of row(s) in the users table that match the given email
	 */
	public function user_exists($email)
	{
		$sql = "SELECT email FROM users WHERE email = :email";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	/**
	 * Registers a new user in the database.
	 * 
	 * @param string $name The name of the user
	 * @param string $email The email of the user
	 * @param string $password The password of the user
	 * @return bool Returns true on successful registration, false otherwise
	 */
	public function createUserAccount($username, $email, $contact, $password)
	{
		$sql = "INSERT INTO users(username, email, contact, password)VALUES(:uname, :email, :phone, :pass)";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['uname' => $username, 'email' => $email, 'phone' => $contact, 'pass' => $password]);
		return true;
	}

	/**
	 * @param string $email A users's email
	 * @return array
	 * @desc Returns username and password records from db based on the method parameters
	 */

	public function loginIntoAccount($email)
	{
		$sql = "SELECT username, password, is_admin FROM users WHERE email = :email";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function fetchEvents()
	{
		$sql = "SELECT * FROM events ORDER BY date DESC";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function fetchUserByEmail(string $email)
	{
		$sql = "SELECT id FROM users WHERE email = :email";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function createReservation($userId, $eventId, $no_tckts, $total)
	{
		$sql = "INSERT INTO reservations(number_of_tickets, total_amount, users_id, events_id)VALUES(:no_tckts, :t_amount, :userId, :eventId)";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['no_tckts' => $no_tckts, 't_amount' => $total, 'userId' => $userId['id'], 'eventId' => $eventId]);
		return true;
	}

}


