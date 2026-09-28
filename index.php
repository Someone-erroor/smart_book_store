<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = "DAASTAAN. — Books That Bite & Digital Literary Mehfil";

// Fetch featured books from MongoDB
$featuredBooks = [];
$categoryStats = [];
$featuredPoetry = [];
$totalBooksCount = 0;
$totalPoetryCount = 0;
$totalPoetsCount = 0;
$sherOfTheDay = null;

try {
    if ($dbConnected) {
        $totalBooksCount = $db->books->countDocuments();
        $totalPoetryCount = $db->poetry->countDocuments();
        $totalPoetsCount = $db->poets->countDocuments();

        $featuredCursor = $db->books->find(
            ['featured' => true],
            ['limit' => 12, 'sort' => ['rating' => -1, 'created_at' => -1]]
        );
        $featuredBooks = $featuredCursor->toArray();

        if (empty($featuredBooks)) {
            $featuredBooks = $db->books->find([], ['limit' => 12])->toArray();
        }

        // Fetch category aggregations
        $categoryCursor = $db->books->aggregate([
            ['$group' => ['_id' => '$category', 'count' => ['$sum' => 1]]],
            ['$sort' => ['count' => -1]],
            ['$limit' => 8]
        ]);
        $categoryStats = $categoryCursor->toArray();

        // Fetch featured poetry (Alfaaz)
        $featuredPoetry = $db->poetry->find(
            ['featured' => true],
            ['limit' => 3, 'sort' => ['created_at' => -1]]
        )->toArray();

        // Sher of the Day
        $sherOfTheDay = $db->poetry->findOne([
            'poet_name' => 'Jaun Elia',
            'title' => new MongoDB\BSON\Regex('Tum Jab Aaogi', 'i')
        ]) ?? ($featuredPoetry[0] ?? null);

        // Fetch 6 featured poets
        $featuredPoets = $db->poets->find(
            [],
            ['limit' => 6, 'sort' => ['created_at' => 1]]
        )->toArray();

        // Fetch mood collections
        $moodCollections = $db->collections->find(
            [],
            ['limit' => 8, 'sort' => ['name' => 1]]
        )->toArray();
    }
} catch (Exception $e) {
    // Graceful fallback
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>

<!-- HERO -->
<section class="relative min-h-[90vh] flex items-center overflow-hidden border-b border-white/10">
    <div class="absolute top-[15%] left-[35%] w-[550px] h-[550px] bg-blood/10 blur-[160px] rounded-full pointer-events-none animate-pulse"></div>

    <div class="max-w-[1500px] mx-auto px-6 md:px-10 w-full py-20">
        <div class="grid lg:grid-cols-[1fr_420px] gap-12 items-center">

            <div class="relative z-10 reveal">
                <div class="mono text-[10px] tracking-[0.4em] text-blood mb-8 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blood animate-ping"></span>
                    <span>MODERN BOOKSTORE & LITERARY MEHFIL / 2026</span>
                </div>

                <h1 class="text-6xl md:text-8xl lg:text-[10rem] font-bold leading-[0.85] tracking-[-0.07em]">
                    STORIES<br>
                    <span class="text-transparent" style="-webkit-text-stroke: 1px rgba(255,255,255,0.4);">THAT</span><br>
                    <span class="text-blood">BITE.</span>
                </h1>

                <div class="mt-12 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <p class="max-w-md text-gray-400 leading-relaxed text-sm">
                        Books aren't decoration. They're escape routes, weapons, obsessions, and entire universes. Dive into <?= $totalBooksCount ?>+ contemporary titles and <?= $totalPoetryCount ?>+ immortal Hindi/Urdu literary archives.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="<?= baseUrl('books.php') ?>" class="danger-button px-8 py-4 text-xs font-bold tracking-widest inline-flex items-center gap-3">
                            <span>EXPLORE BOOKSTORE (<?= $totalBooksCount ?>+)</span>
                            <span>→</span>
                        </a>
                        <a href="<?= baseUrl('poetry/index.php') ?>" class="border border-gold/50 bg-gold/5 text-gold hover:bg-gold hover:text-black px-6 py-4 text-xs mono tracking-wider transition duration-300">
                            ENTER MEHFIL (<?= $totalPoetryCount ?>+) ✦
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3D HERO BOOK VISUAL WITH FLOAT ANIMATION -->
            <div class="relative hidden lg:flex justify-center items-center min-h-[500px]">
                <div class="absolute w-[320px] h-[450px] border border-blood/20 rotate-12 transition-transform duration-700 hover:rotate-6"></div>
                <div class="absolute w-[320px] h-[450px] border border-white/10 -rotate-6 transition-transform duration-700 hover:-rotate-12"></div>

                <div class="hero-book-float book-cover relative w-[280px] h-[400px] bg-gradient-to-br from-[#260812] via-[#1a050c] to-black border border-blood/50 p-8 flex flex-col justify-between shadow-2xl">
                    <div class="mono text-[9px] text-gold tracking-[0.3em] uppercase">
                        DAASTAAN EDITION • MEHFIL
                    </div>

                    <div>
                        <div class="text-4xl font-bold leading-none text-white">THE</div>
                        <div class="text-6xl font-bold text-blood leading-none">DARK</div>
                        <div class="text-4xl font-bold leading-none text-white">PAGE</div>
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="mono text-[8px] text-gray-400">JAUN • GHALIB • DINKAR • FAIZ</div>
                        <div class="w-10 h-10 border border-blood rounded-full flex items-center justify-center text-blood text-xs font-bold">
                            ↓
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- MARQUEE TICKER -->
<div class="overflow-hidden border-b border-white/10 py-5 bg-[#080808] whitespace-nowrap">
    <div class="inline-flex animate-marquee mono text-xs tracking-[0.35em] text-gray-400">
        <span class="mx-8">JAUN ELIA</span><span class="text-blood">✦</span>
        <span class="mx-8">MIRZA GHALIB</span><span class="text-gold">✦</span>
        <span class="mx-8">HARIVANSH RAI BACHCHAN</span><span class="text-blood">✦</span>
        <span class="mx-8">ALLAMA IQBAL</span><span class="text-gold">✦</span>
        <span class="mx-8">DUSHYANT KUMAR</span><span class="text-blood">✦</span>
        <span class="mx-8">FAIZ AHMED FAIZ</span><span class="text-gold">✦</span>
        <span class="mx-8">RAMDHARI SINGH DINKAR</span><span class="text-blood">✦</span>
        <span class="mx-8">SAHIR LUDHIANVI</span><span class="text-gold">✦</span>
        <span class="mx-8">PARVEEN SHAKIR</span><span class="text-blood">✦</span>
        <span class="mx-8">AHMAD FARAZ</span><span class="text-gold">✦</span>
        <span class="mx-8">MUNSHI PREMCHAND</span><span class="text-blood">✦</span>
        <span class="mx-8">VINOD KUMAR SHUKLA</span><span class="text-gold">✦</span>
    </div>
</div>

<!-- SHER OF THE DAY SPOTLIGHT (ALFAAZ-E-KHAS) -->
<?php if ($sherOfTheDay): ?>
<section class="py-12 border-b border-white/10 bg-gradient-to-r from-[#17050b] via-[#090909] to-[#120817]">
    <div class="max-w-[1500px] mx-auto px-6 md:px-10">
        <div class="border border-gold/30 bg-black/60 p-6 md:p-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8 relative overflow-hidden backdrop-blur-md">
            
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-gold/5 blur-3xl pointer-events-none rounded-full"></div>

            <div class="max-w-3xl">
                <div class="flex items-center gap-3 mb-3">
                    <span class="mono text-[10px] text-gold font-bold tracking-[0.3em] uppercase flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gold animate-ping"></span>
                        <span>ALFAAZ-E-KHAS • DAILY RECITATION SPOTLIGHT</span>
                    </span>
                    <span class="text-white/20">•</span>
                    <span class="mono text-[10px] text-gray-400 uppercase"><?= htmlspecialchars($sherOfTheDay['poet_name']) ?> (<?= htmlspecialchars($sherOfTheDay['language'] ?? 'Urdu') ?>)</span>
                </div>

                <div class="border-l-2 border-gold pl-5 py-2 my-4">
                    <p class="text-xl md:text-2xl font-serif-literary text-ivory leading-relaxed whitespace-pre-line">
<?= htmlspecialchars($sherOfTheDay['text_roman']) ?>
                    </p>
                </div>

                <p class="text-xs text-gray-400 italic mt-2">
                    "<?= htmlspecialchars($sherOfTheDay['meaning_en'] ?? '') ?>"
                </p>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 flex-shrink-0 w-full lg:w-auto">
                <a href="<?= baseUrl('poetry/view.php?id=' . (string)$sherOfTheDay['_id']) ?>" 
                   class="border border-gold bg-gold/15 text-gold hover:bg-gold hover:text-black px-6 py-3.5 text-xs mono tracking-wider transition text-center font-bold">
                    READ FULL GHAZAL →
                </a>
                <a href="<?= baseUrl('poetry/index.php') ?>" 
                   class="border border-white/20 hover:border-white px-6 py-3 text-xs mono text-gray-300 hover:text-white transition text-center">
                    MEHFIL ARCHIVE (<?= $totalPoetryCount ?> VERSES) ✦
                </a>
            </div>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- SECTION 01: FEATURED BOOKS ON THE SHELF -->
<section class="py-24 max-w-[1500px] mx-auto px-6 md:px-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-6">
        <div>
            <div class="mono text-[10px] text-blood tracking-[0.3em] mb-3">01 / CURATED SELECTION • <?= $totalBooksCount ?>+ TITLES</div>
            <h2 class="text-4xl md:text-6xl font-bold tracking-tight">THE SHELF.</h2>
        </div>
        <a href="<?= baseUrl('books.php') ?>" class="mono text-xs text-gray-400 hover:text-white transition flex items-center gap-2">
            VIEW ALL INVENTORY IN STORE (<?= $totalBooksCount ?> BOOKS) →
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($featuredBooks as $index => $book): ?>
            <?php 
                $bookId = (string)$book['_id'];
                $coverBg = $book['cover_bg'] ?? 'from-[#241018] to-[#090909]';
                $stock = (int)($book['stock'] ?? 0);
            ?>
            <article class="book-card p-6 flex flex-col justify-between group">
                <div class="h-[360px] flex items-center justify-center relative">
                    <span class="absolute top-2 left-2 mono text-[9px] text-gray-600">
                        #<?= str_pad((string)($index + 1), 2, "0", STR_PAD_LEFT) ?>
                    </span>

                    <div class="book-cover w-[180px] h-[260px] bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> border border-white/10 p-5 flex flex-col justify-between shadow-2xl">
                        <span class="mono text-[8px] text-blood tracking-widest uppercase">
                            <?= htmlspecialchars($book['category'] ?? 'EDITION') ?>
                        </span>
                        <div>
                            <h3 class="text-xl font-bold leading-tight text-white group-hover:text-gold transition">
                                <?= htmlspecialchars($book['title']) ?>
                            </h3>
                        </div>
                        <span class="text-[9px] text-gray-400">
                            <?= htmlspecialchars($book['author']) ?>
                        </span>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/5">
                    <div class="flex justify-between items-center text-[10px] mono">
                        <span class="text-blood tracking-widest uppercase font-semibold"><?= htmlspecialchars($book['category'] ?? 'UNCATEGORIZED') ?></span>
                        <span class="text-yellow-500 font-bold">★ <?= htmlspecialchars((string)($book['rating'] ?? 5.0)) ?></span>
                    </div>

                    <h3 class="mt-2 font-bold text-base line-clamp-1 group-hover:text-blood transition text-white">
                        <?= htmlspecialchars($book['title']) ?>
                    </h3>
                    <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($book['author']) ?></p>

                    <div class="mt-5 flex justify-between items-center">
                        <div>
                            <span class="text-base font-bold text-white font-mono">₹<?= htmlspecialchars((string)$book['price']) ?></span>
                            <div class="mono text-[9px] <?= $stock > 0 ? 'text-emerald-400' : 'text-blood' ?>">
                                <?= $stock > 0 ? "IN STOCK ({$stock})" : "SOLD OUT" ?>
                            </div>
                        </div>
                        <a href="<?= baseUrl('book-details.php?id=' . $bookId) ?>" class="text-xs mono border border-white/15 px-3 py-1.5 hover:bg-white hover:text-black transition">
                            EXPLORE →
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- SECTION 02: ALFAAZ — DIGITAL LITERARY MEHFIL -->
<section class="border-y border-white/10 bg-[#070707] py-28 relative overflow-hidden">
    <div class="absolute -top-40 right-10 w-96 h-96 bg-gold/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-[1500px] mx-auto px-6 md:px-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-6">
            <div>
                <div class="mono text-[10px] text-gold tracking-[0.4em] mb-3 flex items-center gap-2">
                    <span>02 / ALFAAZ</span>
                    <span class="text-white/20">•</span>
                    <span>DIGITAL MEHFIL</span>
                </div>
                <h2 class="text-4xl md:text-7xl font-bold tracking-tight text-white">
                    SOME WORDS ARE READ.<br>
                    <span class="text-gold">SOME WORDS ARE FELT.</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-4 max-w-xl leading-relaxed">
                    Step into our archive of Roman Hindi & Urdu poetry, Ghazals, and philosophical couplets. Read them in the silence of your midnight.
                </p>
            </div>

            <a href="<?= baseUrl('poetry/index.php') ?>" class="border border-gold/40 bg-gold/10 text-gold px-6 py-3 text-xs mono tracking-wider hover:bg-gold hover:text-black transition duration-300">
                VIEW COMPLETE POETRY ARCHIVE →
            </a>
        </div>

        <!-- 3 FEATURED POETRY CARDS IN ROMAN SCRIPT -->
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($featuredPoetry as $pItem): ?>
                <?php 
                    $pId = (string)$pItem['_id'];
                    $pMood = $pItem['mood'] ?? 'Tanhai';
                    $pLang = $pItem['language'] ?? 'Urdu';
                ?>
                <article class="border border-white/10 bg-[#090909] p-8 flex flex-col justify-between hover:border-gold/50 transition duration-300 group relative">
                    <div>
                        <div class="flex justify-between items-center text-[10px] mono mb-4">
                            <span class="text-gold font-bold uppercase tracking-widest"><?= htmlspecialchars($pItem['poet_name']) ?></span>
                            <span class="border border-gold/30 bg-gold/5 px-2 py-0.5 text-gold uppercase tracking-wider text-[9px]">
                                <?= htmlspecialchars($pMood) ?>
                            </span>
                        </div>

                        <h3 class="text-xl font-bold text-white group-hover:text-gold transition mb-4">
                            <?= htmlspecialchars($pItem['title']) ?>
                        </h3>

                        <div class="p-5 bg-black/70 border-l-2 border-blood my-4">
                            <p class="text-sm font-serif-literary text-ivory leading-relaxed whitespace-pre-line">
<?= htmlspecialchars($pItem['text_roman']) ?>
                            </p>
                        </div>

                        <?php if (!empty($pItem['meaning_en'])): ?>
                            <p class="text-xs text-gray-400 leading-relaxed italic line-clamp-2 mt-3">
                                <?= htmlspecialchars($pItem['meaning_en']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-8 pt-4 border-t border-white/5 flex justify-between items-center text-xs">
                        <span class="mono text-[10px] text-gray-500"><?= htmlspecialchars($pItem['genre'] ?? 'Ghazal') ?></span>
                        <a href="<?= baseUrl('poetry/view.php?id=' . $pId) ?>" class="mono text-xs text-gold hover:text-white inline-flex items-center gap-1 font-bold group-hover:translate-x-1 transition duration-200">
                            <span>READ FULL →</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- SECTION 03: FEATURED POETS & AUTHORS (USTAD-E-SUKHAN) -->
<section class="py-28 max-w-[1500px] mx-auto px-6 md:px-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-6">
        <div>
            <div class="mono text-[10px] text-gold tracking-[0.3em] mb-3">03 / USTAD-E-SUKHAN</div>
            <h2 class="text-4xl md:text-6xl font-bold tracking-tight text-white">THE MASTER VOICES.</h2>
            <p class="text-xs text-gray-400 mt-2 max-w-lg">
                The philosophers, rebels, and lovers of verse who forged modern South Asian literary consciousness.
            </p>
        </div>

        <a href="<?= baseUrl('poets/index.php') ?>" class="mono text-xs text-gold hover:text-white transition flex items-center gap-2">
            EXPLORE ALL <?= $totalPoetsCount ?> POETS & AUTHORS →
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($featuredPoets as $poet): ?>
            <?php 
                $slug = $poet['slug'] ?? '';
                $pCover = $poet['cover_bg'] ?? 'from-[#1a0a14] to-[#0a0a0a]';
                $lang = $poet['language'] ?? 'Urdu';
            ?>
            <article class="border border-white/10 bg-[#080808] p-7 flex flex-col justify-between hover:border-gold/50 transition duration-300 group">
                <div>
                    <div class="flex justify-between items-center text-[10px] mono mb-4">
                        <span class="border px-2 py-0.5 uppercase tracking-wider <?= $lang === 'Urdu' ? 'border-blood/40 text-blood' : 'border-gold/40 text-gold' ?>">
                            <?= htmlspecialchars($lang) ?>
                        </span>
                        <span class="text-gray-500"><?= htmlspecialchars($poet['era'] ?? '') ?></span>
                    </div>

                    <h3 class="text-2xl font-bold text-white group-hover:text-gold transition">
                        <?= htmlspecialchars($poet['name']) ?>
                    </h3>

                    <div class="mono text-[10px] text-gray-400 mt-2 line-clamp-1 italic border-l border-gold/40 pl-2">
                        <?= htmlspecialchars($poet['literary_style'] ?? '') ?>
                    </div>

                    <p class="text-xs text-gray-400 mt-4 leading-relaxed line-clamp-3">
                        <?= htmlspecialchars($poet['biography'] ?? '') ?>
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-white/5 flex justify-between items-center">
                    <span class="mono text-[10px] text-gray-500"><?= htmlspecialchars($poet['birthplace'] ?? '') ?></span>
                    <a href="<?= baseUrl('poets/profile.php?slug=' . urlencode($slug)) ?>" class="mono text-xs text-gold hover:text-white font-bold inline-flex items-center gap-1 group-hover:translate-x-1 transition duration-200">
                        <span>PROFILE →</span>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- SECTION 03.5: LITERARY MOVEMENTS & ERAS (TAREEKH-E-SUKHAN) -->
<section class="border-t border-white/10 bg-[#060606] py-24">
    <div class="max-w-[1500px] mx-auto px-6 md:px-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-6">
            <div>
                <div class="mono text-[10px] text-gold tracking-[0.3em] mb-3 flex items-center gap-2">
                    <span>TAREEKH-E-SUKHAN</span>
                    <span class="text-white/20">•</span>
                    <span>LITERARY CHRONOLOGY</span>
                </div>
                <h2 class="text-4xl md:text-6xl font-bold tracking-tight text-white">MOVEMENTS THAT SHAPED CONSCIENCE.</h2>
                <p class="text-xs text-gray-400 mt-2 max-w-xl">
                    From the fading courtyards of Mughal Delhi to revolutionary anti-colonial anthems and modern existential solitude.
                </p>
            </div>

            <a href="<?= baseUrl('poets/index.php') ?>" class="mono text-xs text-gold hover:text-white transition flex items-center gap-2">
                ALL <?= $totalPoetsCount ?> MASTER AUTHORS & POETS →
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="border border-white/10 bg-[#080808] p-7 hover:border-gold/50 transition group">
                <span class="mono text-[9px] text-gold tracking-widest uppercase block mb-3">ERA 01 • 1700s – 1860s</span>
                <h3 class="text-xl font-bold text-white group-hover:text-gold transition">THE MUGHAL COURT TWILIGHT</h3>
                <div class="mono text-[10px] text-gray-500 mt-1">Delhi & Lucknow Traditions</div>
                <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                    Witnessing the sunset of the Mughal era. Metaphysical complexity, paradox, philosophical grief, and linguistic perfection.
                </p>
                <div class="mt-6 pt-4 border-t border-white/5 mono text-[10px] text-blood">
                    KEY VOICES: GHALIB • MIR TAQI MIR • ZAFAR
                </div>
            </div>

            <div class="border border-white/10 bg-[#080808] p-7 hover:border-gold/50 transition group">
                <span class="mono text-[9px] text-cyan-400 tracking-widest uppercase block mb-3">ERA 02 • 1910s – 1930s</span>
                <h3 class="text-xl font-bold text-white group-hover:text-cyan-400 transition">CHHAYAVAD & RENAISSANCE</h3>
                <div class="mono text-[10px] text-gray-500 mt-1">Hindi Romanticist & Vedic Revival</div>
                <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                    The golden era of lyrical romanticism and cosmic mysticism in modern Hindi. Elevating human nature, emotion, and philosophical inquiry.
                </p>
                <div class="mt-6 pt-4 border-t border-white/5 mono text-[10px] text-cyan-400">
                    KEY VOICES: PRASAD • NIRALA • MAHADEVI VARMA
                </div>
            </div>

            <div class="border border-white/10 bg-[#080808] p-7 hover:border-gold/50 transition group">
                <span class="mono text-[9px] text-blood tracking-widest uppercase block mb-3">ERA 03 • 1930s – 1960s</span>
                <h3 class="text-xl font-bold text-white group-hover:text-blood transition">PROGRESSIVE WRITERS</h3>
                <div class="mono text-[10px] text-gray-500 mt-1">Anti-Colonial Resistance & Realism</div>
                <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                    Art as the sword of truth. Discarding decorative court romance to articulate peasant struggles, workers’ freedom, and social dignity.
                </p>
                <div class="mt-6 pt-4 border-t border-white/5 mono text-[10px] text-blood">
                    KEY VOICES: FAIZ • SAHIR • DINKAR • PREMCHAND
                </div>
            </div>

            <div class="border border-white/10 bg-[#080808] p-7 hover:border-gold/50 transition group">
                <span class="mono text-[9px] text-purple-400 tracking-widest uppercase block mb-3">ERA 04 • 1960s – PRESENT</span>
                <h3 class="text-xl font-bold text-white group-hover:text-purple-400 transition">MODERNIST DISILLUSIONMENT</h3>
                <div class="mono text-[10px] text-gray-500 mt-1">Existential Ghazal & Free Verse</div>
                <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                    Shattered political ideals, alienation, raw personal vulnerability, and urban angst confronting post-colonial disillusionment.
                </p>
                <div class="mt-6 pt-4 border-t border-white/5 mono text-[10px] text-purple-400">
                    KEY VOICES: JAUN ELIA • DUSHYANT • FARAZ • SHAKIR
                </div>
            </div>

        </div>

    </div>
</section>

<!-- SECTION 04: EXPLORE BY MOOD (EHSAAS COLLECTIONS) -->
<section class="border-t border-white/10 bg-[#060606] py-28">
    <div class="max-w-[1500px] mx-auto px-6 md:px-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 gap-6">
            <div>
                <div class="mono text-[10px] text-blood tracking-[0.3em] mb-3">04 / EHSAAS</div>
                <h2 class="text-4xl md:text-6xl font-bold tracking-tight text-white">EXPLORE BY MOOD.</h2>
                <p class="text-xs text-gray-400 mt-2 max-w-lg">
                    Collections sculpted around human emotions—from solitary midnight reflections to sacred love.
                </p>
            </div>

            <a href="<?= baseUrl('collections/index.php') ?>" class="mono text-xs text-gray-400 hover:text-white transition flex items-center gap-2">
                ALL 8 EMOTIONAL SUITES →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($moodCollections as $col): ?>
                <?php 
                    $accent = $col['color_accent'] ?? '#c5a059';
                    $bgGrad = $col['bg_gradient'] ?? 'from-[#141414] to-[#070707]';
                ?>
                <a href="<?= baseUrl('collections/view.php?mood=' . urlencode($col['mood'])) ?>"
                   class="border border-white/10 bg-gradient-to-br <?= htmlspecialchars($bgGrad) ?> p-6 hover:border-white/40 transition duration-300 group block relative overflow-hidden">
                    
                    <div class="flex justify-between items-center mb-3">
                        <span class="w-2 h-2 rounded-full" style="background-color: <?= $accent ?>;"></span>
                        <span class="mono text-[9px] text-gray-500 group-hover:text-white transition">EXPLORE →</span>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-white group-hover:text-gold transition">
                        <?= strtoupper(htmlspecialchars($col['name'])) ?>
                    </h3>
                    <p class="mono text-[10px] text-gray-400 mt-1 line-clamp-1">
                        <?= htmlspecialchars($col['tagline'] ?? '') ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- SECTION 05: CATEGORIES (GENRES) -->
<section id="categories" class="border-y border-white/10 bg-[#070707] py-28">
    <div class="max-w-[1500px] mx-auto px-6 md:px-10">
        <div class="grid lg:grid-cols-[350px_1fr] gap-16">
            <div>
                <div class="mono text-[10px] text-blood tracking-[0.3em] mb-4">05 / TAXONOMY</div>
                <h2 class="text-5xl md:text-6xl font-bold tracking-tight">FIND<br>YOUR<br>GENRE.</h2>
                <p class="text-xs text-gray-400 mt-6 leading-relaxed">
                    Filter our collection across intellectual genres—curated across classical Urdu & Hindi poetry, existential philosophy, technology, and speculative fiction.
                </p>
                <div class="mt-8">
                    <a href="<?= baseUrl('genres.php') ?>" class="mono text-xs border border-white/20 px-5 py-3 hover:border-blood hover:text-white transition inline-block">
                        EXPLORE FULL GENRE GUIDE →
                    </a>
                </div>
            </div>

            <div class="divide-y divide-white/10">
                <?php if (!empty($categoryStats)): ?>
                    <?php foreach ($categoryStats as $cat): ?>
                        <a href="<?= baseUrl('books.php?category=' . urlencode($cat['_id'])) ?>" class="flex items-center justify-between py-6 group hover:pl-4 transition-all duration-300">
                            <span class="text-2xl md:text-3xl font-bold text-white group-hover:text-blood transition">
                                <?= htmlspecialchars($cat['_id']) ?>
                            </span>
                            <span class="mono text-xs text-gray-400 group-hover:text-white transition">
                                <?= $cat['count'] ?> BOOKS →
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-32 max-w-[1500px] mx-auto px-6 md:px-10">
    <div class="relative overflow-hidden border border-blood/30 bg-[#0d0407] p-10 md:p-20 shadow-2xl">
        <div class="relative z-10">
            <span class="mono text-[10px] tracking-[0.4em] text-blood block mb-4">BOOKS YOU CAN BUY. WORDS YOU CAN FEEL.</span>
            <h2 class="text-4xl md:text-7xl font-bold tracking-tight leading-tight max-w-4xl text-white">
                GET LOST IN<br>SOMETHING <span class="text-blood">WORTH REMEMBERING.</span>
            </h2>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="<?= baseUrl('books.php') ?>" class="danger-button px-8 py-4 text-xs font-bold tracking-widest">
                    EXPLORE BOOK CATALOG →
                </a>
                <a href="<?= baseUrl('poetry/index.php') ?>" class="border border-gold text-gold hover:bg-gold hover:text-black px-8 py-4 text-xs mono tracking-widest transition duration-300">
                    OPEN LITERARY MEHFIL ✦
                </a>
            </div>
        </div>
    </div>
</section>

</main>

<style>
@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}
.animate-marquee {
    display: inline-flex;
    animation: marquee 30s linear infinite;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>