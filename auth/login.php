<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: ' . baseUrl('admin/index.php'));
    } else {
        header('Location: ' . baseUrl('index.php'));
    }
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = $_POST['redirect'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $userRecord = $db->users->findOne(['email' => $email]);

            if ($userRecord && password_verify($password, $userRecord['password'])) {
                $_SESSION['user'] = [
                    'id' => (string)$userRecord['_id'],
                    'name' => $userRecord['name'],
                    'email' => $userRecord['email'],
                    'role' => $userRecord['role'] ?? 'customer'
                ];

                setFlash('success', 'Welcome back, ' . htmlspecialchars($userRecord['name']) . '!');

                if (!empty($redirect) && strpos($redirect, 'logout.php') === false) {
                    header('Location: ' . $redirect);
                } elseif (($userRecord['role'] ?? '') === 'admin') {
                    header('Location: ' . baseUrl('admin/index.php'));
                } else {
                    header('Location: ' . baseUrl('index.php'));
                }
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Reader Authentication — DAASTAAN. Literary Archive & Bookstore';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main class="py-20 px-6">
    <div class="max-w-md mx-auto">
        
        <div class="mb-10 text-center">
            <span class="mono text-[10px] text-blood tracking-[0.3em]">AUTHENTICATION</span>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mt-2">ACCESS PORTAL</h1>
            <p class="text-xs text-gray-400 mt-2">Sign in to access your literary reading room, orders, and cart.</p>
        </div>

        <!-- Demo Accounts Helper -->
        <div class="p-4 mb-8 border border-white/10 bg-white/5 backdrop-blur-sm text-xs">
            <span class="mono text-[10px] text-blood tracking-wider block mb-2 font-semibold">PRE-CONFIGURED ACCOUNTS:</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-gray-400 font-mono text-[11px]">
                <div class="p-2 border border-white/5 bg-black/40 cursor-pointer hover:border-blood/50 transition" onclick="fillDemo('admin@smartbookstore.com', 'Admin@123')">
                    <span class="text-blood block font-bold">Admin Role:</span>
                    admin@smartbookstore.com<br>Pass: Admin@123
                </div>
                <div class="p-2 border border-white/5 bg-black/40 cursor-pointer hover:border-blood/50 transition" onclick="fillDemo('customer@smartbookstore.com', 'Customer@123')">
                    <span class="text-white block font-bold">Customer Role:</span>
                    customer@smartbookstore.com<br>Pass: Customer@123
                </div>
            </div>
            <span class="text-[10px] text-gray-600 block mt-2">Click any box above to auto-populate credentials.</span>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 border border-blood bg-[#1a0508] text-blood text-xs mono">
                ⚠️ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= baseUrl('auth/login.php') ?>" method="POST" class="border border-white/10 p-8 bg-[#0b0b0b] space-y-6">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

            <div>
                <label for="email" class="block mono text-[10px] text-gray-400 tracking-widest uppercase mb-2">Email Address</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full bg-black/50 border border-white/10 px-4 py-3.5 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="mono text-[10px] text-gray-400 tracking-widest uppercase">Password</label>
                </div>
                <input type="password" id="password" name="password" required
                       class="w-full bg-black/50 border border-white/10 px-4 py-3.5 text-sm text-white focus:outline-none focus:border-blood transition">
            </div>

            <button type="submit" class="danger-button w-full py-4 text-xs font-bold tracking-widest">
                AUTHENTICATE →
            </button>

            <div class="pt-4 border-t border-white/5 text-center text-xs text-gray-500">
                Don't have an account? 
                <a href="<?= baseUrl('auth/register.php') ?>" class="text-white hover:text-blood ml-1 underline">Register now</a>
            </div>
        </form>

    </div>
</main>

<script>
function fillDemo(email, pass) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pass;
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
