<?php
try {
    // Koneksi ke database SQLite
    $db_file = 'login_logout.db'; // Path ke database SQLite
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Membuat tabel histori_new jika belum ada
    $createNewTableSQL = "CREATE TABLE IF NOT EXISTS histori_new (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_pembeli TEXT NOT NULL,
        no_telp TEXT NOT NULL,
        alamat TEXT NOT NULL,
        keterangan TEXT,
        items TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );";
    $db->exec($createNewTableSQL);
    echo "Tabel 'histori_new' berhasil dibuat.<br>";

    // Salin data dari tabel histori ke histori_new
    $copyDataSQL = "INSERT INTO histori_new (nama_pembeli, no_telp, alamat, keterangan, items)
                    SELECT nama_pembeli, no_telp, alamat, keterangan, items FROM histori;";
    $db->exec($copyDataSQL);
    echo "Data berhasil disalin ke tabel 'histori_new'.<br>";

    // Menghapus tabel histori lama
    try {
        $dropOldTableSQL = "DROP TABLE histori;";
        $db->exec($dropOldTableSQL);
        echo "Tabel 'histori' berhasil dihapus.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'database table is locked') !== false) {
            sleep(2); // Tunggu 2 detik dan coba lagi
            $db->exec($dropOldTableSQL);
        } else {
            throw $e;
        }
    }

    // Mengganti nama tabel histori_new menjadi histori
    $renameTableSQL = "ALTER TABLE histori_new RENAME TO histori;";
    $db->exec($renameTableSQL);
    echo "Tabel 'histori_new' berhasil diubah namanya menjadi 'histori'.<br>";

} catch (PDOException $e) {
    echo "Koneksi atau operasi gagal: " . $e->getMessage();
    exit();
}
?>
