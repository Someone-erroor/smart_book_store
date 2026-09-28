<?php
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$search = trim($_GET['search'] ?? '');
$filter = [];

if (!empty($search)) {
    $filter['$or'] = [
        ['title' => new MongoDB\BSON\Regex($search, 'i')],
        ['author' => new MongoDB\BSON\Regex($search, 'i')],
        ['category' => new MongoDB\BSON\Regex($search, 'i')]
    ];
}

$books = [];
try {
    if ($dbConnected) {
        $books = $db->books->find($filter, ['sort' => ['created_at' => -1]])->toArray();
    }
} catch (Exception $e) {
    setFlash('error', 'Query error: ' . $e->getMessage());
}

$pageTitle = "Manage Inventory — DAASTAAN. Console";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-end gap-6 mb-10">
        <div>
            <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">ADMIN CONSOLE / INVENTORY</div>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight">MANAGE BOOKS.</h1>
            <p class="text-xs text-gray-500 mt-2">Create, modify, restock, or remove catalog titles in MongoDB.</p>
        </div>

        <div class="flex gap-4">
            <a href="<?= baseUrl('admin/index.php') ?>" class="border border-white/20 px-5 py-3 text-xs mono text-gray-400 hover:text-white transition">
                ← DASHBOARD
            </a>
            <a href="<?= baseUrl('admin/book-add.php') ?>" class="danger-button px-6 py-3 text-xs font-bold tracking-widest inline-flex items-center gap-2">
                <span>+ ADD NEW BOOK</span>
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="mb-8 border border-white/10 p-4 bg-[#080808] flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="<?= baseUrl('admin/books.php') ?>" method="GET" class="w-full sm:max-w-md flex items-center border border-white/15 bg-black px-4 py-2.5">
            <span class="mono text-xs text-gray-500 mr-3">/</span>
            <input type="text" name="search" placeholder="Search catalog by title, author, genre..." value="<?= htmlspecialchars($search) ?>"
                   class="bg-transparent outline-none w-full text-xs text-white placeholder:text-gray-600">
            <?php if (!empty($search)): ?>
                <a href="<?= baseUrl('admin/books.php') ?>" class="mono text-xs text-gray-500 hover:text-blood ml-2">CLEAR</a>
            <?php endif; ?>
        </form>

        <span class="mono text-xs text-gray-500">
            TOTAL TITLES: <span class="text-white font-bold"><?= count($books) ?></span>
        </span>
    </div>

    <!-- BOOKS TABLE -->
    <div class="border border-white/10 bg-[#080808] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="mono text-[10px] text-gray-500 border-b border-white/10 bg-black/60">
                        <th class="p-4">COVER</th>
                        <th class="p-4">BOOK DETAILS</th>
                        <th class="p-4">CATEGORY</th>
                        <th class="p-4">PRICE</th>
                        <th class="p-4">STOCK</th>
                        <th class="p-4">RATING</th>
                        <th class="p-4 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="7" class="py-16 text-center text-gray-500 mono">
                                No books found matching your query.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <?php 
                                $bId = (string)$book['_id'];
                                $stock = (int)($book['stock'] ?? 0);
                                $coverBg = $book['cover_bg'] ?? 'from-[#241018] to-[#090909]';
                            ?>
                            <tr class="hover:bg-white/[0.02] transition">
                                <!-- Cover Miniature -->
                                <td class="p-4">
                                    <div class="w-12 h-16 bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> border border-white/15 flex items-center justify-center text-center p-1 shadow-md">
                                        <span class="mono text-[7px] text-blood line-clamp-2 uppercase">BK</span>
                                    </div>
                                </td>

                                <!-- Details -->
                                <td class="p-4">
                                    <div class="font-bold text-sm text-white"><?= htmlspecialchars($book['title']) ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($book['author']) ?></div>
                                    <div class="mono text-[9px] text-gray-600 mt-1">ISBN: <?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></div>
                                </td>

                                <!-- Category -->
                                <td class="p-4">
                                    <span class="mono text-[10px] text-blood border border-blood/30 bg-blood/10 px-2 py-0.5 uppercase">
                                        <?= htmlspecialchars($book['category'] ?? 'UNCATEGORIZED') ?>
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="p-4 font-mono font-bold text-sm text-white">
                                    ₹<?= htmlspecialchars((string)$book['price']) ?>
                                </td>

                                <!-- Stock Status -->
                                <td class="p-4">
                                    <?php if ($stock > 10): ?>
                                        <span class="mono text-xs font-bold text-emerald-400">● <?= $stock ?> in stock</span>
                                    <?php elseif ($stock > 0): ?>
                                        <span class="mono text-xs font-bold text-yellow-400 animate-pulse border border-yellow-500/30 bg-yellow-500/10 px-2 py-0.5 inline-block">▲ LOW (<?= $stock ?>)</span>
                                    <?php else: ?>
                                        <span class="mono text-xs font-bold text-blood border border-blood/30 bg-blood/10 px-2 py-0.5 inline-block">✕ Out of stock</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Rating -->
                                <td class="p-4 text-yellow-500 font-mono">
                                    ★ <?= htmlspecialchars((string)($book['rating'] ?? 5.0)) ?>
                                </td>

                                <!-- Actions -->
                                <td class="p-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="<?= baseUrl('book-details.php?id=' . $bId) ?>" target="_blank"
                                           class="mono text-[10px] border border-white/10 px-2.5 py-1 text-gray-400 hover:text-white hover:border-white transition">
                                            PREVIEW
                                        </a>

                                        <a href="<?= baseUrl('admin/book-edit.php?id=' . $bId) ?>"
                                           class="mono text-[10px] border border-white/20 bg-white/5 px-2.5 py-1 text-white hover:bg-white hover:text-black transition">
                                            EDIT
                                        </a>

                                        <a href="<?= baseUrl('admin/book-delete.php?id=' . $bId) ?>"
                                           onclick="return confirm('Are you sure you want to permanently delete \'<?= addslashes(htmlspecialchars($book['title'])) ?>\' from MongoDB?')"
                                           class="mono text-[10px] border border-blood/30 bg-blood/10 px-2.5 py-1 text-blood hover:bg-blood hover:text-black transition">
                                            DELETE
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
