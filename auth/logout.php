<?php
require_once __DIR__ . '/../includes/auth.php';

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

setFlash('info', 'You have been logged out.');
header('Location: ' . baseUrl('index.php'));
exit;
