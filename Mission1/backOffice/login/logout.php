<?php

session_start();
session_unset();
session_destroy();

// Suprimer le cookie token
if (isset($_COOKIE['token'])) {
    setcookie('token', '', time() - 3600, '/');
}

header("Location: ../index.php");
exit();
