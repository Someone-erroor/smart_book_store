<?php
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = "Shopping Bag — DAASTAAN. Literary Archive & Bookstore";
$cartItems = getCartItems();
$subtotal = getCartSubtotal();
$shippingFee = ($subtotal > 999 || $subtotal === 0.0) ? 0.0 : 50.0;
$grandTotal = $subtotal + $shippingFee;

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-14 max-w-[1500px] mx-auto px-6 md:px-10">

    <div class="mb-10 flex flex-col sm:flex-row justify-between sm:items-end gap-4">
        <div>
            <div class="mono text-[10px] tracking-[0.4em] text-blood mb-2">SHOPPING BAG</div>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight">YOUR CART.</h1>
        </div>

        <?php if (!empty($cartItems)): ?>
            <a href="<?= baseUrl('cart/clear.php') ?>" 
               onclick="return confirm('Are you sure you want to clear your entire cart?')"
               class="mono text-xs text-gray-500 hover:text-blood transition underline">
                CLEAR CART
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="border border-white/10 p-16 md:p-24 text-center bg-[#080808]">
            <div class="mono text-blood text-xs tracking-widest mb-4">EMPTY CONTAINER</div>
            <h2 class="text-3xl md:text-5xl font-bold">Your cart has no books yet.</h2>
            <p class="text-gray-400 text-sm mt-4 max-w-md mx-auto">
                Explore our curated collection of Urdu & Hindi poetry collections, philosophical works, and contemporary classics.
            </p>
            <a href="<?= baseUrl('books.php') ?>" class="danger-button inline-block mt-8 px-8 py-4 text-xs font-bold tracking-widest">
                DISCOVER BOOKS →
            </a>
        </div>
    <?php else: ?>

        <div class="grid lg:grid-cols-[1fr_420px] gap-12 items-start">
            
            <!-- ITEMS LIST -->
            <div class="border border-white/10 divide-y divide-white/10 bg-[#080808]">
                <?php foreach ($cartItems as $bookId => $item): ?>
                    <?php 
                        $itemTotal = ((float)$item['price']) * ((int)$item['quantity']);
                        $coverBg = $item['cover_bg'] ?? 'from-[#241018] to-[#090909]';
                    ?>
                    <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:bg-white/[0.02] transition duration-300 group">
                        
                        <!-- Thumbnail & Details -->
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-24 bg-gradient-to-br <?= htmlspecialchars($coverBg) ?> border border-white/10 flex-shrink-0 flex items-center justify-center p-2 text-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                                <span class="mono text-[8px] text-blood line-clamp-2 uppercase font-bold"><?= htmlspecialchars($item['category'] ?? 'BOOK') ?></span>
                            </div>

                            <div>
                                <a href="<?= baseUrl('book-details.php?id=' . $bookId) ?>" class="text-lg font-bold hover:text-blood transition">
                                    <?= htmlspecialchars($item['title']) ?>
                                </a>
                                <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($item['author']) ?></p>
                                <div class="text-sm font-semibold text-gray-300 mt-2 font-mono">
                                    ₹<?= htmlspecialchars((string)$item['price']) ?> <span class="text-xs text-gray-600 font-normal">each</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity & Item Actions -->
                        <div class="flex items-center justify-between w-full sm:w-auto gap-8 pt-4 sm:pt-0 border-t sm:border-0 border-white/5">
                            <!-- Quantity Controls -->
                            <div class="flex items-center border border-white/20 bg-black">
                                <a href="<?= baseUrl('cart/update.php?id=' . $bookId . '&action=decrease') ?>" 
                                   class="w-8 h-8 flex items-center justify-center hover:bg-white hover:text-black transition font-bold text-xs active:scale-90">−</a>
                                <span class="w-10 h-8 flex items-center justify-center text-xs font-bold border-x border-white/20 font-mono">
                                    <?= $item['quantity'] ?>
                                </span>
                                <a href="<?= baseUrl('cart/update.php?id=' . $bookId . '&action=increase') ?>" 
                                   class="w-8 h-8 flex items-center justify-center hover:bg-white hover:text-black transition font-bold text-xs active:scale-90">+</a>
                            </div>

                            <!-- Line Total -->
                            <div class="text-right">
                                <div class="text-base font-bold font-mono text-white">₹<?= number_format($itemTotal, 2) ?></div>
                                <a href="<?= baseUrl('cart/remove.php?id=' . $bookId) ?>" 
                                   class="mono text-[10px] text-gray-500 hover:text-blood transition block mt-1 hover:underline">
                                    REMOVE
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- ORDER SUMMARY CARD -->
            <div class="border border-white/10 p-8 bg-[#0b0b0b] sticky top-28 space-y-6">
                <div class="mono text-[10px] text-blood tracking-[0.3em] uppercase">SUMMARY</div>
                <h2 class="text-2xl font-bold tracking-tight">ORDER TOTAL</h2>

                <div class="space-y-4 text-sm pt-4 border-t border-white/10">
                    <div class="flex justify-between text-gray-400">
                        <span>Items Subtotal</span>
                        <span class="text-white font-semibold">₹<?= number_format($subtotal, 2) ?></span>
                    </div>

                    <div class="flex justify-between text-gray-400">
                        <span>Shipping</span>
                        <span class="<?= $shippingFee === 0.0 ? 'text-emerald-400' : 'text-white' ?> font-semibold">
                            <?= $shippingFee === 0.0 ? 'FREE (Orders > ₹999)' : '₹' . number_format($shippingFee, 2) ?>
                        </span>
                    </div>

                    <div class="flex justify-between text-gray-400 text-xs">
                        <span>Taxes & Duties</span>
                        <span class="text-gray-500">Included in book price</span>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex justify-between items-baseline">
                        <span class="text-base font-bold">Total Amount</span>
                        <span class="text-2xl font-bold text-blood">₹<?= number_format($grandTotal, 2) ?></span>
                    </div>
                </div>

                <a href="<?= baseUrl('orders/checkout.php') ?>" class="danger-button w-full py-4 text-xs font-bold tracking-widest block text-center mt-6">
                    CHECKOUT ORDER →
                </a>

                <a href="<?= baseUrl('books.php') ?>" class="block text-center mono text-xs text-gray-500 hover:text-white transition">
                    ← Continue Browsing
                </a>
            </div>

        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
