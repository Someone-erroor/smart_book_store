<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . baseUrl('index.php'));
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please provide a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $existing = $db->users->findOne(['email' => $email]);
            if ($existing) {
                $error = 'An account with this email already exists.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $insertResult = $db->users->insertOne([
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'role' => 'customer',
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ]);

                // Auto login user
                $_SESSION['user'] = [
                    'id' => (string)$insertResult->getInsertedId(),
                    'name' => $name,
                    'email' => $email,
                    'role' => 'customer'
                ];

                setFlash('success', 'Account created successfully! Welcome to DAASTAAN. Literary Archive & Bookstore.');
                header('Location: ' . baseUrl('index.php'));
                exit;
            }
        } catch (Exception $e) {
            $error = 'Registration error: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Create Reader Account — DAASTAAN. Literary Archive & Bookstore';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-20 px-6">
    <div class="max-w-md mx-auto">
        
        <div class="mb-10 text-center">
            <span class="mono text-[10px] text-blood tracking-[0.3em]">NEW READER</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mt-2">CREATE ACCOUNT</h1>
            <p class="text-xs text-gray-400 mt-2">Join our curated community of literature readers, poetry enthusiasts, and collectors.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 border border-blood bg-[#1a0508] text-blood text-xs mono">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= baseUrl('auth/register.php') ?>" method="POST" class="border border-white/10 p-8 bg-[#0b0b0b] space-y-6">
            <div>
                <label for="name" class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Full Name</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                       class="w-full bg-black/50 border border-white/10 px-4 py-3.5 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label for="email" class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Email Address</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full bg-black/50 border border-white/10 px-4 py-3.5 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label for="password" class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Password (Min. 6 chars)</label>
                <input type="password" id="password" name="password" required minlength="6"
                       class="w-full bg-black/50 border border-white/10 px-4 py-3.5 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <label for="confirm_password" class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                       class="w-full bg-black/50 border border-white/10 px-4 py-3.5 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <button type="submit" class="danger-button w-full py-4 text-xs font-bold tracking-widest">
                CREATE ACCOUNT →
            </button>

            <div class="pt-4 border-t border-white/5 text-center text-xs text-gray-500">
                Already have an account? 
                <a href="<?= baseUrl('auth/login.php') ?>" class="text-white hover:text-blood ml-1 underline">Sign in</a>
            </div>
        </form>

    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
