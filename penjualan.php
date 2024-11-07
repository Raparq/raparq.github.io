<?php
session_start(); // Pastikan ini hanya ada satu kali di bagian atas

// Menentukan path ke database SQLite
$db_file = 'login_logout.db'; // Pastikan path ini sesuai dengan lokasi database Anda

// Membuat koneksi ke database SQLite
try {
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}

// Fungsi untuk mencari barang
function searchBarang($nama_barang) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM barang WHERE nama_barang LIKE :nama_barang");
    $like_nama_barang = "%$nama_barang%";
    $stmt->bindParam(':nama_barang', $like_nama_barang);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Proses pencarian
$barang = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $barang = searchBarang($nama_barang);
}

// Reset items_to_sell saat halaman dimuat tanpa aksi
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Menghapus barang yang akan dijual jika tidak ada data POST
    if (isset($_SESSION['items_to_sell'])) {
        unset($_SESSION['items_to_sell']);
    }
}

// Tangani penambahan barang ke tabel sementara
$items_to_sell = [];
if (!isset($_SESSION['items_to_sell'])) {
    $_SESSION['items_to_sell'] = [];
}
if (isset($_POST['add_to_sell'])) {
    $item = [
        'nama_barang' => $_POST['nama_barang_hidden'],
        'harga' => $_POST['harga_hidden'],
        'jumlah' => $_POST['jumlah'],
    ];
    $_SESSION['items_to_sell'][] = $item; // Simpan barang ke dalam sesi
    $items_to_sell = $_SESSION['items_to_sell'];
} else {
    $items_to_sell = $_SESSION['items_to_sell'];
}

// Proses data pembeli dan update stok
if (isset($_POST['process_sell'])) {
    // Ambil data pembeli dari form
    $_SESSION['nama_pembeli'] = $_POST['nama_pembeli'];
    $_SESSION['no_telp'] = $_POST['no_telp'];
    $_SESSION['alamat'] = $_POST['alamat'];
    $_SESSION['keterangan'] = $_POST['keterangan'];

    // Update jumlah barang di database
    foreach ($_SESSION['items_to_sell'] as $sell_item) {
        $nama_barang = $sell_item['nama_barang'];
        $jumlah = $sell_item['jumlah'];
        
        // Kurangi jumlah barang di database
        $stmt = $db->prepare("UPDATE barang SET jumlah = jumlah - :jumlah WHERE nama_barang = :nama_barang");
        $stmt->bindParam(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmt->bindParam(':nama_barang', $nama_barang);
        $stmt->execute();
    }

    // Redirect ke nota.php
    header("Location: nota.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Warna latar belakang */
            color: #004d40; /* Warna teks */
            margin: 0;
            padding: 20px;
        }
         /* Navbar styles */
         .navbar {
            background-color: #0000CD; /* Sky blue color */
            padding: 20px;
            text-align: right;
        }
        .navbar a {
            margin: 20 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }

        h2, h3 {
            color: #00796b; /* Warna judul */
            margin-top: 40px;
        }
        a {
            margin-right: 15px;
            text-decoration: none;
            color: #00796b; /* Warna tautan */
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #00796b;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #004d40;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #b2dfdb; /* Warna latar belakang baris genap */
        }
        tr:nth-child(odd) {
            background-color: #e0f2f1; /* Warna latar belakang baris ganjil */
        }
        input[type="text"], input[type="number"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #00796b;
            border-radius: 5px;
        }
        button {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #004d40;
        }
          /* Cube Styles */
          .cube {
          
          width: 40px; /* Set to a smaller size */
          height: 40px; /* Set to a smaller size */
          position: absolute; /* Absolute positioning */
          top: 25px; /* Adjust top position */
          left: 60px; /* Adjust left position to shift the cube to the right */
          transform-style: preserve-3d; /* Enable 3D effects */
          animation: rotate 3s infinite linear; /* Rotate animation */
      }
      .cube div {
          position: absolute;
          width: 40px;
          height: 40px;
          background: rgba(255, 255, 255, 0.8);
          border: 1px solid #ccc;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 8px; /* Reduce font size */
          color: black;
          font-weight: bold;
      }
      .front  { transform: translateZ(20px); }
      .back   { transform: rotateY(180deg) translateZ(20px); }
      .right  { transform: rotateY(90deg) translateZ(20px); }
      .left   { transform: rotateY(-90deg) translateZ(20px); }
      .top    { transform: rotateX(90deg) translateZ(20px); }
      .bottom { transform: rotateX(-90deg) translateZ(20px); }

      @keyframes rotate {
          0% { transform: rotateX(0) rotateY(0); }
          100% { transform: rotateX(360deg) rotateY(360deg); }
      }
    </style>
</head>
<body>
<!-- Navbar -->
<div class="navbar">
    <a href="home.php">HOME</a>
    <a href="cst.php">Daftar Cst</a>
    <a href="input_barang.php">Daftar Barang</a>
    <a href="penjualan.php">Penjualan</a>
    <a href="histori.php">Histori</a>
    <a href="nota.php">Nota</a>
</div>
<div class="cube-container">
    <div class="cube">
        <div class="face front">EZ-Comp</div>
        <div class="face back">EZ-Comp</div>
        <div class="face right">EZ-Comp</div>
        <div class="face left">EZ-Comp</div>
        <div class="face top">EZ-Comp</div>
        <div class="face bottom">EZ-Comp</div>
    </div>
</div>

<form method="post">
    <h3>Pencarian Barang</h3>
    <input type="text" name="nama_barang" placeholder="Cari Nama Barang" required>
    <button type="submit" name="search_barang">Cari</button>
</form>

<!-- Tabel untuk menampilkan hasil pencarian barang -->
<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($barang) > 0): ?>
            <?php foreach ($barang as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($item['harga']); ?></td>
                    <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="nama_barang_hidden" value="<?php echo htmlspecialchars($item['nama_barang']); ?>">
                            <input type="hidden" name="harga_hidden" value="<?php echo htmlspecialchars($item['harga']); ?>">
                            <input type="number" name="jumlah" placeholder="Jumlah" min="1" required style="width: 60px;">
                            <button type="submit" name="add_to_sell">Tambah ke Penjualan</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Tidak ada barang yang ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h3>Barang yang Akan Dijual</h3>
<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($_SESSION['items_to_sell']) > 0): ?>
            <?php foreach ($_SESSION['items_to_sell'] as $sell_item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sell_item['nama_barang']); ?></td>
                    <td><?php echo htmlspecialchars($sell_item['harga']); ?></td>
                    <td><?php echo htmlspecialchars($sell_item['jumlah']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Belum ada barang yang ditambahkan untuk dijual.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Form untuk input data pembeli -->
<h3>Data Pembeli</h3>
<form method="post">
    <input type="text" name="nama_pembeli" placeholder="Nama Pembeli" required>
    <input type="text" name="no_telp" placeholder="Nomor Telepon" required>
    <input type="text" name="alamat" placeholder="Alamat" required>
    <input type="text" name="keterangan" placeholder="Keterangan">
    <button type="submit" name="process_sell">Proses Jual</button>
</form>

</body>
</html>
