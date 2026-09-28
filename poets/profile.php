<?php
require_once __DIR__ . '/../includes/auth.php';

$slug = $_GET['slug'] ?? '';
$id = $_GET['id'] ?? '';
$poet = null;

try {
    if (!empty($slug)) {
        $poet = $db->poets->findOne(['slug' => $slug]);
    }
    if (!$poet && !empty($id)) {
        $poet = $db->poets->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
} catch (Exception $e) {
    $poet = null;
}

if (!$poet) {
    setFlash('error', 'Poet profile not found.');
    header('Location: ' . baseUrl('poets/index.php'));
    exit;
}

$pageTitle = htmlspecialchars($poet['name']) . " — DAASTAAN. Literary Profile & Archive";
$poetId = $poet['_id'];

// Fetch poetry by this poet
$poetVerses = [];
try {
    $poetVerses = $db->poetry->find(
        ['$or' => [
            ['poet_id' => $poetId],
            ['poet_name' => $poet['name']]
        ]],
        ['sort' => ['created_at' => -1]]
    )->toArray();
} catch (Exception $e) {}

// Fetch books by this poet in our bookstore
$poetBooks = [];
try {
    $poetBooks = $db->books->find(
        ['$or' => [
            ['poet_id' => $poetId],
            ['author' => new MongoDB\BSON\Regex('^' . preg_quote($poet['name']), 'i')]
        ]]
    )->toArray();

    // Fallback: If no direct books, fetch general literary books in this language
    if (empty($poetBooks)) {
        $langCat = ($poet['language'] === 'Urdu') ? 'Urdu Poetry' : 'Hindi Literature';
        $poetBooks = $db->books->find(
            ['$or' => [
                ['category' => $langCat],
                ['category' => 'Classics']
            ]],
            ['limit' => 3]
        )->toArray();
    }
} catch (Exception $e) {}

$coverBg = $poet['cover_bg'] ?? 'from-[#1a0a14] to-[#0a0a0a]';
$lang = $poet['language'] ?? 'Urdu';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- BREADCRUMB -->
    <div class="mb-8">
        <a href="<?= baseUrl('poets/index.php') ?>" class="mono text-xs text-gray-500 hover:text-white transition flex items-center gap-2">
            <span>←</span> BACK TO ALL POETS & AUTHORS
        </a>
    </div>

    <!-- POET HERO SPOTLIGHT -->
    <section class="border border-white/10 p-8 md:p-14 bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> relative overflow-hidden mb-16">
        <div class="max-w-4xl relative z-10">
            
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="mono text-[10px] tracking-widest uppercase border px-3 py-1 font-bold <?= $lang === 'Urdu' ? 'border-blood/50 text-blood bg-blood/10' : 'border-gold/50 text-gold bg-gold/10' ?>">
                    <?= strtoupper(htmlspecialchars($lang)) ?> LITERARY TRADITION
                </span>
                <span class="mono text-xs text-gray-400 font-mono"><?= htmlspecialchars($poet['era'] ?? '') ?></span>
                <span class="text-white/20">•</span>
                <span class="mono text-xs text-gray-400"><?= htmlspecialchars($poet['birthplace'] ?? '') ?></span>
            </div>

            <h1 class="text-4xl md:text-7xl font-bold tracking-tight text-white mb-6">
                <?= htmlspecialchars($poet['name']) ?>
            </h1>

            <div class="border-l-2 border-gold pl-4 py-1 mb-8">
                <span class="mono text-[11px] text-gold uppercase tracking-widest block font-semibold">LITERARY SIGNATURE:</span>
                <p class="text-base text-gray-300 italic mt-1">
                    <?= htmlspecialchars($poet['literary_style'] ?? '') ?>
                </p>
            </div>

            <p class="text-sm md:text-base text-gray-300 leading-relaxed max-w-3xl">
                <?= nl2br(htmlspecialchars($poet['biography'] ?? '')) ?>
            </p>

            <?php if (!empty($poet['famous_works'])): ?>
                <div class="mt-8 pt-6 border-t border-white/10 flex flex-wrap items-center gap-2">
                    <span class="mono text-[10px] text-gray-400 uppercase tracking-widest mr-2">NOTABLE VOLUMES:</span>
                    <?php foreach ((array)$poet['famous_works'] as $work): ?>
                        <span class="mono text-xs border border-white/15 bg-black/60 px-3 py-1 text-gray-200">
                            <?= htmlspecialchars($work) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- TWO COLUMN: POETRY VERSES & RELATED BOOKS -->
    <div class="grid lg:grid-cols-[1.5fr_1fr] gap-14 items-start">
        
        <!-- LEFT: SELECTED POETRY IN ROMAN SCRIPT -->
        <div>
            <div class="flex justify-between items-end mb-8 pb-4 border-b border-white/10">
                <div>
                    <div class="mono text-[10px] text-gold tracking-widest uppercase mb-1">SELECTED VERSES (ALFAAZ)</div>
                    <h2 class="text-3xl font-bold tracking-tight">POETRY & GHAZALS.</h2>
                </div>
                <span class="mono text-xs text-gray-500"><?= count($poetVerses) ?> COMPOSITIONS</span>
            </div>

            <?php if (empty($poetVerses)): ?>
                <div class="border border-white/10 p-12 text-center bg-[#080808]">
                    <p class="mono text-xs text-gray-500">More verses by this poet are currently being transcribed in the archive.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($poetVerses as $v): ?>
                        <?php 
                            $vId = (string)$v['_id'];
                            $mood = $v['mood'] ?? 'Falsafa';
                        ?>
                        <article class="border border-white/10 bg-[#080808] p-8 hover:border-gold/40 transition duration-300 group">
                            
                            <div class="flex justify-between items-center mb-4">
                                <span class="mono text-[10px] tracking-widest text-gold border border-gold/30 bg-gold/5 px-2.5 py-0.5 uppercase">
                                    MOOD: <?= htmlspecialchars($mood) ?>
                                </span>
                                <span class="mono text-xs text-gray-500"><?= htmlspecialchars($v['genre'] ?? 'Ghazal') ?></span>
                            </div>

                            <h3 class="text-xl font-bold text-white group-hover:text-gold transition mb-4">
                                <?= htmlspecialchars($v['title']) ?>
                            </h3>

                            <!-- Roman Hindi/Urdu Verses -->
                            <div class="p-6 bg-black/60 border-l-2 border-blood/60 my-4">
                                <p class="text-base sm:text-lg font-serif-literary text-ivory leading-relaxed whitespace-pre-line tracking-wide">
<?= htmlspecialchars($v['text_roman']) ?>
                                </p>
                            </div>

                            <!-- English Tafseer/Meaning -->
                            <?php if (!empty($v['meaning_en'])): ?>
                                <p class="text-xs text-gray-400 mt-4 leading-relaxed italic">
                                    <span class="text-gray-500 font-mono not-italic uppercase text-[10px] block mb-1">LITERARY REFLECTION:</span>
                                    <?= htmlspecialchars($v['meaning_en']) ?>
                                </p>
                            <?php endif; ?>

                            <div class="mt-6 pt-4 border-t border-white/5 flex justify-between items-center">
                                <span class="mono text-[10px] text-gray-600">TRANSCRIBED IN ROMAN SCRIPT</span>
                                <a href="<?= baseUrl('poetry/view.php?id=' . $vId) ?>" 
                                   class="mono text-xs text-gold hover:text-white transition">
                                    READ FULL COMPOSITION →
                                </a>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT: BOOKS AVAILABLE IN OUR STORE (CONTENT TO COMMERCE) -->
        <div class="border border-white/10 p-8 bg-[#080808] sticky top-28 space-y-6">
            <div class="pb-4 border-b border-white/10">
                <span class="mono text-[10px] text-blood tracking-widest uppercase block mb-1">AVAILABLE IN STORE</span>
                <h2 class="text-2xl font-bold tracking-tight">PUBLISHED WORKS</h2>
                <p class="text-xs text-gray-500 mt-1">Get the complete published anthologies and collections delivered to your door.</p>
            </div>

            <?php if (empty($poetBooks)): ?>
                <div class="py-8 text-center text-xs text-gray-500 mono">
                    Physical copies currently being restocked in the bookstore.
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($poetBooks as $b): ?>
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
                                    <div class="mono text-[10px] text-gray-500 mt-0.5"><?= htmlspecialchars($b['format'] ?? 'Paperback') ?></div>
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
                    Browse All 24+ Bookstore Titles →
                </a>
            </div>
        </div>

    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
