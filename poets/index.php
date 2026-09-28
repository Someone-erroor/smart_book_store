<?php
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = "Poets & Authors — Modern Literary Archive";

$languageFilter = trim($_GET['lang'] ?? 'all');
$search = trim($_GET['search'] ?? '');

$filter = [];
if ($languageFilter !== 'all' && !empty($languageFilter)) {
    $filter['language'] = new MongoDB\BSON\Regex('^' . preg_quote($languageFilter) . '$', 'i');
}

if (!empty($search)) {
    $filter['$or'] = [
        ['name' => new MongoDB\BSON\Regex($search, 'i')],
        ['literary_style' => new MongoDB\BSON\Regex($search, 'i')],
        ['famous_works' => new MongoDB\BSON\Regex($search, 'i')]
    ];
}

$poets = [];
try {
    if ($dbConnected) {
        $poets = $db->poets->find($filter, ['sort' => ['name' => 1]])->toArray();
    }
} catch (Exception $e) {
    setFlash('error', 'Error fetching poets: ' . $e->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="mb-12">
        <div class="mono text-[10px] tracking-[0.4em] text-gold mb-3 flex items-center gap-2">
            <span>USTAAD-E-SUKHAN</span>
            <span class="text-white/20">•</span>
            <span>VOICES OF HINDI & URDU</span>
        </div>
        <h1 class="text-5xl md:text-8xl font-bold tracking-tight leading-tight">
            POETS & <span class="text-transparent" style="-webkit-text-stroke: 1px rgba(197,160,89,0.7);">AUTHORS.</span>
        </h1>
        <p class="mt-4 text-gray-400 text-sm max-w-2xl leading-relaxed">
            From the mystical philosophical verses of Mirza Ghalib to the raw existential angst of Jaun Elia, the fiery verses of Allama Iqbal, and the timeless cadence of Harivansh Rai Bachchan. Explore their lives, verses, and published works.
        </p>

        <!-- STATS RIBBON -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-gold"><?= count($poets) ?></div>
                <div class="mono text-[10px] text-gray-400 uppercase tracking-widest mt-1">Ustads Catalogued</div>
            </div>
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-white">2</div>
                <div class="mono text-[10px] text-blood uppercase tracking-widest mt-1">Poetic Traditions</div>
            </div>
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-white">4</div>
                <div class="mono text-[10px] text-gray-400 uppercase tracking-widest mt-1">Historic Eras</div>
            </div>
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-white">100%</div>
                <div class="mono text-[10px] text-gold uppercase tracking-widest mt-1">Bookstore Linked</div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="mb-12 flex flex-col md:flex-row gap-5 justify-between items-stretch md:items-center border-b border-white/10 pb-8">
        
        <!-- Search Input -->
        <form action="<?= baseUrl('poets/index.php') ?>" method="GET" class="border border-white/15 bg-[#080808] flex items-center px-4 py-3 w-full md:max-w-md focus-within:border-gold transition">
            <input type="hidden" name="lang" value="<?= htmlspecialchars($languageFilter) ?>">
            <span class="mono text-xs text-gold mr-3">/</span>
            <input type="text" name="search" placeholder="Search poets by name, style, or works..."
                   value="<?= htmlspecialchars($search) ?>"
                   class="bg-transparent outline-none w-full text-xs text-white placeholder:text-gray-600">
            <?php if (!empty($search)): ?>
                <a href="<?= baseUrl('poets/index.php?lang=' . urlencode($languageFilter)) ?>" class="mono text-[10px] text-gray-500 hover:text-white ml-2">CLEAR</a>
            <?php endif; ?>
        </form>

        <!-- Language Tabs -->
        <div class="flex gap-2 mono text-xs">
            <a href="<?= baseUrl('poets/index.php?lang=all' . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>"
               class="px-4 py-2.5 border <?= $languageFilter === 'all' ? 'border-gold bg-gold/15 text-gold font-bold' : 'border-white/10 text-gray-400 hover:text-white hover:border-white/30' ?> transition">
                ALL POETS (<?= count($poets) ?>)
            </a>
            <a href="<?= baseUrl('poets/index.php?lang=Urdu' . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>"
               class="px-4 py-2.5 border <?= strcasecmp($languageFilter, 'Urdu') === 0 ? 'border-blood bg-blood/15 text-blood font-bold' : 'border-white/10 text-gray-400 hover:text-white hover:border-white/30' ?> transition">
                URDU TRADITION
            </a>
            <a href="<?= baseUrl('poets/index.php?lang=Hindi' . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>"
               class="px-4 py-2.5 border <?= strcasecmp($languageFilter, 'Hindi') === 0 ? 'border-gold bg-gold/15 text-gold font-bold' : 'border-white/10 text-gray-400 hover:text-white hover:border-white/30' ?> transition">
                HINDI TRADITION
            </a>
        </div>

    </div>

    <!-- POETS GRID -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($poets as $poet): ?>
            <?php 
                $pId = (string)$poet['_id'];
                $slug = $poet['slug'] ?? $pId;
                $lang = $poet['language'] ?? 'Urdu';
                $coverBg = $poet['cover_bg'] ?? 'from-[#1a0a14] to-[#0a0a0a]';
                $accentColor = $poet['accent_color'] ?? '#c5a059';
            ?>
            <article class="border border-white/10 bg-[#080808] p-8 flex flex-col justify-between hover:border-gold/50 transition duration-300 relative group overflow-hidden">
                
                <!-- Ambient Accent Glow -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> blur-3xl opacity-40 pointer-events-none group-hover:opacity-80 transition duration-500"></div>

                <div>
                    <!-- Language & Era Badge -->
                    <div class="flex justify-between items-center text-[10px] mono mb-5">
                        <span class="border px-2.5 py-1 tracking-widest uppercase font-semibold <?= $lang === 'Urdu' ? 'border-blood/40 text-blood bg-blood/5' : 'border-gold/40 text-gold bg-gold/5' ?>">
                            <?= strtoupper(htmlspecialchars($lang)) ?> POETRY
                        </span>
                        <span class="text-gray-500 font-mono"><?= htmlspecialchars($poet['era'] ?? '') ?></span>
                    </div>

                    <!-- Poet Name -->
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-white group-hover:text-gold transition duration-300">
                        <?= htmlspecialchars($poet['name']) ?>
                    </h2>

                    <!-- Literary Style -->
                    <div class="mono text-[11px] text-gray-400 mt-3 italic line-clamp-1 border-l-2 border-gold/40 pl-3">
                        <?= htmlspecialchars($poet['literary_style'] ?? 'Classical & Modern Ghazals') ?>
                    </div>

                    <!-- Biography Excerpt -->
                    <p class="text-xs text-gray-400 mt-5 leading-relaxed line-clamp-3">
                        <?= htmlspecialchars($poet['biography'] ?? '') ?>
                    </p>

                    <!-- Notable Works Pills -->
                    <?php if (!empty($poet['famous_works'])): ?>
                        <div class="mt-6 pt-5 border-t border-white/5">
                            <span class="mono text-[9px] text-gray-500 uppercase tracking-widest block mb-2">NOTABLE ANTHOLOGIES:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <?php foreach (array_slice((array)$poet['famous_works'], 0, 4) as $work): ?>
                                    <span class="mono text-[10px] border border-white/10 bg-black/60 px-2 py-0.5 text-gray-300">
                                        <?= htmlspecialchars($work) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer Action -->
                <div class="mt-8 pt-5 border-t border-white/10 flex justify-between items-center">
                    <span class="mono text-[10px] text-gray-500">
                        <?= htmlspecialchars($poet['birthplace'] ?? '') ?>
                    </span>

                    <a href="<?= baseUrl('poets/profile.php?slug=' . urlencode($slug)) ?>"
                       class="mono text-xs text-gold hover:text-white font-bold inline-flex items-center gap-1.5 group-hover:translate-x-1 transition duration-200">
                        <span>EXPLORE WORKS</span>
                        <span>→</span>
                    </a>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
