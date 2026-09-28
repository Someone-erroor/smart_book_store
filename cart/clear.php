<?php
require_once __DIR__ . '/../includes/auth.php';

$_SESSION['cart'] = [];
setFlash('info', 'Your shopping cart has been cleared.');
header('Location: ' . baseUrl('cart/cart.php'));
exit;
