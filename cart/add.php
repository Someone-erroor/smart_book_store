<?php
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . baseUrl('books.php'));
    exit;
}

$bookId = $_POST['book_id'] ?? '';
$qty = max(1, (int)($_POST['quantity'] ?? 1));

if (empty($bookId)) {
    setFlash('error', 'Invalid book selection.');
    header('Location: ' . baseUrl('books.php'));
    exit;
}

try {
    $mongoId = new MongoDB\BSON\ObjectId($bookId);
    $book = $db->books->findOne(['_id' => $mongoId]);

    if (!$book) {
        setFlash('error', 'Book not found.');
        header('Location: ' . baseUrl('books.php'));
        exit;
    }

    $availableStock = (int)($book['stock'] ?? 0);
    if ($availableStock <= 0) {
        setFlash('error', 'Sorry, this book is currently out of stock.');
        header('Location: ' . baseUrl('book-details.php?id=' . $bookId));
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $currentQty = isset($_SESSION['cart'][$bookId]) ? (int)$_SESSION['cart'][$bookId]['quantity'] : 0;
    $newQty = min($availableStock, $currentQty + $qty);

    $_SESSION['cart'][$bookId] = [
        'id' => $bookId,
        'title' => $book['title'],
        'author' => $book['author'],
        'price' => (float)$book['price'],
        'category' => $book['category'] ?? '',
        'quantity' => $newQty,
        'stock' => $availableStock,
        'cover_bg' => $book['cover_bg'] ?? 'from-[#241018] to-[#090909]'
    ];

    setFlash('success', 'Added "' . htmlspecialchars($book['title']) . '" to your cart.');
    header('Location: ' . baseUrl('cart/cart.php'));
    exit;

} catch (Exception $e) {
    setFlash('error', 'Error adding item to cart: ' . $e->getMessage());
    header('Location: ' . baseUrl('books.php'));
    exit;
}
