<?php
// includes/navbar.php
$cartCount = getCartCount();
$user = currentUser();

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$currentScript = basename($_SERVER['PHP_SELF'] ?? '');

$isHome = ($currentScript === 'index.php' && strpos($currentUri, 'poetry') === false && strpos($currentUri, 'poets') === false && strpos($currentUri, 'collections') === false && strpos($currentUri, 'admin') === false && strpos($currentUri, 'orders') === false && strpos($currentUri, 'cart') === false);
$isBooks = ($currentScript === 'books.php' || $currentScript === 'book-details.php');
$isGenres = ($currentScript === 'genres.php');
$isPoetry = (strpos($currentUri, 'poetry') !== false);
$isPoets = (strpos($currentUri, 'poets') !== false);
$isCollections = (strpos($currentUri, 'collections') !== false);
$isOrders = (strpos($currentUri, 'orders') !== false);
$isAdminSection = (strpos($currentUri, 'admin') !== false);
$isCart = (strpos($currentUri, 'cart') !== false);
?>
<header class="sticky top-0 left-0 w-full z-40 bg-[#050505]/95 backdrop-blur-md border-b border-white/10">
    <nav class="max-w-[1500px] mx-auto px-6 md:px-10 h-20 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="<?= baseUrl('index.php') ?>" class="text-2xl font-bold tracking-[-0.08em] flex items-center gap-1 group">
            <span class="text-white group-hover:text-gold transition font-serif-literary tracking-normal">DAASTAAN</span>
            <span class="text-blood font-sans">.</span>
            <span class="hidden xl:inline-block mono text-[9px] text-gray-500 tracking-widest pl-2 border-l border-white/10">LITERARY ARCHIVE & BOOKSTORE</span>
        </a>

        <!-- Center Links with Active States -->
        <div class="hidden lg:flex items-center gap-7 text-xs mono tracking-wider">
            <a href="<?= baseUrl('index.php') ?>" 
               class="nav-link <?= $isHome ? 'text-white font-bold border-b-2 border-blood pb-1' : 'text-gray-400 hover:text-white' ?>">
                HOME
            </a>
            
            <a href="<?= baseUrl('books.php') ?>" 
               class="nav-link <?= $isBooks ? 'text-white font-bold border-b-2 border-blood pb-1' : 'text-gray-400 hover:text-white' ?>">
                BOOKS
            </a>
            
            <a href="<?= baseUrl('genres.php') ?>" 
               class="nav-link <?= $isGenres ? 'text-white font-bold border-b-2 border-blood pb-1' : 'text-gray-400 hover:text-white' ?>">
                GENRES
            </a>

            <a href="<?= baseUrl('poetry/index.php') ?>" 
               class="nav-link <?= $isPoetry ? 'text-gold font-bold border-b-2 border-gold pb-1 shadow-[0_4px_15px_rgba(197,160,89,0.3)]' : 'text-gray-300 hover:text-gold' ?> flex items-center gap-1.5">
                <span>POETRY & SHAYARI</span>
                <span class="text-[9px] text-gold animate-pulse">✦</span>
            </a>
            
            <a href="<?= baseUrl('poets/index.php') ?>" 
               class="nav-link <?= $isPoets ? 'text-gold font-bold border-b-2 border-gold pb-1' : 'text-gray-400 hover:text-gold' ?>">
                POETS
            </a>
            
            <a href="<?= baseUrl('collections/index.php') ?>" 
               class="nav-link <?= $isCollections ? 'text-gold font-bold border-b-2 border-gold pb-1' : 'text-gray-400 hover:text-gold' ?>">
                COLLECTIONS
            </a>
            
            <?php if (isLoggedIn()): ?>
                <a href="<?= baseUrl('orders/index.php') ?>" 
                   class="nav-link <?= $isOrders ? 'text-white font-bold border-b-2 border-blood pb-1' : 'text-gray-400 hover:text-white' ?>">
                    MY ORDERS
                </a>
            <?php endif; ?>

            <?php if (isAdmin()): ?>
                <a href="<?= baseUrl('admin/index.php') ?>" 
                   class="border <?= $isAdminSection ? 'border-blood bg-blood text-black font-bold' : 'border-blood/60 bg-blood/10 text-blood hover:bg-blood hover:text-black' ?> px-3 py-1 transition duration-200">
                    ⚡ ADMIN
                </a>
            <?php endif; ?>
        </div>

        <!-- Right Side: Cart + User Auth + Mobile Toggle -->
        <div class="flex items-center gap-4">
            
            <!-- Cart Link -->
            <a href="<?= baseUrl('cart/cart.php') ?>" 
               class="mono text-xs border <?= $isCart ? 'border-blood bg-blood/10 text-white' : 'border-white/15 hover:border-blood hover:text-white' ?> px-3.5 py-2 transition flex items-center gap-2 bg-black/40">
                <span class="text-gray-400">CART</span>
                <span class="text-blood font-bold">[<?= $cartCount ?>]</span>
            </a>

            <?php if (isLoggedIn()): ?>
                <div class="hidden sm:flex items-center gap-3">
                    <div class="flex flex-col text-right">
                        <span class="text-xs font-semibold text-white leading-tight"><?= htmlspecialchars($user['name'] ?? 'User') ?></span>
                        <span class="mono text-[9px] <?= isAdmin() ? 'text-blood' : 'text-gold' ?> uppercase"><?= htmlspecialchars($user['role'] ?? 'customer') ?></span>
                    </div>
                    <a href="<?= baseUrl('auth/logout.php') ?>" class="border border-white/20 px-3 py-1.5 text-xs mono text-gray-400 hover:text-white hover:border-blood transition">
                        LOGOUT
                    </a>
                </div>
            <?php else: ?>
                <div class="hidden sm:flex items-center gap-2">
                    <a href="<?= baseUrl('auth/login.php') ?>" class="border border-white/20 px-3.5 py-2 text-xs tracking-wider hover:bg-white hover:text-black transition">
                        LOGIN
                    </a>
                    <a href="<?= baseUrl('auth/register.php') ?>" class="bg-white/10 px-3.5 py-2 text-xs tracking-wider hover:bg-blood hover:text-black transition">
                        REGISTER
                    </a>
                </div>
            <?php endif; ?>

            <!-- Mobile Hamburger Button -->
            <button id="mobileMenuBtn" aria-label="Toggle Navigation" class="lg:hidden p-2 text-gray-400 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </nav>

    <!-- Mobile Dropdown Menu -->
    <div id="mobileMenu" class="hidden lg:hidden border-t border-white/10 bg-[#080808]/98 px-6 py-6 space-y-4 mono text-xs">
        <a href="<?= baseUrl('index.php') ?>" class="block <?= $isHome ? 'text-blood font-bold' : 'text-gray-300 hover:text-white' ?> py-1">HOME</a>
        <a href="<?= baseUrl('books.php') ?>" class="block <?= $isBooks ? 'text-blood font-bold' : 'text-gray-300 hover:text-white' ?> py-1">ALL BOOKS</a>
        <a href="<?= baseUrl('genres.php') ?>" class="block <?= $isGenres ? 'text-blood font-bold' : 'text-gray-300 hover:text-white' ?> py-1">GENRES & TAXONOMY</a>
        <a href="<?= baseUrl('poetry/index.php') ?>" class="block <?= $isPoetry ? 'text-gold font-bold' : 'text-gold hover:text-gold-light' ?> py-1 flex items-center justify-between">
            <span>POETRY & SHAYARI</span>
            <span class="text-[10px] text-gold">✦ MEHFIL</span>
        </a>
        <a href="<?= baseUrl('poets/index.php') ?>" class="block <?= $isPoets ? 'text-gold font-bold' : 'text-gray-300 hover:text-gold' ?> py-1">POETS & AUTHORS</a>
        <a href="<?= baseUrl('collections/index.php') ?>" class="block <?= $isCollections ? 'text-gold font-bold' : 'text-gray-300 hover:text-gold' ?> py-1">LITERARY COLLECTIONS (MOODS)</a>

        <?php if (isLoggedIn()): ?>
            <a href="<?= baseUrl('orders/index.php') ?>" class="block <?= $isOrders ? 'text-white font-bold' : 'text-gray-300 hover:text-white' ?> py-1">MY ORDERS</a>
            <?php if (isAdmin()): ?>
                <a href="<?= baseUrl('admin/index.php') ?>" class="block text-blood font-bold py-1">ADMIN CONSOLE</a>
            <?php endif; ?>
            <div class="pt-3 border-t border-white/10 flex justify-between items-center">
                <span class="text-white"><?= htmlspecialchars($user['name'] ?? '') ?> (<?= htmlspecialchars($user['role'] ?? '') ?>)</span>
                <a href="<?= baseUrl('auth/logout.php') ?>" class="text-blood underline">LOGOUT</a>
            </div>
        <?php else: ?>
            <div class="pt-3 border-t border-white/10 flex gap-3">
                <a href="<?= baseUrl('auth/login.php') ?>" class="w-1/2 text-center border border-white/20 py-2.5">LOGIN</a>
                <a href="<?= baseUrl('auth/register.php') ?>" class="w-1/2 text-center bg-white/10 py-2.5">REGISTER</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
    const mobileBtn = document.getElementById("mobileMenuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }
</script>

