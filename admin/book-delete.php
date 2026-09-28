<?php
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$id = $_GET['id'] ?? '';

if (!empty($id)) {
    try {
        $mongoId = new MongoDB\BSON\ObjectId($id);
        $book = $db->books->findOne(['_id' => $mongoId]);

        if ($book) {
            $db->books->deleteOne(['_id' => $mongoId]);
            setFlash('info', 'Book "' . htmlspecialchars($book['title']) . '" was permanently removed from MongoDB.');
        } else {
            setFlash('error', 'Book not found.');
        }
    } catch (Exception $e) {
        setFlash('error', 'Delete operation failed: ' . $e->getMessage());
    }
}

header('Location: ' . baseUrl('admin/books.php'));
exit;
