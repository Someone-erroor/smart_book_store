<?php
require_once __DIR__ . '/../includes/auth.php';

$bookId = $_GET['id'] ?? $_POST['id'] ?? '';
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if (!empty($bookId) && isset($_SESSION['cart'][$bookId])) {
    if ($action === 'increase') {
        $maxStock = (int)$_SESSION['cart'][$bookId]['stock'];
        if ($_SESSION['cart'][$bookId]['quantity'] < $maxStock) {
            $_SESSION['cart'][$bookId]['quantity']++;
        } else {
            setFlash('error', 'Cannot add more. Reached maximum available stock.');
        }
    } elseif ($action === 'decrease') {
        $_SESSION['cart'][$bookId]['quantity']--;
        if ($_SESSION['cart'][$bookId]['quantity'] <= 0) {
            unset($_SESSION['cart'][$bookId]);
            setFlash('info', 'Item removed from cart.');
        }
    } elseif ($action === 'set' && isset($_POST['quantity'])) {
        $qty = (int)$_POST['quantity'];
        $maxStock = (int)$_SESSION['cart'][$bookId]['stock'];
        if ($qty <= 0) {
            unset($_SESSION['cart'][$bookId]);
        } else {
            $_SESSION['cart'][$bookId]['quantity'] = min($qty, $maxStock);
        }
    }
}

header('Location: ' . baseUrl('cart/cart.php'));
exit;
