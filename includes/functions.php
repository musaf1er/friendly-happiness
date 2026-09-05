<?php
declare(strict_types=1);

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function url(string $route = 'home', array $params = []): string { return 'index.php?' . http_build_query(array_merge(['page' => $route], $params)); }
function redirect(string $route = 'home', array $params = []): never { header('Location: ' . url($route, $params)); exit; }
function flash(string $message, string $type = 'info'): void { $_SESSION['flash'] = ['message' => $message, 'type' => $type]; }
function take_flash(): ?array { $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $flash; }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Request expired.'); } }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function current_route(string $page): string { return match ($page) { 'about' => 'about', 'gallery' => 'gallery', 'events', 'event' => 'events', 'shop' => 'shop', 'prospect' => 'prospect', default => 'home' }; }
function image_url(?string $path, string $fallback = 'assets/images/placeholders/default.jpg'): string {
    $path = trim((string)$path);
    if ($path === '') return $fallback;
    if (preg_match('#^(https?:)?//#i', $path)) return $path;
    return ltrim($path, '/');
}
function require_admin(): void { if (!current_user()) redirect('admin/login'); }
function query_all(string $sql, array $params = []): array { $pdo = db(); if (!$pdo) return []; $statement = $pdo->prepare($sql); $statement->execute($params); return $statement->fetchAll(); }
function query_one(string $sql, array $params = []): ?array { $rows = query_all($sql, $params); return $rows[0] ?? null; }
function query_exec(string $sql, array $params = []): bool { $pdo = db(); if (!$pdo) return false; $statement = $pdo->prepare($sql); return $statement->execute($params); }
function old(string $key): string { return e($_POST[$key] ?? ''); }
function format_date(?string $date): string { return $date ? date('M j, Y', strtotime($date)) : ''; }
function upload_image(string $field, string $category = 'gallery'): ?string {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) return null;
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime])) return null;
    $allowedCategories = ['gallery', 'events', 'officers', 'merchandise'];
    if (!in_array($category, $allowedCategories, true)) $category = 'gallery';
    $dir = __DIR__ . '/../uploads/' . $category;
    if (!is_dir($dir)) mkdir($dir, 0750, true);
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) return null;
    return 'uploads/' . $category . '/' . $name;
}
