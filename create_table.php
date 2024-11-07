<?php
session_start();
$db_file = 'login_logout.db'; // Path ke database SQLite

// Membuat koneksi ke database SQLite
try {
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Membuat tabel users jika belum ada
    $createTableSQL = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        last_login TIMESTAMP,
        last_logout TIMESTAMP
    );";
    $db->exec($createTableSQL);
    echo "Tabel 'users' berhasil dibuat atau sudah ada.<br>";

    // Menambahkan pengguna jika tabel kosong
    $checkUsersSQL = "SELECT COUNT(*) FROM users;";
    $stmt = $db->query($checkUsersSQL);
    $userCount = $stmt->fetchColumn();

    if ($userCount == 0) {
        // Menambahkan pengguna awal dengan password yang di-hash
        $username = 'user1';
        $password = password_hash('password1', PASSWORD_DEFAULT);
        $insertUserSQL = "INSERT INTO users (username, password) VALUES (:username, :password);";
        
        $insertStmt = $db->prepare($insertUserSQL);
        $insertStmt->bindParam(':username', $username);
        $insertStmt->bindParam(':password', $password);
        
        if ($insertStmt->execute()) {
            echo "Pengguna 'user1' berhasil ditambahkan ke tabel 'users'.<br>";
        } else {
            echo "Gagal menambahkan pengguna 'user1'.<br>";
        }
    } else {
        echo "Tabel 'users' sudah berisi pengguna.<br>";
    }

    // Membuat tabel histori jika belum ada
    $createHistoriTableSQL = "CREATE TABLE IF NOT EXISTS histori (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_pembeli TEXT NOT NULL,
        no_telp TEXT NOT NULL,
        alamat TEXT NOT NULL,
        keterangan TEXT,
        items TEXT NOT NULL
    );";
    $db->exec($createHistoriTableSQL);
    echo "Tabel 'histori' berhasil dibuat atau sudah ada.<br>";

    // Menambahkan kolom created_at jika belum ada
    $alterTableSQL = "ALTER TABLE histori ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP;";
    try {
        $db->exec($alterTableSQL);
        echo "Kolom 'created_at' berhasil ditambahkan ke tabel 'histori'.<br>";
    } catch (PDOException $e) {
        // Jika kolom sudah ada, tangani kesalahan ini dengan baik
        if ($e->getCode() == '1') { // 1 adalah kode untuk UNIQUE constraint violation
            echo "Kolom 'created_at' sudah ada di tabel 'histori'.<br>";
        } else {
            echo "Gagal menambahkan kolom 'created_at': " . $e->getMessage() . "<br>";
        }
    }

    // Membuat tabel cst jika belum ada
    $createCstTableSQL = "CREATE TABLE IF NOT EXISTS cst (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        no_urut INTEGER NOT NULL,
        nama TEXT NOT NULL,
        alamat TEXT,
        tlp TEXT,
        keterangan TEXT
    );";
    $db->exec($createCstTableSQL);
    echo "Tabel 'cst' berhasil dibuat atau sudah ada.<br>";

} catch (PDOException $e) {
    echo "Koneksi atau operasi gagal: " . $e->getMessage();
    exit();
}
?>
