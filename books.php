<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = "Book Catalog — DAASTAAN. Literary Archive & Bookstore";

$selectedCategory = trim($_GET['category'] ?? 'all');
$searchQuery = trim($_GET['search'] ?? '');
$sortBy = trim($_GET['sort'] ?? 'featured');

$sortMap = [
    'featured' => ['featured' => -1, 'rating' => -1],
    'rating' => ['rating' => -1],
    'newest' => ['created_at' => -1],
    'price_asc' => ['price' => 1],
    'price_desc' => ['price' => -1],
    'title' => ['title' => 1]
];
$sortCriteria = $sortMap[$sortBy] ?? ['featured' => -1, 'rating' => -1];

$filter = [];
if ($selectedCategory !== 'all' && !empty($selectedCategory)) {
    $filter['category'] = $selectedCategory;
}

if (!empty($searchQuery)) {
    $filter['$or'] = [
        ['title' => new MongoDB\BSON\Regex($searchQuery, 'i')],
        ['author' => new MongoDB\BSON\Regex($searchQuery, 'i')],
        ['category' => new MongoDB\BSON\Regex($searchQuery, 'i')]
    ];
}

$books = [];
$categories = [];

try {
    if ($dbConnected) {
        $books = $db->books->find($filter, ['sort' => $sortCriteria])->toArray();
        $categories = $db->books->distinct('category');
        sort($categories);
    }
} catch (Exception $e) {
    setFlash('error', 'Error fetching catalog: ' . $e->getMessage());
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-16 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="mb-14">
        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-3">01 / ARCHIVE & INVENTORY • <?= count($books) ?> TITLES</div>
        <h1 class="text-5xl md:text-8xl font-bold tracking-[-0.06em] leading-tight">
            BOOK <span class="text-gray-600">CATALOG.</span>
        </h1>
        <p class="mt-4 text-gray-400 text-sm max-w-2xl leading-relaxed">
            Explore our curated database of classical Urdu & Hindi poetry collections, philosophical treatises, software craftsmanship, and speculative fiction.
        </p>
    </div>

    <!-- SEARCH & CONTROLS BAR -->
    <div class="mb-12 space-y-5">
        
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
            <!-- Search Input -->
            <div class="border border-white/15 bg-[#0a0a0a] flex items-center px-4 py-3.5 w-full md:max-w-md focus-within:border-blood focus-within:shadow-[0_0_25px_rgba(255,23,68,0.2)] transition duration-300">
                <span class="mono text-xs text-blood mr-3 font-bold">/</span>
                <input id="searchInput" type="text" placeholder="Search title, author, or keywords..."
                       value="<?= htmlspecialchars($searchQuery) ?>"
                       class="bg-transparent outline-none w-full text-sm text-white placeholder:text-gray-600">
                <span id="matchCounter" class="mono text-[10px] text-gray-500 ml-2 whitespace-nowrap">(<?= count($books) ?> BOOKS)</span>
            </div>

            <!-- Sort By Select -->
            <div class="flex items-center gap-2">
                <span class="mono text-[10px] text-gray-500 uppercase tracking-widest whitespace-nowrap">SORT BY:</span>
                <select id="sortSelect" class="bg-[#0a0a0a] border border-white/15 text-xs text-gray-300 px-4 py-3 mono outline-none focus:border-blood transition cursor-pointer">
                    <option value="featured" <?= $sortBy === 'featured' ? 'selected' : '' ?>>Curated & Featured</option>
                    <option value="rating" <?= $sortBy === 'rating' ? 'selected' : '' ?>>Rating: Highest First</option>
                    <option value="price_asc" <?= $sortBy === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $sortBy === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="title" <?= $sortBy === 'title' ? 'selected' : '' ?>>Title: Alphabetical</option>
                </select>
            </div>
        </div>

        <!-- Category Pill Buttons -->
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin items-center">
            <button class="category-btn category-pill mono text-xs px-4 py-2.5 border <?= $selectedCategory === 'all' ? 'border-blood bg-blood text-black font-bold' : 'border-white/10 hover:border-white/40 text-gray-400' ?> transition whitespace-nowrap"
                    data-category="all">
                ALL (<?= count($books) ?>)
            </button>
            <?php foreach ($categories as $cat): ?>
                <button class="category-btn category-pill mono text-xs px-4 py-2.5 border <?= strcasecmp($selectedCategory, $cat) === 0 ? 'border-blood bg-blood text-black font-bold' : 'border-white/10 hover:border-white/40 text-gray-400' ?> transition whitespace-nowrap"
                        data-category="<?= htmlspecialchars($cat) ?>">
                    <?= strtoupper(htmlspecialchars($cat)) ?>
                </button>
            <?php endforeach; ?>
            <a href="<?= baseUrl('genres.php') ?>" class="mono text-xs px-4 py-2.5 border border-gold/40 text-gold hover:bg-gold hover:text-black transition whitespace-nowrap">
                TAXONOMY GUIDE ↗
            </a>
        </div>

    </div>

    <!-- BOOK GRID -->
    <div id="bookGrid" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($books as $index => $book): ?>
            <?php 
                $bookId = (string)$book['_id'];
                $stock = (int)($book['stock'] ?? 0);
                $coverBg = $book['cover_bg'] ?? 'from-[#241018] to-[#090909]';
            ?>
            <article class="book-card book-item reveal p-6 flex flex-col justify-between group"
                     data-category="<?= htmlspecialchars($book['category'] ?? '') ?>"
                     data-price="<?= (float)($book['price'] ?? 0) ?>"
                     data-rating="<?= (float)($book['rating'] ?? 5.0) ?>"
                     data-title="<?= strtolower(htmlspecialchars($book['title'] ?? '')) ?>"
                     data-search="<?= strtolower(htmlspecialchars($book['title'] . ' ' . $book['author'] . ' ' . ($book['category'] ?? ''))) ?>">

                <!-- Visual Cover -->
                <div class="h-[380px] flex items-center justify-center relative">
                    <span class="absolute top-2 left-2 mono text-[9px] text-gray-600">
                        #<?= str_pad((string)($index + 1), 2, "0", STR_PAD_LEFT) ?>
                    </span>

                    <div class="book-cover w-[190px] h-[280px] bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> border border-white/10 p-5 flex flex-col justify-between shadow-2xl">
                        <span class="mono text-[8px] text-blood tracking-widest uppercase">
                            <?= htmlspecialchars($book['category'] ?? 'DAASTAAN EDITION') ?>
                        </span>
                        <div>
                            <h2 class="text-xl font-bold leading-tight group-hover:text-white transition">
                                <?= htmlspecialchars($book['title']) ?>
                            </h2>
                        </div>
                        <span class="text-[9px] text-gray-500">
                            <?= htmlspecialchars($book['author']) ?>
                        </span>
                    </div>
                </div>

                <!-- Book Metadata -->
                <div class="pt-6 border-t border-white/10 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center text-[10px] mono">
                            <span class="text-blood tracking-wider uppercase font-semibold">
                                <?= htmlspecialchars($book['category'] ?? 'GENERAL') ?>
                            </span>
                            <span class="text-yellow-500 font-bold">
                                ★ <?= htmlspecialchars((string)($book['rating'] ?? 5.0)) ?>
                            </span>
                        </div>

                        <h3 class="mt-2 text-lg font-bold leading-tight line-clamp-1 group-hover:text-blood transition">
                            <?= htmlspecialchars($book['title']) ?>
                        </h3>
                        <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($book['author']) ?></p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold font-mono">₹<?= htmlspecialchars((string)$book['price']) ?></span>
                            <div class="mono text-[9px] mt-0.5">
                                <?php if ($stock > 5): ?>
                                    <span class="text-emerald-500">● IN STOCK (<?= $stock ?>)</span>
                                <?php elseif ($stock > 0): ?>
                                    <span class="text-yellow-400 animate-pulse font-semibold">▲ LOW STOCK (<?= $stock ?>)</span>
                                <?php else: ?>
                                    <span class="text-blood font-semibold">✕ SOLD OUT</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="<?= baseUrl('book-details.php?id=' . $bookId) ?>"
                           class="border border-white/15 px-3 py-1.5 text-xs mono hover:bg-white hover:text-black transition">
                            DETAILS →
                        </a>
                    </div>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

    <!-- EMPTY SEARCH RESULTS FALLBACK -->
    <div id="noResults" class="<?= empty($books) ? '' : 'hidden' ?> py-32 text-center">
        <p class="mono text-blood text-xs tracking-widest">QUERY RETURNED ZERO MATCHES</p>
        <h2 class="text-4xl font-bold mt-4">No books match your criteria.</h2>
        <a href="<?= baseUrl('books.php') ?>" class="inline-block mt-6 border border-white/20 px-6 py-2.5 text-xs mono hover:border-blood">
            RESET ALL FILTERS
        </a>
    </div>

</main>

<script>
    const searchInput = document.getElementById("searchInput");
    const bookGrid = document.getElementById("bookGrid");
    const noResults = document.getElementById("noResults");
    const categoryButtons = document.querySelectorAll(".category-btn");
    const sortSelect = document.getElementById("sortSelect");

    let currentCategory = "<?= $selectedCategory ?>";

    function getBookItems() {
        return Array.from(document.querySelectorAll(".book-item"));
    }

    function applyFilterAndSort() {
        const query = searchInput.value.toLowerCase().trim();
        const sortVal = sortSelect ? sortSelect.value : "featured";
        let items = getBookItems();
        let matches = 0;

        items.forEach(item => {
            const cat = item.dataset.category.toLowerCase();
            const text = item.dataset.search;

            const categoryMatch = (currentCategory === "all" || cat === currentCategory.toLowerCase());
            const searchMatch = !query || text.includes(query);

            if (categoryMatch && searchMatch) {
                item.classList.remove("hidden");
                matches++;
            } else {
                item.classList.add("hidden");
            }
        });

        // Instant In-Place Sort
        if (sortVal !== "featured") {
            items.sort((a, b) => {
                if (sortVal === "price_asc") {
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                } else if (sortVal === "price_desc") {
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                } else if (sortVal === "rating") {
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                } else if (sortVal === "title") {
                    return a.dataset.title.localeCompare(b.dataset.title);
                }
                return 0;
            });
            items.forEach(item => bookGrid.appendChild(item));
        }

        if (matches === 0) {
            noResults.classList.remove("hidden");
        } else {
            noResults.classList.add("hidden");
        }

        const matchCounter = document.getElementById("matchCounter");
        if (matchCounter) {
            matchCounter.textContent = `(${matches} ${matches === 1 ? 'BOOK' : 'BOOKS'})`;
        }
    }

    searchInput.addEventListener("input", applyFilterAndSort);
    if (sortSelect) {
        sortSelect.addEventListener("change", applyFilterAndSort);
    }

    categoryButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            categoryButtons.forEach(b => {
                b.className = "category-btn mono text-xs px-4 py-2.5 border border-white/10 hover:border-white/40 text-gray-400 transition whitespace-nowrap";
            });
            btn.className = "category-btn mono text-xs px-4 py-2.5 border border-blood bg-blood text-black font-bold transition whitespace-nowrap";

            currentCategory = btn.dataset.category;
            applyFilterAndSort();
        });
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>