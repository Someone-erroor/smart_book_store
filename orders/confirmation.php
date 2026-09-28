<?php
require_once __DIR__ . '/../includes/auth.php';

requireAuth();

$orderId = $_GET['id'] ?? '';
$order = null;

if (!empty($orderId)) {
    try {
        $mongoId = new MongoDB\BSON\ObjectId($orderId);
        $order = $db->orders->findOne(['_id' => $mongoId]);
    } catch (Exception $e) {
        $order = null;
    }
}

if (!$order) {
    setFlash('error', 'Order not found.');
    header('Location: ' . baseUrl('orders/index.php'));
    exit;
}

$currentUser = currentUser();
// Customer can only view their own order unless admin
if (!isAdmin() && ($order['user_id'] ?? '') !== $currentUser['id'] && ($order['customer_email'] ?? '') !== $currentUser['email']) {
    setFlash('error', 'Unauthorized access to order.');
    header('Location: ' . baseUrl('orders/index.php'));
    exit;
}

$pageTitle = "Order Confirmation #" . substr($orderId, -6) . " — DAASTAAN. Literary Archive & Bookstore";
$dateString = isset($order['created_at']) && $order['created_at'] instanceof MongoDB\BSON\UTCDateTime 
    ? $order['created_at']->toDateTime()->format('M d, Y - h:i A')
    : date('M d, Y');

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-16 max-w-4xl mx-auto px-6">

    <!-- SUCCESS BANNER -->
    <div class="border border-emerald-500/40 bg-emerald-950/20 p-8 sm:p-10 mb-10 text-center">
        <div class="w-14 h-14 rounded-full border border-emerald-500 text-emerald-400 mx-auto flex items-center justify-center text-2xl font-bold mb-4">
            ✓
        </div>
        <span class="mono text-[10px] text-emerald-400 tracking-[0.3em] uppercase">TRANSACTION RECORDED</span>
        <h1 class="text-3xl sm:text-5xl font-bold mt-2">THANK YOU FOR YOUR ORDER.</h1>
        <p class="text-xs text-gray-400 mt-3">
            A confirmation has been logged for <span class="text-white font-medium"><?= htmlspecialchars($order['customer_email']) ?></span>.
        </p>
    </div>

    <!-- INVOICE RECEIPT -->
    <div class="border border-white/10 p-8 sm:p-12 bg-[#090909] space-y-10" id="receipt">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-8 border-b border-white/10 gap-4">
            <div>
                <div class="text-2xl font-bold tracking-tight font-serif-literary">DAASTAAN<span class="text-blood font-sans">.</span></div>
                <div class="mono text-[10px] text-gray-500 mt-1">OFFICIAL PURCHASE ORDER • LITERARY ARCHIVE</div>
            </div>

            <div class="sm:text-right mono text-xs">
                <div><span class="text-gray-500">ORDER REF:</span> <span class="text-blood font-bold font-mono">#<?= strtoupper(substr($orderId, -8)) ?></span></div>
                <div class="text-gray-400 mt-1"><?= $dateString ?></div>
                <div class="mt-2">
                    <span class="inline-block px-3 py-1 text-[10px] border border-emerald-500/50 bg-emerald-500/10 text-emerald-400 uppercase font-bold">
                        STATUS: <?= strtoupper(htmlspecialchars($order['status'] ?? 'CONFIRMED')) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Addresses & Payment Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs">
            <div>
                <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block mb-2">SHIPPING DESTINATION</span>
                <div class="font-bold text-sm text-white"><?= htmlspecialchars($order['customer_name']) ?></div>
                <div class="text-gray-400 mt-1"><?= htmlspecialchars($order['shipping_address']['address'] ?? '') ?></div>
                <div class="text-gray-400"><?= htmlspecialchars(($order['shipping_address']['city'] ?? '') . ', ' . ($order['shipping_address']['state'] ?? '') . ' - ' . ($order['shipping_address']['pincode'] ?? '')) ?></div>
                <div class="text-gray-400 mt-1">Phone: <?= htmlspecialchars($order['phone'] ?? 'N/A') ?></div>
            </div>

            <div>
                <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block mb-2">PAYMENT METHOD</span>
                <div class="font-bold text-sm text-white"><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></div>
                <div class="text-gray-400 mt-1">Billing Account: <?= htmlspecialchars($order['customer_email']) ?></div>
                <div class="text-gray-400 mt-1">Payment Status: <span class="uppercase text-emerald-400 font-semibold"><?= htmlspecialchars($order['payment_status'] ?? 'pending') ?></span></div>
            </div>
        </div>

        <!-- Purchased Items Table -->
        <div>
            <span class="mono text-[10px] text-gray-500 uppercase tracking-widest block mb-4">PURCHASED BOOKS</span>
            <div class="border border-white/10 divide-y divide-white/10 bg-black/40">
                <?php foreach (($order['items'] ?? []) as $item): ?>
                    <div class="p-4 flex justify-between items-center text-xs">
                        <div>
                            <div class="font-bold text-white"><?= htmlspecialchars($item['title'] ?? 'Book') ?></div>
                            <div class="text-gray-500 mono text-[10px]"><?= htmlspecialchars($item['author'] ?? '') ?> • Qty: <?= $item['quantity'] ?> × ₹<?= $item['price'] ?></div>
                        </div>
                        <div class="font-bold text-sm text-white">
                            ₹<?= number_format(((float)$item['price']) * ((int)$item['quantity']), 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Totals -->
        <div class="pt-6 border-t border-white/10 flex justify-end">
            <div class="w-full sm:w-72 space-y-3 text-xs">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span class="text-white font-semibold">₹<?= number_format((float)($order['subtotal'] ?? 0), 2) ?></span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Shipping Fee</span>
                    <span class="text-white font-semibold">₹<?= number_format((float)($order['shipping_fee'] ?? 0), 2) ?></span>
                </div>
                <div class="pt-3 border-t border-white/10 flex justify-between items-baseline text-sm">
                    <span class="font-bold">Total Paid</span>
                    <span class="text-2xl font-bold text-blood">₹<?= number_format((float)($order['total_amount'] ?? 0), 2) ?></span>
                </div>
            </div>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
        <a href="<?= baseUrl('orders/index.php') ?>" class="border border-white/20 px-6 py-3 text-xs mono text-gray-300 hover:text-white hover:border-white transition">
            VIEW ALL MY ORDERS
        </a>

        <div class="flex gap-4">
            <button onclick="window.print()" class="border border-white/20 px-6 py-3 text-xs mono text-gray-300 hover:text-white hover:border-white transition">
                🖨️ PRINT RECEIPT
            </button>

            <a href="<?= baseUrl('books.php') ?>" class="danger-button px-6 py-3 text-xs font-bold tracking-widest">
                BACK TO STORE →
            </a>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
