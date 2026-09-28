<?php
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

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
            $insertData = [
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
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ];

            $result = $db->books->insertOne($insertData);
            setFlash('success', 'Book "' . htmlspecialchars($title) . '" has been added to MongoDB.');
            header('Location: ' . baseUrl('admin/books.php'));
            exit;
        } catch (Exception $e) {
            $error = 'Failed to insert book: ' . $e->getMessage();
        }
    }
}

$pageTitle = "Add Book — Admin Console";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-4xl mx-auto px-6">

    <div class="mb-10">
        <a href="<?= baseUrl('admin/books.php') ?>" class="mono text-xs text-gray-500 hover:text-white transition flex items-center gap-2 mb-3">
            ← BACK TO INVENTORY
        </a>
        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">CRUD / CREATE RECORD</div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight">ADD NEW BOOK.</h1>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 p-4 border border-blood bg-[#1a0508] text-blood text-xs mono">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="<?= baseUrl('admin/book-add.php') ?>" method="POST" class="border border-white/10 p-8 sm:p-12 bg-[#080808] space-y-8">
        
        <!-- Basic Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Book Title *</label>
                <input type="text" name="title" required placeholder="e.g. Neuromancer"
                       value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Author *</label>
                <input type="text" name="author" required placeholder="e.g. William Gibson"
                       value="<?= htmlspecialchars($_POST['author'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Category / Genre *</label>
                <input type="text" name="category" required placeholder="e.g. Science Fiction, Thriller, Technology"
                       value="<?= htmlspecialchars($_POST['category'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Price (₹ INR) *</label>
                <input type="number" step="1" min="1" name="price" required placeholder="499"
                       value="<?= htmlspecialchars($_POST['price'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Stock Inventory Units *</label>
                <input type="number" min="0" name="stock" required placeholder="20"
                       value="<?= htmlspecialchars($_POST['stock'] ?? '15') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Editorial Rating (1.0 - 5.0)</label>
                <input type="number" step="0.1" min="1.0" max="5.0" name="rating" placeholder="4.8"
                       value="<?= htmlspecialchars($_POST['rating'] ?? '4.7') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Format</label>
                <select name="format" class="w-full bg-black border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    <option value="Paperback">Paperback</option>
                    <option value="Hardcover">Hardcover</option>
                    <option value="Deluxe Edition">Deluxe Edition</option>
                    <option value="E-Book Edition">E-Book Edition</option>
                </select>
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">ISBN</label>
                <input type="text" name="isbn" placeholder="978-0441569595"
                       value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Number of Pages</label>
                <input type="number" name="pages" placeholder="320"
                       value="<?= htmlspecialchars($_POST['pages'] ?? '320') ?>"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Language</label>
                <input type="text" name="language" value="English"
                       class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Cover Gradient Theme</label>
                <select name="cover_bg" class="w-full bg-black border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    <option value="from-[#241018] to-[#090909]">Dark Crimson (Brutalist)</option>
                    <option value="from-[#261508] to-[#090909]">Obsidian Amber</option>
                    <option value="from-[#0b1c2b] to-[#090909]">Midnight Navy</option>
                    <option value="from-[#0d2319] to-[#090909]">Emerald Void</option>
                    <option value="from-[#170a24] to-[#090909]">Deep Violet</option>
                    <option value="from-[#21090c] to-[#090909]">Blood Ember</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Book Description & Synopsis</label>
            <textarea name="description" rows="5" placeholder="Enter a captivating editorial synopsis..."
                      class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" id="featured" name="featured" class="w-4 h-4 accent-blood">
            <label for="featured" class="text-sm font-semibold text-white cursor-pointer">
                Feature on Homepage Shelf ("01 / CURATED SELECTION")
            </label>
        </div>

        <div class="pt-6 border-t border-white/10 flex justify-between items-center">
            <a href="<?= baseUrl('admin/books.php') ?>" class="mono text-xs text-gray-500 hover:text-white">Cancel</a>
            <button type="submit" class="danger-button px-10 py-4 text-xs font-bold tracking-widest">
                SAVE BOOK TO MONGODB →
            </button>
        </div>

    </form>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
