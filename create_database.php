<?php
// Menentukan path ke database SQLite
$db_file = 'login_logout.db'; // Pastikan path ini sesuai dengan lokasi database Anda

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
        // Menambahkan pengguna awal
        $insertUserSQL = "INSERT INTO users (username, password) VALUES ('user1', 'password1');";
        $db->exec($insertUserSQL);
        echo "Pengguna 'user1' berhasil ditambahkan ke tabel 'users'.<br>";
    } else {
        echo "Tabel 'users' sudah berisi pengguna.<br>";
    }

} catch (PDOException $e) {
    echo "Koneksi atau operasi gagal: " . $e->getMessage();
    exit();
}
?>
