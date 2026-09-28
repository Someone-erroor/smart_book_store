<?php
require_once __DIR__ . '/../includes/auth.php';

requireAuth(baseUrl('orders/index.php'));

$user = currentUser();
$orders = [];

try {
    if ($dbConnected) {
        $cursor = $db->orders->find(
            ['$or' => [
                ['user_id' => $user['id']],
                ['customer_email' => $user['email']]
            ]],
            ['sort' => ['created_at' => -1]]
        );
        $orders = $cursor->toArray();
    }
} catch (Exception $e) {
    setFlash('error', 'Error fetching orders: ' . $e->getMessage());
}

$pageTitle = "My Orders — DAASTAAN. Literary Archive & Bookstore";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <div class="mb-10">
        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">ACCOUNT HISTORY</div>
        <h1 class="text-4xl md:text-6xl font-bold tracking-tight">MY ORDERS.</h1>
        <p class="text-xs text-gray-500 mt-2">View status, receipts, and order logs for <?= htmlspecialchars($user['email']) ?>.</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="border border-white/10 p-16 md:p-24 text-center bg-[#080808]">
            <div class="mono text-blood text-xs tracking-widest mb-4">NO TRANSACTIONS FOUND</div>
            <h2 class="text-3xl font-bold">You haven't placed any orders yet.</h2>
            <p class="text-gray-400 text-sm mt-3 max-w-md mx-auto">
                Ready to dive into a new read or explore our literary collections? Browse our catalog.
            </p>
            <a href="<?= baseUrl('books.php') ?>" class="danger-button inline-block mt-8 px-8 py-4 text-xs font-bold tracking-widest">
                START BROWSING →
            </a>
        </div>
    <?php else: ?>

        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <?php 
                    $orderId = (string)$order['_id'];
                    $items = $order['items'] ?? [];
                    $dateString = isset($order['created_at']) && $order['created_at'] instanceof MongoDB\BSON\UTCDateTime 
                        ? $order['created_at']->toDateTime()->format('M d, Y - h:i A')
                        : 'Recent';
                    $status = strtolower($order['status'] ?? 'confirmed');
                    
                    $statusColor = 'border-blue-500/40 text-blue-400 bg-blue-950/20';
                    if ($status === 'delivered') $statusColor = 'border-emerald-500/40 text-emerald-400 bg-emerald-950/20';
                    if ($status === 'shipped') $statusColor = 'border-purple-500/40 text-purple-400 bg-purple-950/20';
                    if ($status === 'cancelled') $statusColor = 'border-blood text-blood bg-[#1a0508]';
                ?>
                <div class="border border-white/10 bg-[#080808] p-6 sm:p-8">
                    
                    <!-- Order Header -->
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center pb-6 border-b border-white/10 gap-4">
                        <div>
                            <div class="mono text-xs">
                                <span class="text-gray-500">ORDER:</span>
                                <span class="font-bold text-white ml-1">#<?= strtoupper(substr($orderId, -8)) ?></span>
                            </div>
                            <div class="mono text-[11px] text-gray-500 mt-1"><?= $dateString ?></div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="mono text-[10px] uppercase font-bold border px-3 py-1 <?= $statusColor ?>">
                                ● <?= strtoupper($order['status'] ?? 'CONFIRMED') ?>
                            </span>

                            <a href="<?= baseUrl('orders/confirmation.php?id=' . $orderId) ?>"
                               class="border border-white/20 px-4 py-2 text-xs mono hover:border-blood hover:text-white transition">
                                VIEW RECEIPT →
                            </a>
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div class="py-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($items as $item): ?>
                            <div class="flex items-center gap-4 p-3 border border-white/5 bg-black/40">
                                <div class="w-10 h-14 bg-white/5 border border-white/10 flex-shrink-0 flex items-center justify-center text-[9px] mono text-blood">
                                    BK
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-sm font-semibold text-white truncate"><?= htmlspecialchars($item['title'] ?? 'Book') ?></div>
                                    <div class="mono text-[10px] text-gray-500 mt-0.5">Qty: <?= $item['quantity'] ?> × ₹<?= $item['price'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Order Footer -->
                    <div class="pt-4 border-t border-white/5 flex flex-col sm:flex-row justify-between sm:items-center text-xs text-gray-400 gap-2">
                        <div>
                            <span>Delivery To:</span>
                            <span class="text-white ml-1"><?= htmlspecialchars($order['shipping_address']['city'] ?? '') ?>, <?= htmlspecialchars($order['shipping_address']['state'] ?? '') ?></span>
                            <span class="text-gray-600 mx-2">•</span>
                            <span>Payment: <span class="text-white"><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></span></span>
                        </div>

                        <div class="text-sm font-bold text-white">
                            Total: <span class="text-blood text-base font-mono">₹<?= number_format((float)($order['total_amount'] ?? 0), 2) ?></span>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
