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
function demo_image(string $type, int $seed = 0): string {
    $images = match ($type) {
        'events' => [
            'assets/images/events/night-ride-demo.jpg',
            'assets/images/events/club-meet-demo.jpg',
            'assets/images/events/garage-night-demo.jpg',
            'assets/images/events/weekend-ride-demo.jpg',
        ],
        'officers' => [
            'assets/images/officers/officer-president-demo.jpg',
            'assets/images/officers/officer-road-captain-demo.jpg',
            'assets/images/officers/officer-saa-demo.jpg',
        ],
        'merchandise' => [
            'assets/images/merchandise/shirt-demo.jpg',
            'assets/images/merchandise/hoodie-demo.jpg',
            'assets/images/merchandise/patch-demo.jpg',
            'assets/images/merchandise/sticker-demo.jpg',
        ],
        default => [
            'assets/images/gallery/touring-01.jpg',
            'assets/images/gallery/clubhouse-01.jpg',
            'assets/images/gallery/bike-01.jpg',
            'assets/images/gallery/event-gallery-01.jpg',
        ],
    };
    return $images[abs($seed) % count($images)];
}
function demo_records(string $type): array {
    return match ($type) {
        'events' => [
            ['id' => 901, 'title' => 'North Line Run', 'event_date' => '2026-10-17', 'location' => 'Meet at the clubhouse', 'description' => 'A full-day ride north. Route notes go to confirmed riders.', 'image_path' => demo_image('events', 0), 'status' => 'upcoming'],
            ['id' => 902, 'title' => 'Workshop Night', 'event_date' => '2026-10-29', 'location' => 'The garage', 'description' => 'Bring a machine, a question, or a set of hands.', 'image_path' => demo_image('events', 2), 'status' => 'upcoming'],
        ],
        'officers' => [
            ['id' => 901, 'name' => 'Marcus Hale', 'position' => 'President', 'bio' => 'Keeps the club pointed in the right direction.', 'image_path' => demo_image('officers', 0), 'sort_order' => 1],
            ['id' => 902, 'name' => 'Rhea Cole', 'position' => 'Road Captain', 'bio' => 'Plans the miles and makes sure everyone gets home.', 'image_path' => demo_image('officers', 1), 'sort_order' => 2],
            ['id' => 903, 'name' => 'Dane Mercer', 'position' => 'Sergeant at Arms', 'bio' => 'Looks after the clubhouse and its standards.', 'image_path' => demo_image('officers', 2), 'sort_order' => 3],
        ],
        'gallery' => [
            ['id' => 901, 'title' => 'Northbound', 'image_path' => 'assets/images/gallery/touring-01.jpg', 'category' => 'touring'],
            ['id' => 902, 'title' => 'Open Road', 'image_path' => 'assets/images/gallery/touring-02.jpg', 'category' => 'touring'],
            ['id' => 903, 'title' => 'Garage Hours', 'image_path' => 'assets/images/gallery/clubhouse-01.jpg', 'category' => 'clubhouse'],
            ['id' => 904, 'title' => 'After Dark', 'image_path' => 'assets/images/gallery/clubhouse-02.jpg', 'category' => 'clubhouse'],
            ['id' => 905, 'title' => 'Machine Detail', 'image_path' => 'assets/images/gallery/bike-01.jpg', 'category' => 'bikes'],
            ['id' => 906, 'title' => 'Road Ready', 'image_path' => 'assets/images/gallery/bike-02.jpg', 'category' => 'bikes'],
            ['id' => 907, 'title' => 'Club Meet', 'image_path' => 'assets/images/gallery/event-gallery-01.jpg', 'category' => 'events'],
            ['id' => 908, 'title' => 'Night Assembly', 'image_path' => 'assets/images/gallery/event-gallery-02.jpg', 'category' => 'events'],
        ],
        'merchandise' => [
            ['id' => 901, 'name' => 'Workshop Tee', 'description' => 'Heavy cotton with a restrained club mark.', 'price' => 28.00, 'image_path' => demo_image('merchandise', 0), 'stock' => 24, 'active' => 1],
            ['id' => 902, 'name' => 'Road Hoodie', 'description' => 'Heavyweight layer for late garage hours.', 'price' => 54.00, 'image_path' => demo_image('merchandise', 1), 'stock' => 12, 'active' => 1],
            ['id' => 903, 'name' => 'Woven Patch', 'description' => 'A compact woven club emblem.', 'price' => 12.00, 'image_path' => demo_image('merchandise', 2), 'stock' => 18, 'active' => 1],
            ['id' => 904, 'name' => 'Garage Sticker', 'description' => 'Weather-resistant workshop sticker.', 'price' => 4.00, 'image_path' => demo_image('merchandise', 3), 'stock' => 40, 'active' => 1],
        ],
        default => [],
    };
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
