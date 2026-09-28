<!-- FOOTER -->
<footer class="border-t border-white/10 bg-[#050505] mt-auto relative z-10">
    <div class="max-w-[1500px] mx-auto px-6 md:px-10 py-16">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div>
                <div class="text-3xl font-bold tracking-[-0.08em] flex items-center">
                    <span class="font-serif-literary">DAASTAAN</span><span class="text-blood animate-pulse">.</span>
                </div>
                <p class="mono text-[10px] text-gray-500 mt-2 max-w-sm">
                    DIGITAL CURATION FOR CRITICAL THINKERS. BUILT WITH MODERN PHP 8.5 & MONGODB.
                </p>
            </div>

            <!-- Fast Navigation Links -->
            <div class="flex flex-wrap gap-x-8 gap-y-3 mono text-xs text-gray-400">
                <a href="<?= baseUrl('index.php') ?>" class="hover:text-blood transition transform hover:-translate-y-0.5">HOME</a>
                <a href="<?= baseUrl('books.php') ?>" class="hover:text-blood transition transform hover:-translate-y-0.5">CATALOG</a>
                <a href="<?= baseUrl('genres.php') ?>" class="hover:text-blood transition transform hover:-translate-y-0.5">GENRES</a>
                <a href="<?= baseUrl('poetry/index.php') ?>" class="hover:text-gold transition transform hover:-translate-y-0.5">POETRY & SHAYARI</a>
                <a href="<?= baseUrl('poets/index.php') ?>" class="hover:text-gold transition transform hover:-translate-y-0.5">POETS</a>
                <a href="<?= baseUrl('collections/index.php') ?>" class="hover:text-gold transition transform hover:-translate-y-0.5">COLLECTIONS</a>
                <a href="<?= baseUrl('cart/cart.php') ?>" class="hover:text-blood transition transform hover:-translate-y-0.5">CART</a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= baseUrl('orders/index.php') ?>" class="hover:text-blood transition transform hover:-translate-y-0.5">MY ORDERS</a>
                <?php else: ?>
                    <a href="<?= baseUrl('auth/login.php') ?>" class="hover:text-blood transition transform hover:-translate-y-0.5">LOGIN</a>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                    <a href="<?= baseUrl('admin/index.php') ?>" class="text-blood hover:text-white border-b border-blood transition pb-0.5">ADMIN CONSOLE</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="mono text-[9px] text-gray-500">
                © <?= date("Y") ?> DAASTAAN. — MODERN BOOKSTORE & DIGITAL LITERARY MEHFIL. ALL RIGHTS RESERVED.
            </span>
            <span class="mono text-[9px] text-gray-500 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>DATABASE ONLINE: MONGODB • STACK: PHP 8.5</span>
            </span>
        </div>
    </div>
</footer>

<script>
    // Smooth Lerped Cursor Glow Animation
    const cursorGlow = document.getElementById("cursorGlow");
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let currentX = mouseX;
    let currentY = mouseY;

    document.addEventListener("mousemove", (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateGlow() {
        if (cursorGlow) {
            // Linear interpolation (lerp) for smooth trailing motion
            currentX += (mouseX - currentX) * 0.12;
            currentY += (mouseY - currentY) * 0.12;
            cursorGlow.style.left = currentX + "px";
            cursorGlow.style.top = currentY + "px";
        }
        requestAnimationFrame(animateGlow);
    }
    animateGlow();

    // 3D Card Interactive Tilt Effect
    document.querySelectorAll(".book-card").forEach(card => {
        card.addEventListener("mousemove", (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; // x position within card
            const y = e.clientY - rect.top;  // y position within card
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            // Calculate tilt angle
            const rotateX = ((y - centerY) / centerY) * -7;
            const rotateY = ((x - centerX) / centerX) * 7;

            const cover = card.querySelector(".book-cover");
            if (cover) {
                cover.style.transform = `scale(1.07) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            }
        });

        card.addEventListener("mouseleave", () => {
            const cover = card.querySelector(".book-cover");
            if (cover) {
                cover.style.transform = "scale(1) rotateX(0deg) rotateY(0deg)";
            }
        });
    });

    // Staggered Scroll Reveal Observer
    const revealElements = document.querySelectorAll(".reveal");
    if (revealElements.length > 0 && "IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, idx) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add("show");
                    }, idx * 60);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealElements.forEach((el) => revealObserver.observe(el));
    }

    // Number Counting Animation (for Stats)
    document.querySelectorAll("[data-counter]").forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-counter"));
        const prefix = counter.getAttribute("data-prefix") || "";
        const duration = 1200; // ms
        const startTime = performance.now();

        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out cubic
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const currentVal = target * easeOut;

            if (target % 1 === 0) {
                counter.textContent = prefix + Math.floor(currentVal);
            } else {
                counter.textContent = prefix + currentVal.toFixed(2);
            }

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = prefix + (target % 1 === 0 ? target : target.toFixed(2));
            }
        }
        requestAnimationFrame(updateCounter);
    });
</script>

</body>
</html>
