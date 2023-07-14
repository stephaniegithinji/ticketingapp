<?php

require_once  '../assets/php/admin/admin_functions.php';

require_once '../utils.php';

if (!isset($_SESSION['admin'])) {
    Utils::redirect_to('../index.php');
}

$admin = $_SESSION['admin'];

$interfaces = new Admin();

$cards_data = $interfaces->fetchEvents();

// coalescing operator `??`
// checks if a variable exists and is not null,
// and if it doesn't, it returns a default value
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;
// `unset()` function destroys a variable. Once a variable is unset, it's no longer accessible
unset($_SESSION['success'], $_SESSION['error'], $_SESSION['contactUsMessage']);


?>

<!DOCTYPE Html>
<html lang="en">

<head>
    <meta charset="x-UTF-16LE-BOM">
    <meta name="viewport" content="width=device=width, initial-scale=1.0">
    <title>TickeTok </title>
    <link href="https://fonts.googleapis.com/css?family=Bad+Script|Comfortaa|Amiri|Cormorant+Garamond|Rancho|Fredericka+the+Great|Handlee|Homemade+Apple|Philosopher|Playfair+Display+SC|Reenie+Beanie|Unna|Zilla+Slab" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0d8179c479.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/modals.css">
    <link rel="stylesheet" href="../assets/css/alerts.css">
    <link rel="stylesheet" href="../assets/css/cards.css">
    <link rel="stylesheet" href="../assets/css/admincards.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>

<body>

    <div>
        <div class="navbar">
            <h1 class="logo" style="font-size: 40px;">TickeTok </h1>
            <ul style="font-size: 15px;">
                <li><a href="#">Hi, <?= $admin ?></li>

                <li><a href="#" id="addBtn">Create Event</a></li>
                <li><a href="../assets/php/admin/logout.php">Logout</a></li>
                <li><input type="text" id="search" name="search" placeholder="SEARCH"></li>
            </ul>
        </div>
    </div>
    <br><br>
    <?= $message ?>

    <?php if (!$cards_data) : ?>
        <div>
            <p class="box">No Events at the moments!</p>
        </div>
    <?php else : ?>
        <?= str_repeat('<br>', 4); ?>
        <p class="box">Events Line up</p>
        <!-- this row is the container with the card-->
        <div class="card-container" style="margin-left: 130px; margin-bottom: 100px">
            <?php $rowCount = 0; ?>
            <table class="cards-table">
                <?php foreach ($cards_data as $card) : ?>
                    <?php if ($rowCount % 3 === 0) : ?>
                        <tr>
                        <?php endif; ?>
                        <td data-id="<?= $card['id'] ?>">
                            <div class="column-item">
                                <div class="card" style="width: 30rem;">
                                    <input type="hidden" name="eventId">
                                    <img class="card-img-top" src="<?= $card['banner'] ?>" alt="Event Image">
                                    <div class="card-body">
                                        <h2 class="card-title">Event Name: <?= $card['event_name'] ?></h2>
                                        <ul class="card-text">
                                            <li>Date: <?= date('M d, Y', strtotime($card['date'])) ?></li>
                                            <li>Venue: <?= $card['venue'] ?></li>
                                            <li>Time: <?= $card['time'] ?></li>
                                            <li>Ticket Price: Ksh <?= number_format($card['ticket_price']) ?> each</li>
                                            <li>Tickets Capacity: <?= $card['tickets_capacity'] ?> tickets</li>
                                            <!-- <li>Tickets Available: <?= $card['tickets_capacity'] ?> tickets</li> -->
                                        </ul>
                                        <br><br>
                                        <button id="editBtn" class="btn btn-primary">Edit </button>
                                        <br><br>
                                        <button id="deleteBtn" class="btn btn-danger">Delete</button>
                                        <br><br>
                                        <button class="btn btn-success">View Report</button>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <?php $rowCount++; ?>

                        <?php if ($rowCount % 3 === 0 || $rowCount === count($cards_data)) : ?>
                        </tr>
                    <?php endif; ?>

                <?php endforeach; ?>
            </table>

        </div>
    <?php endif; ?>

    <!--Modal for Adding Events-->
    <div class="modal" id="addevent">
        <div class="form-box" style="height: 820px;">
            <h1>Add Event</h1>
            <form method="POST" action="../assets/php/admin-action.php" enctype="multipart/form-data">
                <div class="form-field">
                    <label>Event Name:</label>
                    <input type="text" name="event_name" placeholder="Event Name">
                </div>
                <div class="form-field">
                    <label>Event Date:</label>
                    <input type="date" name="date" placeholder="Event Date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                </div>
                <div class="form-field">
                    <label>Event venue:</label>
                    <input type="text" name="venue" placeholder="Venue">
                </div>
                <div class="form-field">
                    <label>Event time:</label>
                    <input type="time" name="time" placeholder="Time">
                </div>
                <div class="form-field">
                    <label>Ticket price:</label>
                    <input type="number" name="ticket_price" placeholder="Ticket Price">
                </div>
                <div class="form-field">
                    <label>Event Name:</label>
                    <input type="number" name="tickets_available" placeholder="Tickets Available">
                </div>
                <div class="form-field">
                    <input type="file" name="banner" accept="image/*">
                </div>
                <div class="btn-field">
                    <button type="button" class="close" id="addEventClose">Close</button>
                    <button type="submit" class="btn" name="addEventBtn">Add event &rarr;</button>
                </div>
            </form>
            <br>
        </div>
    </div>



    <div class="modal" id="editEvent">
        <div class="form-box" style="height: 770px">
            <h2 id="event-name-edt"></h2>
            <form method="POST" action="../assets/php/admin-action.php">
                <input type="hidden" name="eventId" value="<?= $card['id'] ?>">
                <div class="form-field">
                    <label>Event Name:</label>
                    <input type="text" name="event_name" placeholder="Event Name" value="<?= $card['event_name'] ?>">
                </div>
                <!-- <div class="form-field">
                    <label>Event Date:</label>
                    <input type="date" name="date"  value="<?= $card['date'] ?>">
                </div> -->
                <div class="form-field">
                    <label>Event Venue:</label>
                    <input type="text" name="venue" placeholder="Venue" value="<?= $card['venue'] ?>">
                </div>
                <div class="form-field">
                    <label>Event Time:</label>
                    <input type="time" name="time" placeholder="Time" value="<?= $card['time'] ?>">
                </div>
                <div class="form-field">
                    <label>Tickets price:</label>
                    <input type="number" name="ticket_price" placeholder="Ticket Price" value="<?= $card['ticket_price'] ?>">
                </div>
                <div class="form-field">
                    <label>Tickets capacity:</label>
                    <input type="number" name="ticket_capacity" placeholder="Tickets Available" value="<?= $card['tickets_capacity'] ?>">
                </div>
                <div class="btn-field">
                    <button type="button" class="close" id="editClose">Close</button>
                    <button type="submit" class="btn editEvent-btn" name="editEventBtn">Update &rarr;</button>
                </div>
            </form>
            <br>
        </div>
    </div>

    <div class="modal" id="deleteEvent">
        <div class="form-box" style="height: 350px;">
            <h1 id="event-name-edt-del"></h1>
            <form method="POST" action="../assets/php/admin-action.php">
                <input type="hidden" name="eventId" value="<?= $card['id'] ?>">
                <h2>Are you sure you want to delete this Event?</h2>
                <br>
                <div class="btn-field">
                    <button type="button" class="close" id="deleteClose">Close</button>
                    <button type="submit" class="btn" name="deleteEventBtn">Delete &rarr;</button>
                </div>
            </form>
        </div>
    </div>

    <!--Footer-->
    <?= str_repeat('<br>', 4); ?>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>company</h4>
                    <ul>
                        <li><a href="../assets/index.php">About Us</a></li>
                        <li><a href="../assets/index.php">Our Services</a></li>
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
    <script src="../assets/js/adminmodals.js"></script>
</body>

</html>