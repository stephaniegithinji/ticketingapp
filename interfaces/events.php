<?php

require_once '../utils.php';

require_once  '../assets/php/client_functions.php';

if (!isset($_SESSION['client'])) {
    Utils::redirect_to('../index.php');
}

$currentlyLoggedInUser = $_SESSION['client'];

$interfaces = new Client();

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
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/modals.css">
    <link rel="stylesheet" href="../assets/css/alerts.css">
    <link rel="stylesheet" href="../assets/css/cards.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>

<body>

    <div class="navbar">
        <h1 class="logo" style="font-size: 40px;">TickeTok </h1>
        <ul style="font-size: 15px;">
            <li><a href="#">Hi, <?= $currentlyLoggedInUser ?></li>
            <li><a href="../assets/interfaces/history.php">My History</a></li>
            <li><a href="../assets/php/logout.php">Logout</a></li>
            <li><input type="text" id="search" name="search" placeholder="SEARCH"></li>
        </ul>
    </div>


    <br><br>
    <?= $message ?>

    <?php if (!$cards_data) : ?>
        <p class="box">Upcoming Events</p>
    <?php endif; ?>
    <div>


        <!-- this row is the container with the card-->

        <div class="card-container" style="margin-left: 50px; margin-bottom: 70px;">
            <?php $rowCount = 0; ?>
            <table class="cards-table">
                <?php foreach ($cards_data as $card) : ?>
                    <?php if ($rowCount % 3 === 0) : ?>
                        <tr>
                        <?php endif; ?>
                        <td data-id="<?= $card['id'] ?>">
                            <div class="column-item">
                                <div class="card" style="width: 30rem">
                                    <img class="card-img-top" src="<?= $card['banner'] ?>" alt="Event Image">
                                    <div class="card-body">
                                        <h3 class="card-title">Event Name: <?= $card['event_name'] ?></h3>
                                        <ul class="card-text">
                                            <li>Date: <?= date('M d, Y', strtotime($card['date'])) ?></li>
                                            <li>Venue: <?= $card['venue'] ?></li>
                                            <li>Time: <?= $card['time'] ?></li>
                                            <li>Ticket Price: Ksh <?= number_format($card['ticket_price']) ?> each</li>
                                        </ul>
                                        <br><br>
                                        <button type="button" id="buyingBtn" class="btn btn-primary">Buy Ticket &rarr;</button>
                                        <br><br>
                                        <button type="submit" id="qrCodeBtn" class="btn btn-primary">Generate QR Code</button>
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

        <!--Modal for Buying Tickets-->
        <div class="modal" id="buying">
            <div class="form-box">
                <h2 id="event-name-buy"></h2>
                <form id="purchaseTicketForm" method="POST" action="../assets/php/action.php">
                    <input type="hidden" name="eventId" value="<?= $card['id'] ?>">
                    <div class="form-field">
                        <select class="input-field" style="width:100%; height:40px" name="number_of_tickets">
                            <option disabled>Number Of Tickets (Maximum - 5)</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <input type="hidden" name="ticket_price" value="<?= $card['ticket_price'] ?>">
                    <div class="form-field">
                        <select class="input-field" style="width:100%; height:40px;" name="mpesa">
                            <option disabled selected>Payment Method</option>
                            <option value="MPESA">M-PESA</option>
                        </select>
                    </div>
                    <div class="btn-field">
                        <button type="button" class="close" id="buyingClose">Close</button>
                        <button type="submit" class="btn" name="purchase-ticket-btn">Pay &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

        <!--Modal for QR Code-->
        <div class="modal" id="qr">
            <div class="form-box">
                <h1>Your e-Ticket</h1>
                <h3 style="margin-top: 15px; color:red; margin-left: 10px; font-size:13px; text-align: center;">Do not close before scanning! Ticket will not be regenerated</h3>
                <!--Should be displayed here-->
                <div class="error"></div>
                <div class="btn-field">
                    <button type="button" class="btn btn-primary" style="margin-top: 260px; margin-left: 100px;" id="qrClose"> Ok &rarr;</button>
                </div>
                </form>
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
        <script src='../assets/js/clientmodals.js'></script>
        <script>
            const form = document.getElementById("purchaseTicketForm");

            const selectField = document.querySelector('[name="number_of_tickets"]');
            form.addEventListener("submit", function(event) {
                if (selectField.value === null || selectField.value === "") {
                    event.preventDefault(); // Prevent form submission
                    alert("Please select an option for number of tickets.");
                }
            });
        </script>
</body>

</html>