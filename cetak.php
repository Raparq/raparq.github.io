<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_jual'])) {
    // Mendapatkan data dari form
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    // Tampilkan informasi penjualan
    echo "<h1>Struk Penjualan</h1>";
    echo "<p>Nama Barang: " . htmlspecialchars($nama_barang) . "</p>";
    echo "<p>Harga: " . htmlspecialchars($harga) . "</p>";
    echo "<p>Jumlah: " . htmlspecialchars($jumlah) . "</p>";
    echo "<p>Total: " . htmlspecialchars($harga * $jumlah) . "</p>";
    echo "<button onclick='window.print()'>Cetak</button>";
} else {
    echo "Tidak ada data penjualan.";
}
?>
