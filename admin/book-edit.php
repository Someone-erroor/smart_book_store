<?php
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$id = $_GET['id'] ?? '';
$book = null;

if (!empty($id)) {
    try {
        $mongoId = new MongoDB\BSON\ObjectId($id);
        $book = $db->books->findOne(['_id' => $mongoId]);
    } catch (Exception $e) {
        $book = null;
    }
}

if (!$book) {
    setFlash('error', 'Book not found.');
    header('Location: ' . baseUrl('admin/books.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $rating = (float)($_POST['rating'] ?? 5.0);
    $format = trim($_POST['format'] ?? 'Paperback');
    $language = trim($_POST['language'] ?? 'English');
    $pages = (int)($_POST['pages'] ?? 300);
    $isbn = trim($_POST['isbn'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $featured = isset($_POST['featured']);
    $coverBg = $_POST['cover_bg'] ?? 'from-[#241018] to-[#090909]';

    if (empty($title) || empty($author) || empty($category) || $price <= 0) {
        $error = 'Please fill in Title, Author, Category, and a valid Price.';
    } else {
        try {
            $updateData = [
                'title' => $title,
                'author' => $author,
                'category' => $category,
                'price' => $price,
                'stock' => max(0, $stock),
                'rating' => min(5.0, max(1.0, $rating)),
                'format' => $format,
                'language' => $language,
                'pages' => $pages,
                'isbn' => $isbn,
                'description' => $description,
                'featured' => $featured,
                'cover_bg' => $coverBg,
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ];

            $db->books->updateOne(['_id' => $mongoId], ['$set' => $updateData]);
            setFlash('success', 'Book "' . htmlspecialchars($title) . '" successfully updated.');
            header('Location: ' . baseUrl('admin/books.php'));
            exit;
        } catch (Exception $e) {
            $error = 'Update failed: ' . $e->getMessage();
        }
    }
}

$pageTitle = "Edit " . htmlspecialchars($book['title']) . " — Admin Console";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-4xl mx-auto px-6">

    <div class="mb-10 flex justify-between items-end">
        <div>
            <a href="<?= baseUrl('admin/books.php') ?>" class="mono text-xs text-gray-500 hover:text-white transition flex items-center gap-2 mb-3">
                ← BACK TO INVENTORY
            </a>
            <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">CRUD / UPDATE RECORD</div>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">EDIT BOOK.</h1>
        </div>

        <a href="<?= baseUrl('book-details.php?id=' . $id) ?>" target="_blank" class="border border-white/20 px-4 py-2 text-xs mono text-gray-400 hover:text-white">
            VIEW LIVE PRODUCT PAGE ↗
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 p-4 border border-blood bg-[#1a0508] text-blood text-xs mono">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="<?= baseUrl('admin/book-edit.php?id=' . $id) ?>" method="POST" class="border border-white/10 p-8 sm:p-12 bg-[#080808] space-y-8">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Book Title *</label>
                <input type="text" name="title" required
                       value="<?= htmlspecialchars($_POST['title'] ?? $book['title'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Author *</label>
                <input type="text" name="author" required
                       value="<?= htmlspecialchars($_POST['author'] ?? $book['author'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Category / Genre *</label>
                <input type="text" name="category" required
                       value="<?= htmlspecialchars($_POST['category'] ?? $book['category'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Price (₹ INR) *</label>
                <input type="number" step="1" min="1" name="price" required
                       value="<?= htmlspecialchars((string)($_POST['price'] ?? $book['price'] ?? 0)) ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Stock Inventory Units *</label>
                <input type="number" min="0" name="stock" required
                       value="<?= htmlspecialchars((string)($_POST['stock'] ?? $book['stock'] ?? 0)) ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Editorial Rating (1.0 - 5.0)</label>
                <input type="number" step="0.1" min="1.0" max="5.0" name="rating"
                       value="<?= htmlspecialchars((string)($_POST['rating'] ?? $book['rating'] ?? 5.0)) ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Format</label>
                <?php $currentFormat = $_POST['format'] ?? $book['format'] ?? 'Paperback'; ?>
                <select name="format" class="w-full bg-black border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    <option value="Paperback" <?= $currentFormat === 'Paperback' ? 'selected' : '' ?>>Paperback</option>
                    <option value="Hardcover" <?= $currentFormat === 'Hardcover' ? 'selected' : '' ?>>Hardcover</option>
                    <option value="Deluxe Edition" <?= $currentFormat === 'Deluxe Edition' ? 'selected' : '' ?>>Deluxe Edition</option>
                    <option value="E-Book Edition" <?= $currentFormat === 'E-Book Edition' ? 'selected' : '' ?>>E-Book Edition</option>
                </select>
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">ISBN</label>
                <input type="text" name="isbn"
                       value="<?= htmlspecialchars($_POST['isbn'] ?? $book['isbn'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Number of Pages</label>
                <input type="number" name="pages"
                       value="<?= htmlspecialchars((string)($_POST['pages'] ?? $book['pages'] ?? 300)) ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Language</label>
                <input type="text" name="language"
                       value="<?= htmlspecialchars($_POST['language'] ?? $book['language'] ?? 'English') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Cover Gradient Theme</label>
                <?php $currentBg = $_POST['cover_bg'] ?? $book['cover_bg'] ?? 'from-[#241018] to-[#090909]'; ?>
                <select name="cover_bg" class="w-full bg-black border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    <option value="from-[#241018] to-[#090909]" <?= $currentBg === 'from-[#241018] to-[#090909]' ? 'selected' : '' ?>>Dark Crimson (Brutalist)</option>
                    <option value="from-[#261508] to-[#090909]" <?= $currentBg === 'from-[#261508] to-[#090909]' ? 'selected' : '' ?>>Obsidian Amber</option>
                    <option value="from-[#0b1c2b] to-[#090909]" <?= $currentBg === 'from-[#0b1c2b] to-[#090909]' ? 'selected' : '' ?>>Midnight Navy</option>
                    <option value="from-[#0d2319] to-[#090909]" <?= $currentBg === 'from-[#0d2319] to-[#090909]' ? 'selected' : '' ?>>Emerald Void</option>
                    <option value="from-[#170a24] to-[#090909]" <?= $currentBg === 'from-[#170a24] to-[#090909]' ? 'selected' : '' ?>>Deep Violet</option>
                    <option value="from-[#21090c] to-[#090909]" <?= $currentBg === 'from-[#21090c] to-[#090909]' ? 'selected' : '' ?>>Blood Ember</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Book Description & Synopsis</label>
            <textarea name="description" rows="5"
                      class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition"><?= htmlspecialchars($_POST['description'] ?? $book['description'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center gap-3">
            <?php $isFeatured = isset($_POST['featured']) ? true : (!empty($book['featured'])); ?>
            <input type="checkbox" id="featured" name="featured" class="w-4 h-4 accent-blood" <?= $isFeatured ? 'checked' : '' ?>>
            <label for="featured" class="text-sm font-semibold text-white cursor-pointer">
                Feature on Homepage Shelf ("01 / CURATED SELECTION")
            </label>
        </div>

        <div class="pt-6 border-t border-white/10 flex justify-between items-center">
            <a href="<?= baseUrl('admin/books.php') ?>" class="mono text-xs text-gray-500 hover:text-white">Cancel</a>
            <button type="submit" class="danger-button px-10 py-4 text-xs font-bold tracking-widest">
                UPDATE BOOK IN MONGODB →
            </button>
        </div>

    </form>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
