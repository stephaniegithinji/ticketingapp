<?php
require_once '../../../utils.php';

if (isset($_SESSION['admin'])) {

    // Remove only the session variables specific to the current user
    unset($_SESSION['adminEmail']);
    unset($_SESSION['admin']);

    Utils::redirect_to("../../index.php");
}
