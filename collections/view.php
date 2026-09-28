<?php
require_once __DIR__ . '/../includes/auth.php';

$mood = trim($_GET['mood'] ?? $_GET['slug'] ?? 'Tanhai');

$collection = null;
try {
    $collection = $db->collections->findOne([
        '$or' => [
            ['mood' => new MongoDB\BSON\Regex('^' . preg_quote($mood) . '$', 'i')],
            ['slug' => new MongoDB\BSON\Regex('^' . preg_quote($mood) . '$', 'i')]
        ]
    ]);
} catch (Exception $e) {}

if (!$collection) {
    // Default fallback
    $collection = [
        'name' => ucfirst($mood),
        'mood' => ucfirst($mood),
        'tagline' => 'A State of Poetic Feeling',
        'description' => 'Explore verses and books curated for this mood.',
        'quote_roman' => 'Alfaaz jo dil ki gehraiyon ko chhoo lein...',
        'color_accent' => '#c5a059',
        'bg_gradient' => 'from-[#141414] to-[#070707]'
    ];
}

$pageTitle = htmlspecialchars($collection['name']) . " Collection — DAASTAAN. Literary Archive";

// Fetch poetry in this mood
$poetryInMood = [];
try {
    $poetryInMood = $db->poetry->find(
        ['mood' => new MongoDB\BSON\Regex('^' . preg_quote($collection['mood']) . '$', 'i')]
    )->toArray();
} catch (Exception $e) {}

// Fetch books matching this mood or related literary genres
$booksInMood = [];
try {
    $booksInMood = $db->books->find([
        '$or' => [
            ['mood' => new MongoDB\BSON\Regex('^' . preg_quote($collection['mood']) . '$', 'i')],
            ['category' => 'Urdu Poetry'],
            ['category' => 'Hindi Literature'],
            ['category' => 'Classics']
        ]
    ], ['limit' => 6])->toArray();
} catch (Exception $e) {}

// Fetch all collections for quick tab switching
$allMoods = [];
try {
    $allMoods = $db->collections->find([], ['projection' => ['name' => 1, 'mood' => 1, 'color_accent' => 1]])->toArray();
} catch (Exception $e) {}

$accent = $collection['color_accent'] ?? '#c5a059';
$gradient = $collection['bg_gradient'] ?? 'from-[#141414] to-[#070707]';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- ALL MOODS QUICK SWITCHER BAR -->
    <div class="mb-10 flex items-center gap-2 overflow-x-auto pb-2 border-b border-white/10 scrollbar-thin">
        <span class="mono text-[10px] text-gray-500 uppercase tracking-widest mr-2 flex-shrink-0">MOOD ROOMS:</span>
        <?php foreach ($allMoods as $mItem): ?>
            <?php 
                $mName = $mItem['mood'] ?? $mItem['name'];
                $isActive = (strcasecmp($mName, $collection['mood']) === 0);
            ?>
            <a href="<?= baseUrl('collections/view.php?mood=' . urlencode($mName)) ?>"
               class="mono text-xs px-3.5 py-1.5 border transition whitespace-nowrap <?= $isActive ? 'border-gold bg-gold text-black font-bold' : 'border-white/10 text-gray-400 hover:text-white hover:border-white/30' ?>">
                <?= strtoupper(htmlspecialchars($mName)) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- COLLECTION HERO BANNER -->
    <section class="border border-white/10 bg-gradient-to-br <?= htmlspecialchars($gradient) ?> p-8 md:p-14 relative overflow-hidden mb-16 shadow-2xl">
        <div class="max-w-3xl relative z-10">
            <span class="mono text-[10px] tracking-widest uppercase border px-3 py-1 font-bold inline-block mb-4"
                  style="border-color: <?= $accent ?>50; color: <?= $accent ?>; background: <?= $accent ?>10;">
                CURATED EMOTION ARCHIVE
            </span>

            <h1 class="text-4xl md:text-7xl font-bold tracking-tight text-white mb-2">
                <?= strtoupper(htmlspecialchars($collection['name'])) ?>
            </h1>

            <div class="mono text-sm text-gray-300 font-semibold mb-6">
                <?= htmlspecialchars($collection['tagline'] ?? '') ?>
            </div>

            <div class="p-6 bg-black/70 border-l-4 my-6" style="border-color: <?= $accent ?>;">
                <p class="text-base sm:text-lg font-serif-literary text-ivory italic leading-relaxed">
                    "<?= htmlspecialchars($collection['quote_roman'] ?? '') ?>"
                </p>
            </div>

            <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-2xl">
                <?= htmlspecialchars($collection['description'] ?? '') ?>
            </p>
        </div>
    </section>

    <!-- TWO COLUMN: POETRY IN THIS MOOD & BOOKS TO BUY -->
    <div class="grid lg:grid-cols-[1.3fr_1fr] gap-14 items-start">
        
        <!-- LEFT: POETRY IN THIS MOOD -->
        <div>
            <div class="flex justify-between items-end mb-8 pb-4 border-b border-white/10">
                <div>
                    <span class="mono text-[10px] text-gold tracking-widest uppercase block mb-1">ALFAAZ OF <?= strtoupper(htmlspecialchars($collection['mood'])) ?></span>
                    <h2 class="text-3xl font-bold tracking-tight">FEATURED VERSES.</h2>
                </div>
                <span class="mono text-xs text-gray-500"><?= count($poetryInMood) ?> PIECES</span>
            </div>

            <?php if (empty($poetryInMood)): ?>
                <div class="border border-white/10 p-12 text-center bg-[#080808]">
                    <p class="mono text-xs text-gray-500">More verses for this emotion are being curated.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($poetryInMood as $poem): ?>
                        <?php $pId = (string)$poem['_id']; ?>
                        <article class="border border-white/10 bg-[#080808] p-8 hover:border-gold/40 transition duration-300 group">
                            
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-bold text-gold uppercase tracking-wider"><?= htmlspecialchars($poem['poet_name']) ?></span>
                                <span class="mono text-[10px] text-gray-500 border border-white/10 px-2 py-0.5"><?= htmlspecialchars($poem['genre'] ?? 'Ghazal') ?></span>
                            </div>

                            <h3 class="text-xl font-bold text-white group-hover:text-gold transition mb-4">
                                <?= htmlspecialchars($poem['title']) ?>
                            </h3>

                            <div class="p-5 bg-black/60 border-l-2 border-blood/60 my-4">
                                <p class="text-sm font-serif-literary text-ivory leading-relaxed whitespace-pre-line">
<?= htmlspecialchars($poem['text_roman']) ?>
                                </p>
                            </div>

                            <?php if (!empty($poem['meaning_en'])): ?>
                                <p class="text-xs text-gray-400 mt-3 leading-relaxed italic line-clamp-2">
                                    <?= htmlspecialchars($poem['meaning_en']) ?>
                                </p>
                            <?php endif; ?>

                            <div class="mt-6 pt-4 border-t border-white/5 flex justify-between items-center text-xs">
                                <span class="mono text-[10px] text-gray-600">ROMAN TRANSCRIPTION</span>
                                <a href="<?= baseUrl('poetry/view.php?id=' . $pId) ?>" class="mono text-xs text-gold hover:text-white transition font-bold">
                                    READ FULL COMPOSITION →
                                </a>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT: PURCHASABLE BOOKS MATCHING THIS MOOD (COMMERCE CONNECTION) -->
        <div class="border border-white/10 p-8 bg-[#080808] sticky top-28 space-y-6">
            <div class="pb-4 border-b border-white/10">
                <span class="mono text-[10px] text-blood tracking-widest uppercase block mb-1">BOOKSTORE SELECTIONS</span>
                <h2 class="text-2xl font-bold tracking-tight">BOOKS OF THIS SPIRIT</h2>
                <p class="text-xs text-gray-500 mt-1">Volumes curated to accompany this mood. Delivered from our inventory.</p>
            </div>

            <?php if (empty($booksInMood)): ?>
                <div class="py-8 text-center text-xs text-gray-500 mono">No specific books tagged yet.</div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($booksInMood as $b): ?>
                        <?php 
                            $bId = (string)$b['_id'];
                            $bCover = $b['cover_bg'] ?? 'from-[#241018] to-[#090909]';
                            $stock = (int)($b['stock'] ?? 0);
                        ?>
                        <div class="p-4 border border-white/10 bg-black/40 hover:border-gold/50 transition duration-300 flex gap-4">
                            <!-- Mini Cover -->
                            <div class="w-16 h-24 bg-gradient-to-br <?= htmlspecialchars($bCover) ?> border border-white/15 flex-shrink-0 flex items-center justify-center p-2 text-center shadow-lg">
                                <span class="mono text-[8px] text-blood line-clamp-2 uppercase font-bold"><?= htmlspecialchars($b['category'] ?? 'BOOK') ?></span>
                            </div>

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <a href="<?= baseUrl('book-details.php?id=' . $bId) ?>" class="text-sm font-bold text-white hover:text-gold transition line-clamp-1">
                                        <?= htmlspecialchars($b['title']) ?>
                                    </a>
                                    <div class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($b['author']) ?></div>
                                    <div class="text-sm font-bold text-white mt-1">₹<?= htmlspecialchars((string)$b['price']) ?></div>
                                </div>

                                <div class="mt-3 flex items-center justify-between">
                                    <span class="mono text-[9px] <?= $stock > 0 ? 'text-emerald-400' : 'text-blood' ?>">
                                        <?= $stock > 0 ? "IN STOCK ({$stock})" : "SOLD OUT" ?>
                                    </span>

                                    <form action="<?= baseUrl('cart/add.php') ?>" method="POST" class="inline">
                                        <input type="hidden" name="book_id" value="<?= $bId ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <?php if ($stock > 0): ?>
                                            <button type="submit" class="border border-gold/50 text-gold hover:bg-gold hover:text-black px-2.5 py-1 text-[10px] mono font-bold transition">
                                                + CART
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="pt-4 border-t border-white/10 text-center">
                <a href="<?= baseUrl('books.php') ?>" class="mono text-xs text-gray-400 hover:text-white transition">
                    Browse All Catalog Books →
                </a>
            </div>
        </div>

    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
