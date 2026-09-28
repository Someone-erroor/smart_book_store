<?php

require_once __DIR__ . '/database.php';

if (!$dbConnected) {
    die("Database connection failed: " . ($dbError ?? "Unknown error"));
}

echo "=== Smart Bookstore Literary Seeder (Mehfil Extension) ===\n";

// 1. Seed Poets Collection
$poetsCol = $db->poets;
$poetsCol->drop();
$poetsCol->createIndex(['slug' => 1], ['unique' => true]);
$poetsCol->createIndex(['language' => 1]);

$poets = [
    // Urdu Poets
    [
        'name' => 'Jaun Elia',
        'slug' => 'jaun-elia',
        'language' => 'Urdu',
        'era' => '1931 – 2002',
        'birthplace' => 'Amroha, British India (later Karachi)',
        'literary_style' => 'Existential Nihilism, Raw Heartache, Unapologetic Sarcasm & Modernist Ghazal',
        'biography' => 'Syed Sibt-e-Asghar Naqvi, universally celebrated as Jaun Elia, was an iconoclastic scholar, philosopher, and poet. Known for his unconventional non-conformist lifestyle, devastating vulnerability, and fierce intellect, Jaun revolutionized modern Urdu Ghazal by discarding romantic ornamentation in favor of brutally honest existential despair and self-interrogation.',
        'famous_works' => ['Shayad', 'Yaani', 'Gumaan', 'Lekin', 'Goya', 'Farnood'],
        'cover_bg' => 'from-[#2a0614] to-[#0a0a0a]',
        'accent_color' => '#ff1744',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Mirza Ghalib',
        'slug' => 'mirza-ghalib',
        'language' => 'Urdu',
        'era' => '1797 – 1869',
        'birthplace' => 'Kala Mahal, Agra',
        'literary_style' => 'Philosophical Skepticism, Mystical Paradox, Unmatched Wit & Linguistic Majesty',
        'biography' => 'Mirza Asadullah Baig Khan, pen-named Ghalib, is the undisputed towering colossus of classical Urdu and Persian poetry. Witness to the twilight of the Mughal Empire in Delhi, Ghalib turned human fragility, cosmic questioning, unrequited devotion, and intellectual irony into an eternal art form that remains the bedrock of South Asian literary consciousness.',
        'famous_works' => ['Diwan-e-Ghalib', 'Kulliyat-e-Ghalib', 'Ood-e-Hindi', 'Urdu-e-Mualla'],
        'cover_bg' => 'from-[#2e1d08] to-[#0a0a0a]',
        'accent_color' => '#c5a059',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Faiz Ahmed Faiz',
        'slug' => 'faiz-ahmed-faiz',
        'language' => 'Urdu',
        'era' => '1911 – 1984',
        'birthplace' => 'Sialkot, Punjab',
        'literary_style' => 'Revolutionary Romance, Resistance Poetry, Lyrical Marxism & Humanist Hope',
        'biography' => 'A Nobel Prize nominee and Lenin Peace Prize laureate, Faiz masterfully wove the classical metaphors of Urdu romantic love with the urgent socio-political cries for freedom, justice, and human dignity. His verses became battle anthems for resistance movements across continents while retaining sublime lyrical grace.',
        'famous_works' => ['Naqsh-e-Faryadi', 'Dast-e-Saba', 'Zindan-Nama', 'Nuskha-e-Haye-Wafa'],
        'cover_bg' => 'from-[#240b0b] to-[#0a0a0a]',
        'accent_color' => '#ff1744',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Mir Taqi Mir',
        'slug' => 'mir-taqi-mir',
        'language' => 'Urdu',
        'era' => '1723 – 1810',
        'birthplace' => 'Agra, Mughal India',
        'literary_style' => 'Khuda-e-Sukhan (God of Poetic Craft), Sublime Melancholy & Delicate Pathos',
        'biography' => 'Revered as the pioneer who established Urdu Ghazal as a sublime literary vehicle, Mir endured personal tragedies and the historic sack of Delhi. His poetry carries a quiet, heart-piercing musicality that Ghalib famously conceded was unmatched: "Reekhta ke tumhi ustaad nahi ho Ghalib / Kehte hain agle zamaane mein koi Mir bhi tha."',
        'famous_works' => ['Kulliyat-e-Mir', 'Nukat-ush-Shuara', 'Zikr-e-Mir'],
        'cover_bg' => 'from-[#190d26] to-[#0a0a0a]',
        'accent_color' => '#b388ff',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Ahmad Faraz',
        'slug' => 'ahmad-faraz',
        'language' => 'Urdu',
        'era' => '1931 – 2008',
        'birthplace' => 'Kohat, British India',
        'literary_style' => 'Sensual Romanticism, Defiant Resistance & Melodic Simplicity',
        'biography' => 'One of the most celebrated and recited modern Urdu poets of the 20th century. Faraz combined effortless lyricism with courageous anti-dictatorship resistance, leaving an indelible imprint on generations of poetry lovers across the globe.',
        'famous_works' => ['Tanha Tanha', 'Dard-e-Aashob', 'Janan Janan', 'Khab-e-Gul Pareshan Hai'],
        'cover_bg' => 'from-[#081e2b] to-[#0a0a0a]',
        'accent_color' => '#00e5ff',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Parveen Shakir',
        'slug' => 'parveen-shakir',
        'language' => 'Urdu',
        'era' => '1952 – 1994',
        'birthplace' => 'Karachi, Pakistan',
        'literary_style' => 'Feminine Sensibility, Fragrant Intimacy, Subtle Longing & Modern Womanhood',
        'biography' => 'Parveen Shakir brought an unprecedented, authentic female voice to the male-dominated sphere of Urdu Ghazal. Writing with the delicacy of fragrance ("Khushboo"), her verses articulated love, separation, societal constraints, and professional womanhood with radiant poise and raw sincerity.',
        'famous_works' => ['Khushboo', 'Sad-barg', 'Khud-Kalami', 'Inkaar', 'Maah-e-Tamaam'],
        'cover_bg' => 'from-[#2b0c1e] to-[#0a0a0a]',
        'accent_color' => '#ff4081',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Sahir Ludhianvi',
        'slug' => 'sahir-ludhianvi',
        'language' => 'Urdu',
        'era' => '1921 – 1980',
        'birthplace' => 'Ludhiana, Punjab',
        'literary_style' => 'Social Realism, Romantic Disillusionment & Anti-Imperialist Fire',
        'biography' => 'Abdul Hayee, immortalized as Sahir Ludhianvi, redefined South Asian poetry and cinema. Uncompromisingly socialist, deeply anti-war, and skeptical of hollow romanticism, Sahir spoke for the working class, the destitute, and the disillusioned lover with unmatched rhetorical power.',
        'famous_works' => ['Talkhiyan', 'Parchhaiyan', 'Aao Ke Koi Khwab Bunein'],
        'cover_bg' => 'from-[#241706] to-[#0a0a0a]',
        'accent_color' => '#ffab00',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],

    // Hindi Literary Figures
    [
        'name' => 'Harivansh Rai Bachchan',
        'slug' => 'harivansh-rai-bachchan',
        'language' => 'Hindi',
        'era' => '1907 – 2003',
        'birthplace' => 'Babupatti, United Provinces',
        'literary_style' => 'Halaavada (Neo-Romanticism), Rhythmic Metaphor & Humanist Philosophy',
        'biography' => 'A colossal figure in modern Hindi literature, Bachchan pioneered the "Halaavada" literary movement inspired by Omar Khayyam. His magnum opus, Madhushala, uses the metaphor of the tavern, wine, and the cup to reflect profoundly on life, death, unity, and cosmic harmony, transcending caste, creed, and time.',
        'famous_works' => ['Madhushala', 'Madhubala', 'Madhukalash', 'Nisha Nimantran', 'Kya Bhoolun Kya Yaad Karun'],
        'cover_bg' => 'from-[#2d1607] to-[#0a0a0a]',
        'accent_color' => '#ff9100',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Dushyant Kumar',
        'slug' => 'dushyant-kumar',
        'language' => 'Hindi',
        'era' => '1933 – 1975',
        'birthplace' => 'Bijnor, Uttar Pradesh',
        'literary_style' => 'Modern Hindi Ghazal, Anti-Establishment Rebellion & Social Awakening',
        'biography' => 'Dushyant Kumar achieved what few thought possible: he adapted the traditionally Urdu form of the Ghazal into contemporary Hindustani/Hindi idiom with roaring political relevance. His poetry became the voice of rebellion against political complacency and moral stagnation.',
        'famous_works' => ['Saaye Mein Dhoop', 'Surya Ka Swagat', 'Awaazon Ke Ghere', 'Jalte Huye Van Ka Vasant'],
        'cover_bg' => 'from-[#2e0909] to-[#0a0a0a]',
        'accent_color' => '#ff1744',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Ramdhari Singh Dinkar',
        'slug' => 'ramdhari-singh-dinkar',
        'language' => 'Hindi',
        'era' => '1908 – 1974',
        'birthplace' => 'Simariya, Bengal Presidency (now Bihar)',
        'literary_style' => 'Veer Rasa (Heroic Majesty), Cosmic Moral Duty & Nationalist Courage',
        'biography' => 'Hailed as the Rashtrakavi (National Poet), Dinkar was a poet of volcanic energy and profound ethical inquiry. In works like Rashmirathi and Kurukshetra, he explored the tension between warrior duty, cosmic justice, and individual dignity through the tragic hero Karna.',
        'famous_works' => ['Rashmirathi', 'Kurukshetra', 'Urvashi', 'Hunkar', 'Parshuram Ki Pratiksha'],
        'cover_bg' => 'from-[#331704] to-[#0a0a0a]',
        'accent_color' => '#ff6d00',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Vinod Kumar Shukla',
        'slug' => 'vinod-kumar-shukla',
        'language' => 'Hindi',
        'era' => '1937 – Present',
        'birthplace' => 'Rajnandgaon, Madhya Pradesh (now Chhattisgarh)',
        'literary_style' => 'Magical Quietism, Minimalist Wonder & Gentle Surrealism',
        'biography' => 'Winner of the prestigious Sahitya Akademi Award and the PEN/Nabokov Award for International Literature, Shukla is revered for finding infinite cosmic poetry in the smallest, quietest details of mundane Indian domesticity and nature.',
        'famous_works' => ['Naukar Ki Kameez', 'Sab Kuch Hona Bacha Rahega', 'Diwar Mein Ek Khirkee Rahti Thi'],
        'cover_bg' => 'from-[#072417] to-[#0a0a0a]',
        'accent_color' => '#00e676',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Suryakant Tripathi \'Nirala\'',
        'slug' => 'suryakant-tripathi-nirala',
        'language' => 'Hindi',
        'era' => '1899 – 1961',
        'birthplace' => 'Midnapore, Bengal Presidency',
        'literary_style' => 'Chhayavad (Romantic Mysticism), Free Verse Pioneer & Fearless Rebel',
        'biography' => 'Nirala broke the rigid shackles of traditional Hindi prosody to birth modern Hindi free verse. His life was plagued by immense poverty and grief, which he transfigured into immortal verses of fierce defiance, compassion for the oppressed, and spiritual transcendence.',
        'famous_works' => ['Saroj Smriti', 'Ram Ki Shakti Pooja', 'Anamika', 'Parimal', 'Kukurmutta'],
        'cover_bg' => 'from-[#1a0f2e] to-[#0a0a0a]',
        'accent_color' => '#7c4dff',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Mahadevi Varma',
        'slug' => 'mahadevi-varma',
        'language' => 'Hindi',
        'era' => '1907 – 1987',
        'birthplace' => 'Farrukhabad, United Provinces',
        'literary_style' => 'Chhayavadi Mysticism, Soulful Pain (Vedana) & Lyrical Devotion',
        'biography' => 'Regarded as the "Modern Meera", Mahadevi Varma was one of the four foundational pillars of the Chhayavad movement. A feminist icon, educationalist, and Jnanpith laureate, her deeply musical poetry explores the communion of the finite human soul with the infinite divine through sacred yearning.',
        'famous_works' => ['Yama', 'Neehar', 'Rashmi', 'Neerja', 'Deepshikha'],
        'cover_bg' => 'from-[#241306] to-[#0a0a0a]',
        'accent_color' => '#ffd600',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Kabir',
        'slug' => 'kabir',
        'language' => 'Hindi',
        'era' => '15th Century',
        'birthplace' => 'Varanasi',
        'literary_style' => 'Mystic Bhakti, Iconoclastic Dohas, Direct Satire & Universal Truth',
        'biography' => 'The immortal 15th-century weaver-poet of Banaras whose terse couplets (Dohas) demolished religious hypocrisy, ritualism, and dogma. Kabir spoke in the everyday dialect of the common people, calling for inward self-realization, love, and spiritual simplicity.',
        'famous_works' => ['Kabir Bijak', 'Sakhi Granth', 'Kabir Granthavali'],
        'cover_bg' => 'from-[#2b1f06] to-[#0a0a0a]',
        'accent_color' => '#ffd600',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

$poetsInsertResult = $poetsCol->insertMany($poets);
echo "✓ " . count($poets) . " poets seeded into 'poets' collection!\n";

// Map poet name to inserted ObjectId
$poetMap = [];
$allPoets = $poetsCol->find()->toArray();
foreach ($allPoets as $p) {
    $poetMap[$p['name']] = $p['_id'];
}

// 2. Seed Literary Collections (Moods & Themes)
$collectionsCol = $db->collections;
$collectionsCol->drop();
$collectionsCol->createIndex(['slug' => 1], ['unique' => true]);

$collectionsData = [
    [
        'name' => 'Tanhai',
        'slug' => 'tanhai',
        'tagline' => 'The Architecture of Solitude',
        'mood' => 'Tanhai',
        'description' => 'When the world retreats into silence, solitude is not an absence—it is a presence. A sanctuary for quiet midnight reflections, bittersweet longings, and conversations with one’s own shadow.',
        'quote_roman' => 'Kitni azeem thi woh tanhaiyan jahaan humne khudi ko dhoondh nikala.',
        'color_accent' => '#00e5ff',
        'bg_gradient' => 'from-[#061824] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Dard',
        'slug' => 'dard',
        'tagline' => 'Ache That Transcends Words',
        'mood' => 'Dard',
        'description' => 'Grief, unrequited longing, and the sacred beauty of wounds that refuse to heal easily. In South Asian poetry, pain is not merely suffered; it is sculpted into high art.',
        'quote_roman' => 'Ranjish hi sahi dil hi dukhaane ke liye aa, aa phir se mujhe chhod ke jaane ke liye aa.',
        'color_accent' => '#ff1744',
        'bg_gradient' => 'from-[#26080e] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Mohabbat',
        'slug' => 'mohabbat',
        'tagline' => 'Devotion, Union & Tenderness',
        'mood' => 'Mohabbat',
        'description' => 'The delicate breath of first glances, fragile promises, eternal devotion, and the ecstasy of two souls recognizing each other across lifetimes.',
        'quote_roman' => 'Tere aane ki khabar jab se suni hai humne, har hawa mein teri khushboo ka gumaan hota hai.',
        'color_accent' => '#ff4081',
        'bg_gradient' => 'from-[#29081a] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Falsafa',
        'slug' => 'falsafa',
        'tagline' => 'Existential Skepticism & Cosmic Wonder',
        'mood' => 'Falsafa',
        'description' => 'Questioning fate, God, time, mortality, and the theatre of human ego. The philosophical Ghazal pierces through worldly illusions with ruthless intellect and wry detachment.',
        'quote_roman' => 'Na tha kuch toh khuda tha, kuch na hota toh khuda hota...',
        'color_accent' => '#c5a059',
        'bg_gradient' => 'from-[#241a06] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Zindagi',
        'slug' => 'zindagi',
        'tagline' => 'The Tapestry of Being',
        'mood' => 'Zindagi',
        'description' => 'The everyday struggles, bitter ironies, resilient triumphs, and fragile poetry woven through the simple acts of breathing, working, and enduring life.',
        'quote_roman' => 'Zindagi kya hai anasir mein zahoor-e-tarteeb, maut kya hai inhi ajza ka pareshan hona.',
        'color_accent' => '#00e676',
        'bg_gradient' => 'from-[#072414] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Ishq',
        'slug' => 'ishq',
        'tagline' => 'Passionate Surrender & Sacred Fire',
        'mood' => 'Ishq',
        'description' => 'Far beyond romantic affection lies Ishq—the all-consuming fire of self-annihilation, mystical surrender, and mad obsession celebrated from Rumi to Ghalib.',
        'quote_roman' => 'Yeh ishq nahi aasan itna hi samajh lijiye, ik aag ka darya hai aur doob ke jaana hai.',
        'color_accent' => '#ff5252',
        'bg_gradient' => 'from-[#2b0808] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Safar',
        'slug' => 'safar',
        'tagline' => 'Wanderlust, Displacement & Return',
        'mood' => 'Safar',
        'description' => 'The poetry of roads, train stations, foreign cities, inner pilgrimages, and the haunting realization that home is often a place that exists only in memory.',
        'quote_roman' => 'Hum toh nikal pade the akele hi safar par, manzil mile na mile raaste naye the.',
        'color_accent' => '#b388ff',
        'bg_gradient' => 'from-[#190c29] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Yaad',
        'slug' => 'yaad',
        'tagline' => 'Nostalgia & The Shadows of Yesterday',
        'mood' => 'Yaad',
        'description' => 'Old letters, faded photographs, twilight conversations, and the persistent presence of what once was. Remembering is an involuntary act of resistance against forgetting.',
        'quote_roman' => 'Kab yaad mein tera saath nahi, kab haath mein tera haath nahi...',
        'color_accent' => '#ffd600',
        'bg_gradient' => 'from-[#261f06] to-[#070707]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

$collectionsCol->insertMany($collectionsData);
echo "✓ " . count($collectionsData) . " mood collections seeded!\n";

// 3. Seed Poetry / Shayari Collection (Legal, short evocative excerpts in Roman script)
$poetryCol = $db->poetry;
$poetryCol->drop();
$poetryCol->createIndex(['mood' => 1]);
$poetryCol->createIndex(['language' => 1]);
$poetryCol->createIndex(['genre' => 1]);

$poetryList = [
    // 1. Jaun Elia - Tanhai / Falsafa
    [
        'title' => 'Be-Dili Kya Yoonhi Din Guzar Jaayenge',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'poet_name' => 'Jaun Elia',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Falsafa', 'Dard', 'Zindagi'],
        'text_roman' => "Be-dili kya yoonhi din guzar jaayenge,\nSirf zinda rahe hum toh mar jaayenge.\n\nYeh ajeeb gham hai ki kisi se gham nahi,\nKya hum waaqai itne be-khabar ho gaye?",
        'meaning_en' => 'Reflecting on the existential numbness where merely surviving feels akin to dying, and alienation from sorrow itself becomes the ultimate tragedy.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 2. Jaun Elia - Dard / Ishq
    [
        'title' => 'Uss Gali Ne Sun Ke Sabr Mera',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'poet_name' => 'Jaun Elia',
        'language' => 'Urdu',
        'genre' => 'Shayari',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Mohabbat', 'Ishq'],
        'text_roman' => "Uss gali ne sun ke sabr mera,\nJaane kyun aah bhari shaam ke waqt.\n\nHumko yaaron ne yaad bhi na kiya,\nJaun aana tha kaam ke waqt.",
        'meaning_en' => 'A haunting observation on enduring silence in solitude, and how companions vanish the moment genuine need arises.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 3. Mirza Ghalib - Falsafa / Zindagi
    [
        'title' => 'Hazaaron Khwahishein Aisi',
        'poet_id' => $poetMap['Mirza Ghalib'] ?? null,
        'poet_name' => 'Mirza Ghalib',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Khwahish', 'Zindagi'],
        'text_roman' => "Hazaaron khwahishein aisi ki har khwahish pe dam nikle,\nBahut nikle mere armaan lekin phir bhi kam nikle.\n\nNikalna khuld se aadam ka sunte aaye the lekin,\nBahut be-aabroo hokar tere kooche se hum nikle.",
        'meaning_en' => 'Ghalib’s quintessential meditation on the boundless nature of human desires—each intense enough to consume a lifetime—and the existential humility of love.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 4. Mirza Ghalib - Ishq / Dard
    [
        'title' => 'Dil-e-Nadaan Tujhe Hua Kya Hai',
        'poet_id' => $poetMap['Mirza Ghalib'] ?? null,
        'poet_name' => 'Mirza Ghalib',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Ishq',
        'tags' => ['Ishq', 'Dard', 'Mohabbat'],
        'text_roman' => "Dil-e-nadaan tujhe hua kya hai,\nAakhir is dard ki dawa kya hai?\n\nHum hain mushtaaq aur woh be-zaar,\nYaa Ilaahi yeh maajra kya hai?",
        'meaning_en' => 'Addressing one’s naive heart with gentle, self-aware irony: asking what cure exists for an affliction born of longing for one who remains indifferent.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 5. Faiz Ahmed Faiz - Mohabbat / Zindagi
    [
        'title' => 'Mujh Se Pehli Si Mohabbat',
        'poet_id' => $poetMap['Faiz Ahmed Faiz'] ?? null,
        'poet_name' => 'Faiz Ahmed Faiz',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Mohabbat',
        'tags' => ['Mohabbat', 'Zindagi', 'Falsafa'],
        'text_roman' => "Mujh se pehli si mohabbat mere mehboob na maang,\nMaine samjha tha ki tu hai toh darakhshaan hai hayaat.\n\nAur bhi dukh hain zamaane mein mohabbat ke siwa,\nRaahatein aur bhi hain wasl ki raahat ke siwa.",
        'meaning_en' => 'A historic pivot in modern verse where private romantic adoration yields to awareness of the vast socio-political suffering of the wider world.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 6. Faiz Ahmed Faiz - Safar / Zindagi
    [
        'title' => 'Hum Dekhenge',
        'poet_id' => $poetMap['Faiz Ahmed Faiz'] ?? null,
        'poet_name' => 'Faiz Ahmed Faiz',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Zindagi',
        'tags' => ['Zindagi', 'Inquilab', 'Falsafa'],
        'text_roman' => "Hum dekhenge,\nLaazim hai ki hum bhi dekhenge,\nWoh din ki jis ka waada hai,\nJo lauh-e-azal mein likkha hai.\n\nJab zulm-o-sitam ke koh-e-garaan,\nRooi ki tarah ud jaayenge.",
        'meaning_en' => 'Faiz’s defiant song of inevitability: justice will dawn, heavy mountains of oppression will scatter like cotton, and truth will prevail.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 7. Mir Taqi Mir - Dard / Yaad
    [
        'title' => 'Patta Patta Boota Boota',
        'poet_id' => $poetMap['Mir Taqi Mir'] ?? null,
        'poet_name' => 'Mir Taqi Mir',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Yaad', 'Mohabbat'],
        'text_roman' => "Patta patta boota boota haal humaara jaane hai,\nJaane na jaane gul hi na jaane, baagh toh saara jaane hai.\n\nMeher-o-wafa-o-lutf-o-karam sab kehne ki baatein hain,\nUsne kab humse yeh kiya, kisse be-wafa keh ke pukaarein?",
        'meaning_en' => 'Every leaf and shrub in the garden senses the lover’s quiet agony; only the rose itself remains blissfully unaware.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 8. Ahmad Faraz - Mohabbat / Dard
    [
        'title' => 'Ranjish Hi Sahi',
        'poet_id' => $poetMap['Ahmad Faraz'] ?? null,
        'poet_name' => 'Ahmad Faraz',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Mohabbat', 'Yaad'],
        'text_roman' => "Ranjish hi sahi dil hi dukhaane ke liye aa,\nAa phir se mujhe chhod ke jaane ke liye aa.\n\nKuch toh mere pindaar-e-mohabbat ka bharam rakh,\nTu bhi toh kabhi mujhko manaane ke liye aa.",
        'meaning_en' => 'Even if it is born of grievance and destined to end in departure, return just once—to break the heart again, for even the pain of presence is sweeter than absence.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 9. Parveen Shakir - Mohabbat / Yaad
    [
        'title' => 'Woh Toh Khushboo Hai Hawaon Mein Bikhar Jaayega',
        'poet_id' => $poetMap['Parveen Shakir'] ?? null,
        'poet_name' => 'Parveen Shakir',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Mohabbat',
        'tags' => ['Mohabbat', 'Yaad', 'Tanhai'],
        'text_roman' => "Woh toh khushboo hai hawaon mein bikhar jaayega,\nMasla phool ka hai phool kidhar jaayega?\n\nHum toh samjhe the ki ik zakhm hai bhar jaayega,\nKya khabar thi ki rag-o-jaan mein utar jaayega.",
        'meaning_en' => 'The beloved, like fragrance, drifts freely into the breeze; the fragile flower is the one left to bear the weight of fading.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 10. Sahir Ludhianvi - Falsafa / Yaad
    [
        'title' => 'Kabhi Kabhi Mere Dil Mein',
        'poet_id' => $poetMap['Sahir Ludhianvi'] ?? null,
        'poet_name' => 'Sahir Ludhianvi',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Yaad',
        'tags' => ['Yaad', 'Mohabbat', 'Falsafa'],
        'text_roman' => "Kabhi kabhi mere dil mein khayal aata hai,\nKi jaise tujhko banaya gaya hai mere liye.\n\nTu ab se pehle sitaaron mein bas rahi thi kahin,\nTujhe zameen pe utaara gaya hai mere liye.",
        'meaning_en' => 'Sahir’s immortal ode to timeless serendipity and the cosmic illusion of destinies intertwining.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],

    // 11. Harivansh Rai Bachchan - Falsafa / Zindagi
    [
        'title' => 'Madhushala (Chaupai Excerpt)',
        'poet_id' => $poetMap['Harivansh Rai Bachchan'] ?? null,
        'poet_name' => 'Harivansh Rai Bachchan',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Zindagi', 'Safar'],
        'text_roman' => "Musalman aur Hindu hain do, ek magar unka pyaala,\nEk magar unka madiralay, ek magar unki haala.\nDono rahte ek na jab tak mandir masjid mein jaate,\nVair badhaate mandir masjid, mel karaati Madhushala!",
        'meaning_en' => 'Bachchan’s iconic humanist stanza: sectarian divides arise when dogmas preach separation, but shared human fragility and fellowship unite us all.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 12. Dushyant Kumar - Zindagi / Safar
    [
        'title' => 'Ho Gayi Hai Peer Parvat Si Pighalni Chahiye',
        'poet_id' => $poetMap['Dushyant Kumar'] ?? null,
        'poet_name' => 'Dushyant Kumar',
        'language' => 'Hindi',
        'genre' => 'Ghazal',
        'mood' => 'Zindagi',
        'tags' => ['Zindagi', 'Safar', 'Inquilab'],
        'text_roman' => "Ho gayi hai peer parvat si pighalni chahiye,\nIs Himalaya se koi Ganga nikalni chahiye.\n\nSirf hungama khada karna mera maqsad nahi,\nMeri koshish hai ki yeh soorat badalni chahiye.\n\nMere seene mein nahi toh tere seene mein sahi,\nHo kahin bhi aag, lekin aag jalni chahiye.",
        'meaning_en' => 'Dushyant’s electrifying call for moral renewal: suffering has swollen into a massive mountain; now a fresh river of transformation must burst forth.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 13. Dushyant Kumar - Tanhai / Yaad
    [
        'title' => 'Kahaan Toh Tay Tha Chiragaan',
        'poet_id' => $poetMap['Dushyant Kumar'] ?? null,
        'poet_name' => 'Dushyant Kumar',
        'language' => 'Hindi',
        'genre' => 'Ghazal',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Dard', 'Falsafa'],
        'text_roman' => "Kahaan toh tay tha chiragaan har ek ghar ke liye,\nKahaan chirag mayassar nahi shahar ke liye.\n\nYahaan darakhton ke saaye mein dhoop lagti hai,\nChalo yahaan se chalein aur umr bhar ke liye.",
        'meaning_en' => 'Lamenting the broken promises of modernity: where trees cast shadows that feel like scorching sun, prompting the soul to wander into lifelong exile.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 14. Ramdhari Singh Dinkar - Zindagi / Safar
    [
        'title' => 'Rashmirathi (Krishna Ki Chetavani)',
        'poet_id' => $poetMap['Ramdhari Singh Dinkar'] ?? null,
        'poet_name' => 'Ramdhari Singh Dinkar',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Safar',
        'tags' => ['Safar', 'Veer', 'Falsafa'],
        'text_roman' => "Varsho tak van mein ghoom ghoom,\nBadha vighno ko choom choom,\nSeh dhoop ghaam, paani pathar,\nPandav aaye kuch aur nikhar.\n\nSaundarya yahi, bal ka pramaan,\nJo sankat mein bhi hans de muskaan!",
        'meaning_en' => 'Enduring wilderness, storms, and scorching stone does not destroy the resilient spirit—it polishes character into invincible steel.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 15. Vinod Kumar Shukla - Tanhai / Zindagi
    [
        'title' => 'Sab Kuch Hona Bacha Rahega',
        'poet_id' => $poetMap['Vinod Kumar Shukla'] ?? null,
        'poet_name' => 'Vinod Kumar Shukla',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Zindagi', 'Falsafa'],
        'text_roman' => "Hawa chali toh ped hila,\nPed hila toh chidiya udi,\nChidiya udi toh aakash hua.\n\nHumne socha ki agar chup rahein,\nToh kya duniya mein shabd bachenge?\nShabd bache rahe, kyunki humne prem kiya.",
        'meaning_en' => 'Shukla’s luminous minimalist meditation: when wind stirs the tree, the bird flies, creating sky. And words endure because love quietly speaks.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 16. Mahadevi Varma - Tanhai / Ishq
    [
        'title' => 'Main Neer Bhari Dukh Ki Badli',
        'poet_id' => $poetMap['Mahadevi Varma'] ?? null,
        'poet_name' => 'Mahadevi Varma',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Ishq', 'Dard'],
        'text_roman' => "Main neer bhari dukh ki badli!\nSpandan mein chir nispand basa,\nKrandon mein aahat vishwa hansa,\nNayanon mein deepak se jalte,\nPalkon mein nirjharini machli!",
        'meaning_en' => 'A soul self-described as a rain-cloud of compassionate sorrow: bearing silent stillness inside the heartbeat, weeping tears that nourish the parched world.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 17. Kabir - Falsafa / Zindagi
    [
        'title' => 'Moko Kahan Dhoondhe Re Bande',
        'poet_id' => $poetMap['Kabir'] ?? null,
        'poet_name' => 'Kabir',
        'language' => 'Hindi',
        'genre' => 'Doha',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Bhakti', 'Zindagi'],
        'text_roman' => "Moko kahan dhoondhe re bande, main toh tere paas mein.\nNa teerath mein, na moorat mein, na ekant nivaas mein.\n\nKaho Kabir suno bhai saadho, sab saanson ki saans mein!",
        'meaning_en' => 'Kabir’s timeless couplet: Where do you search for truth in temples and distant pilgrimages? It resides intimately within the very breath of your breathing.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // 18. Suryakant Tripathi 'Nirala' - Yaad / Dard
    [
        'title' => 'Saroj Smriti (Smriti Excerpt)',
        'poet_id' => $poetMap['Suryakant Tripathi \'Nirala\''] ?? null,
        'poet_name' => 'Suryakant Tripathi \'Nirala\'',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Yaad',
        'tags' => ['Yaad', 'Dard', 'Zindagi'],
        'text_roman' => "Dukh hi jeevan ki katha rahi,\nKya kahoon aaj, jo nahi kahi!\n\nPar smriti ke is aangan mein,\nTeri muskaan amar rahegi.",
        'meaning_en' => 'Nirala’s deeply personal elegy for his daughter Saroj: life has been a relentless chronicle of grief, yet the courtyard of memory holds an eternal smile.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

$poetryCol->insertMany($poetryList);
echo "✓ " . count($poetryList) . " poetry pieces seeded into 'poetry' collection!\n";

// 4. Enrich 'books' Collection with Hindi & Urdu Literary Titles linked to poets
$booksCol = $db->books;

$literaryBooks = [
    [
        'title' => 'Shayad',
        'author' => 'Jaun Elia',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 450,
        'rating' => 4.9,
        'stock' => 14,
        'format' => 'Hardcover Edition',
        'language' => 'Urdu (Roman & Nastaliq Notes)',
        'pages' => 320,
        'isbn' => '978-9694190143',
        'description' => 'The legendary first poetry collection of Jaun Elia, featuring his iconic preface and timeless ghazals on disillusionment, philosophy, and unadorned heartbreak.',
        'featured' => true,
        'cover_bg' => 'from-[#2e0717] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Tanhai',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Diwan-e-Ghalib',
        'author' => 'Mirza Ghalib',
        'poet_id' => $poetMap['Mirza Ghalib'] ?? null,
        'category' => 'Classics',
        'price' => 399,
        'rating' => 4.9,
        'stock' => 20,
        'format' => 'Deluxe Collector Edition',
        'language' => 'Urdu & English Tafseer',
        'pages' => 280,
        'isbn' => '978-8171676644',
        'description' => 'The complete immortal Ghazals of Mirza Asadullah Khan Ghalib with parallel Roman transcription and insightful literary commentary.',
        'featured' => true,
        'cover_bg' => 'from-[#2e1d08] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Falsafa',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Madhushala',
        'author' => 'Harivansh Rai Bachchan',
        'poet_id' => $poetMap['Harivansh Rai Bachchan'] ?? null,
        'category' => 'Hindi Literature',
        'price' => 299,
        'rating' => 4.9,
        'stock' => 25,
        'format' => 'Paperback',
        'language' => 'Hindi (With Roman Notes)',
        'pages' => 160,
        'isbn' => '978-8170281030',
        'description' => 'One of the most celebrated poetic masterpieces of 20th-century Hindi literature. 135 profound quatrains exploring human destiny through the timeless metaphor of the tavern.',
        'featured' => true,
        'cover_bg' => 'from-[#331704] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Falsafa',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Rashmirathi',
        'author' => 'Ramdhari Singh Dinkar',
        'poet_id' => $poetMap['Ramdhari Singh Dinkar'] ?? null,
        'category' => 'Hindi Literature',
        'price' => 349,
        'rating' => 4.9,
        'stock' => 18,
        'format' => 'Hardcover',
        'language' => 'Hindi',
        'pages' => 192,
        'isbn' => '978-8170281894',
        'description' => 'The heroic, electrifying epic narrative tracing the valor, cosmic moral dilemmas, and unyielding honor of the Mahabharata’s most tragic figure, Karna.',
        'featured' => true,
        'cover_bg' => 'from-[#331505] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Safar',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Nuskha-e-Haye-Wafa',
        'author' => 'Faiz Ahmed Faiz',
        'poet_id' => $poetMap['Faiz Ahmed Faiz'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 550,
        'rating' => 4.8,
        'stock' => 12,
        'format' => 'Hardcover',
        'language' => 'Urdu (With Roman Edition)',
        'pages' => 440,
        'isbn' => '978-9694190013',
        'description' => 'The definitive collective works of Faiz Ahmed Faiz, compiling Naqsh-e-Faryadi, Dast-e-Saba, Zindan-Nama, and his immortal resistance anthems.',
        'featured' => false,
        'cover_bg' => 'from-[#240b0b] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Mohabbat',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Saaye Mein Dhoop',
        'author' => 'Dushyant Kumar',
        'poet_id' => $poetMap['Dushyant Kumar'] ?? null,
        'category' => 'Hindi Literature',
        'price' => 249,
        'rating' => 4.8,
        'stock' => 22,
        'format' => 'Paperback',
        'language' => 'Hindi',
        'pages' => 128,
        'isbn' => '978-8126702671',
        'description' => 'The revolutionary collection that democratized and popularized Hindustani ghazals across India, featuring 52 poignant and rebellious compositions.',
        'featured' => false,
        'cover_bg' => 'from-[#2e0909] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Zindagi',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Khushboo',
        'author' => 'Parveen Shakir',
        'poet_id' => $poetMap['Parveen Shakir'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 380,
        'rating' => 4.8,
        'stock' => 15,
        'format' => 'Paperback',
        'language' => 'Urdu',
        'pages' => 240,
        'isbn' => '978-9694190457',
        'description' => 'Parveen Shakir’s ground-breaking debut anthology, capturing the tender intimacies, whispered dreams, and poignant separations of youth.',
        'featured' => false,
        'cover_bg' => 'from-[#2b0c1e] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Mohabbat',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

// Check if book already exists before inserting
foreach ($literaryBooks as $lBook) {
    $existing = $booksCol->findOne(['title' => $lBook['title']]);
    if (!$existing) {
        $booksCol->insertOne($lBook);
    } else {
        $booksCol->updateOne(['_id' => $existing['_id']], ['$set' => $lBook]);
    }
}
echo "✓ Literary books successfully synced into 'books' collection!\n";

echo "=== Seeding of Literary Mehfil Completed Successfully! ===\n";
