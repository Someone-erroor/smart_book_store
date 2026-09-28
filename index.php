<?php

require_once __DIR__ . "/config/database.php";

$pageTitle = "SMART. — Books That Bite";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blood: "#ff1744",
                        void: "#050505",
                        smoke: "#111111"
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: #050505;
            color: #f5f5f5;
            font-family: "Space Grotesk", sans-serif;
            overflow-x: hidden;
        }

        .mono {
            font-family: "DM Mono", monospace;
        }

        /* Noise */

        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 100;
            opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.7'/%3E%3C/svg%3E");
        }

        /* Cursor glow */

        .cursor-glow {
            position: fixed;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255, 23, 68, 0.08);
            filter: blur(80px);
            pointer-events: none;
            transform: translate(-50%, -50%);
            z-index: 0;
        }

        /* Navbar */

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0;
            height: 1px;
            background: #ff1744;
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: white;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Hero */

        .hero-title {
            font-size: clamp(5rem, 14vw, 13rem);
            line-height: 0.76;
            letter-spacing: -0.08em;
            font-weight: 700;
        }

        .outline-text {
            color: transparent;
            -webkit-text-stroke: 1px rgba(255,255,255,0.45);
        }

        .hero-book {
            animation: floatBook 5s ease-in-out infinite;
            transform: rotate(8deg);
            box-shadow:
                25px 35px 70px rgba(0,0,0,0.8),
                -20px 0 80px rgba(255,23,68,0.12);
        }

        @keyframes floatBook {
            0%, 100% {
                transform: translateY(0) rotate(8deg);
            }

            50% {
                transform: translateY(-18px) rotate(4deg);
            }
        }

        /* Marquee */

        .marquee {
            overflow: hidden;
            border-top: 1px solid #242424;
            border-bottom: 1px solid #242424;
            white-space: nowrap;
        }

        .marquee-track {
            display: inline-flex;
            animation: marquee 22s linear infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        /* Reveal */

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition:
                opacity 0.8s ease,
                transform 0.8s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Book card */

        .book-card {
            position: relative;
            overflow: hidden;
            border: 1px solid #252525;
            background: #0b0b0b;
            transition:
                transform 0.5s ease,
                border-color 0.5s ease;
        }

        .book-card:hover {
            transform: translateY(-10px);
            border-color: #ff1744;
        }

        .book-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                transparent 50%,
                rgba(255,23,68,0.12)
            );
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .book-card:hover::before {
            opacity: 1;
        }

        .book-cover {
            transition:
                transform 0.7s cubic-bezier(.2,.8,.2,1),
                filter 0.7s ease;
        }

        .book-card:hover .book-cover {
            transform: scale(1.08) rotate(-2deg);
            filter: brightness(1.15);
        }

        /* Category */

        .category {
            border-bottom: 1px solid #252525;
            transition:
                padding 0.4s ease,
                color 0.4s ease;
        }

        .category:hover {
            padding-left: 20px;
            color: #ff1744;
        }

        /* Red button */

        .danger-button {
            position: relative;
            overflow: hidden;
            background: #ff1744;
            color: black;
        }

        .danger-button::before {
            content: "";
            position: absolute;
            width: 0;
            height: 0;
            background: white;
            border-radius: 50%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            transition:
                width 0.5s ease,
                height 0.5s ease;
        }

        .danger-button:hover::before {
            width: 400px;
            height: 400px;
        }

        .danger-button span {
            position: relative;
            z-index: 2;
        }

        /* Scroll indicator */

        .scroll-line {
            animation: scrollLine 1.7s ease-in-out infinite;
        }

        @keyframes scrollLine {
            0% {
                transform: scaleY(0);
                transform-origin: top;
            }

            50% {
                transform: scaleY(1);
                transform-origin: top;
            }

            51% {
                transform-origin: bottom;
            }

            100% {
                transform: scaleY(0);
                transform-origin: bottom;
            }
        }
    </style>
</head>


<body>

<div class="noise"></div>

<div id="cursorGlow" class="cursor-glow"></div>


<!-- NAVBAR -->

<header class="fixed top-0 left-0 w-full z-50">

    <nav class="max-w-[1500px] mx-auto px-6 md:px-10 h-24 flex items-center justify-between">

        <a
            href="index.php"
            class="text-2xl font-bold tracking-[-0.08em]"
        >
            SMART<span class="text-blood">.</span>
        </a>


        <div class="hidden md:flex items-center gap-10 text-sm text-gray-500">

            <a href="index.php" class="nav-link">HOME</a>

            <a href="books.php" class="nav-link">BOOKS</a>

            <a href="#categories" class="nav-link">CATEGORIES</a>

        </div>


        <div class="flex items-center gap-5">

            <a
                href="cart/cart.php"
                class="mono text-xs text-gray-500 hover:text-white transition"
            >
                CART [0]
            </a>

            <a
                href="auth/login.php"
                class="border border-white/20 px-5 py-2.5 text-xs tracking-wider hover:bg-white hover:text-black transition"
            >
                LOGIN
            </a>

        </div>

    </nav>

</header>


<main>


<!-- HERO -->

<section class="relative min-h-screen flex items-center overflow-hidden">

    <div class="absolute top-[15%] left-[35%] w-[500px] h-[500px] bg-blood/10 blur-[150px] rounded-full"></div>


    <div class="max-w-[1500px] mx-auto px-6 md:px-10 w-full pt-24">

        <div class="grid lg:grid-cols-[1fr_420px] gap-12 items-center">


            <div class="relative z-10">

                <div class="mono text-[10px] tracking-[0.4em] text-gray-600 mb-10">
                    DIGITAL BOOKSTORE / 2026
                </div>


                <h1 class="hero-title">

                    STORIES

                    <br>

                    <span class="outline-text">
                        THAT
                    </span>

                    <br>

                    <span class="text-blood">
                        BITE.
                    </span>

                </h1>


                <div class="mt-12 flex flex-col md:flex-row gap-8 items-start md:items-center">

                    <p class="max-w-md text-gray-500 leading-relaxed text-sm">
                        Books aren't decoration.
                        They're escape routes, weapons,
                        obsessions and entire worlds waiting
                        to be opened.
                    </p>


                    <a
                        href="books.php"
                        class="danger-button px-8 py-4 text-xs font-bold tracking-widest"
                    >
                        <span>ENTER THE STORE →</span>
                    </a>

                </div>

            </div>


            <!-- BOOK -->

            <div class="relative hidden lg:flex justify-center items-center min-h-[550px]">

                <div class="absolute w-[330px] h-[450px] border border-blood/20 rotate-12"></div>

                <div class="absolute w-[330px] h-[450px] border border-white/10 -rotate-6"></div>


                <div
                    class="hero-book relative w-[280px] h-[400px] bg-gradient-to-br from-[#21070d] via-[#470b18] to-black border border-blood/40 p-8 flex flex-col justify-between"
                >

                    <div class="mono text-[9px] text-blood tracking-[0.3em]">
                        SMART EDITIONS
                    </div>


                    <div>

                        <div class="text-5xl font-bold leading-none">
                            THE
                        </div>

                        <div class="text-6xl font-bold text-blood leading-none">
                            DARK
                        </div>

                        <div class="text-5xl font-bold leading-none">
                            PAGE
                        </div>

                    </div>


                    <div class="flex justify-between items-end">

                        <div class="mono text-[8px] text-gray-500">
                            VOL. 01
                        </div>

                        <div class="w-10 h-10 border border-blood rounded-full flex items-center justify-center text-blood text-xs">
                            ↓
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- SCROLL -->

        <div class="absolute bottom-10 left-10 hidden md:flex items-center gap-4">

            <div class="w-[1px] h-12 bg-gray-800 overflow-hidden">
                <div class="scroll-line w-full h-full bg-blood"></div>
            </div>

            <span class="mono text-[9px] tracking-[0.3em] text-gray-600">
                SCROLL TO DISCOVER
            </span>

        </div>

    </div>

</section>


<!-- MARQUEE -->

<div class="marquee py-5">

    <div class="marquee-track mono text-xs tracking-[0.35em] text-gray-600">

        <span class="mx-8">NEW RELEASES</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">DARK FICTION</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">THRILLERS</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">SCI-FI</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">CLASSICS</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">NEW RELEASES</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">DARK FICTION</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">THRILLERS</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">SCI-FI</span>
        <span class="text-blood">✦</span>

        <span class="mx-8">CLASSICS</span>
        <span class="text-blood">✦</span>

    </div>

</div>


<!-- INTRO -->

<section class="py-36">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10">

        <div class="grid md:grid-cols-[200px_1fr] gap-10 reveal">

            <div class="mono text-[10px] text-gray-600 tracking-widest">
                01 / WHY SMART
            </div>

            <h2 class="text-4xl md:text-7xl max-w-5xl leading-[1.05] tracking-[-0.05em]">

                We don't sell books.

                <span class="text-gray-600">
                    We help you find the one you weren't looking for.
                </span>

            </h2>

        </div>

    </div>

</section>


<!-- FEATURED -->

<section class="pb-36">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10">

        <div class="flex justify-between items-end mb-14 reveal">

            <div>

                <div class="mono text-[10px] text-blood tracking-[0.3em] mb-4">
                    02 / CURATED
                </div>

                <h2 class="text-5xl md:text-7xl font-bold tracking-[-0.06em]">
                    THE SHELF.
                </h2>

            </div>


            <a
                href="books.php"
                class="hidden md:block mono text-[10px] text-gray-600 hover:text-white transition"
            >
                VIEW ALL BOOKS →
            </a>

        </div>


        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">


            <?php

            $books = [

                [
                    "number" => "01",
                    "title" => "THE SILENT PATIENT",
                    "author" => "Alex Michaelides",
                    "type" => "THRILLER"
                ],

                [
                    "number" => "02",
                    "title" => "ATOMIC HABITS",
                    "author" => "James Clear",
                    "type" => "SELF DEVELOPMENT"
                ],

                [
                    "number" => "03",
                    "title" => "THE ALCHEMIST",
                    "author" => "Paulo Coelho",
                    "type" => "FICTION"
                ],

                [
                    "number" => "04",
                    "title" => "DEEP WORK",
                    "author" => "Cal Newport",
                    "type" => "PRODUCTIVITY"
                ]

            ];

            ?>


            <?php foreach ($books as $book): ?>

                <article class="book-card reveal">

                    <div class="h-[420px] flex items-center justify-center relative">

                        <span class="absolute top-5 left-5 mono text-[9px] text-gray-700">
                            <?= $book["number"] ?>
                        </span>


                        <div class="book-cover w-[180px] h-[270px] bg-gradient-to-br from-[#241018] to-[#090909] border border-white/10 p-6 flex flex-col justify-between shadow-2xl">

                            <span class="mono text-[8px] text-blood tracking-widest">
                                SMART EDITION
                            </span>


                            <div>

                                <h3 class="text-2xl font-bold leading-none">
                                    <?= $book["title"] ?>
                                </h3>

                            </div>


                            <span class="text-[9px] text-gray-600">
                                <?= $book["author"] ?>
                            </span>

                        </div>

                    </div>


                    <div class="p-6 border-t border-white/5">

                        <div class="mono text-[9px] text-blood tracking-widest">
                            <?= $book["type"] ?>
                        </div>

                        <h3 class="mt-2 font-semibold">
                            <?= $book["title"] ?>
                        </h3>

                        <p class="text-xs text-gray-600 mt-1">
                            <?= $book["author"] ?>
                        </p>

                    </div>

                </article>

            <?php endforeach; ?>


        </div>

    </div>

</section>


<!-- CATEGORIES -->

<section id="categories" class="border-y border-white/10">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10 py-32">

        <div class="grid lg:grid-cols-[300px_1fr] gap-16">

            <div>

                <div class="mono text-[10px] text-blood tracking-[0.3em] mb-4">
                    03 / EXPLORE
                </div>

                <h2 class="text-5xl md:text-6xl font-bold tracking-[-0.06em]">
                    FIND<br>
                    YOUR<br>
                    WORLD.
                </h2>

            </div>


            <div>

                <?php

                $categories = [
                    ["name" => "Fiction", "count" => "2,430"],
                    ["name" => "Thriller", "count" => "1,284"],
                    ["name" => "Technology", "count" => "976"],
                    ["name" => "Business", "count" => "842"],
                    ["name" => "Science Fiction", "count" => "731"],
                    ["name" => "Classics", "count" => "619"]
                ];

                ?>


                <?php foreach ($categories as $category): ?>

                    <a
                        href="books.php?category=<?= strtolower(str_replace(' ', '-', $category["name"])) ?>"
                        class="category flex items-center justify-between py-7 group"
                    >

                        <span class="text-2xl md:text-4xl font-semibold">
                            <?= $category["name"] ?>
                        </span>

                        <span class="mono text-[10px] text-gray-600 group-hover:text-blood transition">
                            <?= $category["count"] ?>
                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</section>


<!-- CTA -->

<section class="py-40">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10">

        <div class="relative overflow-hidden border border-blood/30 bg-[#0d0507] p-10 md:p-24 reveal">

            <div class="absolute -right-20 -top-40 text-[20rem] font-bold text-blood/5 select-none">
                S
            </div>


            <div class="relative z-10">

                <div class="mono text-[10px] tracking-[0.4em] text-blood mb-7">
                    YOUR NEXT CHAPTER
                </div>

                <h2 class="text-5xl md:text-8xl font-bold tracking-[-0.07em] max-w-5xl leading-[0.9]">
                    GET LOST<br>
                    IN SOMETHING
                    <span class="text-blood">GOOD.</span>
                </h2>


                <a
                    href="books.php"
                    class="danger-button inline-block mt-12 px-10 py-5 text-xs font-bold tracking-widest"
                >
                    <span>EXPLORE THE COLLECTION →</span>
                </a>

            </div>

        </div>

    </div>

</section>


</main>


<!-- FOOTER -->

<footer class="border-t border-white/10">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10 py-12">

        <div class="flex flex-col md:flex-row justify-between gap-8">

            <div>

                <div class="text-2xl font-bold tracking-[-0.08em]">
                    SMART<span class="text-blood">.</span>
                </div>

                <p class="mono text-[9px] text-gray-700 mt-3">
                    READ DIFFERENT.
                </p>

            </div>


            <div class="flex gap-8 mono text-[9px] text-gray-600">

                <a href="#" class="hover:text-white transition">
                    INSTAGRAM
                </a>

                <a href="#" class="hover:text-white transition">
                    GITHUB
                </a>

                <a href="#" class="hover:text-white transition">
                    CONTACT
                </a>

            </div>

        </div>


        <div class="mt-12 pt-6 border-t border-white/5 flex justify-between">

            <span class="mono text-[8px] text-gray-700">
                © <?= date("Y") ?> SMART BOOK STORE
            </span>

            <span class="mono text-[8px] text-gray-700">
                BUILT WITH PHP
            </span>

        </div>

    </div>

</footer>


<script>

    // Cursor glow

    const cursorGlow = document.getElementById("cursorGlow");

    document.addEventListener("mousemove", (event) => {

        cursorGlow.style.left = event.clientX + "px";
        cursorGlow.style.top = event.clientY + "px";

    });


    // Scroll reveal

    const revealElements = document.querySelectorAll(".reveal");

    const revealObserver = new IntersectionObserver(
        (entries) => {

            entries.forEach((entry) => {

                if (entry.isIntersecting) {

                    entry.target.classList.add("show");

                    revealObserver.unobserve(entry.target);

                }

            });

        },
        {
            threshold: 0.15
        }
    );


    revealElements.forEach((element) => {

        revealObserver.observe(element);

    });

</script>

</body>
</html>