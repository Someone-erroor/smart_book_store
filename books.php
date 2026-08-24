<?php

$pageTitle = "Books — Smart Book Store";

$books = [

    [
        "id" => 1,
        "title" => "The Silent Patient",
        "author" => "Alex Michaelides",
        "category" => "Thriller",
        "price" => 499,
        "rating" => 4.7
    ],

    [
        "id" => 2,
        "title" => "Atomic Habits",
        "author" => "James Clear",
        "category" => "Self Development",
        "price" => 599,
        "rating" => 4.9
    ],

    [
        "id" => 3,
        "title" => "The Alchemist",
        "author" => "Paulo Coelho",
        "category" => "Fiction",
        "price" => 399,
        "rating" => 4.8
    ],

    [
        "id" => 4,
        "title" => "Deep Work",
        "author" => "Cal Newport",
        "category" => "Productivity",
        "price" => 549,
        "rating" => 4.6
    ],

    [
        "id" => 5,
        "title" => "The Psychology of Money",
        "author" => "Morgan Housel",
        "category" => "Business",
        "price" => 499,
        "rating" => 4.8
    ],

    [
        "id" => 6,
        "title" => "Dune",
        "author" => "Frank Herbert",
        "category" => "Science Fiction",
        "price" => 699,
        "rating" => 4.9
    ],

    [
        "id" => 7,
        "title" => "1984",
        "author" => "George Orwell",
        "category" => "Classics",
        "price" => 349,
        "rating" => 4.8
    ],

    [
        "id" => 8,
        "title" => "The Pragmatic Programmer",
        "author" => "David Thomas",
        "category" => "Technology",
        "price" => 799,
        "rating" => 4.7
    ]

];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= $pageTitle ?></title>

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

        .book-card {
            background: #0b0b0b;
            border: 1px solid #242424;
            transition:
                transform .5s ease,
                border-color .5s ease;
        }

        .book-card:hover {
            transform: translateY(-10px);
            border-color: #ff1744;
        }

        .book-cover {
            transition:
                transform .6s ease,
                filter .6s ease;
        }

        .book-card:hover .book-cover {
            transform: scale(1.06) rotate(-2deg);
            filter: brightness(1.15);
        }

        .category-btn {
            border: 1px solid #292929;
            transition: .3s ease;
        }

        .category-btn:hover,
        .category-btn.active {
            background: #ff1744;
            border-color: #ff1744;
            color: black;
        }

        .search-box {
            transition: border-color .3s ease;
        }

        .search-box:focus-within {
            border-color: #ff1744;
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

            <a
                href="index.php#categories"
                class="hover:text-white transition"
            >
                CATEGORIES
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

    <!-- PAGE HEADER -->

    <section class="max-w-[1500px] mx-auto px-6 md:px-10 pt-24 pb-16">

        <div class="mono text-[10px] tracking-[0.4em] text-blood mb-6">
            01 / THE COLLECTION
        </div>

        <h1 class="text-6xl md:text-9xl font-bold tracking-[-0.08em] leading-[.8]">
            ALL
            <span class="text-gray-600">
                BOOKS.
            </span>
        </h1>

        <p class="mt-10 max-w-xl text-gray-500 leading-relaxed">
            Explore stories, ideas and worlds curated for curious minds.
            Find something worth losing yourself in.
        </p>

    </section>


    <!-- SEARCH + FILTER -->

    <section class="max-w-[1500px] mx-auto px-6 md:px-10 pb-16">

        <div class="flex flex-col lg:flex-row gap-5 justify-between">

            <!-- SEARCH -->

            <div
                class="search-box border border-[#292929] bg-[#0b0b0b] flex items-center px-5 py-4 w-full lg:max-w-xl"
            >

                <span class="text-gray-600 mr-4">
                    /
                </span>

                <input
                    id="searchInput"
                    type="text"
                    placeholder="Search books, authors..."
                    class="bg-transparent outline-none w-full text-sm text-white placeholder:text-gray-700"
                >

            </div>


            <!-- CATEGORIES -->

            <div class="flex gap-2 overflow-x-auto pb-2">

                <button
                    class="category-btn active px-5 py-3 text-xs whitespace-nowrap"
                    data-category="all"
                >
                    ALL
                </button>

                <button
                    class="category-btn px-5 py-3 text-xs whitespace-nowrap"
                    data-category="Fiction"
                >
                    FICTION
                </button>

                <button
                    class="category-btn px-5 py-3 text-xs whitespace-nowrap"
                    data-category="Thriller"
                >
                    THRILLER
                </button>

                <button
                    class="category-btn px-5 py-3 text-xs whitespace-nowrap"
                    data-category="Technology"
                >
                    TECHNOLOGY
                </button>

                <button
                    class="category-btn px-5 py-3 text-xs whitespace-nowrap"
                    data-category="Business"
                >
                    BUSINESS
                </button>

            </div>

        </div>

    </section>


    <!-- BOOK GRID -->

    <section class="max-w-[1500px] mx-auto px-6 md:px-10 pb-32">

        <div
            id="bookGrid"
            class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5"
        >

            <?php foreach ($books as $book): ?>

                <article
                    class="book-card book-item"
                    data-category="<?= $book["category"] ?>"
                    data-search="<?= strtolower($book["title"] . " " . $book["author"]) ?>"
                >

                    <!-- COVER -->

                    <div class="h-[430px] flex items-center justify-center relative">

                        <span class="absolute top-5 left-5 mono text-[9px] text-gray-700">
                            #<?= str_pad($book["id"], 2, "0", STR_PAD_LEFT) ?>
                        </span>


                        <div
                            class="book-cover w-[190px] h-[285px] bg-gradient-to-br from-[#260812] to-black border border-white/10 p-6 flex flex-col justify-between shadow-2xl"
                        >

                            <span class="mono text-[8px] text-blood tracking-widest">
                                SMART EDITION
                            </span>


                            <h2 class="text-2xl font-bold leading-none">
                                <?= $book["title"] ?>
                            </h2>


                            <span class="text-[9px] text-gray-500">
                                <?= $book["author"] ?>
                            </span>

                        </div>

                    </div>


                    <!-- INFO -->

                    <div class="p-6 border-t border-white/5">

                        <div class="flex justify-between items-center">

                            <span class="mono text-[9px] text-blood tracking-widest">
                                <?= strtoupper($book["category"]) ?>
                            </span>

                            <span class="text-xs text-yellow-500">
                                ★ <?= $book["rating"] ?>
                            </span>

                        </div>


                        <h2 class="mt-3 font-semibold text-lg">
                            <?= $book["title"] ?>
                        </h2>


                        <p class="text-xs text-gray-600 mt-1">
                            <?= $book["author"] ?>
                        </p>


                        <div class="mt-6 flex items-center justify-between">

                            <span class="text-lg font-bold">
                                ₹<?= $book["price"] ?>
                            </span>


                            <a
                                href="book-details.php?id=<?= $book["id"] ?>"
                                class="text-xs border border-white/10 px-4 py-2 hover:bg-white hover:text-black transition"
                            >
                                VIEW →
                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>


        <!-- NO RESULTS -->

        <div
            id="noResults"
            class="hidden py-32 text-center"
        >

            <p class="mono text-blood text-xs tracking-widest">
                NOTHING FOUND
            </p>

            <h2 class="text-4xl font-bold mt-4">
                Try another search.
            </h2>

        </div>

    </section>

</main>


<!-- FOOTER -->

<footer class="border-t border-white/10">

    <div class="max-w-[1500px] mx-auto px-6 md:px-10 py-12 flex flex-col md:flex-row justify-between gap-5">

        <div class="text-xl font-bold">
            SMART<span class="text-blood">.</span>
        </div>

        <div class="mono text-[9px] text-gray-700">
            © <?= date("Y") ?> SMART BOOK STORE
        </div>

    </div>

</footer>


<script>

    const searchInput = document.getElementById("searchInput");

    const books = document.querySelectorAll(".book-item");

    const noResults = document.getElementById("noResults");

    const categoryButtons = document.querySelectorAll(".category-btn");

    let selectedCategory = "all";


    function filterBooks() {

        const search = searchInput.value.toLowerCase().trim();

        let visibleBooks = 0;


        books.forEach(book => {

            const category = book.dataset.category;

            const searchableText = book.dataset.search;


            const categoryMatch =
                selectedCategory === "all" ||
                category === selectedCategory;


            const searchMatch =
                searchableText.includes(search);


            if (categoryMatch && searchMatch) {

                book.classList.remove("hidden");

                visibleBooks++;

            } else {

                book.classList.add("hidden");

            }

        });


        if (visibleBooks === 0) {

            noResults.classList.remove("hidden");

        } else {

            noResults.classList.add("hidden");

        }

    }


    searchInput.addEventListener("input", filterBooks);


    categoryButtons.forEach(button => {

        button.addEventListener("click", () => {

            categoryButtons.forEach(btn => {
                btn.classList.remove("active");
            });

            button.classList.add("active");

            selectedCategory = button.dataset.category;

            filterBooks();

        });

    });

</script>

</body>
</html>