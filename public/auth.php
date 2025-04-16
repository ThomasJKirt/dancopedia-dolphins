<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto logout after 30 mins
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 1800) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

// Check login
function requireLogin() {
    if (!isset($_SESSION['email'])) {
        header("Location: login.php?auth_required=1");
        exit();
    }
}

// Check admin
function requireAdmin() {
    requireLogin();
    if ($_SESSION['user_type'] !== 'admin') {
        header("Location: userhome.php?unauthorized=1");
        exit();
    }
}

// Check user
function requireUser() {
    requireLogin();
    if ($_SESSION['user_type'] !== 'user') {
        header("Location: adminhome.php?unauthorized=1");
        exit();
    }
}
?>
