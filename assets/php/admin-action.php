<?php

require_once '../../utils.php';
require_once 'admin/admin_functions.php';


// Create an instance of admin
$action = new Admin();


if (isset($_POST["addEventBtn"])) {
    try {

        $imageDir = '../images/banners/';
        $imageName = $_FILES['banner']['name'];

        // Generate a unique file name
        $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '.' . $fileExtension;

        $imagePath = $imageDir . $uniqueName;
        $thumbnailPath = '../assets/images/banners/' . $uniqueName;


        // Define the allowed banner extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        // Check if the banner extension is valid
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            Utils::redirect_with_message('../../interfaces/admin.php', 'error', "Invalid banner file extension. Only JPG, JPEG and PNG files are allowed.");
        }

        // Move the uploaded image to the defined dir
        if (move_uploaded_file($_FILES['banner']['tmp_name'], $imagePath)) {
            $event_name = isset($_POST["event_name"]) && !empty($_POST["event_name"]) ? Utils::sanitizeInput(ucwords($_POST["event_name"])) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Event Name cannot be blank!');
            $date = isset($_POST["date"]) && !empty($_POST["date"]) ? Utils::sanitizeInput($_POST["date"]) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Date cannot be blank!');
            $venue = isset($_POST["venue"]) && !empty($_POST["venue"]) ? Utils::sanitizeInput(ucwords($_POST["venue"])) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Venue cannot be blank!');
            $time = isset($_POST["time"]) && !empty($_POST["time"]) ? date('H:i:s', strtotime(Utils::sanitizeInput($_POST["time"]))) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Time cannot be blank!');
            $ticket_price = isset($_POST["ticket_price"]) && !empty($_POST["ticket_price"]) ? Utils::sanitizeInput($_POST["ticket_price"]) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Ticket Price cannot be blank!');
            $tickets_available = isset($_POST["tickets_available"]) && !empty($_POST["tickets_available"]) ? Utils::sanitizeInput($_POST["tickets_available"]) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Tickets Available cannot be blank!');
            if ($action->addEvent($event_name, $date, $venue, $time, $ticket_price, $tickets_available, $thumbnailPath)) {
                Utils::redirect_with_message('../../interfaces/admin.php', 'success', 'New event added successfully');
            } else {
                // Failed to insert into the database
                Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Failed to add new event.');
            }
        } else {
            // Failed to move the uploaded image
            Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Failed to upload the image.');
        }
    } catch (Exception $e) {
        // Handle exceptions by returning error to the user
        Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Opps...Some error occurred: ' . $e->getMessage());

        return;
    }
}

if (isset($_POST["editEventBtn"])) {

    try {
        $eventId = isset($_POST["eventId"]) && !empty($_POST["eventId"]) ? Utils::sanitizeInput($_POST["eventId"]) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Event Id cannot be blank!');
        $eventName = isset($_POST["event_name"]) && !empty($_POST["event_name"]) ? Utils::sanitizeInput($_POST["event_name"]) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Event name cannot be blank!');
        // $date = isset($_POST["date"]) && !empty($_POST["date"]) ? Utils::sanitizeInput(ucwords($_POST["date"])) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Date cannot be blank!');
        $venue = isset($_POST["venue"]) && !empty($_POST["venue"]) ? Utils::sanitizeInput(ucwords($_POST["venue"])) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Venue cannot be blank!');
        $time = isset($_POST["time"]) && !empty($_POST["time"]) ? date('H:i:s', strtotime(Utils::sanitizeInput($_POST["time"]))) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Time cannot be blank!');
        $ticket_price = isset($_POST["ticket_price"]) && !empty($_POST["ticket_price"]) ? Utils::sanitizeInput(ucwords($_POST["ticket_price"])) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Ticket price cannot be blank!');
        $ticket_capacity = isset($_POST["ticket_capacity"]) && !empty($_POST["ticket_capacity"]) ? Utils::sanitizeInput(ucwords($_POST["ticket_capacity"])) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Ticket capacity cannot be blank!');

        if ($action->editEvent($eventId, $eventName, $venue, $time, $ticket_price, $ticket_capacity)) {
            Utils::redirect_with_message('../../interfaces/admin.php', 'success', 'Event data updated successfully');
        } else {
            // Failed to move the uploaded image
            Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Failed to edit event data.');
        }
    } catch (Exception $e) {
        // Handle exceptions by returning error to the user
        Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Opps...Some error occurred: ' . $e->getMessage());

        return;
    }
}



if (isset($_POST["deleteEventBtn"])) {
    try {
        $eventId = isset($_POST["eventId"]) && !empty($_POST["eventId"]) ? Utils::sanitizeInput($_POST["eventId"]) : Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Event Id cannot be blank!');
        // Perform the event deletion here
        $result = $action->deleteEvent($eventId);
        if ($result) {
            Utils::redirect_with_message('../../interfaces/admin.php', 'success', 'Event Deleted!');
        } else {
            echo 'Failed to delete the event.';
        }
    } catch (Exception $e) {
        // Handle exceptions by returning error to the user
        Utils::redirect_with_message('../../interfaces/admin.php', 'error', 'Opps...Some error occurred: ' . $e->getMessage());
        return;
    }
}
