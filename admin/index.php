<?php
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$totalBooks = 0;
$totalOrders = 0;
$totalRevenue = 0.0;
$lowStockCount = 0;
$recentOrders = [];
$lowStockBooks = [];

try {
    if ($dbConnected) {
        $totalBooks = $db->books->countDocuments();
        $totalOrders = $db->orders->countDocuments();

        // Calculate total revenue
        $revenueAgg = $db->orders->aggregate([
            ['$match' => ['status' => ['$ne' => 'cancelled']]],
            ['$group' => ['_id' => null, 'total' => ['$sum' => '$total_amount']]]
        ])->toArray();
        if (!empty($revenueAgg)) {
            $totalRevenue = (float)$revenueAgg[0]['total'];
        }

        // Low stock count (<= 5)
        $lowStockBooks = $db->books->find(['stock' => ['$lte' => 5]], ['limit' => 5])->toArray();
        $lowStockCount = $db->books->countDocuments(['stock' => ['$lte' => 5]]);

        // Recent orders
        $recentOrders = $db->orders->find([], ['sort' => ['created_at' => -1], 'limit' => 6])->toArray();

        // Mehfil Literary Assets Counts
        $totalPoets = $db->poets->countDocuments();
        $totalPoetry = $db->poetry->countDocuments();
        $totalCollections = $db->collections->countDocuments();
    }
} catch (Exception $e) {
    setFlash('error', 'Dashboard query error: ' . $e->getMessage());
}

$pageTitle = "Admin Console — DAASTAAN. Literary Archive & Bookstore";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- DASHBOARD HEADER -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-end gap-6 mb-12">
        <div>
            <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">CONTROL PANEL / ROLE: ADMINISTRATOR</div>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight">STORE METRICS.</h1>
            <p class="text-xs text-gray-500 mt-2">Database operations, inventory tracking, orders fulfillment, and literary archive management.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="<?= baseUrl('admin/book-add.php') ?>" class="danger-button px-5 py-3 text-xs font-bold tracking-widest inline-flex items-center gap-2">
                <span>+ ADD NEW BOOK</span>
            </a>
            <a href="<?= baseUrl('admin/books.php') ?>" class="border border-white/20 px-5 py-3 text-xs mono text-white hover:border-white transition">
                CATALOG INVENTORY
            </a>
            <a href="<?= baseUrl('admin/orders.php') ?>" class="border border-white/20 px-5 py-3 text-xs mono text-white hover:border-white transition">
                CUSTOMER ORDERS
            </a>
            <a href="<?= baseUrl('genres.php') ?>" class="border border-gold/40 text-gold hover:bg-gold hover:text-black px-4 py-3 text-xs mono transition">
                TAXONOMY ↗
            </a>
        </div>
    </div>

    <!-- STAT METRICS CARDS: E-COMMERCE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="border border-white/10 p-6 bg-[#080808] hover:border-blood/40 transition group">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block group-hover:text-blood transition">TOTAL CATALOG BOOKS</span>
            <div class="text-4xl font-bold text-white mt-2 font-mono" data-counter="<?= $totalBooks ?>"><?= $totalBooks ?></div>
            <div class="mono text-[10px] text-gray-600 mt-3">Active titles in MongoDB books</div>
        </div>

        <div class="border border-white/10 p-6 bg-[#080808] hover:border-blood/40 transition group">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block group-hover:text-blood transition">ALL CUSTOMER ORDERS</span>
            <div class="text-4xl font-bold text-white mt-2 font-mono" data-counter="<?= $totalOrders ?>"><?= $totalOrders ?></div>
            <div class="mono text-[10px] text-gray-600 mt-3">Completed & pipeline orders</div>
        </div>

        <div class="border border-white/10 p-6 bg-[#080808] hover:border-emerald-500/40 transition group">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block group-hover:text-emerald-400 transition">GROSS REVENUE</span>
            <div class="text-4xl font-bold text-emerald-400 mt-2 font-mono" data-counter="<?= $totalRevenue ?>" data-prefix="₹">₹<?= number_format($totalRevenue, 2) ?></div>
            <div class="mono text-[10px] text-gray-600 mt-3">Total orders value excluding cancels</div>
        </div>

        <div class="border border-white/10 p-6 bg-[#080808] hover:border-blood/40 transition group">
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block group-hover:text-blood transition">LOW STOCK ALERTS</span>
            <div class="text-4xl font-bold <?= $lowStockCount > 0 ? 'text-blood' : 'text-gray-400' ?> mt-2 font-mono" data-counter="<?= $lowStockCount ?>"><?= $lowStockCount ?></div>
            <div class="mono text-[10px] text-gray-600 mt-3">Books with 5 or fewer copies</div>
        </div>

    </div>

    <!-- STAT METRICS CARDS: LITERARY MEHFIL ARCHIVE -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-14">
        
        <div class="border border-gold/20 p-6 bg-gradient-to-br from-[#1c1507] to-[#080808] hover:border-gold/50 transition">
            <div class="flex justify-between items-center">
                <span class="mono text-[10px] text-gold uppercase tracking-widest block font-semibold">POETS & AUTHORS</span>
                <a href="<?= baseUrl('poets/index.php') ?>" class="mono text-[10px] text-gold hover:underline">EXPLORE →</a>
            </div>
            <div class="text-3xl font-bold text-white mt-2 font-mono" data-counter="<?= $totalPoets ?? 14 ?>"><?= $totalPoets ?? 14 ?></div>
            <div class="mono text-[10px] text-gray-400 mt-2">Biographies & published bibliographic profiles</div>
        </div>

        <div class="border border-gold/20 p-6 bg-gradient-to-br from-[#1c1507] to-[#080808] hover:border-gold/50 transition">
            <div class="flex justify-between items-center">
                <span class="mono text-[10px] text-gold uppercase tracking-widest block font-semibold">POETRY ARCHIVE (ALFAAZ)</span>
                <a href="<?= baseUrl('poetry/index.php') ?>" class="mono text-[10px] text-gold hover:underline">EXPLORE →</a>
            </div>
            <div class="text-3xl font-bold text-white mt-2 font-mono" data-counter="<?= $totalPoetry ?? 18 ?>"><?= $totalPoetry ?? 18 ?></div>
            <div class="mono text-[10px] text-gray-400 mt-2">Roman Hindi/Urdu verses with English tafseer</div>
        </div>

        <div class="border border-gold/20 p-6 bg-gradient-to-br from-[#1c1507] to-[#080808] hover:border-gold/50 transition">
            <div class="flex justify-between items-center">
                <span class="mono text-[10px] text-gold uppercase tracking-widest block font-semibold">EMOTIONAL MOOD SUITES</span>
                <a href="<?= baseUrl('collections/index.php') ?>" class="mono text-[10px] text-gold hover:underline">EXPLORE →</a>
            </div>
            <div class="text-3xl font-bold text-white mt-2 font-mono" data-counter="<?= $totalCollections ?? 8 ?>"><?= $totalCollections ?? 8 ?></div>
            <div class="mono text-[10px] text-gray-400 mt-2">Curated thematic emotion suites (Ehsaas)</div>
        </div>

    </div>

    <!-- TWO COLUMN: RECENT ORDERS & LOW STOCK -->
    <div class="grid lg:grid-cols-[1.5fr_1fr] gap-10">

        <!-- RECENT ORDERS -->
        <div class="border border-white/10 bg-[#080808] p-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/10">
                <div>
                    <span class="mono text-[10px] text-blood tracking-widest uppercase">REALTIME PIPELINE</span>
                    <h2 class="text-xl font-bold text-white mt-1">RECENT ORDERS</h2>
                </div>
                <a href="<?= baseUrl('admin/orders.php') ?>" class="mono text-xs text-gray-400 hover:text-white transition">
                    VIEW ALL →
                </a>
            </div>

            <?php if (empty($recentOrders)): ?>
                <div class="py-12 text-center text-gray-500 text-xs mono">No orders placed yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="mono text-[10px] text-gray-500 border-b border-white/10 pb-2">
                                <th class="py-3">ORDER ID</th>
                                <th class="py-3">CUSTOMER</th>
                                <th class="py-3">TOTAL</th>
                                <th class="py-3">STATUS</th>
                                <th class="py-3 text-right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($recentOrders as $ord): ?>
                                <?php 
                                    $ordId = (string)$ord['_id']; 
                                    $st = strtolower($ord['status'] ?? 'confirmed');
                                    $badge = 'text-blue-400 border-blue-500/40 bg-blue-950/20';
                                    if ($st === 'delivered') $badge = 'text-emerald-400 border-emerald-500/40 bg-emerald-950/20';
                                    if ($st === 'shipped') $badge = 'text-purple-400 border-purple-500/40 bg-purple-950/20';
                                    if ($st === 'cancelled') $badge = 'text-blood border-blood/40 bg-blood/10';
                                ?>
                                <tr>
                                    <td class="py-3.5 mono font-bold text-white">#<?= strtoupper(substr($ordId, -6)) ?></td>
                                    <td class="py-3.5 text-gray-300">
                                        <div><?= htmlspecialchars($ord['customer_name'] ?? 'Guest') ?></div>
                                        <div class="text-[10px] text-gray-600"><?= htmlspecialchars($ord['customer_email'] ?? '') ?></div>
                                    </td>
                                    <td class="py-3.5 font-mono font-bold text-white">₹<?= number_format((float)($ord['total_amount'] ?? 0), 2) ?></td>
                                    <td class="py-3.5">
                                        <span class="mono text-[9px] uppercase border px-2 py-0.5 <?= $badge ?>">
                                            <?= htmlspecialchars($ord['status'] ?? 'confirmed') ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <a href="<?= baseUrl('orders/confirmation.php?id=' . $ordId) ?>" class="mono text-[10px] text-gray-400 hover:text-white border border-white/10 px-2 py-1">
                                            VIEW
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- LOW STOCK OR QUICK INVENTORY -->
        <div class="border border-white/10 bg-[#080808] p-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-white/10">
                <div>
                    <span class="mono text-[10px] text-blood tracking-widest uppercase">SUPPLY AUDIT</span>
                    <h2 class="text-xl font-bold text-white mt-1">STOCK WATCH</h2>
                </div>
                <a href="<?= baseUrl('admin/books.php') ?>" class="mono text-xs text-gray-400 hover:text-white transition">
                    CATALOG →
                </a>
            </div>

            <?php if (empty($lowStockBooks)): ?>
                <div class="py-12 text-center text-emerald-400 text-xs mono">
                    ✓ All inventory stocks are currently healthy (> 5 units).
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($lowStockBooks as $b): ?>
                        <?php $bId = (string)$b['_id']; ?>
                        <div class="p-3 border border-white/5 bg-black/40 flex justify-between items-center text-xs">
                            <div class="overflow-hidden pr-2">
                                <div class="font-bold text-white truncate"><?= htmlspecialchars($b['title']) ?></div>
                                <div class="mono text-[10px] text-gray-500"><?= htmlspecialchars($b['author']) ?></div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="mono text-xs font-bold text-blood border border-blood/30 bg-blood/10 px-2 py-0.5">
                                    <?= (int)($b['stock'] ?? 0) ?> LEFT
                                </span>
                                <a href="<?= baseUrl('admin/book-edit.php?id=' . $bId) ?>" class="mono text-[10px] text-white underline hover:text-blood">
                                    RESTOCK
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <a href="<?= baseUrl('admin/book-add.php') ?>" class="w-full block py-3 border border-dashed border-white/20 text-xs mono text-gray-400 hover:border-blood hover:text-white transition">
                    + CREATE NEW BOOK LISTING
                </a>
            </div>
        </div>

    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
