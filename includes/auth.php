<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

// Calculate the base URL dynamically and robustly across all subfolders and server configurations
function baseUrl($path = '') {
    static $base = null;
    if ($base === null) {
        $projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__)));
        $scriptFilename = str_replace('\\', '/', realpath($_SERVER['SCRIPT_FILENAME'] ?? '') ?: ($_SERVER['SCRIPT_FILENAME'] ?? ''));
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

        $calculatedBase = null;
        if (!empty($projectRoot) && !empty($scriptFilename) && str_starts_with(strtolower($scriptFilename), strtolower($projectRoot))) {
            $relPath = substr($scriptFilename, strlen($projectRoot));
            if ($relPath !== '' && str_ends_with(strtolower($scriptName), strtolower($relPath))) {
                $calculatedBase = substr($scriptName, 0, -strlen($relPath));
            }
        }

        if ($calculatedBase === null) {
            // Comprehensive fallback: strip any subfolder from dirname
            $dir = dirname($scriptName);
            $dir = str_replace('\\', '/', $dir);
            $subdirs = ['/auth', '/cart', '/orders', '/admin', '/poetry', '/poets', '/collections', '/includes', '/config'];
            foreach ($subdirs as $sub) {
                if (str_ends_with(strtolower($dir), $sub)) {
                    $dir = substr($dir, 0, -strlen($sub));
                    break;
                }
            }
            $calculatedBase = ($dir === '/' || $dir === '.') ? '' : $dir;
        }

        $base = rtrim($calculatedBase, '/');
    }
    
    $cleanPath = ltrim($path, '/');
    return $base ? ($base . '/' . $cleanPath) : ('/' . $cleanPath);
}

// Authentication Helpers
function isLoggedIn(): bool {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function isAdmin(): bool {
    return isLoggedIn() && (($_SESSION['user']['role'] ?? '') === 'admin');
}

function requireAuth(string $redirect = ''): void {
    if (!isLoggedIn()) {
        $redirectUrl = $redirect ?: $_SERVER['REQUEST_URI'] ?? baseUrl();
        header('Location: ' . baseUrl('auth/login.php?redirect=' . urlencode($redirectUrl)));
        exit;
    }
}

function requireAdmin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . baseUrl('auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? '')));
        exit;
    }
    if (!isAdmin()) {
        header('Location: ' . baseUrl('index.php?error=unauthorized'));
        exit;
    }
}

// Flash Message Helpers
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'error', 'info'
        'message' => $message
    ];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Cart Helpers
function getCartCount(): int {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += (int)($item['quantity'] ?? 0);
    }
    return $count;
}

function getCartItems(): array {
    return $_SESSION['cart'] ?? [];
}

function getCartSubtotal(): float {
    $total = 0.0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += ((float)$item['price']) * ((int)$item['quantity']);
        }
    }
    return $total;
}
