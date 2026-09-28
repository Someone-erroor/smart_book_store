<?php
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = strtolower(trim($_POST['status']));
    $allowedStatuses = ['confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];

    if (in_array($newStatus, $allowedStatuses)) {
        try {
            $db->orders->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($orderId)],
                ['$set' => ['status' => $newStatus, 'updated_at' => new MongoDB\BSON\UTCDateTime()]]
            );
            setFlash('success', 'Order status updated to ' . strtoupper($newStatus) . '.');
        } catch (Exception $e) {
            setFlash('error', 'Status update failed: ' . $e->getMessage());
        }
    }
    header('Location: ' . baseUrl('admin/orders.php'));
    exit;
}

$statusFilter = strtolower(trim($_GET['status'] ?? 'all'));
$search = trim($_GET['search'] ?? '');

$filter = [];
if ($statusFilter !== 'all' && !empty($statusFilter)) {
    $filter['status'] = $statusFilter;
}

if (!empty($search)) {
    $filter['$or'] = [
        ['customer_name' => new MongoDB\BSON\Regex($search, 'i')],
        ['customer_email' => new MongoDB\BSON\Regex($search, 'i')],
        ['phone' => new MongoDB\BSON\Regex($search, 'i')]
    ];
}

$orders = [];
try {
    if ($dbConnected) {
        $orders = $db->orders->find($filter, ['sort' => ['created_at' => -1]])->toArray();
    }
} catch (Exception $e) {
    setFlash('error', 'Query error: ' . $e->getMessage());
}

$pageTitle = "Manage Orders — DAASTAAN. Console";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-12 max-w-[1500px] mx-auto px-6 md:px-10">

    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-end gap-6 mb-10">
        <div>
            <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">ADMIN CONSOLE / PIPELINE</div>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight">CUSTOMER ORDERS.</h1>
            <p class="text-xs text-gray-500 mt-2">Inspect incoming fulfillment requests and update order lifecycle status.</p>
        </div>

        <div class="flex gap-4">
            <a href="<?= baseUrl('admin/index.php') ?>" class="border border-white/20 px-5 py-3 text-xs mono text-gray-400 hover:text-white transition">
                ← DASHBOARD
            </a>
            <a href="<?= baseUrl('admin/books.php') ?>" class="border border-white/20 px-5 py-3 text-xs mono text-gray-400 hover:text-white transition">
                MANAGE CATALOG
            </a>
        </div>
    </div>

    <!-- FILTER TABS & SEARCH -->
    <div class="mb-8 border border-white/10 p-4 bg-[#080808] flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4">
        
        <!-- Status Pills -->
        <div class="flex gap-2 overflow-x-auto pb-1 text-xs mono">
            <?php 
                $statuses = ['all', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
                foreach ($statuses as $st): 
            ?>
                <a href="<?= baseUrl('admin/orders.php?status=' . $st . (!empty($search) ? '&search=' . urlencode($search) : '')) ?>"
                   class="px-3.5 py-2 border <?= $statusFilter === $st ? 'border-blood bg-blood text-black font-bold' : 'border-white/10 hover:border-white/30 text-gray-400' ?> transition whitespace-nowrap">
                    <?= strtoupper($st) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Search Input -->
        <form action="<?= baseUrl('admin/orders.php') ?>" method="GET" class="flex items-center border border-white/15 bg-black px-4 py-2 text-xs">
            <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
            <span class="mono text-gray-500 mr-2">/</span>
            <input type="text" name="search" placeholder="Search customer, email, phone..." value="<?= htmlspecialchars($search) ?>"
                   class="bg-transparent outline-none w-full sm:w-64 text-white placeholder:text-gray-600">
            <?php if (!empty($search)): ?>
                <a href="<?= baseUrl('admin/orders.php?status=' . $statusFilter) ?>" class="mono text-[10px] text-gray-500 hover:text-blood ml-2">CLEAR</a>
            <?php endif; ?>
        </form>

    </div>

    <!-- ORDERS TABLE -->
    <div class="border border-white/10 bg-[#080808] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="mono text-[10px] text-gray-500 border-b border-white/10 bg-black/60">
                        <th class="p-4">ORDER ID & DATE</th>
                        <th class="p-4">CUSTOMER INFO</th>
                        <th class="p-4">SHIPPING DESTINATION</th>
                        <th class="p-4">ITEMS PURCHASED</th>
                        <th class="p-4">TOTAL AMOUNT</th>
                        <th class="p-4">STATUS UPDATE</th>
                        <th class="p-4 text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="py-16 text-center text-gray-500 mono">
                                No orders found for the selected filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php 
                                $ordId = (string)$order['_id'];
                                $dateStr = isset($order['created_at']) && $order['created_at'] instanceof MongoDB\BSON\UTCDateTime 
                                    ? $order['created_at']->toDateTime()->format('M d, Y - h:i A')
                                    : 'Recent';
                                $currentStatus = strtolower($order['status'] ?? 'confirmed');
                            ?>
                            <tr class="hover:bg-white/[0.02] transition">
                                <!-- ID & Date -->
                                <td class="p-4">
                                    <div class="mono font-bold text-white text-sm">#<?= strtoupper(substr($ordId, -8)) ?></div>
                                    <div class="mono text-[10px] text-gray-500 mt-1"><?= $dateStr ?></div>
                                </td>

                                <!-- Customer -->
                                <td class="p-4">
                                    <div class="font-bold text-white"><?= htmlspecialchars($order['customer_name'] ?? 'N/A') ?></div>
                                    <div class="text-gray-400 text-[11px]"><?= htmlspecialchars($order['customer_email'] ?? '') ?></div>
                                    <div class="mono text-[10px] text-gray-500 mt-0.5"><?= htmlspecialchars($order['phone'] ?? '') ?></div>
                                </td>

                                <!-- Shipping Address -->
                                <td class="p-4 text-gray-400 max-w-xs truncate">
                                    <div><?= htmlspecialchars($order['shipping_address']['address'] ?? '') ?></div>
                                    <div class="text-[11px]"><?= htmlspecialchars(($order['shipping_address']['city'] ?? '') . ', ' . ($order['shipping_address']['pincode'] ?? '')) ?></div>
                                </td>

                                <!-- Items -->
                                <td class="p-4">
                                    <div class="text-[11px] space-y-0.5">
                                        <?php foreach (($order['items'] ?? []) as $it): ?>
                                            <div class="text-gray-300">
                                                <span class="text-blood font-mono font-bold"><?= $it['quantity'] ?>×</span>
                                                <span><?= htmlspecialchars($it['title'] ?? 'Book') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>

                                <!-- Total & Payment -->
                                <td class="p-4">
                                    <div class="font-mono font-bold text-sm text-white">₹<?= number_format((float)($order['total_amount'] ?? 0), 2) ?></div>
                                    <div class="mono text-[9px] text-gray-500 uppercase mt-0.5"><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></div>
                                </td>

                                <!-- Status Updater Form -->
                                <td class="p-4">
                                    <form action="<?= baseUrl('admin/orders.php') ?>" method="POST" class="flex items-center gap-2">
                                        <input type="hidden" name="order_id" value="<?= $ordId ?>">
                                        <select name="status" class="bg-black border border-white/20 text-[11px] mono px-2 py-1 text-white focus:outline-none focus:border-blood">
                                            <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>CONFIRMED</option>
                                            <option value="processing" <?= $currentStatus === 'processing' ? 'selected' : '' ?>>PROCESSING</option>
                                            <option value="shipped" <?= $currentStatus === 'shipped' ? 'selected' : '' ?>>SHIPPED</option>
                                            <option value="delivered" <?= $currentStatus === 'delivered' ? 'selected' : '' ?>>DELIVERED</option>
                                            <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>CANCELLED</option>
                                        </select>
                                        <button type="submit" class="border border-white/20 px-2 py-1 text-[10px] mono hover:bg-white hover:text-black transition">
                                            SAVE
                                        </button>
                                    </form>
                                </td>

                                <!-- Action Link -->
                                <td class="p-4 text-right">
                                    <a href="<?= baseUrl('orders/confirmation.php?id=' . $ordId) ?>" target="_blank"
                                       class="mono text-[10px] border border-white/10 px-2.5 py-1 text-gray-400 hover:text-white hover:border-white transition">
                                        RECEIPT ↗
                                    </a>
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
