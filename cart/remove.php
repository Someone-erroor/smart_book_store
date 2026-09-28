<?php
require_once __DIR__ . '/../includes/auth.php';

$bookId = $_GET['id'] ?? '';

if (!empty($bookId) && isset($_SESSION['cart'][$bookId])) {
    $title = $_SESSION['cart'][$bookId]['title'] ?? 'Item';
    unset($_SESSION['cart'][$bookId]);
    setFlash('info', 'Removed "' . htmlspecialchars($title) . '" from your cart.');
}

header('Location: ' . baseUrl('cart/cart.php'));
exit;
