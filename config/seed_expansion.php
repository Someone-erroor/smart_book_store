<?php

require_once __DIR__ . '/database.php';

if (!$dbConnected) {
    die("Database connection failed: " . ($dbError ?? "Unknown error"));
}

echo "=== SMART Bookstore Master Content & Literary Expansion Seeder ===\n";

$poetsCol = $db->poets;
$poetryCol = $db->poetry;
$booksCol = $db->books;

// 1. ADD NEW MASTER POETS (Expanding to 19 poets)
$newPoets = [
    [
        'name' => 'Sahir Ludhianvi',
        'slug' => 'sahir-ludhianvi',
        'language' => 'Urdu',
        'era' => '1921 – 1980',
        'birthplace' => 'Ludhiana, Punjab',
        'literary_style' => 'Progressive Disillusionment, Bitter Romanticism & Anti-Establishment Verse',
        'biography' => 'Abdul Hayee, immortalized as Sahir Ludhianvi, brought revolutionary fire and raw existential melancholy to Urdu poetry and cinema lyrics. A fierce champion of the poor and a relentless critic of feudal vanity, his words cut through societal hypocrisy with unmatched lyricism.',
        'famous_works' => ['Talkhiyan', 'Parchhaiyan', 'Aao Ki Koi Khwaab Bunein', 'Gata Jaye Banjara'],
        'cover_bg' => 'from-[#2a0e14] to-[#0a0a0a]',
        'accent_color' => '#ff1744',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Ahmad Faraz',
        'slug' => 'ahmad-faraz',
        'language' => 'Urdu',
        'era' => '1931 – 2008',
        'birthplace' => 'Kohat, British India',
        'literary_style' => 'Romantic Melancholy, Resistance Lyricism & Sublime Emotional Grace',
        'biography' => 'Syed Ahmad Shah, widely celebrated as Ahmad Faraz, was one of modern Urdu literature’s supreme voices of love, sorrow, and defiance. Jailed for opposing military dictatorships, Faraz’s ghazals remain immortal for their tender cadence and profound resonance.',
        'famous_works' => ['Tanha Tanha', 'Dard-e-Aashob', 'Nayaft', 'Shab-e-Khoon', 'Be-Awaz Gali-Koochon Mein'],
        'cover_bg' => 'from-[#1a1128] to-[#0a0a0a]',
        'accent_color' => '#a855f7',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Bahadur Shah Zafar',
        'slug' => 'bahadur-shah-zafar',
        'language' => 'Urdu',
        'era' => '1775 – 1862',
        'birthplace' => 'Red Fort, Old Delhi',
        'literary_style' => 'Tragic Imperial Elegy, Exile Heartbreak & Mystical Resignation',
        'biography' => 'The last Mughal Emperor of India and a poignant master poet of the Delhi court. Following the rebellion of 1857, exiled to Rangoon by the British Raj, Zafar penned verses of unutterable sorrow mourning the loss of his beloved homeland, his murdered sons, and the fallen city of Delhi.',
        'famous_works' => ['Kulliyat-e-Zafar', 'Diwan-e-Zafar'],
        'cover_bg' => 'from-[#2e1d08] to-[#0a0a0a]',
        'accent_color' => '#c5a059',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Munshi Premchand',
        'slug' => 'munshi-premchand',
        'language' => 'Hindi',
        'era' => '1880 – 1936',
        'birthplace' => 'Lamhi, Varanasi',
        'literary_style' => 'Radical Socio-Economic Realism, Peasant Conscience & Humanist Prose',
        'biography' => 'Dhanpat Rai Srivastava, reverently known as Munshi Premchand, is the colossal founding father of modern Hindi and Urdu fiction. Writing with intense empathy about rural destitution, caste oppression, communalism, and bureaucratic corruption, his stories transformed Indian conscience.',
        'famous_works' => ['Godaan', 'Nirmala', 'Kafan', 'Ghaban', 'Seva Sadan', 'Idgah'],
        'cover_bg' => 'from-[#291708] to-[#0a0a0a]',
        'accent_color' => '#c5a059',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'name' => 'Jaishankar Prasad',
        'slug' => 'jaishankar-prasad',
        'language' => 'Hindi',
        'era' => '1889 – 1937',
        'birthplace' => 'Varanasi, Uttar Pradesh',
        'literary_style' => 'Majestic Chhayavad, Philosophical Epic Romance & Vedic Symbolism',
        'biography' => 'A towering pillar of the Hindi Chhayavad (Romanticist-Symbolist) movement, dramatist, and mystic philosopher. His magnum opus Kamayani is revered as the greatest modern epic in the Hindi language, weaving psychology, evolution, and transcendent spiritual love.',
        'famous_works' => ['Kamayani', 'Aansoo', 'Lahar', 'Skandagupta', 'Chandragupta'],
        'cover_bg' => 'from-[#141f2a] to-[#0a0a0a]',
        'accent_color' => '#38bdf8',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

foreach ($newPoets as $np) {
    $existing = $poetsCol->findOne(['slug' => $np['slug']]);
    if (!$existing) {
        $poetsCol->insertOne($np);
        echo "+ Added Poet: {$np['name']}\n";
    }
}

// Build fresh poet map
$poetMap = [];
$allPoets = $poetsCol->find()->toArray();
foreach ($allPoets as $p) {
    $poetMap[$p['name']] = $p['_id'];
}

// 2. ADD 26 MORE IMMORTAL POETRY PIECES (Bringing total to 44+ pieces)
$newPoetry = [
    // Ghalib 2
    [
        'title' => 'Dil-E-Nadan Tujhe Hua Kya Hai',
        'poet_id' => $poetMap['Mirza Ghalib'] ?? null,
        'poet_name' => 'Mirza Ghalib',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Mohabbat', 'Zindagi'],
        'text_roman' => "Dil-e-nadan tujhe hua kya hai,\nAakhir is dard ki dawa kya hai?\n\nHum hain mushtaaq aur woh bezaar,\nYa Ilaahi yeh maajra kya hai?\n\nMain bhi munh mein zabaan rakhta hoon,\nKaash poochho ki muddaa kya hai!",
        'meaning_en' => 'Ghalib interrogates his bewildered heart: What has befallen you, naive heart? What is the elusive cure for this ache, where one yearns while the other turns away?',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Ghalib 3
    [
        'title' => 'Yeh Na Thi Hamari Qismat',
        'poet_id' => $poetMap['Mirza Ghalib'] ?? null,
        'poet_name' => 'Mirza Ghalib',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Tanhai', 'Yaad'],
        'text_roman' => "Yeh na thi hamari qismat ki visaal-e-yaar hota,\nAgar aur jeete rehte yahi intezaar hota.\n\nTere vaade par jiye hum toh yeh jaan jhooth jaana,\nKi khushi se mar na jaate agar aitbaar hota!",
        'meaning_en' => 'Union with the beloved was never inscribed in my destiny; had I lived longer, the waiting would have simply outlived life itself.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Ghalib 4
    [
        'title' => 'Ibn-E-Maryam Hua Kare Koi',
        'poet_id' => $poetMap['Mirza Ghalib'] ?? null,
        'poet_name' => 'Mirza Ghalib',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Zindagi'],
        'text_roman' => "Ibn-e-Maryam hua kare koi,\nMere dukh ki dawa kare koi.\n\nBak raha hoon junoon mein kya kya kuch,\nKuch na samjhe khuda kare koi!",
        'meaning_en' => 'Let someone be the miraculous Son of Mary—what matters to me is whether someone can heal my mortal grief in the present.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Jaun Elia 2
    [
        'title' => 'Tum Jab Aaogi',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'poet_name' => 'Jaun Elia',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Yaad',
        'tags' => ['Yaad', 'Tanhai', 'Dard'],
        'text_roman' => "Tum jab aaogi toh khoya hua paaogi mujhe,\nMeri tanhaai mein khwaabon ke siva kuch bhi nahi.\n\nMere kamre ko sajaane ki tamanna hai tumhe?\nMere kamre mein kitaabon ke siva kuch bhi nahi!",
        'meaning_en' => 'Jaun’s definitive bohemian warning: When you arrive, you will find me dissolved in thought. In my desolate room, there is nothing left except open books and lingering dreams.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Jaun Elia 3
    [
        'title' => 'Main Bhi Bahut Ajeeb Hoon',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'poet_name' => 'Jaun Elia',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Falsafa', 'Dard'],
        'text_roman' => "Main bhi bahut ajeeb hoon, itna ajeeb hoon ki bas,\nKhud ko tabaah kar liya aur malaal bhi nahi.\n\nAb meri koi zindagi hi nahi,\nAb bhi tum meri zindagi ho kya?",
        'meaning_en' => 'A chilling confession of existential ruin: I am strange enough to have annihilated myself without a shred of regret. And now that I have no life left, do you still dare call yourself my life?',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Jaun Elia 4
    [
        'title' => 'Uss Ki Gali Se Uth Kar Main',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'poet_name' => 'Jaun Elia',
        'language' => 'Urdu',
        'genre' => 'Sher',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Tanhai', 'Safar'],
        'text_roman' => "Uss ki gali se uth kar main anjaan raste chal pada,\nApne hi qadmon ki aahat se main darr gaya tha.\n\nWoh shakhs jo mera kabhi ho na saka,\nUssi ke naam par maine har zakhm seh liya.",
        'meaning_en' => 'Wandering away from her threshold into unfamiliar darkness, frightened by the solitary echo of my own footsteps.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Faiz 2 - Hum Dekhenge
    [
        'title' => 'Hum Dekhenge (Laazim Hai Ki Hum Bhi Dekhenge)',
        'poet_id' => $poetMap['Faiz Ahmed Faiz'] ?? null,
        'poet_name' => 'Faiz Ahmed Faiz',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Zindagi',
        'tags' => ['Zindagi', 'Safar', 'Falsafa'],
        'text_roman' => "Hum dekhenge,\nLaazim hai ki hum bhi dekhenge,\nWoh din ki jis ka vaada hai,\nJo lauh-e-azal mein likha hai!\n\nJab zulm-o-sitam ke koh-e-giraan,\nRooi ki tarah ud jayenge,\nHum mehkoomon ke paaon tale,\nYeh dharti dhad dhad dhadkegi,\nAur ahl-e-hakam ke saron par,\nJab bijli kad kad kadkegi!\n\nBas naam rahega Allah ka,\nJo ghayab bhi hai haazir bhi,\nJo manzar bhi hai naazir bhi,\nUtthega anal-haq ka naara,\nJo main bhi hoon aur tum bhi ho!",
        'meaning_en' => 'The eternal anthem of resilience and human truth: We shall witness the day when heavy mountains of tyranny blow away like wisps of cotton, and sovereignty belongs only to the people.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Faiz 3 - Bol Ke Lab Azaad Hain Tere
    [
        'title' => 'Bol Ke Lab Azaad Hain Tere',
        'poet_id' => $poetMap['Faiz Ahmed Faiz'] ?? null,
        'poet_name' => 'Faiz Ahmed Faiz',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Safar',
        'tags' => ['Safar', 'Zindagi', 'Falsafa'],
        'text_roman' => "Bol, ke lab azaad hain tere,\nBol, zabaan ab tak teri hai,\nTera sutwaan jism hai tera,\nBol, ke jaan ab tak teri hai!\n\nDekh ke aahangar ki dukaan mein,\nTund hain sholey, surkh hai aahan,\nKhulne lage quflon ke dahaane,\nPhaili har ek zanjeer ka daaman!",
        'meaning_en' => 'Speak now, for your lips are yet unchained; speak while your voice remains your own. In the forge of history, the embers burn red, and chains begin to tear apart.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Allama Iqbal 2 - Sitaron Se Aage
    [
        'title' => 'Sitaron Se Aage Jahan Aur Bhi Hain',
        'poet_id' => $poetMap['Allama Iqbal'] ?? null,
        'poet_name' => 'Allama Iqbal',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Safar',
        'tags' => ['Safar', 'Falsafa', 'Zindagi'],
        'text_roman' => "Sitaron se aage jahan aur bhi hain,\nAbhi ishq ke imtihaan aur bhi hain.\n\nTahi zindagi se nahi yeh fazaayein,\nYahaan sainkdon kaarwaan aur bhi hain.\n\nTu shaheen hai, parwaaz hai kaam tera,\nTere saamne aasmaan aur bhi hain!",
        'meaning_en' => 'Beyond the stars exist infinite unseen realms; love still has trials ahead. You are an eagle—flight is your essence, and an endless sky awaits your wings.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Allama Iqbal 3 - Khudi Ko Kar Buland Itna
    [
        'title' => 'Khudi Ko Kar Buland Itna',
        'poet_id' => $poetMap['Allama Iqbal'] ?? null,
        'poet_name' => 'Allama Iqbal',
        'language' => 'Urdu',
        'genre' => 'Sher',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Zindagi'],
        'text_roman' => "Khudi ko kar buland itna ki har taqdeer se pehle,\nKhuda bande se khud poochhe bata teri raza kya hai!\n\nNahi tera nasheman qasr-e-sultani ke gumbad par,\nTu shaheen hai basera kar pahaadon ki chattano par.",
        'meaning_en' => 'Elevate your conscious self to such sublime heights that before drafting your destiny, the Divine Himself asks: Tell Me, what is your desire?',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Harivansh Rai Bachchan 2 - Agnipath
    [
        'title' => 'Agnipath',
        'poet_id' => $poetMap['Harivansh Rai Bachchan'] ?? null,
        'poet_name' => 'Harivansh Rai Bachchan',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Zindagi',
        'tags' => ['Zindagi', 'Safar', 'Falsafa'],
        'text_roman' => "Vriksh hon bhale khade,\nHon ghane, hon bade,\nEk patr chaanv bhi\nMaang mat! Maang mat! Maang mat!\nAgnipath! Agnipath! Agnipath!\n\nTu na thakega kabhi,\nTu na thamega kabhi,\nTu na mudega kabhi,\nKar shapath! Kar shapath! Kar shapath!",
        'meaning_en' => 'The immortal rallying cry of human endurance: Ask not for shade, even under sheltering branches. You shall never tire, never halt, never turn back—on this path of fire.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Harivansh Rai Bachchan 3 - Jo Beet Gayi So Baat Gayi
    [
        'title' => 'Jo Beet Gayi So Baat Gayi',
        'poet_id' => $poetMap['Harivansh Rai Bachchan'] ?? null,
        'poet_name' => 'Harivansh Rai Bachchan',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Zindagi', 'Yaad'],
        'text_roman' => "Jo beet gayi so baat gayi!\nJeevan mein ek sitara tha,\nMaana, woh behad pyaara tha,\nWoh doob gaya toh doob gaya;\nAmbar ke aanan ko dekho,\nKitne iske taare toote,\nKitne iske pyaare chhoote,\nJo chhoot gaye phir kahan mile?\nPar bolo toote taaron par,\nKab ambar shok manata hai?\nJo beet gayi so baat gayi!",
        'meaning_en' => 'Look at the vast forehead of the sky: countless beloved stars fall, yet the heavens never mourn their loss. What has passed belongs to the past; live in the living moment.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Ramdhari Singh Dinkar 2 - Kalam Aaj Unki Jay Bol
    [
        'title' => 'Kalam Aaj Unki Jay Bol',
        'poet_id' => $poetMap['Ramdhari Singh Dinkar'] ?? null,
        'poet_name' => 'Ramdhari Singh Dinkar',
        'language' => 'Hindi',
        'genre' => 'Kavita',
        'mood' => 'Zindagi',
        'tags' => ['Zindagi', 'Safar'],
        'text_roman' => "Jala asthiyan baari baari,\nChhitkaai jinne chingaari,\nJo chadh gaye punya-vedi par,\nBina gardan ka mol kiye;\nKalam, aaj unki jay bol!\n\nJo aganit laghu deep hamare,\nToofano mein ek kinare,\nJal jal kar bujh gaye sawere,\nTinka unka nahi gina gaya,\nKalam, aaj unki jay bol!",
        'meaning_en' => 'Pen, sing the victory of the unsung martyrs who scattered sparks of conscience into history without ever calculating the price of their lives.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Dushyant Kumar 2 - Sirf Hungama Khada Karna
    [
        'title' => 'Sirf Hungama Khada Karna',
        'poet_id' => $poetMap['Dushyant Kumar'] ?? null,
        'poet_name' => 'Dushyant Kumar',
        'language' => 'Hindi',
        'genre' => 'Ghazal',
        'mood' => 'Zindagi',
        'tags' => ['Zindagi', 'Falsafa'],
        'text_roman' => "Sirf hungama khada karna mera maqsad nahi,\nMeri koshish hai ki yeh soorat badalni chahiye!\n\nMere seene mein nahi toh tere seene mein sahi,\nHo kahin bhi aag, lekin aag jalni chahiye!",
        'meaning_en' => 'My purpose is not merely to cause commotion; my mission is that this reality must change. Be it in my chest or yours, the flame of conscience must burn!',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Parveen Shakir 2 - Koo-Ba-Koo Phail Gayi
    [
        'title' => 'Koo-Ba-Koo Phail Gayi Baat',
        'poet_id' => $poetMap['Parveen Shakir'] ?? null,
        'poet_name' => 'Parveen Shakir',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Mohabbat',
        'tags' => ['Mohabbat', 'Ishq', 'Yaad'],
        'text_roman' => "Koo-ba-koo phail gayi baat shanasai ki,\nUsne khushboo ki tarah meri paziraai ki.\n\nKaise keh doon ki mujhe chhod diya usne,\nBaat toh sach hai magar baat hai ruswai ki!",
        'meaning_en' => 'Word of our tender intimacy spread street to street, like intoxicating fragrance across morning air. How do I admit that he abandoned me, when truth itself carries the sting of dishonor?',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Sahir Ludhianvi 1 - Kabhi Kabhie
    [
        'title' => 'Kabhi Kabhie Mere Dil Mein',
        'poet_id' => $poetMap['Sahir Ludhianvi'] ?? null,
        'poet_name' => 'Sahir Ludhianvi',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Yaad',
        'tags' => ['Yaad', 'Mohabbat', 'Tanhai'],
        'text_roman' => "Kabhi kabhie mere dil mein khayal aata hai,\nKi jaise tujhko banaya gaya hai mere liye.\nTu ab se pehle sitaron mein bas rahi thi kahin,\nTujhe zameen pe bulaya gaya hai mere liye.\n\nKabhi kabhie mere dil mein khayal aata hai,\nKi yeh badan, yeh nigahein meri amanat hain!",
        'meaning_en' => 'Sometimes in the quiet sanctuary of the heart, a thought arrives: that you were sculpted from starlight solely to answer the longings of my mortal soul.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Sahir Ludhianvi 2 - Yeh Duniya Agar Mil Bhi Jaye
    [
        'title' => 'Yeh Duniya Agar Mil Bhi Jaye',
        'poet_id' => $poetMap['Sahir Ludhianvi'] ?? null,
        'poet_name' => 'Sahir Ludhianvi',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Falsafa', 'Dard'],
        'text_roman' => "Yeh mehlon, yeh takhton, yeh taajon ki duniya,\nYeh insaan ke dushman samaajon ki duniya,\nYeh daulat ke bhookhe rivaajon ki duniya,\nYeh duniya agar mil bhi jaye to kya hai?\n\nHar ek jism ghaayal, har ek rooh pyaasi,\nNigahon mein jeevan ki be-aas-o-yaasi,\nYeh duniya jahan aadmi kuch nahi hai,\nYeh duniya agar mil bhi jaye to kya hai?",
        'meaning_en' => 'This realm of thrones, palaces, hollow customs, and famished greed—even if this entire world were surrendered into my hands, what is its worth without human compassion?',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Sahir Ludhianvi 3 - Taj Mahal
    [
        'title' => 'Taj Mahal',
        'poet_id' => $poetMap['Sahir Ludhianvi'] ?? null,
        'poet_name' => 'Sahir Ludhianvi',
        'language' => 'Urdu',
        'genre' => 'Nazm',
        'mood' => 'Mohabbat',
        'tags' => ['Mohabbat', 'Falsafa'],
        'text_roman' => "Ek shahanshah ne daulat ka sahara lekar,\nHum ghareebon ki mohabbat ka udaya hai mazaak!\n\nMeri mehboob, kahin aur mila kar mujhse.",
        'meaning_en' => 'Sahir’s famous class critique of the marble monument: By weaponizing imperial wealth, an emperor made a mockery of poor lovers’ quiet devotion. My love, meet me somewhere else.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Ahmad Faraz 1 - Ranjish Hi Sahi
    [
        'title' => 'Ranjish Hi Sahi',
        'poet_id' => $poetMap['Ahmad Faraz'] ?? null,
        'poet_name' => 'Ahmad Faraz',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Ishq', 'Yaad'],
        'text_roman' => "Ranjish hi sahi, dil hi dukhane ke liye aa,\nAa phir se mujhe chhod ke jaane ke liye aa!\n\nPehle se marasim na sahi, phir bhi kabhi toh,\nRasman hi sahi, duniya ko dikhaane ke liye aa.\n\nEk umr se hoon lazzat-e-girya se bhi mehroom,\nAye raahat-e-jaan, mujhko rulaane ke liye aa!",
        'meaning_en' => 'Even if born of bitterness or intended only to hurt this heart, return! Come back, if only to abandon me once more.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Ahmad Faraz 2 - Suna Hai Log Use
    [
        'title' => 'Suna Hai Log Use Aankh Bhar Ke Dekhte Hain',
        'poet_id' => $poetMap['Ahmad Faraz'] ?? null,
        'poet_name' => 'Ahmad Faraz',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Ishq',
        'tags' => ['Ishq', 'Mohabbat'],
        'text_roman' => "Suna hai log use aankh bhar ke dekhte hain,\nSo uske shahar mein kuch din thehar ke dekhte hain.\n\nSuna hai rabt hai usko kharab-haalon se,\nSo apne aap ko barbaad karke dekhte hain!\n\nSuna hai dard ki gaahak hai chashm-e-naaz uski,\nSo hum bhi uski gali se guzar ke dekhte hain.",
        'meaning_en' => 'I hear people gaze at her with breathless wonder; let us linger a while in her city. I hear she has empathy for the ruined; let me ruin myself to catch her eye.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Bahadur Shah Zafar 1 - Lagta Nahi Hai Dil Mera
    [
        'title' => 'Lagta Nahi Hai Dil Mera Ujde Dayar Mein',
        'poet_id' => $poetMap['Bahadur Shah Zafar'] ?? null,
        'poet_name' => 'Bahadur Shah Zafar',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Tanhai',
        'tags' => ['Tanhai', 'Dard', 'Yaad'],
        'text_roman' => "Lagta nahi hai dil mera ujde dayar mein,\nKiski bani hai aalam-e-na-payedaar mein?\n\nKah do in hasraton se kahin aur ja basein,\nItni jagah kahan hai dil-e-daghdaar mein?\n\nKitna hai bad-naseeb 'Zafar' dafn ke liye,\nDo gaz zameen bhi na mili koo-e-yaar mein!",
        'meaning_en' => 'My soul finds no solace in this desolate, ruined land. How tragic is Zafar’s fate in exile—denied even two yards of earth in the soil of his beloved homeland for a grave.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Bahadur Shah Zafar 2 - Na Kisi Ki Aankh Ka Noor Hoon
    [
        'title' => 'Na Kisi Ki Aankh Ka Noor Hoon',
        'poet_id' => $poetMap['Bahadur Shah Zafar'] ?? null,
        'poet_name' => 'Bahadur Shah Zafar',
        'language' => 'Urdu',
        'genre' => 'Ghazal',
        'mood' => 'Dard',
        'tags' => ['Dard', 'Tanhai'],
        'text_roman' => "Na kisi ki aankh ka noor hoon, na kisi ke dil ka qaraar hoon,\nJo kisi ke kaam na aa sake, main woh ek musht-e-ghubaar hoon!\n\nMera rang-roop bigad gaya, mera yaar mujhse bichhad gaya,\nJo chaman khizan se ujad gaya, main usi ki fasl-e-bahaar hoon.",
        'meaning_en' => 'I am the light of no eye, nor the solace of any heart. I am merely a handful of wandering dust that can serve no purpose to any living soul.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Kabir 2 - Pothi Padhi Padhi
    [
        'title' => 'Pothi Padhi Padhi Jag Mua',
        'poet_id' => $poetMap['Kabir'] ?? null,
        'poet_name' => 'Kabir',
        'language' => 'Hindi',
        'genre' => 'Doha',
        'mood' => 'Falsafa',
        'tags' => ['Falsafa', 'Zindagi'],
        'text_roman' => "Pothi padhi padhi jag mua, pandit bhaya na koye,\nDhai aakhar prem ka, padhe so pandit hoye!\n\nBura jo dekhan main chala, bura na milya koye,\nJo dil khoja aapna, mujhse bura na koye.",
        'meaning_en' => 'Scholars perished turning ancient pages without attaining true illumination; he who understands the two-and-a-half letters of LOVE becomes truly wise.',
        'featured' => true,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Rahim 1 - Rahiman Dhaga Prem Ka
    [
        'title' => 'Rahiman Dhaga Prem Ka',
        'poet_id' => null,
        'poet_name' => 'Rahim',
        'language' => 'Hindi',
        'genre' => 'Doha',
        'mood' => 'Mohabbat',
        'tags' => ['Mohabbat', 'Falsafa'],
        'text_roman' => "Rahiman dhaga prem ka, mat todo chatkaay,\nToote se phir na jude, jude gaanth pad jaaye!\n\nJo raheem uttam prakriti, ka kari sakat kusang,\nChandan vish vyaapat nahi, lapte rahat bhujang.",
        'meaning_en' => 'Do not snap the delicate thread of love in haste; once severed, it can never rejoin without leaving a visible knot.',
        'featured' => false,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

foreach ($newPoetry as $p) {
    $existing = $poetryCol->findOne(['title' => $p['title']]);
    if (!$existing) {
        $poetryCol->insertOne($p);
        echo "+ Added Poetry: {$p['title']}\n";
    }
}

// 3. ADD 24 MORE CURATED BOOKS (Bringing total to 55+ books)
$newBooks = [
    // Urdu Literature / Poetry
    [
        'title' => 'Kulliyat-e-Faiz',
        'author' => 'Faiz Ahmed Faiz',
        'poet_id' => $poetMap['Faiz Ahmed Faiz'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 650,
        'rating' => 4.9,
        'stock' => 15,
        'format' => 'Hardcover Grand Edition',
        'language' => 'Urdu (With Roman Glossary)',
        'pages' => 520,
        'isbn' => '978-9694190884',
        'description' => 'The complete, unabridged master compendium containing all seven anthologies of Faiz Ahmed Faiz, including historical prefaces and notes.',
        'featured' => true,
        'cover_bg' => 'from-[#2e0909] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Mohabbat',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Yaani',
        'author' => 'Jaun Elia',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 480,
        'rating' => 4.9,
        'stock' => 12,
        'format' => 'Paperback',
        'language' => 'Urdu',
        'pages' => 288,
        'isbn' => '978-9694190228',
        'description' => 'Jaun Elia’s second legendary poetic collection, diving deeper into existential dread, lost youth, and uncompromising literary craftsmanship.',
        'featured' => false,
        'cover_bg' => 'from-[#2a0614] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Tanhai',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Gumaan',
        'author' => 'Jaun Elia',
        'poet_id' => $poetMap['Jaun Elia'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 490,
        'rating' => 4.8,
        'stock' => 14,
        'format' => 'Paperback',
        'language' => 'Urdu',
        'pages' => 304,
        'isbn' => '978-9694190334',
        'description' => 'A haunting meditation on illusion, solitude, and the theater of existence by the maestro of contemporary Urdu verse.',
        'featured' => false,
        'cover_bg' => 'from-[#1c0818] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Falsafa',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Baang-e-Dra',
        'author' => 'Allama Iqbal',
        'poet_id' => $poetMap['Allama Iqbal'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 420,
        'rating' => 4.9,
        'stock' => 20,
        'format' => 'Hardcover',
        'language' => 'Urdu & English Tafseer',
        'pages' => 360,
        'isbn' => '978-8171675517',
        'description' => 'The Call of the Marching Bell: Iqbal’s landmark first Urdu philosophical collection containing Tarana-e-Hindi, Shikwa, and Jawab-e-Shikwa.',
        'featured' => true,
        'cover_bg' => 'from-[#0d2818] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Safar',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Bal-e-Jibril',
        'author' => 'Allama Iqbal',
        'poet_id' => $poetMap['Allama Iqbal'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 450,
        'rating' => 4.9,
        'stock' => 16,
        'format' => 'Hardcover',
        'language' => 'Urdu',
        'pages' => 272,
        'isbn' => '978-8171675524',
        'description' => 'Wings of Gabriel: Iqbal’s highest summit of philosophical and spiritual poetry, reflecting upon human greatness and cosmic purpose.',
        'featured' => false,
        'cover_bg' => 'from-[#07241d] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Falsafa',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Talkhiyan',
        'author' => 'Sahir Ludhianvi',
        'poet_id' => $poetMap['Sahir Ludhianvi'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 350,
        'rating' => 4.8,
        'stock' => 22,
        'format' => 'Paperback',
        'language' => 'Urdu',
        'pages' => 216,
        'isbn' => '978-8126701124',
        'description' => 'Bitterness: Sahir’s monumental debut poetry collection that captured the rebellious spirit, heartaches, and ideals of post-independence youth.',
        'featured' => true,
        'cover_bg' => 'from-[#2e0e0e] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Tanhai',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Dard-e-Aashob',
        'author' => 'Ahmad Faraz',
        'poet_id' => $poetMap['Ahmad Faraz'] ?? null,
        'category' => 'Urdu Poetry',
        'price' => 390,
        'rating' => 4.8,
        'stock' => 14,
        'format' => 'Paperback',
        'language' => 'Urdu',
        'pages' => 240,
        'isbn' => '978-9694190556',
        'description' => 'Faraz’s celebrated collection interweaving the aches of unfulfilled romance with courageous protests against political tyranny.',
        'featured' => false,
        'cover_bg' => 'from-[#1e0f2b] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Dard',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Kulliyat-e-Zafar',
        'author' => 'Bahadur Shah Zafar',
        'poet_id' => $poetMap['Bahadur Shah Zafar'] ?? null,
        'category' => 'Classics',
        'price' => 440,
        'rating' => 4.7,
        'stock' => 10,
        'format' => 'Hardcover Antique',
        'language' => 'Urdu',
        'pages' => 340,
        'isbn' => '978-8171676699',
        'description' => 'The collected lamentations, ghazals, and historical couplets of the last Mughal ruler, penned during Delhi’s fall and in Burmese exile.',
        'featured' => false,
        'cover_bg' => 'from-[#2b1f06] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Tanhai',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Hindi Literature Masterpieces
    [
        'title' => 'Godaan',
        'author' => 'Munshi Premchand',
        'poet_id' => $poetMap['Munshi Premchand'] ?? null,
        'category' => 'Hindi Literature',
        'price' => 320,
        'rating' => 4.9,
        'stock' => 28,
        'format' => 'Paperback Classic',
        'language' => 'Hindi',
        'pages' => 368,
        'isbn' => '978-8170280101',
        'description' => 'The crowning achievement of Indian realistic literature. The heartbreaking saga of Hori, an impoverished peasant caught in the debt-trap of feudal India.',
        'featured' => true,
        'cover_bg' => 'from-[#291708] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Dard',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Gunahon Ka Devta',
        'author' => 'Dharamvir Bharati',
        'category' => 'Hindi Literature',
        'price' => 299,
        'rating' => 4.9,
        'stock' => 25,
        'format' => 'Paperback',
        'language' => 'Hindi',
        'pages' => 264,
        'isbn' => '978-8126700448',
        'description' => 'One of the most beloved romantic-existential tragedies in modern Hindi prose, exploring pure, idealistic love versus cruel social boundaries in Allahabad.',
        'featured' => true,
        'cover_bg' => 'from-[#2e0917] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Ishq',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Kamayani',
        'author' => 'Jaishankar Prasad',
        'poet_id' => $poetMap['Jaishankar Prasad'] ?? null,
        'category' => 'Hindi Literature',
        'price' => 360,
        'rating' => 4.9,
        'stock' => 18,
        'format' => 'Hardcover',
        'language' => 'Hindi',
        'pages' => 312,
        'isbn' => '978-8170281146',
        'description' => 'The sublime epic of Hindi Chhayavad poetry depicting Manu, Shraddha, and Ida—a profound allegorical treatise on human psychology and spiritual triumph.',
        'featured' => false,
        'cover_bg' => 'from-[#0d1d2b] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Falsafa',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Raag Darbari',
        'author' => 'Shrilal Shukla',
        'category' => 'Hindi Literature',
        'price' => 380,
        'rating' => 4.8,
        'stock' => 16,
        'format' => 'Paperback',
        'language' => 'Hindi',
        'pages' => 344,
        'isbn' => '978-8126700578',
        'description' => 'The Sahitya Akademi Award-winning satirical masterpiece exposing the cynical machinery of rural Indian politics, education, and village bureaucracy.',
        'featured' => false,
        'cover_bg' => 'from-[#241b07] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Falsafa',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Tamas',
        'author' => 'Bhisham Sahni',
        'category' => 'Hindi Literature',
        'price' => 310,
        'rating' => 4.8,
        'stock' => 18,
        'format' => 'Paperback',
        'language' => 'Hindi',
        'pages' => 280,
        'isbn' => '978-8126700721',
        'description' => 'A harrowing and deeply empathetic fictional portrait of communal madness, partition, human devastation, and quiet courage in Punjab in 1947.',
        'featured' => false,
        'cover_bg' => 'from-[#260c0c] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Dard',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Maila Anchal',
        'author' => 'Phanishwar Nath \'Renu\'',
        'category' => 'Hindi Literature',
        'price' => 340,
        'rating' => 4.9,
        'stock' => 15,
        'format' => 'Paperback',
        'language' => 'Hindi',
        'pages' => 320,
        'isbn' => '978-8126700516',
        'description' => 'The pioneering regional novel (Aanchalik Upanyas) capturing the raw dialect, folklore, sorrows, and political stirrings of village Bihar.',
        'featured' => false,
        'cover_bg' => 'from-[#211608] to-[#0a0a0a]',
        'literary' => true,
        'mood' => 'Zindagi',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Philosophy Classics
    [
        'title' => 'Thus Spoke Zarathustra',
        'author' => 'Friedrich Nietzsche',
        'category' => 'Philosophy',
        'price' => 450,
        'rating' => 4.8,
        'stock' => 20,
        'format' => 'Hardcover',
        'language' => 'English',
        'pages' => 352,
        'isbn' => '978-0140441185',
        'description' => 'Nietzsche’s prophetic masterpiece presenting the Will to Power, the Ubermensch, and the eternal recurrence through volcanic prose.',
        'featured' => true,
        'cover_bg' => 'from-[#22072e] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'The Myth of Sisyphus',
        'author' => 'Albert Camus',
        'category' => 'Philosophy',
        'price' => 380,
        'rating' => 4.9,
        'stock' => 24,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 212,
        'isbn' => '978-0679733737',
        'description' => 'One of the most influential existential philosophical essays of the 20th century. One must imagine Sisyphus happy.',
        'featured' => true,
        'cover_bg' => 'from-[#17112b] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Meditations',
        'author' => 'Marcus Aurelius',
        'category' => 'Philosophy',
        'price' => 320,
        'rating' => 4.9,
        'stock' => 30,
        'format' => 'Pocket Hardcover',
        'language' => 'English',
        'pages' => 256,
        'isbn' => '978-0812968255',
        'description' => 'The intimate personal journals of the Roman Emperor Marcus Aurelius, offering timeless Stoic wisdom on duty, resilience, and mortality.',
        'featured' => true,
        'cover_bg' => 'from-[#211b08] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Beyond Good and Evil',
        'author' => 'Friedrich Nietzsche',
        'category' => 'Philosophy',
        'price' => 420,
        'rating' => 4.7,
        'stock' => 15,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 240,
        'isbn' => '978-0140449235',
        'description' => 'A devastating critique of traditional Western morality, exposing the psychological motives beneath philosophical systems.',
        'featured' => false,
        'cover_bg' => 'from-[#280a22] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // High-Impact Technology Classics
    [
        'title' => 'Designing Data-Intensive Applications',
        'author' => 'Martin Kleppmann',
        'category' => 'Technology',
        'price' => 1499,
        'rating' => 5.0,
        'stock' => 18,
        'format' => 'Paperback Reference',
        'language' => 'English',
        'pages' => 616,
        'isbn' => '978-1449373320',
        'description' => 'The definitive bible of modern software architectures: transactions, consensus, distributed streaming, storage engines, and replication models.',
        'featured' => true,
        'cover_bg' => 'from-[#05222b] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Structure and Interpretation of Computer Programs',
        'author' => 'Harold Abelson & Gerald Jay Sussman',
        'category' => 'Technology',
        'price' => 1199,
        'rating' => 4.9,
        'stock' => 10,
        'format' => 'Hardcover Classic',
        'language' => 'English',
        'pages' => 657,
        'isbn' => '978-0262510875',
        'description' => 'The legendary MIT textbook (The Wizard Book) that reveals computation as magic: abstraction, interpreters, recursion, and computational processes.',
        'featured' => false,
        'cover_bg' => 'from-[#071b26] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Clean Architecture',
        'author' => 'Robert C. Martin',
        'category' => 'Technology',
        'price' => 899,
        'rating' => 4.7,
        'stock' => 20,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 432,
        'isbn' => '978-0134494166',
        'description' => 'Universal rules of software structure: boundaries, dependency inversion, component cohesion, and decoupling business rules from frameworks.',
        'featured' => false,
        'cover_bg' => 'from-[#0a1e28] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Speculative Fiction Classics
    [
        'title' => 'Neuromancer',
        'author' => 'William Gibson',
        'category' => 'Speculative Fiction',
        'price' => 499,
        'rating' => 4.8,
        'stock' => 22,
        'format' => 'Paperback Collector',
        'language' => 'English',
        'pages' => 271,
        'isbn' => '978-0441569595',
        'description' => 'The groundbreaking cyberpunk masterpiece that coined the term Cyberspace and won the Hugo, Nebula, and Philip K. Dick awards.',
        'featured' => true,
        'cover_bg' => 'from-[#2b1405] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Dune',
        'author' => 'Frank Herbert',
        'category' => 'Speculative Fiction',
        'price' => 650,
        'rating' => 4.9,
        'stock' => 25,
        'format' => 'Hardcover Deluxe',
        'language' => 'English',
        'pages' => 688,
        'isbn' => '978-0441172719',
        'description' => 'The greatest science fiction epic ever written. A complex tapestry of ecology, religion, betrayal, and destiny on desert planet Arrakis.',
        'featured' => true,
        'cover_bg' => 'from-[#2e1c07] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => '1984',
        'author' => 'George Orwell',
        'category' => 'Speculative Fiction',
        'price' => 399,
        'rating' => 4.9,
        'stock' => 30,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 328,
        'isbn' => '978-0451524935',
        'description' => 'The definitive dystopian nightmare of surveillance, thought-police, doublethink, and authoritarian psychological subjugation.',
        'featured' => false,
        'cover_bg' => 'from-[#2e0909] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    // Psychology Masterpieces
    [
        'title' => 'Thinking, Fast and Slow',
        'author' => 'Daniel Kahneman',
        'category' => 'Psychology',
        'price' => 599,
        'rating' => 4.8,
        'stock' => 22,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 499,
        'isbn' => '978-0374533557',
        'description' => 'Nobel laureate Daniel Kahneman’s tour de force explaining the two systems that drive the way we think: intuitive fast System 1, and deliberate slow System 2.',
        'featured' => true,
        'cover_bg' => 'from-[#062619] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'The Denial of Death',
        'author' => 'Ernest Becker',
        'category' => 'Psychology',
        'price' => 620,
        'rating' => 4.9,
        'stock' => 14,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 336,
        'isbn' => '978-0684832401',
        'description' => 'Pulitzer Prize-winning masterpiece exploring human fear of mortality and how civilization itself is constructed as a grand heroism project.',
        'featured' => false,
        'cover_bg' => 'from-[#170a24] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ],
    [
        'title' => 'Man\'s Search for Meaning',
        'author' => 'Viktor E. Frankl',
        'category' => 'Psychology',
        'price' => 350,
        'rating' => 4.9,
        'stock' => 28,
        'format' => 'Paperback',
        'language' => 'English',
        'pages' => 192,
        'isbn' => '978-0807014295',
        'description' => 'Psychiatrist Viktor Frankl’s immortal memoir of surviving Auschwitz and founding Logotherapy: finding purpose even in the darkest suffering.',
        'featured' => true,
        'cover_bg' => 'from-[#1f1908] to-[#0a0a0a]',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]
];

foreach ($newBooks as $nb) {
    $existing = $booksCol->findOne(['title' => $nb['title']]);
    if (!$existing) {
        $booksCol->insertOne($nb);
        echo "+ Added Book: {$nb['title']} ({$nb['category']})\n";
    } else {
        $booksCol->updateOne(['_id' => $existing['_id']], ['$set' => $nb]);
    }
}

echo "\n=== Database Expansion Successfully Complete! ===\n";
echo "Total Poets Now: " . $poetsCol->countDocuments() . "\n";
echo "Total Poetry Pieces Now: " . $poetryCol->countDocuments() . "\n";
echo "Total Books in Catalog Now: " . $booksCol->countDocuments() . "\n";
