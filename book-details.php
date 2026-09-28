<?php
require_once __DIR__ . '/includes/auth.php';

$id = $_GET['id'] ?? '';
$book = null;

if (!empty($id)) {
    try {
        $bookId = new MongoDB\BSON\ObjectId($id);
        $book = $db->books->findOne(['_id' => $bookId]);
    } catch (Exception $e) {
        $book = null;
    }
}

if (!$book) {
    setFlash('error', 'The requested book was not found in our catalog.');
    header('Location: ' . baseUrl('books.php'));
    exit;
}

$pageTitle = htmlspecialchars($book['title']) . " — DAASTAAN. Literary Archive & Bookstore";
$stock = (int)($book['stock'] ?? 0);
$coverBg = $book['cover_bg'] ?? 'from-[#260812] to-[#090909]';

// Check if author is a recognized literary poet
$authorPoet = null;
try {
    if (!empty($book['poet_id'])) {
        $authorPoet = $db->poets->findOne(['_id' => $book['poet_id']]);
    }
    if (!$authorPoet && !empty($book['author'])) {
        $authorPoet = $db->poets->findOne(['name' => new MongoDB\BSON\Regex('^' . preg_quote($book['author']), 'i')]);
    }
} catch (Exception $e) {}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-12 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- BREADCRUMB -->
    <div class="mb-10 flex items-center justify-between">
        <a href="<?= baseUrl('books.php') ?>" class="mono text-xs text-gray-400 hover:text-white transition flex items-center gap-2">
            <span>←</span> BACK TO CATALOG
        </a>

        <?php if (isAdmin()): ?>
            <a href="<?= baseUrl('admin/book-edit.php?id=' . (string)$book['_id']) ?>"
               class="mono text-xs text-blood border border-blood/40 px-3 py-1.5 hover:bg-blood hover:text-black transition">
                ⚡ EDIT BOOK AS ADMIN
            </a>
        <?php endif; ?>
    </div>

    <!-- MAIN PRODUCT SECTION -->
    <div class="grid lg:grid-cols-2 gap-16 items-center">

        <!-- 3D BOOK COVER VISUAL WITH FLOATING ANIMATION -->
        <div class="relative flex justify-center items-center min-h-[550px] reveal">
            <div class="absolute w-[330px] h-[470px] border border-blood/20 rotate-12 pointer-events-none transition-transform duration-700 hover:rotate-6"></div>
            <div class="absolute w-[330px] h-[470px] border border-white/10 -rotate-6 pointer-events-none transition-transform duration-700 hover:-rotate-12"></div>

            <div class="hero-book-float book-cover relative w-[300px] h-[450px] bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> border border-blood/50 p-8 flex flex-col justify-between shadow-2xl">
                <div class="mono text-[10px] text-blood tracking-[0.3em] uppercase flex items-center justify-between">
                    <span><?= htmlspecialchars($book['category'] ?? 'DAASTAAN EDITION') ?></span>
                    <span class="text-xs text-yellow-500">★ <?= htmlspecialchars((string)($book['rating'] ?? 5.0)) ?></span>
                </div>

                <div>
                    <h1 class="text-3xl font-bold leading-tight">
                        <?= htmlspecialchars($book['title']) ?>
                    </h1>
                </div>

                <div>
                    <p class="text-xs text-gray-400"><?= htmlspecialchars($book['author']) ?></p>
                    <div class="mt-4 flex justify-between items-center text-xs mono text-gray-500">
                        <span>FORMAT: <?= htmlspecialchars($book['format'] ?? 'Paperback') ?></span>
                        <span class="w-8 h-8 rounded-full border border-blood text-blood flex items-center justify-center font-bold">↓</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOOK METADATA & ORDER ACTIONS -->
        <div class="reveal">
            <div class="mono text-[11px] text-blood tracking-[0.3em] uppercase mb-4 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-blood"></span>
                <span>GENRE: <?= htmlspecialchars($book['category'] ?? 'UNCATEGORIZED') ?></span>
            </div>

            <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-tight">
                <?= htmlspecialchars($book['title']) ?>
            </h1>

            <p class="mt-4 text-base text-gray-400">
                Author: 
                <?php if ($authorPoet): ?>
                    <a href="<?= baseUrl('poets/profile.php?slug=' . urlencode($authorPoet['slug'] ?? '')) ?>" class="text-gold font-bold hover:text-white transition underline">
                        <?= htmlspecialchars($book['author']) ?> <span class="mono text-[11px] not-italic ml-1 border border-gold/40 px-2 py-0.5 text-gold bg-gold/5">MEHFIL PROFILE ✦</span>
                    </a>
                <?php else: ?>
                    <span class="text-white font-medium"><?= htmlspecialchars($book['author']) ?></span>
                <?php endif; ?>
            </p>

            <div class="flex items-center gap-4 mt-6 text-sm">
                <span class="text-yellow-500 text-lg">★</span>
                <span class="font-bold text-white"><?= htmlspecialchars((string)($book['rating'] ?? 5.0)) ?></span>
                <span class="text-gray-600">/</span>
                <span class="text-gray-400 text-xs mono">VERIFIED EDITORIAL RATING</span>
            </div>

            <div class="border-t border-white/10 mt-8 pt-8">
                <p class="text-gray-300 leading-relaxed text-sm">
                    <?= nl2br(htmlspecialchars($book['description'] ?? 'No description available for this book.')) ?>
                </p>
            </div>

            <!-- PRICE & STOCK -->
            <div class="mt-8 flex items-baseline gap-6">
                <div>
                    <span class="mono text-[10px] text-gray-500 tracking-widest block uppercase">Unit Price</span>
                    <div class="text-4xl font-bold mt-1 text-white font-mono">₹<?= htmlspecialchars((string)$book['price']) ?></div>
                </div>
                <div class="mono text-xs">
                    <?php if ($stock > 5): ?>
                        <span class="text-emerald-400 border border-emerald-500/30 bg-emerald-500/10 px-3 py-1.5 inline-block">
                            ● IN STOCK (<?= $stock ?> COPIES AVAILABLE)
                        </span>
                    <?php elseif ($stock > 0): ?>
                        <span class="text-yellow-400 border border-yellow-500/30 bg-yellow-500/10 px-3 py-1.5 inline-block animate-pulse">
                            ▲ LOW STOCK (ONLY <?= $stock ?> LEFT)
                        </span>
                    <?php else: ?>
                        <span class="text-blood border border-blood/30 bg-blood/10 px-3 py-1.5 inline-block">
                            ✕ CURRENTLY OUT OF STOCK
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ADD TO CART FORM -->
            <form action="<?= baseUrl('cart/add.php') ?>" method="POST" class="mt-10">
                <input type="hidden" name="book_id" value="<?= (string)$book['_id'] ?>">

                <?php if ($stock > 0): ?>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Quantity Controller -->
                        <div class="flex items-center border border-white/20 bg-black hover:border-white/40 transition">
                            <button type="button" id="minus" class="w-12 h-12 flex items-center justify-center hover:bg-white hover:text-black transition font-bold text-base active:scale-90">−</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $stock ?>"
                                   class="w-16 h-12 bg-transparent text-center font-bold text-sm text-white outline-none border-x border-white/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button" id="plus" class="w-12 h-12 flex items-center justify-center hover:bg-white hover:text-black transition font-bold text-base active:scale-90">+</button>
                        </div>

                        <!-- Add Button with Shimmer -->
                        <button type="submit" class="danger-button px-10 py-4 text-xs font-bold tracking-widest flex-1 flex items-center justify-center gap-3">
                            <span>ADD TO CART</span>
                            <span>→</span>
                        </button>
                    </div>
                <?php else: ?>
                    <button type="button" disabled class="w-full border border-white/10 py-4 text-xs mono text-gray-500 cursor-not-allowed">
                        ITEM TEMPORARILY UNAVAILABLE
                    </button>
                <?php endif; ?>
            </form>

        </div>
    </div>

    <!-- SPECIFICATIONS TABLE -->
    <div class="mt-24 border-t border-white/10 pt-16">
        <div class="mono text-[10px] text-blood tracking-[0.3em] mb-8">SPECIFICATIONS</div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="border-l border-white/15 pl-4">
                <span class="mono text-[10px] text-gray-500 uppercase block">FORMAT</span>
                <span class="text-sm font-semibold mt-1 block"><?= htmlspecialchars($book['format'] ?? 'Paperback') ?></span>
            </div>
            <div class="border-l border-white/15 pl-4">
                <span class="mono text-[10px] text-gray-500 uppercase block">LANGUAGE</span>
                <span class="text-sm font-semibold mt-1 block"><?= htmlspecialchars($book['language'] ?? 'English') ?></span>
            </div>
            <div class="border-l border-white/15 pl-4">
                <span class="mono text-[10px] text-gray-500 uppercase block">PAGES</span>
                <span class="text-sm font-semibold mt-1 block"><?= htmlspecialchars((string)($book['pages'] ?? 300)) ?> pages</span>
            </div>
            <div class="border-l border-white/15 pl-4">
                <span class="mono text-[10px] text-gray-500 uppercase block">ISBN</span>
                <span class="text-sm font-semibold mt-1 block mono text-xs"><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>

</main>

<script>
    const minus = document.getElementById("minus");
    const plus = document.getElementById("plus");
    const qtyInput = document.getElementById("quantity");
    const maxStock = <?= $stock ?>;

    if (minus && plus && qtyInput) {
        minus.addEventListener("click", () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });

        plus.addEventListener("click", () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val < maxStock) qtyInput.value = val + 1;
        });
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>