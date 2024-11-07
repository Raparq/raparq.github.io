<?php
session_start();
$db_file = 'login_logout.db'; // Path ke database SQLite

// Membuat koneksi ke database SQLite
try {
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}

// Mengambil data berdasarkan ID untuk diubah
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("SELECT * FROM histori WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $history = $stmt->fetch(PDO::FETCH_ASSOC);

    // Decode items jika formatnya JSON
    $history['items'] = json_decode($history['items'], true);
}

// Proses update data
if (isset($_POST['submit'])) {
    // Mengambil data dari form
    $nama_pembeli = trim($_POST['nama_pembeli']);
    $no_telp = trim($_POST['no_telp']);
    $alamat = trim($_POST['alamat']);
    $keterangan = trim($_POST['keterangan']);
    
    // Pastikan items tidak kosong dan valid
    if (isset($_POST['items']) && !empty($_POST['items'])) {
        $items = $_POST['items'];
    } else {
        echo "Items tidak boleh kosong.";
        exit();
    }

    // Encode items ke format JSON
    $items = json_encode($items);

    // Validasi inputan sebelum update
    if (!empty($nama_pembeli) && !empty($no_telp) && !empty($alamat)) {
        $stmt = $db->prepare("UPDATE histori SET nama_pembeli = :nama_pembeli, no_telp = :no_telp, alamat = :alamat, keterangan = :keterangan, items = :items WHERE id = :id");
        $stmt->bindParam(':nama_pembeli', $nama_pembeli);
        $stmt->bindParam(':no_telp', $no_telp);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':items', $items);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            // Arahkan kembali ke histori.php setelah data berhasil diubah
            header("Location: histori.php");
            exit();
        } else {
            echo "Gagal mengubah data.";
        }
    } else {
        echo "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ubah Data</title>
</head>
<body>

<h2>Ubah Data Penjualan</h2>
<form method="post">
    <label>Nama Pembeli:</label><br>
    <input type="text" name="nama_pembeli" value="<?php echo htmlspecialchars($history['nama_pembeli']); ?>"><br><br>
    
    <label>Nomor Telepon:</label><br>
    <input type="text" name="no_telp" value="<?php echo htmlspecialchars($history['no_telp']); ?>"><br><br>
    
    <label>Alamat:</label><br>
    <input type="text" name="alamat" value="<?php echo htmlspecialchars($history['alamat']); ?>"><br><br>
    
    <label>Keterangan:</label><br>
    <input type="text" name="keterangan" value="<?php echo htmlspecialchars($history['keterangan']); ?>"><br><br>
    
    <label>Items (dalam format JSON):</label><br>
    <textarea name="items"><?php echo htmlspecialchars(json_encode($history['items'], JSON_PRETTY_PRINT)); ?></textarea><br><br>
    
    <input type="submit" name="submit" value="Ubah Data">
</form>

</body>
</html>
