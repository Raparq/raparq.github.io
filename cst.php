<?php
// Menentukan path ke database SQLite
$db_file = 'login_logout.db';

try {
    // Membuat koneksi ke database SQLite
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Variabel untuk menyimpan pesan
    $message = "";

    // Variabel untuk menyimpan data input
    $nama = $alamat = $tlp = $keterangan = ""; // Reset awal input

    // Menangani pengiriman form untuk menambahkan data baru
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $tlp = $_POST['tlp'];
        $keterangan = $_POST['keterangan'];

        // Menentukan no_urut otomatis dengan mencari nilai maksimum + 1
        $noUrutQuery = "SELECT IFNULL(MAX(no_urut), 0) + 1 FROM cst";
        $no_urut = $db->query($noUrutQuery)->fetchColumn();

        // Menambahkan data ke tabel cst
        $insertCstSQL = "INSERT INTO cst (no_urut, nama, alamat, tlp, keterangan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($insertCstSQL);
        $stmt->execute([$no_urut, $nama, $alamat, $tlp, $keterangan]);
        
        // Menampilkan pesan sukses
        $message = "Data berhasil ditambahkan ke tabel 'cst'.<br>";
        // Reset form input setelah penambahan
        $nama = $alamat = $tlp = $keterangan = ""; 
    }

    // Menangani penghapusan data
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $deleteSQL = "DELETE FROM cst WHERE id = ?";
        $stmt = $db->prepare($deleteSQL);
        $stmt->execute([$id]);
        $message = "Data berhasil dihapus dari tabel 'cst'.<br>";
    }

    // Menangani pembaruan data
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $tlp = $_POST['tlp'];
        $keterangan = $_POST['keterangan'];

        $updateSQL = "UPDATE cst SET nama = ?, alamat = ?, tlp = ?, keterangan = ? WHERE id = ?";
        $stmt = $db->prepare($updateSQL);
        $stmt->execute([$nama, $alamat, $tlp, $keterangan, $id]);

        // Menampilkan pesan sukses
        $message = "Data berhasil diperbarui di tabel 'cst'.<br>";
        // Reset form input setelah pembaruan
        $nama = $alamat = $tlp = $keterangan = ""; // Reset input untuk tambah baru
    }

    // Menangani permintaan edit
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $selectCstSQL = "SELECT * FROM cst WHERE id = ?";
        $stmt = $db->prepare($selectCstSQL);
        $stmt->execute([$id]);
        $editData = $stmt->fetch(PDO::FETCH_ASSOC);
        // Mengisi form dengan data yang akan diedit
        $nama = $editData['nama'];
        $alamat = $editData['alamat'];
        $tlp = $editData['tlp'];
        $keterangan = $editData['keterangan'];
    }

    // Mengambil data dari tabel `cst` untuk ditampilkan
    $selectCstSQL = "SELECT * FROM cst ORDER BY no_urut ASC";
    $stmt = $db->query($selectCstSQL);
    $cstData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Koneksi atau operasi gagal: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Customer (cst)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Latar belakang biru cerah */
            color: #005662; /* Warna teks yang gelap */
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #00796b; /* Judul berwarna hijau gelap */
            text-align: center;
        }
        /* Gaya navbar */
        .navbar {
            background-color: #00796b; /* Warna navbar */
            padding: 20px;
            text-align: center;
        }
        .navbar a {
            margin: 0 15px;
            color: white; /* Warna teks link */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s; /* Transisi warna */
        }
        .navbar a:hover {
            color: #b2dfdb; /* Warna teks saat hover */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #00796b; /* Garis tepi tabel berwarna hijau gelap */
            text-align: left;
            font-size: 14px; /* Ukuran font yang lebih kecil */
        }
        th {
            background-color: #b2dfdb; /* Latar belakang header tabel */
        }
        tr:nth-child(even) {
            background-color: #b2ebf2; /* Warna latar belakang baris genap */
        }
        tr:hover {
            background-color: #80deea; /* Warna latar belakang saat hover */
        }
        .form-container {
    display: flex; /* Menggunakan flexbox untuk container */
    flex-direction: column; /* Menyusun elemen secara kolom */
    align-items: center; /* Memusatkan konten */
}

form {
    margin-top: 20px;
    padding: 15px;
 
    background-color: #ffffff; /* Latar belakang form */
    border: 1px solid #00796b; /* Garis tepi form */
    border-radius: 5px; /* Sudut melingkar pada form */
    max-width: 500px; /* Lebar maksimum form */
    width: 100%; /* Lebar penuh pada perangkat kecil */
}

input[type="text"] {
    width: calc(100% - 20px); /* Lebar input */
    padding: 6px; /* Padding yang lebih kecil */
    margin-bottom: 10px;
    border: 1px solid #00796b; /* Garis tepi input */
    border-radius: 4px; /* Sudut melingkar pada input */
    font-size: 14px; /* Ukuran font yang lebih kecil */
}

button {
    background-color: #00796b; /* Latar belakang tombol */
    color: white; /* Warna teks tombol */
    padding: 6px 10px; /* Padding tombol */
    border: none;
    border-radius: 5px; /* Sudut melingkar pada tombol */
    cursor: pointer; /* Pointer saat hover */
    font-size: 14px; /* Ukuran font tombol */
}

button:hover {
    background-color: #004d40; /* Latar belakang tombol saat hover */
}

.reset-button {
    background-color: #f44336; /* Latar belakang tombol reset */
    margin-left: 10px; /* Jarak antara tombol tambah dan reset */
}

.reset-button:hover {
    background-color: #c62828; /* Latar belakang tombol reset saat hover */
}
          /* Cube Styles */
          .cube {
            width: 40px; /* Set to a smaller size */
            height: 40px; /* Set to a smaller size */
            position: absolute; /* Absolute positioning */
            top: 25px; /* Adjust top position */
            left: 60px; /* Adjust left position to shift the cube to the right */
            transform-style: preserve-3d; /* Enable 3D effects */
            animation: rotate 5s infinite linear; /* Rotate animation */
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
        
        @keyframes wave {
            0%, 100% {
                transform: translateY(0);
            }
            20% {
                transform: translateY(-10px);
            }
            40% {
                transform: translateY(10px);
            }
            60% {
                transform: translateY(-10px);
            }
            80% {
                transform: translateY(5px);
            }
        }

    </style>
</head>
<body>

  

<!-- Navbar untuk navigasi -->
    <div class="cube">
        <div class="front">EZ-Computer</div>
        <div class="back">EZ-Computer</div>
        <div class="right">EZ-Computer</div>
        <div class="left">EZ-Computer</div>
        <div class="top">EZ-Computer</div>
        <div class="bottom">EZ-Computer</div>
    </div>
<div class="navbar">
    <a href="home.php">Home</a>
    <a href="input_barang.php">Input Barang</a>
    <a href="histori.php">Histori Nota</a>
    <a href="penjualan.php">Penjualan</a>
    <a href="nota.php">Nota</a>
</div>

<h1>Form Input Data Customer</h1>
<div class="form-container">
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= isset($editData) ? $editData['id'] : ''; ?>">
        
        <label>Nama:</label>
        <input type="text" name="nama" required value="<?= htmlspecialchars($nama); ?>">

        <label>Alamat:</label>
        <input type="text" name="alamat" value="<?= htmlspecialchars($alamat); ?>">

        <label>Telepon:</label>
        <input type="text" name="tlp" value="<?= htmlspecialchars($tlp); ?>">

        <label>Keterangan:</label>
        <input type="text" name="keterangan" value="<?= htmlspecialchars($keterangan); ?>">

        <button type="submit" name="<?= isset($editData) ? 'update' : 'add'; ?>">
            <?= isset($editData) ? 'Perbarui' : 'Tambahkan'; ?>
        </button>
        <button type="reset" class="reset-button" onclick="resetForm()">Reset</button> <!-- Tombol Reset -->
    </form>
</div>

<!-- Menampilkan pesan jika ada -->
<?php if (!empty($message)): ?>
    <div style="margin-top: 20px; color: green;"><?= $message; ?></div>
<?php endif; ?>

<h2>Daftar Customer</h2>
<table border="1">
    <tr>
        <th>No Urut</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Telepon</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($cstData as $row): ?>
    <tr>
        <td><?= $row['no_urut']; ?></td>
        <td><?= htmlspecialchars($row['nama']); ?></td>
        <td><?= htmlspecialchars($row['alamat']); ?></td>
        <td><?= htmlspecialchars($row['tlp']); ?></td>
        <td><?= htmlspecialchars($row['keterangan']); ?></td>
        <td>
            <a href="?edit=<?= $row['id']; ?>">Edit</a>
            <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
