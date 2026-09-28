<?php
require_once __DIR__ . '/../includes/auth.php';

requireAuth(baseUrl('orders/checkout.php'));

$cartItems = getCartItems();
if (empty($cartItems)) {
    setFlash('error', 'Your cart is empty. Add books before proceeding to checkout.');
    header('Location: ' . baseUrl('books.php'));
    exit;
}

$subtotal = getCartSubtotal();
$shippingFee = ($subtotal > 999) ? 0.0 : 50.0;
$grandTotal = $subtotal + $shippingFee;

$error = '';
$user = currentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? 'cod';

    if (empty($fullName) || empty($phone) || empty($address) || empty($city) || empty($pincode)) {
        $error = 'Please fill in all mandatory shipping fields.';
    } else {
        try {
            // Verify stock for all items
            foreach ($cartItems as $bookId => $item) {
                $bookDoc = $db->books->findOne(['_id' => new MongoDB\BSON\ObjectId($bookId)]);
                if (!$bookDoc || (int)($bookDoc['stock'] ?? 0) < (int)$item['quantity']) {
                    $error = 'Stock has changed for "' . htmlspecialchars($item['title']) . '". Please adjust your cart.';
                    break;
                }
            }

            if (empty($error)) {
                // Deduct stock
                foreach ($cartItems as $bookId => $item) {
                    $db->books->updateOne(
                        ['_id' => new MongoDB\BSON\ObjectId($bookId)],
                        ['$inc' => ['stock' => -(int)$item['quantity']]]
                    );
                }

                // Insert Order Document
                $orderData = [
                    'user_id' => $user['id'],
                    'customer_name' => $fullName,
                    'customer_email' => $user['email'],
                    'phone' => $phone,
                    'shipping_address' => [
                        'address' => $address,
                        'city' => $city,
                        'state' => $state,
                        'pincode' => $pincode
                    ],
                    'items' => array_values($cartItems),
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $grandTotal,
                    'payment_method' => ($paymentMethod === 'card' ? 'Online Simulation (Paid)' : 'Cash on Delivery'),
                    'payment_status' => ($paymentMethod === 'card' ? 'paid' : 'pending'),
                    'status' => 'confirmed',
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ];

                $insertResult = $db->orders->insertOne($orderData);
                $orderId = (string)$insertResult->getInsertedId();

                // Clear cart
                $_SESSION['cart'] = [];

                setFlash('success', 'Your order #' . substr($orderId, -6) . ' has been placed successfully!');
                header('Location: ' . baseUrl('orders/confirmation.php?id=' . $orderId));
                exit;
            }
        } catch (Exception $e) {
            $error = 'Order creation failed: ' . $e->getMessage();
        }
    }
}

$pageTitle = "Checkout & Fulfillment — DAASTAAN. Literary Archive & Bookstore";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <div class="mb-10">
        <a href="<?= baseUrl('cart/cart.php') ?>" class="mono text-xs text-gray-500 hover:text-white transition flex items-center gap-2 mb-4">
            ← BACK TO CART
        </a>
        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">FINAL STEP</div>
        <h1 class="text-4xl md:text-6xl font-bold tracking-tight">CHECKOUT & SHIPPING.</h1>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-8 p-4 border border-blood bg-[#1a0508] text-blood text-xs mono">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="<?= baseUrl('orders/checkout.php') ?>" method="POST" class="grid lg:grid-cols-[1fr_450px] gap-12 items-start">

        <!-- LEFT: SHIPPING & PAYMENT DETAILS -->
        <div class="space-y-10">
            
            <!-- Shipping Information -->
            <div class="border border-white/10 p-8 bg-[#080808]">
                <h2 class="text-xl font-bold tracking-tight mb-6 flex items-center gap-3">
                    <span class="mono text-xs text-blood">01</span>
                    <span>DELIVERY ADDRESS</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="sm:col-span-2">
                        <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Recipient Name *</label>
                        <input type="text" name="full_name" required value="<?= htmlspecialchars($_POST['full_name'] ?? $user['name'] ?? '') ?>"
                               class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    </div>

                    <div>
                        <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Phone Number *</label>
                        <input type="tel" name="phone" required placeholder="+91 9876543210" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                               class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    </div>

                    <div>
                        <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Pincode *</label>
                        <input type="text" name="pincode" required placeholder="560001" value="<?= htmlspecialchars($_POST['pincode'] ?? '') ?>"
                               class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Street Address *</label>
                        <textarea name="address" rows="3" required placeholder="Flat / House No., Building Name, Street..."
                                  class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">City *</label>
                        <input type="text" name="city" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"
                               class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    </div>

                    <div>
                        <label class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">State *</label>
                        <input type="text" name="state" required value="<?= htmlspecialchars($_POST['state'] ?? 'Karnataka') ?>"
                               class="w-full bg-black/60 border border-white/15 px-4 py-3 text-sm text-white focus:outline-none focus:border-blood transition">
                    </div>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="border border-white/10 p-8 bg-[#080808]">
                <h2 class="text-xl font-bold tracking-tight mb-6 flex items-center gap-3">
                    <span class="mono text-xs text-blood">02</span>
                    <span>PAYMENT METHOD</span>
                </h2>

                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="border border-white/15 p-5 flex items-start gap-4 cursor-pointer hover:border-blood transition bg-black/40">
                        <input type="radio" name="payment_method" value="cod" checked class="mt-1 accent-blood">
                        <div>
                            <span class="block text-sm font-bold text-white">Cash on Delivery</span>
                            <span class="text-xs text-gray-500 mt-1 block">Pay in cash or UPI when your books arrive at your doorstep.</span>
                        </div>
                    </label>

                    <label class="border border-white/15 p-5 flex items-start gap-4 cursor-pointer hover:border-blood transition bg-black/40">
                        <input type="radio" name="payment_method" value="card" class="mt-1 accent-blood">
                        <div>
                            <span class="block text-sm font-bold text-white">Online Payment (Mock)</span>
                            <span class="text-xs text-gray-500 mt-1 block">Simulate instant card/UPI transaction with instant receipt.</span>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        <!-- RIGHT: ORDER OVERVIEW -->
        <div class="border border-white/10 p-8 bg-[#0b0b0b] sticky top-28 space-y-6">
            <div class="mono text-[10px] text-blood tracking-[0.3em] uppercase">ITEMS OVERVIEW</div>
            <h2 class="text-2xl font-bold tracking-tight">ORDER (<?= count($cartItems) ?> <?= count($cartItems) === 1 ? 'ITEM' : 'ITEMS' ?>)</h2>

            <!-- Items Mini List -->
            <div class="divide-y divide-white/10 max-h-60 overflow-y-auto pr-2">
                <?php foreach ($cartItems as $item): ?>
                    <div class="py-3 flex justify-between items-center text-xs">
                        <div>
                            <span class="font-bold text-white line-clamp-1"><?= htmlspecialchars($item['title']) ?></span>
                            <span class="text-gray-500 mono mt-0.5 block">Qty: <?= $item['quantity'] ?> × ₹<?= $item['price'] ?></span>
                        </div>
                        <span class="font-semibold text-white ml-4">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="space-y-3 text-sm pt-4 border-t border-white/10">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span class="text-white font-semibold">₹<?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Delivery</span>
                    <span class="<?= $shippingFee === 0.0 ? 'text-emerald-400' : 'text-white' ?> font-semibold">
                        <?= $shippingFee === 0.0 ? 'FREE' : '₹' . number_format($shippingFee, 2) ?>
                    </span>
                </div>
                <div class="pt-4 border-t border-white/10 flex justify-between items-baseline">
                    <span class="text-base font-bold">Total Amount</span>
                    <span class="text-2xl font-bold text-blood">₹<?= number_format($grandTotal, 2) ?></span>
                </div>
            </div>

            <button type="submit" class="danger-button w-full py-4 text-xs font-bold tracking-widest block text-center mt-6">
                PLACE ORDER CONFIRMATION →
            </button>

            <p class="text-[10px] mono text-gray-600 text-center">
                By placing order, stock is deducted from MongoDB inventory.
            </p>
        </div>

    </form>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
