<?php
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = "Literary Collections — Curated by Mood & Emotion";

$collections = [];
try {
    if ($dbConnected) {
        $collections = $db->collections->find([], ['sort' => ['name' => 1]])->toArray();
    }
} catch (Exception $e) {
    setFlash('error', 'Error fetching collections: ' . $e->getMessage());
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="mb-14">
        <div class="mono text-[10px] tracking-[0.4em] text-gold mb-3 flex items-center gap-2">
            <span>EHSAAS / EMOTIONAL TAXONOMY</span>
            <span class="text-white/20">•</span>
            <span>CURATED LITERARY SUITES</span>
        </div>
        <h1 class="text-5xl md:text-8xl font-bold tracking-tight leading-tight">
            LITERARY <span class="text-transparent" style="-webkit-text-stroke: 1px rgba(197,160,89,0.7);">COLLECTIONS.</span>
        </h1>
        <p class="mt-4 text-gray-400 text-sm max-w-2xl leading-relaxed">
            In South Asian literature, books and poetry are not categorized merely by genre—they are inhabited through states of feeling (Ehsaas). Explore our curated emotional collections.
        </p>
    </div>

    <!-- COLLECTIONS GRID -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($collections as $col): ?>
            <?php 
                $slug = $col['slug'] ?? 'tanhai';
                $accent = $col['color_accent'] ?? '#c5a059';
                $gradient = $col['bg_gradient'] ?? 'from-[#141414] to-[#070707]';
            ?>
            <article class="border border-white/10 bg-gradient-to-br <?= htmlspecialchars($gradient) ?> p-8 flex flex-col justify-between hover:border-white/40 transition duration-300 group relative overflow-hidden shadow-xl">
                
                <!-- Corner Watermark Letter -->
                <div class="absolute -right-4 -bottom-8 text-8xl font-bold text-white/[0.03] select-none pointer-events-none group-hover:text-white/[0.06] transition">
                    <?= substr($col['name'], 0, 1) ?>
                </div>

                <div>
                    <!-- Header Mood Badge -->
                    <div class="flex justify-between items-center mb-6">
                        <span class="mono text-[10px] font-bold tracking-widest uppercase border px-2.5 py-1"
                              style="border-color: <?= $accent ?>40; color: <?= $accent ?>; background: <?= $accent ?>10;">
                            MOOD ARCHIVE
                        </span>
                        <span class="w-2 h-2 rounded-full" style="background-color: <?= $accent ?>;"></span>
                    </div>

                    <!-- Collection Name -->
                    <h2 class="text-3xl font-bold tracking-tight text-white group-hover:text-gold transition">
                        <?= strtoupper(htmlspecialchars($col['name'])) ?>
                    </h2>

                    <!-- Tagline -->
                    <div class="mono text-xs text-gray-400 mt-2 font-medium">
                        <?= htmlspecialchars($col['tagline'] ?? '') ?>
                    </div>

                    <!-- Roman Quote Excerpt -->
                    <div class="p-4 bg-black/60 border-l-2 my-5" style="border-color: <?= $accent ?>;">
                        <p class="text-xs font-serif-literary text-ivory italic leading-relaxed">
                            "<?= htmlspecialchars($col['quote_roman'] ?? '') ?>"
                        </p>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-gray-400 leading-relaxed line-clamp-3">
                        <?= htmlspecialchars($col['description'] ?? '') ?>
                    </p>
                </div>

                <!-- Footer Action -->
                <div class="mt-8 pt-5 border-t border-white/10 flex justify-between items-center">
                    <span class="mono text-[10px] text-gray-500">CURATED SUITE</span>
                    <a href="<?= baseUrl('collections/view.php?mood=' . urlencode($col['mood'])) ?>"
                       class="mono text-xs font-bold text-white hover:text-gold inline-flex items-center gap-1 group-hover:translate-x-1 transition duration-200">
                        <span>EXPLORE SUITE</span>
                        <span>→</span>
                    </a>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
