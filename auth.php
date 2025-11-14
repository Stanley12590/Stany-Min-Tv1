<?php
include 'config.php';

// Hardcoded admin credentials
$admin_username = "Stanytz076";
$admin_password = "Stanytz076#";

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function login($username, $password) {
    global $admin_username, $admin_password;
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Check if user is trying to logout
if (isset($_GET['logout'])) {
    logout();
}

// Redirect to login if not authenticated (except login page)
$current_page = basename($_SERVER['PHP_SELF']);
if (!isLoggedIn() && $current_page !== 'login.php') {
    header('Location: login.php');
    exit;
}
?>
