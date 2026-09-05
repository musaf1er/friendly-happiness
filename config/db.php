<?php
declare(strict_types=1);

function initialize_database(PDO $pdo): void
{
    $statements = [
        "CREATE TABLE IF NOT EXISTS users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, email VARCHAR(150) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, role ENUM('admin','officer') NOT NULL DEFAULT 'officer', is_active TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS officers (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, position VARCHAR(100) NOT NULL, bio TEXT NOT NULL, image_path VARCHAR(255) NULL, sort_order SMALLINT NOT NULL DEFAULT 0, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS events (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(150) NOT NULL, event_date DATE NOT NULL, location VARCHAR(150) NOT NULL, description TEXT NOT NULL, image_path VARCHAR(255) NULL, status ENUM('upcoming','completed') NOT NULL DEFAULT 'upcoming', created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX idx_events_date (event_date)) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS gallery (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(150) NOT NULL, image_path VARCHAR(255) NOT NULL, category ENUM('touring','clubhouse','bikes','events') NOT NULL DEFAULT 'touring', created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX idx_gallery_category (category)) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS merchandise (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(150) NOT NULL, description TEXT NOT NULL, price DECIMAL(10,2) NOT NULL DEFAULT 0, image_path VARCHAR(255) NULL, stock INT NOT NULL DEFAULT 0, active TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS prospects (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, full_name VARCHAR(100) NOT NULL, nickname VARCHAR(50) NULL, phone_number VARCHAR(30) NOT NULL, bike_model VARCHAR(100) NOT NULL, reason_to_join TEXT NULL, status ENUM('pending','reviewed','accepted','rejected') NOT NULL DEFAULT 'pending', created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX idx_prospects_status (status)) ENGINE=InnoDB",
        "CREATE TABLE IF NOT EXISTS app_migrations (migration_key VARCHAR(150) PRIMARY KEY, applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
    ];
    foreach ($statements as $statement) $pdo->exec($statement);

    $username = getenv('ADMIN_INITIAL_USERNAME') ?: 'admin';
    $email = getenv('ADMIN_INITIAL_EMAIL') ?: 'admin@friendly-happiness.local';
    $password = getenv('ADMIN_INITIAL_PASSWORD');
    if ($password) {
        $insertAdmin = $pdo->prepare("INSERT IGNORE INTO users (username, email, password_hash, role, is_active) VALUES (?, ?, ?, 'admin', 1)");
        $insertAdmin->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    $seedSets = [
        'officers' => [
            "INSERT INTO officers (name,position,bio,image_path,sort_order) VALUES
            ('Carrick Wainwright','President','Leads the charter and keeps the club moving with purpose.','assets/images/officers/officer-president-demo.jpg',1),
            ('Joey Miller','Vice','Supports the president and keeps club operations aligned.','assets/images/officers/officer-road-captain-demo.jpg',2),
            ('Kamari Pearson','Sergeant at Arms','Maintains order and supports the safety of the charter.','assets/images/officers/officer-saa-demo.jpg',3),
            ('Hector Mendoza','Sergeant at Arms','Maintains order and supports the safety of the charter.','assets/images/officers/officer-saa-demo.jpg',4),
            ('Julian Valentine','Enforcer','Upholds club standards and assists the charter officers.','assets/images/officers/officer-saa-demo.jpg',5),
            ('Alexandria Axora','Treasurer','Oversees the charter records and club finances.','assets/images/officers/officer-road-captain-demo.jpg',6),
            ('Ernesto Morales','Road Captain','Plans the route and keeps every ride organized.','assets/images/officers/officer-road-captain-demo.jpg',7)"
        ],
        'events' => [
            "INSERT INTO events (title,event_date,location,description,image_path,status) VALUES
            ('North Line Run','2026-10-17','Meet at the clubhouse','A full-day ride north. Route and meeting notes go to confirmed riders.','assets/images/events/night-ride-demo.jpg','upcoming'),
            ('Workshop Night','2026-10-29','The garage','Bring a machine, a question, or a set of hands.','assets/images/events/garage-night-demo.jpg','upcoming')"
        ],
        'gallery' => [
            "INSERT INTO gallery (title,image_path,category) VALUES
            ('Northbound','assets/images/gallery/touring-01.jpg','touring'),
            ('Open Road','assets/images/gallery/touring-02.jpg','touring'),
            ('Garage Hours','assets/images/gallery/clubhouse-01.jpg','clubhouse'),
            ('After Dark','assets/images/gallery/clubhouse-02.jpg','clubhouse'),
            ('Machine Detail','assets/images/gallery/bike-01.jpg','bikes'),
            ('Road Ready','assets/images/gallery/bike-02.jpg','bikes'),
            ('Club Meet','assets/images/gallery/event-gallery-01.jpg','events'),
            ('Night Assembly','assets/images/gallery/event-gallery-02.jpg','events')"
        ],
        'merchandise' => [
            "INSERT INTO merchandise (name,description,price,image_path,stock,active) VALUES
            ('Workshop Tee','Heavy cotton with a restrained club mark.',28.00,'assets/images/merchandise/shirt-demo.jpg',24,1),
            ('Road Hoodie','Heavyweight layer for late garage hours.',54.00,'assets/images/merchandise/hoodie-demo.jpg',12,1),
            ('Woven Patch','A compact woven club emblem.',12.00,'assets/images/merchandise/patch-demo.jpg',18,1),
            ('Garage Sticker','Weather-resistant workshop sticker.',4.00,'assets/images/merchandise/sticker-demo.jpg',40,1)"
        ],
    ];
    foreach ($seedSets as $table => $queries) {
        if ((int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn() === 0) {
            foreach ($queries as $query) $pdo->exec($query);
        }
    }

    $rosterMigration = '20260905_charter_roster';
    $migrationCheck = $pdo->prepare('SELECT 1 FROM app_migrations WHERE migration_key = ?');
    $migrationCheck->execute([$rosterMigration]);
    if (!$migrationCheck->fetchColumn()) {
        $pdo->beginTransaction();
        try {
            $pdo->exec('DELETE FROM officers');
            $insertOfficer = $pdo->prepare('INSERT INTO officers (name, position, bio, image_path, sort_order) VALUES (?, ?, ?, ?, ?)');
            $roster = [
                ['Carrick Wainwright', 'President', 'Leads the charter and keeps the club moving with purpose.', 'assets/images/officers/officer-president-demo.jpg', 1],
                ['Joey Miller', 'Vice', 'Supports the president and keeps club operations aligned.', 'assets/images/officers/officer-road-captain-demo.jpg', 2],
                ['Kamari Pearson', 'Sergeant at Arms', 'Maintains order and supports the safety of the charter.', 'assets/images/officers/officer-saa-demo.jpg', 3],
                ['Hector Mendoza', 'Sergeant at Arms', 'Maintains order and supports the safety of the charter.', 'assets/images/officers/officer-saa-demo.jpg', 4],
                ['Julian Valentine', 'Enforcer', 'Upholds club standards and assists the charter officers.', 'assets/images/officers/officer-saa-demo.jpg', 5],
                ['Alexandria Axora', 'Treasurer', 'Oversees the charter records and club finances.', 'assets/images/officers/officer-road-captain-demo.jpg', 6],
                ['Ernesto Morales', 'Road Captain', 'Plans the route and keeps every ride organized.', 'assets/images/officers/officer-road-captain-demo.jpg', 7],
            ];
            foreach ($roster as $officer) $insertOfficer->execute($officer);
            $recordMigration = $pdo->prepare('INSERT INTO app_migrations (migration_key) VALUES (?)');
            $recordMigration->execute([$rosterMigration]);
            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $error;
        }
    }
}

function db(): ?PDO
{
    static $pdo = null;
    static $attempted = false;
    if ($attempted) return $pdo;
    $attempted = true;
    $driver = getenv('DB_DRIVER') ?: 'mysql';
    try {
        if ($driver === 'sqlite') {
            $path = getenv('DB_PATH') ?: __DIR__ . '/../storage/mischief.sqlite';
            if (!is_dir(dirname($path))) mkdir(dirname($path), 0750, true);
            $pdo = new PDO('sqlite:' . $path);
        } else {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3306';
            $name = getenv('DB_NAME') ?: 'mischief_outlaws';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $pdo = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $pass, [
                PDO::ATTR_TIMEOUT => 5,
            ]);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        if ($driver === 'mysql') initialize_database($pdo);
    } catch (Throwable $error) {
        error_log('Database connection failed: ' . $error->getMessage());
        $pdo = null;
    }
    return $pdo;
}
