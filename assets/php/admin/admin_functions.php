<?php

require_once __DIR__ .  '/../../../connect/config.php';


class Admin extends Db
{

    public function addEvent($event_name, $date, $venue, $time, $ticket_price,  $tickets_capacity, $thumbnail)
	{
		$sql = "INSERT INTO events(id, event_name, date, venue, time, ticket_price, tickets_available, banner, created_at, updated_at) VALUES(:id, :event_name, :date, :venue, :time, :ticket_price, :tickets_available, :banner, NOW(), '0000-00-00 00:00:00')";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['event_name' => $event_name, 'date' => $date, 'venue' => $venue, 'time' => $time, 'ticket_price' => $ticket_price, 'tickets_available' => $tickets_capacity, 'banner' => $thumbnail]);
		return true;
	}

	/* Ask Baha what's going on here */
    public function fetchEvents()
	{
		$sql = "SELECT * FROM events";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

    public function editEvent($id, $date, $venue, $time, $ticket_price)
	{
		
		return true;
	}


	public function deleteEvent($id)
	{
    	$sql = "DELETE FROM events WHERE id = :id";
    	$stmt = $this->conn->prepare($sql);
    	$stmt->execute(['id' => $id]);
     	return true;
	}


	/* Ask Baha what's going on here */
    public function getIdSOfEvents()
	{
		$sql = "SELECT * FROM events";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		return $stmt->rowCount(); //37
	}
}