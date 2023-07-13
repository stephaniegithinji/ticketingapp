<?php

require_once  '../assets/php/admin/admin_functions.php';

require_once '../utils.php';

if (!isset($_SESSION['admin'])) {
    Utils::redirect_to('../index.php');
}

$admin = $_SESSION['admin'];

// $interfaces = new Admin();

// $cards = $interfaces->fetchEvents();

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
    <link rel="stylesheet" href="../assets/css/cards.css">
    <link rel="stylesheet" href="../assets/css/admincards.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>

<body>

    <div>
        <div class="navbar">
            <h1 class="logo" style="font-size: 40px;">TickeTok </h1>
            <ul style="font-size: 15px;">
                <li><a href="#">Hi <?= $admin ?></li>

                <li><a href="#" id="addBtn">Create Event</a></li>
                <li><a href="../assets/php/admin/logout.php">Logout</a></li>
                <li><input type="text" id="search" name="search" placeholder="SEARCH"></li>
            </ul>
        </div>
    </div>
    <br><br>
    <div>
        <p style="color: #fcc201; font-family: 'Fredericka the Great', cursive; font-size: 40px; margin-left: 130px">Upcoming Events</p>
    </div>
    <br><br>


    <!-- this row is the container with the card-->
    <!-- <div class="card-container" style="margin-left: 130px; margin-bottom: 100px;">
        <div class="column-item">
            <div class="card" style="width: 33rem;">
                <img class="card-img-top" src="../assets/images/p1.jpg" alt="Event Image">
                <div class="card-body">
                    <h3 class="card-title">Event Name</h3>
                    <ul class="card-text">
                        <li>Date:</li>
                        <li>Venue:</li>
                        <li>Time:</li>
                        <li>Ticket Price:</li>
                        <li>Tickets Available:</li>
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
    </div> -->



    <!--Modal for Adding Events-->
    <div class="modal" id="addevent">
        <div class="form-box" style="height: 800px;">
            <h1>Add Event</h1>
            <form method="POST" action="../assets/php/admin-action.php" enctype="multipart/form-data">
                <div class="form-field">
                    <input type="text" name="event_name" placeholder="Event Name">
                </div>
                <div class="form-field">
                    <input type="date" name="date" placeholder="Event Date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                </div>
                <div class="form-field">
                    <input type="text" name="venue" placeholder="Venue">
                </div>
                <div class="form-field">
                    <input type="time" name="time" placeholder="Time">
                </div>
                <div class="form-field">
                    <input type="number" name="ticket_price" placeholder="Ticket Price">
                </div>
                <div class="form-field">
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



    <!--Footer-->
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