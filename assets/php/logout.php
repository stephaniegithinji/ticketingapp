<?php
require_once '../../../utils.php';

if (isset($_SESSION['clientEmail'])) {

    // Remove only the session variables specific to the current user
    unset($_SESSION['clientEmail']);
    unset($_SESSION['client']);

    Utils::redirect_to("../../index.php");
}
