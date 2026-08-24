<?php

$books = [

    1 => [
        "title" => "The Silent Patient",
        "author" => "Alex Michaelides",
        "category" => "Thriller",
        "price" => 499,
        "rating" => 4.7,
        "description" => "A psychological thriller about a famous painter whose sudden silence turns a brutal mystery into an obsession."
    ],

    2 => [
        "title" => "Atomic Habits",
        "author" => "James Clear",
        "category" => "Self Development",
        "price" => 599,
        "rating" => 4.9,
        "description" => "A practical guide to building better habits through small, consistent changes that compound over time."
    ],

    3 => [
        "title" => "The Alchemist",
        "author" => "Paulo Coelho",
        "category" => "Fiction",
        "price" => 399,
        "rating" => 4.8,
        "description" => "A timeless story about following your dreams, discovering your purpose, and listening to your heart."
    ],

    4 => [
        "title" => "Deep Work",
        "author" => "Cal Newport",
        "category" => "Productivity",
        "price" => 549,
        "rating" => 4.6,
        "description" => "A powerful exploration of focused work and how deep concentration can transform your professional life."
    ],

    5 => [
        "title" => "The Psychology of Money",
        "author" => "Morgan Housel",
        "category" => "Business",
        "price" => 499,
        "rating" => 4.8,
        "description" => "An exploration of how emotions, behavior, and personal experiences influence the way people handle money."
    ],

    6 => [
        "title" => "Dune",
        "author" => "Frank Herbert",
        "category" => "Science Fiction",
        "price" => 699,
        "rating" => 4.9,
        "description" => "An epic science-fiction story of politics, power, survival, and destiny on the desert planet Arrakis."
    ],

    7 => [
        "title" => "1984",
        "author" => "George Orwell",
        "category" => "Classics",
        "price" => 349,
        "rating" => 4.8,
        "description" => "A dystopian classic exploring surveillance, control, propaganda, and the struggle for individual freedom."
    ],

    8 => [
        "title" => "The Pragmatic Programmer",
        "author" => "David Thomas",
        "category" => "Technology",
        "price" => 799,
        "rating" => 4.7,
        "description" => "A practical guide to becoming a better software developer through strong engineering principles and habits."
    ]

];


$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;


if (!isset($books[$id])) {
    header("Location: books.php");
    exit;
}


$book = $books[$id];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($book["title"]) ?> — Smart Book Store</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {
                        blood: "#ff1744",
                        void: "#050505"
                    }

                }

            }

        }

    </script>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #050505;
            color: #f5f5f5;
            font-family: "Space Grotesk", sans-serif;
        }

        .mono {
            font-family: "DM Mono", monospace;
        }

        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 100;
            opacity: .035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.7'/%3E%3C/svg%3E");
        }

        .book {
            transition: transform .6s ease;
        }

        .book:hover {
            transform: rotate(-3deg) translateY(-10px);
        }

        .quantity-button {
            width: 42px;
            height: 42px;
            border: 1px solid #292929;
            transition: .3s ease;
        }

        .quantity-button:hover {
            background: #ff1744;
            color: black;
            border-color: #ff1744;
        }

        .add-button {
            transition: .3s ease;
        }

        .add-button:hover {
            box-shadow: 0 0 40px rgba(255,23,68,.25);
            transform: translateY(-2px);
        }

    </style>

</head>


<body>

<div class="noise"></div>


<!-- NAVBAR -->

<header class="border-b border-white/10">

    <nav
        class="max-w-[1500px] mx-auto px-6 md:px-10 h-24 flex items-center justify-between"
    >

        <a
            href="index.php"
            class="text-2xl font-bold tracking-[-0.08em]"
        >
            SMART<span class="text-blood">.</span>
        </a>


        <div class="hidden md:flex items-center gap-10 text-sm text-gray-500">

            <a
                href="index.php"
                class="hover:text-white transition"
            >
                HOME
            </a>

            <a
                href="books.php"
                class="text-white"
            >
                BOOKS
            </a>

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
                class="border border-white/20 px-5 py-2.5 text-xs hover:bg-white hover:text-black transition"
            >
                LOGIN
            </a>

        </div>

    </nav>

</header>


<main>

    <!-- BREADCRUMB -->

    <div class="max-w-[1500px] mx-auto px-6 md:px-10 pt-10">

        <a
            href="books.php"
            class="mono text-[10px] text-gray-600 hover:text-white transition"
        >
            ← BACK TO COLLECTION
        </a>

    </div>


    <!-- PRODUCT -->

    <section class="max-w-[1500px] mx-auto px-6 md:px-10 py-20">

        <div class="grid lg:grid-cols-2 gap-20 items-center">


            <!-- BOOK VISUAL -->

            <div class="relative flex justify-center min-h-[600px] items-center">

                <div class="absolute w-[330px] h-[470px] border border-blood/20 rotate-12"></div>

                <div class="absolute w-[330px] h-[470px] border border-white/10 -rotate-6"></div>


                <div
                    class="book relative w-[300px] h-[450px] bg-gradient-to-br from-[#260812] via-[#16070b] to-black border border-blood/40 p-8 flex flex-col justify-between shadow-2xl"
                >

                    <div class="mono text-[9px] text-blood tracking-[0.3em]">
                        SMART EDITION
                    </div>


                    <div>

                        <h1 class="text-4xl font-bold leading-[.9]">
                            <?= htmlspecialchars($book["title"]) ?>
                        </h1>

                    </div>


                    <div>

                        <p class="text-xs text-gray-500">
                            <?= htmlspecialchars($book["author"]) ?>
                        </p>

                        <div class="mt-5 w-10 h-10 border border-blood rounded-full flex items-center justify-center text-blood">
                            ↓
                        </div>

                    </div>

                </div>

            </div>


            <!-- DETAILS -->

            <div>

                <div class="mono text-[10px] text-blood tracking-[0.3em] mb-6">
                    <?= strtoupper(htmlspecialchars($book["category"])) ?>
                </div>


                <h1 class="text-5xl md:text-7xl font-bold tracking-[-0.06em] leading-[.9]">
                    <?= htmlspecialchars($book["title"]) ?>
                </h1>


                <p class="mt-5 text-gray-500">
                    by
                    <span class="text-gray-300">
                        <?= htmlspecialchars($book["author"]) ?>
                    </span>
                </p>


                <div class="flex items-center gap-4 mt-8">

                    <span class="text-yellow-500">
                        ★
                    </span>

                    <span class="font-semibold">
                        <?= $book["rating"] ?>
                    </span>

                    <span class="text-gray-700">
                        /
                    </span>

                    <span class="text-gray-600 text-sm">
                        Reader rating
                    </span>

                </div>


                <div class="border-t border-white/10 mt-10 pt-8">

                    <p class="text-gray-500 leading-relaxed max-w-xl">
                        <?= htmlspecialchars($book["description"]) ?>
                    </p>

                </div>


                <!-- PRICE -->

                <div class="mt-10">

                    <span class="mono text-[9px] text-gray-600 tracking-widest">
                        PRICE
                    </span>

                    <div class="text-4xl font-bold mt-2">
                        ₹<?= $book["price"] ?>
                    </div>

                </div>


                <!-- CART -->

                <div class="mt-8 flex flex-col sm:flex-row gap-4">


                    <div class="flex items-center">

                        <button
                            id="minus"
                            class="quantity-button"
                        >
                            −
                        </button>


                        <span
                            id="quantity"
                            class="w-14 text-center"
                        >
                            1
                        </span>


                        <button
                            id="plus"
                            class="quantity-button"
                        >
                            +
                        </button>

                    </div>


                    <button
                        id="addToCart"
                        class="add-button bg-blood text-black px-8 py-4 font-bold text-xs tracking-widest flex-1"
                    >
                        ADD TO CART →
                    </button>

                </div>


                <p
                    id="cartMessage"
                    class="hidden mt-4 text-blood text-xs mono"
                >
                    BOOK ADDED TO CART ✓
                </p>

            </div>

        </div>

    </section>


    <!-- DETAILS -->

    <section class="border-t border-white/10">

        <div class="max-w-[1500px] mx-auto px-6 md:px-10 py-24">

            <div class="grid md:grid-cols-3 gap-10">

                <div>

                    <span class="mono text-[9px] text-gray-600">
                        FORMAT
                    </span>

                    <p class="mt-3">
                        Paperback
                    </p>

                </div>


                <div>

                    <span class="mono text-[9px] text-gray-600">
                        LANGUAGE
                    </span>

                    <p class="mt-3">
                        English
                    </p>

                </div>


                <div>

                    <span class="mono text-[9px] text-gray-600">
                        AVAILABILITY
                    </span>

                    <p class="mt-3 text-blood">
                        In Stock
                    </p>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- FOOTER -->

<footer class="border-t border-white/10">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10 py-12 flex justify-between">

        <div class="text-xl font-bold">
            SMART<span class="text-blood">.</span>
        </div>

        <div class="mono text-[9px] text-gray-700">
            © <?= date("Y") ?> SMART BOOK STORE
        </div>

    </div>

</footer>


<script>

    const minus = document.getElementById("minus");
    const plus = document.getElementById("plus");
    const quantity = document.getElementById("quantity");
    const addToCart = document.getElementById("addToCart");
    const cartMessage = document.getElementById("cartMessage");

    let count = 1;


    plus.addEventListener("click", () => {

        count++;

        quantity.textContent = count;

    });


    minus.addEventListener("click", () => {

        if (count > 1) {

            count--;

            quantity.textContent = count;

        }

    });


    addToCart.addEventListener("click", () => {

        cartMessage.classList.remove("hidden");

        addToCart.textContent = "ADDED ✓";

        setTimeout(() => {

            cartMessage.classList.add("hidden");

            addToCart.textContent = "ADD TO CART →";

        }, 2000);

    });

</script>

</body>

</html>