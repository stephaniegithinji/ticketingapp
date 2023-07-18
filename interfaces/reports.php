<?php

require_once  '../assets/php/admin/admin_functions.php';

require_once '../utils.php';

if (!isset($_SESSION['admin'])) {
    Utils::redirect_to('../index.php');
}

$admin = $_SESSION['admin'];

$reports = new Admin();

/**
 * Generate a report for a specific event based on its ID
 */
$eventId = isset($_GET['evid']) ? $_GET['evid'] : null;


$reservations_data = $reports->generateUserReservationReport($eventId);

// coalescing operator `??`
// checks if a variable exists and is not null,
// and if it doesn't, it returns a default value
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;
// `unset()` function destroys a variable. Once a variable is unset, it's no longer accessible
unset($_SESSION['success'], $_SESSION['error'], $_SESSION['contactUsMessage']);


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="x-UTF-16LE-BOM">
    <meta name="viewport" content="width=device=width, initial-scale=1.0">
    <title>Booking History</title>
    <link href="https://fonts.googleapis.com/css?family=Bad+Script|Comfortaa|Amiri|Cormorant+Garamond|Rancho|Fredericka+the+Great|Handlee|Homemade+Apple|Philosopher|Playfair+Display+SC|Reenie+Beanie|Unna|Zilla+Slab" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/modals.css">
    <link rel="stylesheet" href="../assets/css/cards.css">
    <link rel="stylesheet" href="../assets/css/tables.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>

<body>

    <div>
        <div class="navbar">
            <h1 class="logo" style="font-size: 40px;">TickeTok </h1>
            <ul style="font-size: 15px;">
                <li><a href="#">Hi, <?= $admin ?></li>
                <li><a href="events.php">View events</a></li>
                <li><a href="../assets/php/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
    <br><br>
    <div>

        <?php if (!$reservations_data) : ?>
            <p class="box1" style="margin-left: 80px;">No user Reservations report at the moment!</p>
        <?php else : ?>
            <div class="wrapper">
                <p class="box1">User Reservations Report for Event "<?= $reports->fetchEventNameById($eventId) ?>"</p>
                <table>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>User Email</th>
                            <th>Event ID</th>
                            <th>Event Name</th>
                            <th>Number of Tickets</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <?php foreach ($reservations_data as $reservation) : ?>
                        <tr>
                            <!-- < ?= ?> is a shorthand syntax for the < ? php echo ?> statement -->
                            <td><?= $reservation['reservation_id'] ?></td>
                            <td><?= $reservation['users_email'] ?></td>
                            <td><?= $reservation['events_id'] ?></td>
                            <td><?= $reservation['event_name'] ?></td>
                            <td><?= $reservation['number_of_tickets']?></td>
                            <td><?= $reservation['total_amount'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="pagination">
                    <?php if ($page > 1) : ?>
                        <a href="history.php?page=<?= $page - 1 ?>">
                            << Previous</a>
                            <?php endif; ?>
                            <?php if ($reportsCount > ($page * 5)) : ?>
                                <a href="history.php?page=<?= $page + 1 ?>">Next >></a>
                            <?php endif; ?>
                </div>

                <?= str_repeat('<br>', 4); ?>

            </div>
        <?php endif; ?>



        <!--Footer-->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col">
                        <h4>company</h4>
                        <ul>
                            <li><a href="../assets/history.php">About Us</a></li>
                            <li><a href="../assets/history.php">Our Services</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h4>get help</h4>
                        <ul>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Refunds</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Payment Options</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </footer>
</body>

</html>