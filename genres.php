<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = "Genres & Taxonomy — DAASTAAN. Literary Archive & Bookstore";

$categories = [];
$genreData = [];

// Curated metadata for known genre categories
$genreMeta = [
    'Urdu Poetry' => [
        'badge' => 'MEHFIL CLASSIC ✦',
        'tagline' => 'Immortal Ghazals, Nazms & Philosophical Couplets',
        'description' => 'The evocative world of Rekhta and classical Urdu masters—Mirza Ghalib, Jaun Elia, Faiz Ahmed Faiz, Allama Iqbal, and Parveen Shakir. Verses exploring the depths of love, existential rebellion, melancholy, and divine madness.',
        'accent' => '#ff1744',
        'bg' => 'from-[#2b0914] via-[#150409] to-[#070707]'
    ],
    'Hindi Literature' => [
        'badge' => 'LITERARY HERITAGE ✦',
        'tagline' => 'Chhayavad, Modern Realism & Revolutionary Kavita',
        'description' => 'From the lyrical cadence of Harivansh Rai Bachchan’s Madhushala to the raging heroic verse of Ramdhari Singh Dinkar’s Rashmirathi and the sharp political gazals of Dushyant Kumar. Literature rooted in the soil, soul, and conscience of India.',
        'accent' => '#c5a059',
        'bg' => 'from-[#2a1d08] via-[#160f04] to-[#070707]'
    ],
    'Philosophy' => [
        'badge' => 'DEEP INQUIRY ✦',
        'tagline' => 'Existentialism, Stoicism & Epistemological Treatises',
        'description' => 'Fundamental inquiries into human existence, morality, consciousness, and truth. From Camus and Nietzsche to Aurelius, Spinoza, and contemporary ontology.',
        'accent' => '#a855f7',
        'bg' => 'from-[#1e0a2e] via-[#100419] to-[#070707]'
    ],
    'Technology' => [
        'badge' => 'SYSTEMS ARCHITECTURE ✦',
        'tagline' => 'Software Craftsmanship, Distributed Systems & Computing Lore',
        'description' => 'Mastery over the machines. High-impact volumes on systems architecture, UNIX philosophy, algorithmic thinking, software craftsmanship, and the evolution of digital infrastructure.',
        'accent' => '#06b6d4',
        'bg' => 'from-[#05222b] via-[#031116] to-[#070707]'
    ],
    'Speculative Fiction' => [
        'badge' => 'FUTURE HORIZONS ✦',
        'tagline' => 'Cyberpunk, Dystopias & Deep-Time Realities',
        'description' => 'Stories that dissect tomorrow. Cyberpunk grit, artificial consciousness, authoritarian dystopias, and mind-bending space operatics by visionary authors.',
        'accent' => '#f97316',
        'bg' => 'from-[#2b1405] via-[#160a03] to-[#070707]'
    ],
    'Psychology' => [
        'badge' => 'HUMAN BEHAVIOR ✦',
        'tagline' => 'Cognition, Irrationality & The Subconscious Mind',
        'description' => 'Unlocking why we think, deceive, perceive, and decide. Empirical studies in behavioral economics, mental models, emotional intelligence, and cognitive biases.',
        'accent' => '#10b981',
        'bg' => 'from-[#052618] via-[#03140d] to-[#070707]'
    ],
    'Classics' => [
        'badge' => 'TIMELESS CANON ✦',
        'tagline' => 'Centuries of Immortal World Literature',
        'description' => 'Masterpieces that have outlasted empires, shifts in culture, and centuries of criticism. The bedrock of world narrative storytelling and philosophical drama.',
        'accent' => '#ec4899',
        'bg' => 'from-[#2b0821] via-[#160411] to-[#070707]'
    ]
];

try {
    if ($dbConnected) {
        $rawCategories = $db->books->distinct('category');
        sort($rawCategories);

        foreach ($rawCategories as $cat) {
            $count = $db->books->countDocuments(['category' => $cat]);
            $sampleBooks = $db->books->find(
                ['category' => $cat],
                ['limit' => 3, 'sort' => ['rating' => -1, 'created_at' => -1]]
            )->toArray();

            $meta = $genreMeta[$cat] ?? [
                'badge' => 'CURATED ARCHIVE',
                'tagline' => 'Curated collection in ' . htmlspecialchars($cat),
                'description' => 'Explore exceptional published works and critical volumes in ' . htmlspecialchars($cat) . '.',
                'accent' => '#c5a059',
                'bg' => 'from-[#141414] via-[#0d0d0d] to-[#070707]'
            ];

            $genreData[] = [
                'name' => $cat,
                'count' => $count,
                'books' => $sampleBooks,
                'meta' => $meta
            ];
        }
    }
} catch (Exception $e) {
    setFlash('error', 'Error fetching taxonomy: ' . $e->getMessage());
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="mb-14">
        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-3 flex items-center gap-2">
            <span>05 / TAXONOMY & GENRES</span>
            <span class="text-white/20">•</span>
            <span>THE LITERARY SPECTRUM</span>
        </div>
        <h1 class="text-5xl md:text-8xl font-bold tracking-tight leading-tight">
            BOOK <span class="text-transparent" style="-webkit-text-stroke: 1px rgba(255,255,255,0.4);">GENRES.</span>
        </h1>
        <p class="mt-4 text-gray-400 text-sm max-w-2xl leading-relaxed">
            Every genre is an intellectual domain. From the philosophical fires of Urdu and Hindi poetry to systems engineering, speculative futures, and classical human thought.
        </p>
    </div>

    <!-- QUICK JUMP PILLS -->
    <div class="sticky top-20 z-30 bg-[#050505]/95 backdrop-blur-md py-4 border-y border-white/10 mb-16 -mx-6 px-6 md:-mx-10 md:px-10">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest mr-2 flex-shrink-0">JUMP TO GENRE:</span>
            <?php foreach ($genreData as $g): ?>
                <a href="#genre-<?= md5($g['name']) ?>"
                   class="mono text-xs px-3.5 py-1.5 border border-white/10 hover:border-blood text-gray-400 hover:text-white transition whitespace-nowrap bg-black/40">
                    <?= strtoupper(htmlspecialchars($g['name'])) ?> (<?= $g['count'] ?>)
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- GENRE SHOWCASE CARDS -->
    <div class="space-y-16">
        <?php foreach ($genreData as $index => $g): ?>
            <?php 
                $meta = $g['meta'];
                $accent = $meta['accent'];
                $bgGrad = $meta['bg'];
                $genreAnchor = 'genre-' . md5($g['name']);
            ?>
            <section id="<?= $genreAnchor ?>" class="border border-white/10 bg-gradient-to-br <?= htmlspecialchars($bgGrad) ?> p-8 md:p-14 relative overflow-hidden transition-all duration-300 hover:border-white/30 scroll-mt-36 shadow-2xl">
                
                <!-- Watermark Number -->
                <div class="absolute -right-6 -bottom-10 text-9xl font-bold text-white/[0.03] select-none pointer-events-none">
                    #<?= str_pad((string)($index + 1), 2, "0", STR_PAD_LEFT) ?>
                </div>

                <div class="grid lg:grid-cols-[1.1fr_1fr] gap-12 items-center relative z-10">
                    
                    <!-- Left: Metadata & Context -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="mono text-[10px] font-bold tracking-widest uppercase border px-3 py-1"
                                  style="border-color: <?= $accent ?>50; color: <?= $accent ?>; background: <?= $accent ?>15;">
                                <?= htmlspecialchars($meta['badge']) ?>
                            </span>
                            <span class="mono text-xs text-gray-400">
                                <?= $g['count'] ?> <?= $g['count'] === 1 ? 'Volume' : 'Volumes' ?> in Catalog
                            </span>
                        </div>

                        <h2 class="text-4xl md:text-6xl font-bold tracking-tight text-white mb-2">
                            <?= htmlspecialchars($g['name']) ?>
                        </h2>

                        <div class="mono text-sm text-gold font-semibold mb-6">
                            <?= htmlspecialchars($meta['tagline']) ?>
                        </div>

                        <p class="text-sm text-gray-300 leading-relaxed mb-8 max-w-xl">
                            <?= htmlspecialchars($meta['description']) ?>
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <a href="<?= baseUrl('books.php?category=' . urlencode($g['name'])) ?>"
                               class="danger-button px-7 py-3.5 text-xs font-bold tracking-widest inline-flex items-center gap-2">
                                <span>VIEW ALL <?= $g['count'] ?> BOOKS IN <?= strtoupper(htmlspecialchars($g['name'])) ?></span>
                                <span>→</span>
                            </a>
                            
                            <?php if ($g['name'] === 'Urdu Poetry' || $g['name'] === 'Hindi Literature'): ?>
                                <a href="<?= baseUrl('poetry/index.php?lang=' . ($g['name'] === 'Urdu Poetry' ? 'Urdu' : 'Hindi')) ?>"
                                   class="border border-gold/50 bg-gold/10 text-gold hover:bg-gold hover:text-black px-6 py-3.5 text-xs mono tracking-wider transition duration-300">
                                    READ VERSES IN MEHFIL ✦
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right: Featured Sample Titles in this Genre -->
                    <div>
                        <span class="mono text-[10px] text-gray-400 uppercase tracking-widest block mb-4">NOTABLE EDITIONS IN THIS GENRE:</span>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <?php foreach ($g['books'] as $sampleBook): ?>
                                <?php 
                                    $sId = (string)$sampleBook['_id'];
                                    $sCoverBg = $sampleBook['cover_bg'] ?? 'from-[#241018] to-[#090909]';
                                    $sStock = (int)($sampleBook['stock'] ?? 0);
                                ?>
                                <article class="border border-white/10 bg-black/60 p-4 flex flex-col justify-between hover:border-gold transition duration-300 group">
                                    <div>
                                        <div class="h-32 bg-gradient-to-br <?= htmlspecialchars($sCoverBg) ?> border border-white/10 p-3 flex flex-col justify-between mb-3 shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            <span class="mono text-[7px] text-blood line-clamp-1 uppercase font-bold"><?= htmlspecialchars($g['name']) ?></span>
                                            <h4 class="text-xs font-bold leading-tight line-clamp-2 text-white"><?= htmlspecialchars($sampleBook['title']) ?></h4>
                                            <span class="text-[8px] text-gray-400 line-clamp-1"><?= htmlspecialchars($sampleBook['author']) ?></span>
                                        </div>

                                        <h3 class="text-xs font-bold text-white line-clamp-1 group-hover:text-gold transition">
                                            <?= htmlspecialchars($sampleBook['title']) ?>
                                        </h3>
                                        <p class="text-[10px] text-gray-500 truncate mt-0.5"><?= htmlspecialchars($sampleBook['author']) ?></p>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-xs">
                                        <span class="font-mono font-bold text-white">₹<?= htmlspecialchars((string)$sampleBook['price']) ?></span>
                                        <a href="<?= baseUrl('book-details.php?id=' . $sId) ?>" class="mono text-[10px] text-gold hover:text-white underline">
                                            VIEW →
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

            </section>
        <?php endforeach; ?>
    </div>

    <!-- BOTTOM DISCOVERY BANNER -->
    <section class="mt-24 p-10 md:p-16 border border-white/10 bg-[#090909] text-center">
        <span class="mono text-[10px] text-blood tracking-[0.3em] uppercase block mb-3">COMPLETE STORE COVERAGE</span>
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight">LOOKING FOR A SPECIFIC TITLE?</h2>
        <p class="text-xs text-gray-400 mt-3 max-w-md mx-auto">
            Search our full inventory by keywords, author names, or ISBN codes with instant matching.
        </p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="<?= baseUrl('books.php') ?>" class="danger-button px-8 py-4 text-xs font-bold tracking-widest">
                BROWSE FULL CATALOG →
            </a>
            <a href="<?= baseUrl('collections/index.php') ?>" class="border border-gold text-gold hover:bg-gold hover:text-black px-8 py-4 text-xs mono tracking-wider transition">
                EXPLORE MOOD SUITES ✦
            </a>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
