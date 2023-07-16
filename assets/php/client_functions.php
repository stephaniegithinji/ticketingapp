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

	public function fetchEventDateById(string $id)
	{
		$sql = "SELECT date FROM events WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			$eventDate = $result['date'];
			$currentDate = date('Y-m-d'); // Get the current date

			$result['hasPassed'] = $eventDate < $currentDate;
		}

		return $result;
	}




	public function createReservation($userId, $eventId, $no_tckts, $total)
	{
		$sql = "INSERT INTO reservations(number_of_tickets, total_amount, users_id, events_id)VALUES(:no_tckts, :t_amount, :userId, :eventId)";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['no_tckts' => $no_tckts, 't_amount' => $total, 'userId' => $userId['id'], 'eventId' => $eventId]);
		return true;
	}

	/**
	 * @param string $tablename
	 * @return array
	 * @desc Returns count of Rows based on the method parameters
	 */
	public function totalCount($tableName)
	{
		$sql = "SELECT COUNT(*) FROM $tableName";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$count = $stmt->fetchColumn();
		return $count;
	}

	/**
	 * Loads data from the "reservations" table.
	 *
	 * @return array An array containing the retrieved data.
	 */
	public function reservationsPerPage($current_page)
	{
		$offset = ($current_page - 1) * 5;

		$sql = "SELECT * FROM reservations ORDER BY created_at LIMIT :offset, :records_per_page";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindValue(':records_per_page', 5, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}


	public function getEventNameFromId($id)
	{
		$sql = "SELECT event_name FROM events WHERE id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result['event_name'];
	}
}
