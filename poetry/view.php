<?php
require_once __DIR__ . '/../includes/auth.php';

$id = $_GET['id'] ?? '';
$poem = null;

if (!empty($id)) {
    try {
        $poem = $db->poetry->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    } catch (Exception $e) {
        $poem = null;
    }
}

if (!$poem) {
    setFlash('error', 'Poetry composition not found in archive.');
    header('Location: ' . baseUrl('poetry/index.php'));
    exit;
}

$pageTitle = htmlspecialchars($poem['title']) . " by " . htmlspecialchars($poem['poet_name']) . " — DAASTAAN. Literary Archive & Mehfil";

// Fetch poet profile
$poet = null;
try {
    if (!empty($poem['poet_id'])) {
        $poet = $db->poets->findOne(['_id' => $poem['poet_id']]);
    }
    if (!$poet) {
        $poet = $db->poets->findOne(['name' => $poem['poet_name']]);
    }
} catch (Exception $e) {}

// Fetch books by this poet or in this literary genre
$relatedBooks = [];
try {
    $relatedBooks = $db->books->find([
        '$or' => [
            ['author' => new MongoDB\BSON\Regex('^' . preg_quote($poem['poet_name']), 'i')],
            ['category' => ($poem['language'] === 'Urdu') ? 'Urdu Poetry' : 'Hindi Literature'],
            ['mood' => $poem['mood'] ?? '']
        ]
    ], ['limit' => 3])->toArray();
} catch (Exception $e) {}

$mood = $poem['mood'] ?? 'Falsafa';
$lang = $poem['language'] ?? 'Urdu';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-4xl mx-auto px-6">

    <!-- BREADCRUMB -->
    <div class="mb-10 flex justify-between items-center text-xs mono">
        <a href="<?= baseUrl('poetry/index.php') ?>" class="text-gray-500 hover:text-white transition flex items-center gap-2">
            <span>←</span> BACK TO POETRY ARCHIVE
        </a>

        <a href="<?= baseUrl('collections/view.php?mood=' . urlencode($mood)) ?>" 
           class="text-gold hover:underline">
            EXPLORE MOOD: <?= strtoupper(htmlspecialchars($mood)) ?> →
        </a>
    </div>

    <!-- MAIN READING CONTAINER -->
    <article class="border border-white/10 bg-[#090909] p-8 sm:p-14 mb-12 shadow-2xl relative">
        
        <!-- Metadata Header -->
        <div class="flex flex-wrap items-center justify-between pb-8 border-b border-white/10 gap-4">
            <div>
                <span class="mono text-[10px] text-gold tracking-widest uppercase block mb-1">
                    <?= htmlspecialchars($poem['genre'] ?? 'GHAZAL') ?> • <?= strtoupper(htmlspecialchars($lang)) ?>
                </span>
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight text-white">
                    <?= htmlspecialchars($poem['title']) ?>
                </h1>
            </div>

            <div class="text-right">
                <span class="mono text-xs text-gray-500 block">COMPOSED BY</span>
                <?php if ($poet): ?>
                    <a href="<?= baseUrl('poets/profile.php?slug=' . urlencode($poet['slug'] ?? '')) ?>" 
                       class="text-lg font-bold text-gold hover:text-white transition underline">
                        <?= htmlspecialchars($poem['poet_name']) ?>
                    </a>
                <?php else: ?>
                    <span class="text-lg font-bold text-white"><?= htmlspecialchars($poem['poet_name']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- ROMAN HINDI/URDU VERSES (PROMINENT READING ROOM STYLING) -->
        <div class="py-12 my-4 px-6 sm:px-12 bg-black/70 border-l-4 border-blood">
            <p class="text-xl sm:text-2xl font-serif-literary text-ivory leading-relaxed whitespace-pre-line tracking-wide">
<?= htmlspecialchars($poem['text_roman']) ?>
            </p>
        </div>

        <!-- ENGLISH REFLECTION / MEANING -->
        <?php if (!empty($poem['meaning_en'])): ?>
            <div class="mt-8 pt-8 border-t border-white/10">
                <span class="mono text-[10px] text-gray-500 tracking-widest uppercase block mb-2 font-semibold">
                    LITERARY TAFSEER & REFLECTION
                </span>
                <p class="text-sm sm:text-base text-gray-300 leading-relaxed italic">
                    "<?= htmlspecialchars($poem['meaning_en']) ?>"
                </p>
            </div>
        <?php endif; ?>

        <!-- Mood & Tags -->
        <div class="mt-8 pt-6 border-t border-white/5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="mono text-[10px] text-gray-500 uppercase">MOOD:</span>
                <a href="<?= baseUrl('collections/view.php?mood=' . urlencode($mood)) ?>" 
                   class="mono text-xs border border-gold/40 bg-gold/10 text-gold px-3 py-1 font-bold hover:bg-gold hover:text-black transition">
                    <?= strtoupper(htmlspecialchars($mood)) ?>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <span class="mono text-[10px] text-gray-500">SHARE:</span>
                <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" 
                        class="mono text-xs border border-white/10 px-3 py-1 text-gray-400 hover:text-white">
                    COPY LINK
                </button>
            </div>
        </div>

    </article>

    <!-- POET SPOTLIGHT CARD -->
    <?php if ($poet): ?>
        <section class="border border-white/10 bg-[#080808] p-8 mb-12 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <span class="mono text-[10px] text-gold tracking-widest uppercase block mb-1">ABOUT THE AUTHOR</span>
                <h3 class="text-2xl font-bold text-white"><?= htmlspecialchars($poet['name']) ?></h3>
                <p class="mono text-xs text-gray-500 mt-1"><?= htmlspecialchars($poet['era'] ?? '') ?> • <?= htmlspecialchars($poet['birthplace'] ?? '') ?></p>
                <p class="text-xs text-gray-400 mt-3 max-w-xl line-clamp-2"><?= htmlspecialchars($poet['biography'] ?? '') ?></p>
            </div>

            <a href="<?= baseUrl('poets/profile.php?slug=' . urlencode($poet['slug'] ?? '')) ?>"
               class="danger-button whitespace-nowrap px-6 py-3.5 text-xs font-bold tracking-widest flex-shrink-0">
                VIEW POET MEHFIL →
            </a>
        </section>
    <?php endif; ?>

    <!-- RELATED BOOKS (CONTENT TO COMMERCE) -->
    <?php if (!empty($relatedBooks)): ?>
        <section class="border-t border-white/10 pt-10">
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <span class="mono text-[10px] text-blood tracking-widest uppercase mb-1">RECOMMENDED VOLUMES</span>
                    <h2 class="text-2xl font-bold">BOOKS MATCHING THIS POETIC SPIRIT</h2>
                </div>
                <a href="<?= baseUrl('books.php') ?>" class="mono text-xs text-gray-500 hover:text-white">ALL BOOKS →</a>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <?php foreach ($relatedBooks as $b): ?>
                    <?php 
                        $bId = (string)$b['_id'];
                        $bCover = $b['cover_bg'] ?? 'from-[#241018] to-[#090909]';
                    ?>
                    <div class="border border-white/10 bg-[#080808] p-5 flex flex-col justify-between hover:border-gold/50 transition">
                        <div>
                            <div class="h-36 bg-gradient-to-br <?= htmlspecialchars($bCover) ?> border border-white/15 p-3 flex flex-col justify-between shadow-md mb-4">
                                <span class="mono text-[7px] text-blood tracking-widest uppercase"><?= htmlspecialchars($b['category'] ?? 'BOOK') ?></span>
                                <span class="font-bold text-sm text-white line-clamp-2"><?= htmlspecialchars($b['title']) ?></span>
                            </div>
                            <h4 class="font-bold text-sm text-white truncate"><?= htmlspecialchars($b['title']) ?></h4>
                            <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($b['author']) ?></p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between">
                            <span class="font-mono font-bold text-white text-sm">₹<?= htmlspecialchars((string)$b['price']) ?></span>
                            <a href="<?= baseUrl('book-details.php?id=' . $bId) ?>" class="mono text-[10px] border border-white/20 px-2.5 py-1 text-gold hover:text-white hover:border-gold">
                                VIEW →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
