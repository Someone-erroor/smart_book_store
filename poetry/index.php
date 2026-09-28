<?php
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = "Poetry & Shayari — Modern Mehfil Archive";

$selectedMood = trim($_GET['mood'] ?? 'all');
$selectedLang = trim($_GET['lang'] ?? 'all');
$selectedGenre = trim($_GET['genre'] ?? 'all');
$search = trim($_GET['search'] ?? '');

$filter = [];
if ($selectedMood !== 'all' && !empty($selectedMood)) {
    $filter['mood'] = new MongoDB\BSON\Regex('^' . preg_quote($selectedMood) . '$', 'i');
}
if ($selectedLang !== 'all' && !empty($selectedLang)) {
    $filter['language'] = new MongoDB\BSON\Regex('^' . preg_quote($selectedLang) . '$', 'i');
}
if ($selectedGenre !== 'all' && !empty($selectedGenre)) {
    $filter['genre'] = new MongoDB\BSON\Regex('^' . preg_quote($selectedGenre) . '$', 'i');
}
if (!empty($search)) {
    $filter['$or'] = [
        ['title' => new MongoDB\BSON\Regex($search, 'i')],
        ['poet_name' => new MongoDB\BSON\Regex($search, 'i')],
        ['text_roman' => new MongoDB\BSON\Regex($search, 'i')],
        ['mood' => new MongoDB\BSON\Regex($search, 'i')],
        ['genre' => new MongoDB\BSON\Regex($search, 'i')]
    ];
}

$poetryList = [];
$distinctMoods = [];
$distinctGenres = [];
$totalCount = 0;
$poetsCount = 0;

try {
    if ($dbConnected) {
        $poetryList = $db->poetry->find($filter, ['sort' => ['created_at' => -1]])->toArray();
        $distinctMoods = $db->poetry->distinct('mood');
        $distinctGenres = $db->poetry->distinct('genre');
        sort($distinctMoods);
        sort($distinctGenres);
        $totalCount = $db->poetry->countDocuments();
        $poetsCount = $db->poets->countDocuments();
    }
} catch (Exception $e) {
    setFlash('error', 'Error fetching poetry: ' . $e->getMessage());
}

// URL builder helper for maintaining filter combinations
function mehfilFilterUrl($overrides = []) {
    global $selectedMood, $selectedLang, $selectedGenre, $search;
    $params = [
        'mood' => $selectedMood,
        'lang' => $selectedLang,
        'genre' => $selectedGenre,
        'search' => $search
    ];
    foreach ($overrides as $k => $v) {
        $params[$k] = $v;
    }
    $query = [];
    if (!empty($params['lang']) && $params['lang'] !== 'all') $query['lang'] = $params['lang'];
    if (!empty($params['genre']) && $params['genre'] !== 'all') $query['genre'] = $params['genre'];
    if (!empty($params['mood']) && $params['mood'] !== 'all') $query['mood'] = $params['mood'];
    if (!empty($params['search'])) $query['search'] = $params['search'];

    $qs = http_build_query($query);
    return baseUrl('poetry/index.php' . ($qs ? '?' . $qs : ''));
}

$hasActiveFilters = ($selectedMood !== 'all' || $selectedLang !== 'all' || $selectedGenre !== 'all' || !empty($search));

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="mb-10">
        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-3 flex items-center gap-2">
            <span>ALFAAZ / DIGITAL MEHFIL</span>
            <span class="text-white/20">•</span>
            <span>ROMAN HINDI & URDU</span>
            <span class="text-white/20">•</span>
            <span class="text-gold font-bold"><?= $totalCount ?>+ COMPOSITIONS</span>
        </div>
        <h1 class="text-5xl md:text-8xl font-bold tracking-tight leading-tight">
            POETRY & <span class="text-transparent" style="-webkit-text-stroke: 1px rgba(255,23,68,0.7);">SHAYARI.</span>
        </h1>
        <p class="mt-4 text-gray-400 text-sm max-w-2xl leading-relaxed">
            "Some words are read. Some words are felt." Immense archive of verses in Roman Hindi & Urdu with English commentary (*Tafseer*), spanning classical ghazals, revolutionary nazms, reflective dohas, and haunting shers.
        </p>

        <!-- STATS RIBBON -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-white"><?= $totalCount ?>+</div>
                <div class="mono text-[10px] text-blood uppercase tracking-widest mt-1">Archived Verses</div>
            </div>
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-gold"><?= $poetsCount ?></div>
                <div class="mono text-[10px] text-gray-400 uppercase tracking-widest mt-1">Master Ustads</div>
            </div>
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-white"><?= count($distinctGenres) ?></div>
                <div class="mono text-[10px] text-gray-400 uppercase tracking-widest mt-1">Poetic Forms</div>
            </div>
            <div class="p-4 border border-white/10 bg-[#080808]">
                <div class="text-2xl md:text-3xl font-bold text-white"><?= count($distinctMoods) ?></div>
                <div class="mono text-[10px] text-gray-400 uppercase tracking-widest mt-1">Emotional States</div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH CONTROLS -->
    <div class="mb-12 space-y-5 border-y border-white/10 py-8 bg-[#060606]">
        
        <!-- Search and Language Row -->
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            
            <!-- Search Bar -->
            <form action="<?= baseUrl('poetry/index.php') ?>" method="GET" class="border border-white/15 bg-[#080808] flex items-center px-4 py-3 w-full md:max-w-md focus-within:border-gold transition">
                <?php if ($selectedMood !== 'all'): ?><input type="hidden" name="mood" value="<?= htmlspecialchars($selectedMood) ?>"><?php endif; ?>
                <?php if ($selectedLang !== 'all'): ?><input type="hidden" name="lang" value="<?= htmlspecialchars($selectedLang) ?>"><?php endif; ?>
                <?php if ($selectedGenre !== 'all'): ?><input type="hidden" name="genre" value="<?= htmlspecialchars($selectedGenre) ?>"><?php endif; ?>
                <span class="mono text-xs text-blood mr-3">/</span>
                <input type="text" name="search" placeholder="Search verses, poets, keywords..."
                       value="<?= htmlspecialchars($search) ?>"
                       class="bg-transparent outline-none w-full text-xs text-white placeholder:text-gray-600">
                <?php if (!empty($search)): ?>
                    <a href="<?= mehfilFilterUrl(['search' => '']) ?>" class="mono text-[10px] text-gray-500 hover:text-white ml-2">CLEAR</a>
                <?php endif; ?>
            </form>

            <!-- Language Switcher -->
            <div class="flex flex-wrap gap-2 mono text-xs">
                <a href="<?= mehfilFilterUrl(['lang' => 'all']) ?>"
                   class="px-4 py-2 border <?= $selectedLang === 'all' ? 'border-white bg-white text-black font-bold' : 'border-white/10 text-gray-400 hover:border-white/40' ?> transition">
                    ALL TRADITIONS
                </a>
                <a href="<?= mehfilFilterUrl(['lang' => 'Urdu']) ?>"
                   class="px-4 py-2 border <?= strcasecmp($selectedLang, 'Urdu') === 0 ? 'border-blood bg-blood/15 text-blood font-bold' : 'border-white/10 text-gray-400 hover:border-white/40' ?> transition">
                    URDU GHAZAL
                </a>
                <a href="<?= mehfilFilterUrl(['lang' => 'Hindi']) ?>"
                   class="px-4 py-2 border <?= strcasecmp($selectedLang, 'Hindi') === 0 ? 'border-gold bg-gold/15 text-gold font-bold' : 'border-white/10 text-gray-400 hover:border-white/40' ?> transition">
                    HINDI KAVITA
                </a>
                <?php if ($hasActiveFilters): ?>
                    <a href="<?= baseUrl('poetry/index.php') ?>" class="px-3 py-2 border border-blood/40 text-blood hover:bg-blood hover:text-white transition mono text-[11px] font-bold">
                        RESET ALL ✕
                    </a>
                <?php endif; ?>
            </div>

        </div>

        <!-- Poetic Form / Genre Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin pt-2 border-t border-white/5">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest mr-2 flex-shrink-0">POETIC FORM:</span>
            
            <a href="<?= mehfilFilterUrl(['genre' => 'all']) ?>"
               class="category-pill mono text-xs px-3 py-1 border <?= $selectedGenre === 'all' ? 'border-white bg-white text-black font-bold' : 'border-white/10 text-gray-400 hover:border-white/30' ?> whitespace-nowrap">
                ALL FORMS
            </a>

            <?php foreach ($distinctGenres as $g): ?>
                <?php $isActiveG = (strcasecmp($selectedGenre, $g) === 0); ?>
                <a href="<?= mehfilFilterUrl(['genre' => $g]) ?>"
                   class="category-pill mono text-xs px-3 py-1 border <?= $isActiveG ? 'border-blood bg-blood text-black font-bold' : 'border-white/10 text-gray-400 hover:border-blood/50 hover:text-white' ?> transition whitespace-nowrap">
                    <?= strtoupper(htmlspecialchars($g)) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Mood Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin pt-2 border-t border-white/5">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest mr-2 flex-shrink-0">EMOTIONAL MOOD:</span>
            
            <a href="<?= mehfilFilterUrl(['mood' => 'all']) ?>"
               class="category-pill mono text-xs px-3 py-1 border <?= $selectedMood === 'all' ? 'border-gold bg-gold text-black font-bold' : 'border-white/10 text-gray-400 hover:border-white/30' ?> whitespace-nowrap">
                ALL MOODS
            </a>

            <?php 
                $moodColors = [
                    'Tanhai' => 'hover:border-cyan-400 hover:text-cyan-400',
                    'Dard' => 'hover:border-blood hover:text-blood',
                    'Mohabbat' => 'hover:border-pink-400 hover:text-pink-400',
                    'Falsafa' => 'hover:border-gold hover:text-gold',
                    'Zindagi' => 'hover:border-emerald-400 hover:text-emerald-400',
                    'Ishq' => 'hover:border-red-400 hover:text-red-400',
                    'Safar' => 'hover:border-purple-400 hover:text-purple-400',
                    'Yaad' => 'hover:border-yellow-400 hover:text-yellow-400'
                ];
                foreach ($distinctMoods as $m): 
                    $isActive = (strcasecmp($selectedMood, $m) === 0);
                    $hColor = $moodColors[$m] ?? 'hover:border-white hover:text-white';
            ?>
                <a href="<?= mehfilFilterUrl(['mood' => $m]) ?>"
                   class="category-pill mono text-xs px-3 py-1 border <?= $isActive ? 'border-gold bg-gold text-black font-bold' : 'border-white/10 text-gray-400 ' . $hColor ?> transition whitespace-nowrap">
                    <?= strtoupper(htmlspecialchars($m)) ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- ACTIVE FILTER STATUS INDICATOR -->
    <div class="flex justify-between items-center mb-8">
        <div class="mono text-xs text-gray-400">
            SHOWING <span class="text-white font-bold"><?= count($poetryList) ?></span> COMPOSITION<?= count($poetryList) === 1 ? '' : 'S' ?>
            <?php if ($hasActiveFilters): ?>
                <span class="text-blood font-bold">(FILTERED)</span>
            <?php endif; ?>
        </div>

        <div class="mono text-xs text-gray-500 hidden sm:block">
            AUTHENTIC TAFSEER & COMMENTARY ATTACHED
        </div>
    </div>

    <!-- POETRY FEED GRID -->
    <?php if (empty($poetryList)): ?>
        <div class="border border-white/10 p-20 text-center bg-[#080808]">
            <div class="mono text-blood text-xs tracking-widest mb-3">ZERO MATCHES IN ARCHIVE</div>
            <h2 class="text-3xl font-bold">No verses match this specific combination.</h2>
            <p class="text-gray-400 text-sm mt-3 max-w-md mx-auto">Try clearing one or more filters or search terms to uncover verses across our wider mehfil archive.</p>
            <a href="<?= baseUrl('poetry/index.php') ?>" class="inline-block mt-6 border border-gold px-6 py-2.5 text-xs mono text-gold hover:bg-gold hover:text-black font-bold transition">
                RESET ALL FILTERS
            </a>
        </div>
    <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($poetryList as $poem): ?>
                <?php 
                    $pId = (string)$poem['_id'];
                    $mood = $poem['mood'] ?? 'Zindagi';
                    $lang = $poem['language'] ?? 'Urdu';
                    $poetId = !empty($poem['poet_id']) ? (string)$poem['poet_id'] : '';
                ?>
                <article class="border border-white/10 bg-[#080808] p-8 flex flex-col justify-between hover:border-white/30 transition duration-300 group relative">
                    
                    <div>
                        <!-- Header: Poet & Mood -->
                        <div class="flex justify-between items-center text-[10px] mono mb-4">
                            <?php if (!empty($poetId)): ?>
                                <a href="<?= baseUrl('poets/profile.php?id=' . $poetId) ?>" class="text-gold font-bold uppercase tracking-widest hover:underline hover:text-white transition">
                                    <?= htmlspecialchars($poem['poet_name']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-gold font-bold uppercase tracking-widest"><?= htmlspecialchars($poem['poet_name']) ?></span>
                            <?php endif; ?>

                            <div class="flex items-center gap-1.5">
                                <span class="border px-2 py-0.5 uppercase tracking-wider <?= $lang === 'Urdu' ? 'border-blood/40 text-blood bg-blood/5' : 'border-gold/40 text-gold bg-gold/5' ?>">
                                    <?= htmlspecialchars($mood) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h2 class="text-xl font-bold text-white group-hover:text-gold transition mb-4">
                            <a href="<?= baseUrl('poetry/view.php?id=' . $pId) ?>">
                                <?= htmlspecialchars($poem['title']) ?>
                            </a>
                        </h2>

                        <!-- Roman Urdu/Hindi Excerpt Box -->
                        <div class="p-5 bg-black/60 border-l-2 border-blood/60 my-4">
                            <p class="text-sm font-serif-literary text-ivory leading-relaxed whitespace-pre-line">
<?= htmlspecialchars($poem['text_roman']) ?>
                            </p>
                        </div>

                        <!-- English Reflection / Meaning -->
                        <?php if (!empty($poem['meaning_en'])): ?>
                            <p class="text-xs text-gray-400 leading-relaxed italic line-clamp-2 mt-3">
                                <?= htmlspecialchars($poem['meaning_en']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Action -->
                    <div class="mt-8 pt-4 border-t border-white/5 flex justify-between items-center text-xs">
                        <span class="mono text-[10px] text-gray-500 uppercase tracking-wider"><?= htmlspecialchars($poem['genre'] ?? 'Ghazal') ?></span>
                        <a href="<?= baseUrl('poetry/view.php?id=' . $pId) ?>" class="mono text-xs text-gold hover:text-white inline-flex items-center gap-1 font-bold group-hover:translate-x-1 transition duration-200">
                            <span>READ FULL →</span>
                        </a>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
