<?php

require_once __DIR__ . '/database.php';

if (!$dbConnected) {
    die("Database connection failed: " . ($dbError ?? "Unknown error"));
}

echo "=== Smart Bookstore Database Seeder ===\n";

// 1. Seed Users (Admin & Customer with Role-based access)
$usersCollection = $db->users;
$usersCollection->drop();

$usersCollection->createIndex(['email' => 1], ['unique' => true]);

$hashedAdminPassword = password_hash("Admin@123", PASSWORD_BCRYPT);
$hashedCustomerPassword = password_hash("Customer@123", PASSWORD_BCRYPT);

$users = [
    [
        'name' => 'Store Administrator',
        'email' => 'admin@smartbookstore.com',
        'password' => $hashedAdminPassword,
        'role' => 'admin',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Ravi Reader',
        'email' => 'customer@smartbookstore.com',
        'password' => $hashedCustomerPassword,
        'role' => 'customer',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

$usersCollection->insertMany($users);
echo "✓ Users seeded (Admin: admin@smartbookstore.com, Customer: customer@smartbookstore.com)\n";

// 2. Seed Books Collection
$booksCollection = $db->books;
$booksCollection->drop();

$books = [
    // 1. Thriller
    [
        'title' => 'The Silent Patient',
        'author' => 'Alex Michaelides',
        'category' => 'Thriller',
        'price' => 499,
        'rating' => 4.7,
        'stock' => 18,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 336,
        'isbn' => '978-1250301696',
        'description' => 'A psychological thriller about a famous painter whose sudden silence turns a brutal mystery into an obsession for forensic psychotherapist Theo Faber.',
        'featured' => true,
        'cover_bg' => 'from-[#2a0815] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 2. Self Development
    [
        'title' => 'Atomic Habits',
        'author' => 'James Clear',
        'category' => 'Self Development',
        'price' => 599,
        'rating' => 4.9,
        'stock' => 25,
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 320,
        'isbn' => '978-0735211292',
        'description' => 'An immensely practical guide to building better habits through small, consistent changes that compound exponentially over time.',
        'featured' => true,
        'cover_bg' => 'from-[#2e1305] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 3. Fiction
    [
        'title' => 'The Alchemist',
        'author' => 'Paulo Coelho',
        'category' => 'Fiction',
        'price' => 399,
        'rating' => 4.8,
        'stock' => 30,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 208,
        'isbn' => '978-0062315007',
        'description' => 'A timeless philosophical story about Santiago, an Andalusian shepherd boy who yearns to travel in search of a worldly treasure.',
        'featured' => true,
        'cover_bg' => 'from-[#2b2005] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 4. Productivity
    [
        'title' => 'Deep Work',
        'author' => 'Cal Newport',
        'category' => 'Technology',
        'price' => 549,
        'rating' => 4.6,
        'stock' => 12,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 304,
        'isbn' => '978-1455586691',
        'description' => 'A powerful exploration of focused work and how deep, uninterrupted concentration transforms your professional and intellectual life.',
        'featured' => true,
        'cover_bg' => 'from-[#081a2b] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 5. Business
    [
        'title' => 'The Psychology of Money',
        'author' => 'Morgan Housel',
        'category' => 'Business',
        'price' => 499,
        'rating' => 4.8,
        'stock' => 22,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 256,
        'isbn' => '978-0857197689',
        'description' => 'Nineteen short stories exploring the strange ways people think about money, risk, greed, happiness, and financial decisions.',
        'featured' => false,
        'cover_bg' => 'from-[#072418] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 6. Science Fiction
    [
        'title' => 'Dune',
        'author' => 'Frank Herbert',
        'category' => 'Science Fiction',
        'price' => 699,
        'rating' => 4.9,
        'stock' => 14,
        'format' => 'Deluxe Paperback',
        'language' => 'English',
        'pages' => 688,
        'isbn' => '978-0441172719',
        'description' => 'Set on the desert planet Arrakis, Dune is the story of the boy Paul Atreides, who inherits a vast destiny intertwined with the universe\'s most precious spice.',
        'featured' => false,
        'cover_bg' => 'from-[#331405] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 7. Classics
    [
        'title' => '1984',
        'author' => 'George Orwell',
        'category' => 'Classics',
        'price' => 349,
        'rating' => 4.8,
        'stock' => 20,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 328,
        'isbn' => '978-0451524935',
        'description' => 'Winston Smith toes the Party line, rewriting history to satisfy the Ministry of Truth. With every lie he writes, he grows to hate the Party that seeks power for its own sake.',
        'featured' => false,
        'cover_bg' => 'from-[#26080d] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 8. Technology
    [
        'title' => 'The Pragmatic Programmer',
        'author' => 'David Thomas, Andrew Hunt',
        'category' => 'Technology',
        'price' => 799,
        'rating' => 4.7,
        'stock' => 15,
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 352,
        'isbn' => '978-0135957059',
        'description' => 'Filled with technical and practical advice, career development wisdom, and core software craftsmanship principles that remain timeless.',
        'featured' => false,
        'cover_bg' => 'from-[#19092b] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 9. Science Fiction & Cyberpunk
    [
        'title' => 'Neuromancer',
        'author' => 'William Gibson',
        'category' => 'Science Fiction',
        'price' => 549,
        'rating' => 4.8,
        'stock' => 11,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 271,
        'isbn' => '978-0441569595',
        'description' => 'The seminal cyberpunk masterpiece. Case was the sharpest data-thief in the matrix until he crossed the wrong people and they damaged his nervous system.',
        'featured' => true,
        'cover_bg' => 'from-[#1f0a33] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 10. Technology & Engineering
    [
        'title' => 'Designing Data-Intensive Applications',
        'author' => 'Martin Kleppmann',
        'category' => 'Technology',
        'price' => 899,
        'rating' => 4.9,
        'stock' => 16,
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 616,
        'isbn' => '978-1449373320',
        'description' => 'The definitive guide to the architecture, scalability, reliability, and maintainability of modern data systems and distributed storage.',
        'featured' => true,
        'cover_bg' => 'from-[#0a232e] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 11. Technology
    [
        'title' => 'Clean Code: Agile Software Craftsmanship',
        'author' => 'Robert C. Martin',
        'category' => 'Technology',
        'price' => 699,
        'rating' => 4.7,
        'stock' => 24,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 464,
        'isbn' => '978-0132350884',
        'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees. Master writing professional code.',
        'featured' => false,
        'cover_bg' => 'from-[#0d1f14] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 12. Science Fiction
    [
        'title' => 'Project Hail Mary',
        'author' => 'Andy Weir',
        'category' => 'Science Fiction',
        'price' => 649,
        'rating' => 4.9,
        'stock' => 19,
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 496,
        'isbn' => '978-0593135204',
        'description' => 'Ryland Grace is the sole survivor on a desperate, last-chance mission—and if he fails, humanity and the Earth itself will perish.',
        'featured' => true,
        'cover_bg' => 'from-[#331c08] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 13. Thriller
    [
        'title' => 'Gone Girl',
        'author' => 'Gillian Flynn',
        'category' => 'Thriller',
        'price' => 449,
        'rating' => 4.6,
        'stock' => 17,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 432,
        'isbn' => '978-0307588371',
        'description' => 'On the morning of his fifth wedding anniversary, Nick Dunne\'s wife Amy suddenly disappears. Under mounting pressure, Nick\'s web of lies unravels.',
        'featured' => false,
        'cover_bg' => 'from-[#2e0915] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 14. Thriller
    [
        'title' => 'Shutter Island',
        'author' => 'Dennis Lehane',
        'category' => 'Thriller',
        'price' => 479,
        'rating' => 4.7,
        'stock' => 9,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 384,
        'isbn' => '978-0061898815',
        'description' => 'US Marshal Teddy Daniels arrives at Ashecliffe Hospital for the criminally insane on Boston Harbor. But as a hurricane hits, paranoia turns fatal.',
        'featured' => false,
        'cover_bg' => 'from-[#1a0824] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 15. Philosophy
    [
        'title' => 'Meditations',
        'author' => 'Marcus Aurelius',
        'category' => 'Philosophy',
        'price' => 329,
        'rating' => 4.9,
        'stock' => 35,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 256,
        'isbn' => '978-0140449334',
        'description' => 'Private reflections of the Roman Emperor Marcus Aurelius on Stoic philosophy, duty, resilience, emotional mastery, and virtue.',
        'featured' => true,
        'cover_bg' => 'from-[#2b1e06] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 16. Philosophy
    [
        'title' => 'Man\'s Search for Meaning',
        'author' => 'Viktor E. Frankl',
        'category' => 'Philosophy',
        'price' => 379,
        'rating' => 4.9,
        'stock' => 28,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 192,
        'isbn' => '978-0807014295',
        'description' => 'Psychiatrist Viktor Frankl\'s memoir of life in Nazi death camps and his discovery of Logotherapy—how purpose transforms human suffering.',
        'featured' => false,
        'cover_bg' => 'from-[#141414] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 17. Business & Strategy
    [
        'title' => 'Zero to One: Notes on Startups',
        'author' => 'Peter Thiel, Blake Masters',
        'category' => 'Business',
        'price' => 520,
        'rating' => 4.7,
        'stock' => 15,
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 224,
        'isbn' => '978-0804139298',
        'description' => 'The next Bill Gates will not build an operating system. The next Mark Zuckerberg won\'t create a social network. How to build monopolies from zero to one.',
        'featured' => false,
        'cover_bg' => 'from-[#081e2b] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 18. Business & Psychology
    [
        'title' => 'Thinking, Fast and Slow',
        'author' => 'Daniel Kahneman',
        'category' => 'Business',
        'price' => 599,
        'rating' => 4.8,
        'stock' => 21,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 512,
        'isbn' => '978-0374533557',
        'description' => 'Nobel laureate Daniel Kahneman reveals the two systems driving human judgment: System 1 (fast, emotional) and System 2 (slow, analytical).',
        'featured' => false,
        'cover_bg' => 'from-[#2b0c15] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 19. Classics
    [
        'title' => 'The Great Gatsby',
        'author' => 'F. Scott Fitzgerald',
        'category' => 'Classics',
        'price' => 299,
        'rating' => 4.6,
        'stock' => 30,
        'format' => 'Deluxe Edition',
        'language' => 'English',
        'pages' => 180,
        'isbn' => '978-0743273565',
        'description' => 'Jay Gatsby\'s decadent parties on Long Island, his obsession with Daisy Buchanan, and the hollow promise of the American Dream in the Roaring Twenties.',
        'featured' => false,
        'cover_bg' => 'from-[#291f07] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 20. Classics
    [
        'title' => 'Crime and Punishment',
        'author' => 'Fyodor Dostoevsky',
        'category' => 'Classics',
        'price' => 450,
        'rating' => 4.9,
        'stock' => 8,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 576,
        'isbn' => '978-0140449136',
        'description' => 'Raskolnikov, an impoverished ex-student in St. Petersburg, commits murder to test his theory of superior beings, spiraling into feverish psychological guilt.',
        'featured' => false,
        'cover_bg' => 'from-[#1f070b] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 21. Fiction
    [
        'title' => 'Kafka on the Shore',
        'author' => 'Haruki Murakami',
        'category' => 'Fiction',
        'price' => 520,
        'rating' => 4.8,
        'stock' => 14,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 512,
        'isbn' => '978-1400079278',
        'description' => 'A tour de force of metaphysical reality, exploring two remarkable characters: teenage runaway Kafka Tamura and the aging, simple Nakata.',
        'featured' => false,
        'cover_bg' => 'from-[#091b26] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 22. Self Development
    [
        'title' => 'Can\'t Hurt Me: Master Your Mind',
        'author' => 'David Goggins',
        'category' => 'Self Development',
        'price' => 649,
        'rating' => 4.9,
        'stock' => 3, // Low stock test!
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 364,
        'isbn' => '978-1544512280',
        'description' => 'For David Goggins, childhood was a nightmare. Through relentless self-discipline and mental toughness, he became the only man in history to complete elite Navy SEAL training.',
        'featured' => true,
        'cover_bg' => 'from-[#2e0e05] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 23. Science Fiction
    [
        'title' => 'The Three-Body Problem',
        'author' => 'Cixin Liu',
        'category' => 'Science Fiction',
        'price' => 599,
        'rating' => 4.8,
        'stock' => 5, // Low stock test!
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 400,
        'isbn' => '978-0765382030',
        'description' => 'Against the backdrop of China\'s Cultural Revolution, a secret military project sends signals into space, establishing contact with an alien civilization on the brink of extinction.',
        'featured' => false,
        'cover_bg' => 'from-[#170529] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 24. Thriller
    [
        'title' => 'The Girl with the Dragon Tattoo',
        'author' => 'Stieg Larsson',
        'category' => 'Thriller',
        'price' => 499,
        'rating' => 4.7,
        'stock' => 12,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 672,
        'isbn' => '978-0307949486',
        'description' => 'Investigative journalist Mikael Blomkvist and genius punk hacker Lisbeth Salander uncover a dark history of corruption and family secrets in remote Sweden.',
        'featured' => false,
        'cover_bg' => 'from-[#240817] to-[#090909]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

$booksCollection->insertMany($books);
echo "✓ " . count($books) . " books successfully seeded into 'books' collection!\n";

$ordersCollection = $db->orders;
echo "✓ Orders collection ready.\n";

echo "=== Seeding Completed Successfully! ===\n";
