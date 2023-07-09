<?php

require_once 'utils.php';

// coalescing operator `??`
// checks if a variable exists and is not null,
// and if it doesn't, it returns a default value
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;

// `unset()` function destroys a variable. Once a variable is unset, it's no longer accessible
unset($_SESSION['success'], $_SESSION['error']);

?>


<!DOCTYPE Html>
<html lang="en">

<head>
    <meta charset="x-UTF-16LE-BOM">
    <meta name="viewport" content="width=device=width, initial-scale=1.0">
    <title>TickeTok</title>
    <link href="https://fonts.googleapis.com/css?family=Bad+Script|Comfortaa|Amiri|Cormorant+Garamond|Rancho|Fredericka+the+Great|Handlee|Homemade+Apple|Philosopher|Playfair+Display+SC|Reenie+Beanie|Unna|Zilla+Slab" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/alerts.css">
    <link rel="stylesheet" href="assets/css/modals.css">
    <link rel="stylesheet" href="assets/css/aboutus.css">
    <link rel="stylesheet" href="assets/css/footer.css">

</head>

<body>
    <div class="banner">
        <div class="navbar">
            <h1 class="logo"> TickeTok </h1>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#" id="signupBtn">Sign Up</a></li>
                <li><a href="#" id="signinBtn">Login</a></li>
            </ul>
        </div>
        <?= $message ?>
        <div class="content">
            <h1>Fast & Convenient Ticketing</h1>
            <p>TickeTok keeps you updated on any local events & gigs happening in the +254! Concerts from your favorite artists, Wine Tasting Events, Stand Up Comedy and so much more! <br>Book your tickets to the latest events effortlessly now by signing up with us today!</p>
            <div><br>

            </div>
        </div>
    </div>

    <!--Signup Modal-->
    <div class="modal" id="signup">
        <div class="form-box">
            <h1>Sign Up</h1>
            <form id="signupForm" method="POST" action="assets/php/action.php">
                <div class="input-group">
                    <div class="input-field">
                        <input type="text" name="username" placeholder="Username">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="email" placeholder="Email">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="contact" placeholder="Contact">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="password" placeholder="Password">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="confirm_password" placeholder="Confirm Password">
                        <div class="error"></div>
                    </div>
                </div>
                <div class="btn-field">
                    <button type="button" style="background-color: #999999; margin-top: 120px" id="signupClose">Close</button>
                    <button type="submit" style="margin-top: 120px" name="signup-btn">Signup &rarr;</button>
                </div>
            </form>
            <br>
        </div>
    </div>
    <!--Signin Modal-->
    <div class="modal" id="signin">
        <div class="form-box">
            <h1>Login</h1>
            <form id="signinForm" method="POST" action="signin.php">
                <div class="input-group">
                    <div class="input-field">
                        <input type="text" id="signinemail" name="signinemail" placeholder="Email">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" id="signinpassword" name="signinpassword" placeholder="Password">
                        <div class="error"></div>
                    </div>
                    <a href="#" style="font-size:13px; text-align: left; float:left;">Forgotten Password?</a>
                </div>
                <div class="btn-field">
                    <button type="button" style="background-color: #999999" id="signinClose">Close</button>
                    <button type="submit" name="login-btn">Login &rarr;</button>
                </div>
            </form>
        </div>
    </div>


    <!--About Us Section-->
    <div id="about">
        <div class="about-heading">
            <h1>About TickeTok</h1>
        </div>
        <section class="about-us">
            <img src="assets/images/p3.jpg">
            <div class="inner-section">
                <h2>We Are An E-ticketing Company</h2>
                <p>
                    As one of the top event ticket vendors in Kenya, we provide a platform where our target audience can buy tickets to various events in the most seamless way.
                    <br><br>Once a ticket to a particular event is purchased, a <strong>QR CODE</strong> can be generated and presented as the electronic ticket at the venue for scanning. <strong>The ticket is only valid for a ONE TIME use.</strong>
                </p>
                <br>
                <h3>
                    For inquiries, contact us via email below at inquiries.ticketok@gmail.com today .
                </h3>
                <br>
                <button type="button" id="contactUsBtn" class="contact-us">Contact Us </button>
                <!--  `<?php ?>` tags is used to output the value of given php variable -->
                <?= $message ?>
            </div>
        </section>
    </div>
    <br><br><br>

    <!--Contact Us Modal-->
    <div class="modal" id="contactus">
        <div class="form-box">
            <h1>Any Inquiries</h1>
            <form id="contactForm" method="post" action="assets/php/action.php">
                <div class="input-group">
                    <div class="input-field">
                        <input type="text" name="email_from" placeholder="Your email">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <input type="text" name="subject" placeholder="Subject:">
                        <div class="error"></div>
                    </div>
                    <div class="input-field">
                        <textarea type="text" name="message" placeholder="Message" style="height: 150px; width:1000px; background-color:#eaeaea;"></textarea>
                        <div class="error"></div>
                    </div>
                </div>
                <div class="btn-field">
                    <button type="button" style="background-color: #999999; margin-top:90px;" id="contactClose">Close</button>
                    <button type="submit" name="contact-btn" style="background-color: green; margin-top: 90px;">Send &rarr;</button>
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
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#about">Our Services</a></li>
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

    <script src="assets/js/modals.js"></script>
    <script src="assets/js/signup.js"></script>
    <script src="assets/js/signin.js"></script>
    <script src="assets/js/contact.js"></script>

</body>

</html>