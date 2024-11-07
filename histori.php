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

// Fungsi untuk menghapus data berdasarkan ID
if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    $stmt = $db->prepare("DELETE FROM histori WHERE id = :id");
    $stmt->bindParam(':id', $hapus_id);
    if ($stmt->execute()) {
        echo "<script>alert('Data dengan ID $hapus_id berhasil dihapus.');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');</script>";
    }
}

// Mengambil data dari tabel histori
$stmt = $db->query("SELECT * FROM histori;");
$histories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        /* Navbar styles */
        .navbar {
            background-color: #0000CD; /* Sky blue color */
            padding: 20px;
            text-align: CENTER;
        }
        .navbar a {
            margin: 0 10px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #7B68EE;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
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
      .print-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-btn:hover {
            background-color: #45a049;
        }
        .cube-container, .navbar { /* Sembunyikan elemen animasi dan navbar saat cetak */
            display: block;
        }
        /* CSS khusus untuk mode cetak */
        @media print {
            body {
                background-color: white;
                color: black;
                padding: 0;
            }
            .navbar, .print-btn, .cube-container {
                display: none; /* Sembunyikan elemen ini saat dicetak */
            }
            table {
                width: 100%;
                border: 1px solid #000; /* Tambahkan batas agar terlihat lebih rapi */
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                font-size: 14px; /* Ukuran font lebih kecil */
            }
            th {
                background-color: #4CAF50;
                color: white;
            }
            h2 {
                color: #333;
                font-size: 18px;
                margin-top: 0;
            }
            /* Hilangkan shadow dan efek hover pada mode cetak */
            table {
                box-shadow: none;
            }
            tr:hover {
                background-color: transparent;
            }
            /* Pengaturan margin cetak */
            @page {
                margin: 1cm;
            }
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
    <a></a><button onclick="window.print()">send pdf</button><a>
</div>

<h2>Histori Penjualan</h2>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Pembeli</th>
            <th>Nomor Telepon</th>
            <th>Alamat</th>
            <th>Keterangan</th>
            <th>Items</th>
            <th>Tanggal</th>
            <th>💚</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($histories as $history): ?>
            <tr>
                <td><?php echo htmlspecialchars($history['id']); ?></td>
                <td><?php echo htmlspecialchars($history['nama_pembeli']); ?></td>
                <td><?php echo htmlspecialchars($history['no_telp']); ?></td>
                <td><?php echo htmlspecialchars($history['alamat']); ?></td>
                <td><?php echo htmlspecialchars($history['keterangan']); ?></td>
                <td>
                    <?php
                    // Decode JSON data
                    $items = json_decode($history['items'], true);
                    if (empty($items)) {
                        echo "Tidak ada items"; // Tampilkan pesan jika tidak ada item
                    } else {
                        echo "<ul>"; // Mulai daftar
                        foreach ($items as $item) {
                            // Menampilkan setiap item dengan format yang lebih rapi
                            echo "<li>" . htmlspecialchars($item['nama_barang']) . 
                                 " - Rp " . htmlspecialchars(number_format($item['harga'], 2, ',', '.')) . 
                                 " (Jumlah: " . htmlspecialchars($item['jumlah']) . ")</li>";
                        }
                        echo "</ul>"; // Akhiri daftar
                    }
                    ?>
                </td>
                <td><?php echo htmlspecialchars($history['created_at']); ?></td>
                <td>
                    <a href="?hapus_id=<?php echo $history['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">🟢</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
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

</body>
</html>
