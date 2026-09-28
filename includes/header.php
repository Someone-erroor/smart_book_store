<?php
require_once __DIR__ . '/auth.php';
$flash = getFlash();
$cartCount = getCartCount();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'DAASTAAN. — Literary Archive & Bookstore') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blood: "#ff1744",
                        void: "#050505",
                        smoke: "#111111",
                        charcoal: "#0c0c0c",
                        gold: "#c5a059",
                        "gold-light": "#e8cf8d",
                        ivory: "#f8f6f0"
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=DM+Mono:wght@400;500&family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: #050505;
            color: #f5f5f5;
            font-family: "Space Grotesk", sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main { flex: 1 0 auto; }
        .mono { font-family: "DM Mono", monospace; }
        .font-serif-literary { font-family: "Playfair Display", Georgia, serif; }
        .font-cinzel { font-family: "Cinzel", serif; }

        /* Noise Texture */
        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 100;
            opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.7'/%3E%3C/svg%3E");
        }

        /* Cursor Glow with smooth lag */
        .cursor-glow {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 23, 68, 0.12) 0%, rgba(255, 23, 68, 0.02) 60%, transparent 80%);
            filter: blur(60px);
            pointer-events: none;
            transform: translate(-50%, -50%);
            z-index: 0;
            transition: width 0.3s ease, height 0.3s ease;
        }

        /* Navbar & Link Animations */
        .nav-link {
            position: relative;
            transition: color 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0;
            height: 1.5px;
            background: #ff1744;
            transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 0 8px rgba(255, 23, 68, 0.8);
        }
        .nav-link:hover { color: white; }
        .nav-link:hover::after { width: 100%; }

        /* Book Card Modern Architecture */
        .book-card {
            background: #090909;
            border: 1px solid #1f1f1f;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), 
                        border-color 0.45s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .book-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.03), transparent);
            transform: skewX(-20deg);
            transition: left 0.8s ease;
            pointer-events: none;
        }
        .book-card:hover::before {
            left: 150%;
        }
        .book-card:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 23, 68, 0.6);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.8),
                        0 0 30px -5px rgba(255, 23, 68, 0.15);
        }

        /* 3D Realistic Book Cover */
        .book-cover {
            position: relative;
            transform-style: preserve-3d;
            perspective: 1200px;
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), 
                        filter 0.5s ease,
                        box-shadow 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: -6px 8px 24px rgba(0, 0, 0, 0.7), 
                        -1px 2px 5px rgba(0, 0, 0, 0.4),
                        inset 4px 0 8px rgba(255, 255, 255, 0.1);
        }
        /* Realistic Book Spine highlight */
        .book-cover::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 8px;
            background: linear-gradient(to right, rgba(255,255,255,0.2), rgba(0,0,0,0.5));
            border-right: 1px solid rgba(0,0,0,0.4);
            pointer-events: none;
        }
        .book-card:hover .book-cover {
            transform: scale(1.06) rotateY(-8deg) rotateX(4deg);
            filter: brightness(1.12);
            box-shadow: -15px 20px 40px rgba(0, 0, 0, 0.85),
                        0 0 30px rgba(255, 23, 68, 0.2);
        }

        /* Premium Buttons with Shimmer Light Beam */
        .danger-button {
            position: relative;
            overflow: hidden;
            background: #ff1744;
            color: black;
            font-weight: 700;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 20px rgba(255, 23, 68, 0.25);
        }
        .danger-button::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            transform: rotate(30deg);
            transition: left 0.7s ease;
        }
        .danger-button:hover::after {
            left: 140%;
        }
        .danger-button:hover {
            background: #ff2d55;
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 23, 68, 0.5), 0 0 15px rgba(255, 23, 68, 0.3);
        }
        .danger-button:active {
            transform: translateY(0px);
        }

        /* Floating Animation */
        @keyframes floatHero {
            0%, 100% {
                transform: translateY(0px) rotate(8deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }
        .hero-book-float {
            animation: floatHero 6s ease-in-out infinite;
        }

        /* Pulse Badge Animation */
        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 5px rgba(255, 23, 68, 0.2);
                border-color: rgba(255, 23, 68, 0.4);
            }
            50% {
                box-shadow: 0 0 18px rgba(255, 23, 68, 0.6);
                border-color: rgba(255, 23, 68, 0.9);
            }
        }
        .pulse-badge {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        /* Cart Bump Animation */
        @keyframes cartBump {
            0% { transform: scale(1); }
            40% { transform: scale(1.25) rotate(-6deg); }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        .cart-bump {
            animation: cartBump 0.5s ease-out;
        }

        /* Scroll Reveal with Stagger */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Interactive Filter Pills */
        .category-pill {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .category-pill:hover {
            transform: translateY(-2px);
            border-color: #ff1744;
            color: white;
        }

        /* Toast Alert Entrance */
        @keyframes slideDownFade {
            from {
                opacity: 0;
                transform: translate(-50%, -20px);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }
        .toast-animate {
            animation: slideDownFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body>
<div class="noise"></div>
<div id="cursorGlow" class="cursor-glow"></div>

<?php if ($flash): ?>
    <div class="fixed top-24 left-1/2 -translate-x-1/2 z-50 w-full max-w-lg px-4 toast-animate">
        <div class="p-4 border <?= $flash['type'] === 'error' ? 'border-blood bg-[#1c080b]/95 text-blood' : 'border-emerald-500 bg-[#081c10]/95 text-emerald-400' ?> flex items-center justify-between shadow-2xl backdrop-blur-md">
            <span class="mono text-xs tracking-wider"><?= htmlspecialchars($flash['message']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-xs hover:opacity-75 ml-4 font-bold">✕</button>
        </div>
    </div>
<?php endif; ?>
