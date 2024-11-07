<?php
// Menentukan path ke database SQLite
$db_file = 'login_logout.db';

try {
    // Membuat koneksi ke database SQLite
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Memeriksa apakah parameter `id` ada di URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Mengambil data customer berdasarkan `id`
        $selectCstSQL = "SELECT * FROM cst WHERE id = ?";
        $stmt = $db->prepare($selectCstSQL);
        $stmt->execute([$id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            echo "Data customer tidak ditemukan.";
            exit();
        }
    } else {
        echo "ID customer tidak ditemukan.";
        exit();
    }

    // Menangani pengiriman form untuk pembaruan data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mengambil data dari form
        $no_urut = $_POST['no_urut'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $tlp = $_POST['tlp'];
        $keterangan = $_POST['keterangan'];

        // Memperbarui data customer di database
        $updateCstSQL = "UPDATE cst SET no_urut = ?, nama = ?, alamat = ?, tlp = ?, keterangan = ? WHERE id = ?";
        $stmt = $db->prepare($updateCstSQL);
        $stmt->execute([$no_urut, $nama, $alamat, $tlp, $keterangan, $id]);

        // Redirect kembali ke halaman utama setelah update
        header("Location: cst.php");
        exit();
    }

} catch (PDOException $e) {
    echo "Koneksi atau operasi gagal: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Customer</title>
</head>
<body>
    <h1>Edit Data Customer</h1>
    <form method="POST" action="">
        <label>No Urut:</label>
        <input type="number" name="no_urut" value="<?= htmlspecialchars($customer['no_urut']) ?>" required><br>

        <label>Nama:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($customer['nama']) ?>" required><br>

        <label>Alamat:</label>
        <input type="text" name="alamat" value="<?= htmlspecialchars($customer['alamat']) ?>"><br>

        <label>Telepon:</label>
        <input type="text" name="tlp" value="<?= htmlspecialchars($customer['tlp']) ?>"><br>

        <label>Keterangan:</label>
        <input type="text" name="keterangan" value="<?= htmlspecialchars($customer['keterangan']) ?>"><br>

        <button type="submit">Perbarui</button>
        <a href="cst.php">Kembali</a>
    </form>
</body>
</html>
