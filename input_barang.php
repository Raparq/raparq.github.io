<?php
// Menentukan path ke database SQLite
$db_file = 'login_logout.db'; // Pastikan path ini sesuai dengan lokasi database Anda

// Membuat koneksi ke database SQLite
try {
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Membuat tabel barang jika belum ada
    $createTableSQL = "CREATE TABLE IF NOT EXISTS barang (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nomor_urut INTEGER NOT NULL UNIQUE,
        nama_barang TEXT NOT NULL,
        harga REAL NOT NULL,
        jumlah INTEGER NOT NULL,
        link_pembelian TEXT,
        keterangan TEXT
    );";

    $db->exec($createTableSQL);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}

// Fungsi untuk menambahkan barang
function addBarang($nomor_urut, $nama_barang, $harga, $jumlah, $link_pembelian, $keterangan) {
    global $db;

    $stmt = $db->prepare("INSERT INTO barang (nomor_urut, nama_barang, harga, jumlah, link_pembelian, keterangan) VALUES (:nomor_urut, :nama_barang, :harga, :jumlah, :link_pembelian, :keterangan)");
    $stmt->bindParam(':nomor_urut', $nomor_urut);
    $stmt->bindParam(':nama_barang', $nama_barang);
    $stmt->bindParam(':harga', $harga);
    $stmt->bindParam(':jumlah', $jumlah);
    $stmt->bindParam(':link_pembelian', $link_pembelian);
    $stmt->bindParam(':keterangan', $keterangan);

    return $stmt->execute();
}

// Fungsi untuk mengedit barang
function editBarang($nomor_urut, $nama_barang, $harga, $jumlah, $link_pembelian, $keterangan) {
    global $db;

    $stmt = $db->prepare("UPDATE barang SET nama_barang = :nama_barang, harga = :harga, jumlah = :jumlah, link_pembelian = :link_pembelian, keterangan = :keterangan WHERE nomor_urut = :nomor_urut");
    $stmt->bindParam(':nomor_urut', $nomor_urut);
    $stmt->bindParam(':nama_barang', $nama_barang);
    $stmt->bindParam(':harga', $harga);
    $stmt->bindParam(':jumlah', $jumlah);
    $stmt->bindParam(':link_pembelian', $link_pembelian);
    $stmt->bindParam(':keterangan', $keterangan);

    return $stmt->execute();
}

// Fungsi untuk menghapus barang
function deleteBarang($nomor_urut) {
    global $db;

    $stmt = $db->prepare("DELETE FROM barang WHERE nomor_urut = :nomor_urut");
    $stmt->bindParam(':nomor_urut', $nomor_urut);

    return $stmt->execute();
}

// Fungsi untuk memperbarui nomor urut agar tetap berurutan
function updateNomorUrut() {
    global $db;

    $stmt = $db->query("SELECT * FROM barang ORDER BY nomor_urut ASC");
    $barang = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Memperbarui nomor urut
    foreach ($barang as $index => $item) {
        $nomor_urut_baru = $index + 1; // Nomor urut baru dimulai dari 1
        if ($item['nomor_urut'] != $nomor_urut_baru) {
            $update_stmt = $db->prepare("UPDATE barang SET nomor_urut = :nomor_urut WHERE id = :id");
            $update_stmt->bindParam(':nomor_urut', $nomor_urut_baru);
            $update_stmt->bindParam(':id', $item['id']);
            $update_stmt->execute();
        }
    }
}

// Fungsi untuk mendapatkan nomor urut terakhir
function getLastNomorUrut() {
    global $db;
    $stmt = $db->query("SELECT MAX(nomor_urut) as max_nomor_urut FROM barang");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['max_nomor_urut'] ? $result['max_nomor_urut'] + 1 : 1; // Mulai dari 1 jika tidak ada barang
}

// Proses form untuk menambah dan mengedit barang
$message = '';
$current_item = null; // Variabel untuk menyimpan item yang sedang diedit

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tambah barang
    if (isset($_POST['add_barang'])) {
        $nomor_urut = getLastNomorUrut(); // Ambil nomor urut berikutnya
        $nama_barang = $_POST['nama_barang'];
        $harga = $_POST['harga'];
        $jumlah = $_POST['jumlah'];
        $link_pembelian = $_POST['link_pembelian'];
        $keterangan = $_POST['keterangan'];

        if (addBarang($nomor_urut, $nama_barang, $harga, $jumlah, $link_pembelian, $keterangan)) {
            $message = "Barang '$nama_barang' berhasil ditambahkan.";
        } else {
            $message = "Gagal menambahkan barang. Silakan coba lagi.";
        }
    }

    // Edit barang
    if (isset($_POST['edit_barang'])) {
        $nomor_urut = $_POST['nomor_urut'];
        $nama_barang = $_POST['nama_barang'];
        $harga = $_POST['harga'];
        $jumlah = $_POST['jumlah'];
        $link_pembelian = $_POST['link_pembelian'];
        $keterangan = $_POST['keterangan'];

        if (editBarang($nomor_urut, $nama_barang, $harga, $jumlah, $link_pembelian, $keterangan)) {
            $message = "Barang '$nama_barang' berhasil diedit.";
            updateNomorUrut(); // Memperbarui nomor urut setelah mengedit
            $current_item = null; // Reset item yang sedang diedit
        } else {
            $message = "Gagal mengedit barang. Silakan coba lagi.";
        }
    }

    // Hapus barang
    if (isset($_POST['delete_barang'])) {
        $nomor_urut = $_POST['nomor_urut'];

        if (deleteBarang($nomor_urut)) {
            $message = "Barang berhasil dihapus.";
            updateNomorUrut(); // Memperbarui nomor urut setelah menghapus
        } else {
            $message = "Gagal menghapus barang. Silakan coba lagi.";
        }
    }

    // Proses edit item
    if (isset($_POST['edit_item'])) {
        $nomor_urut = $_POST['nomor_urut'];
        $stmt = $db->prepare("SELECT * FROM barang WHERE nomor_urut = :nomor_urut");
        $stmt->bindParam(':nomor_urut', $nomor_urut);
        $stmt->execute();
        $current_item = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Mengambil semua barang dari database
function getBarang() {
    global $db;
    $stmt = $db->query("SELECT * FROM barang ORDER BY nomor_urut ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$barang = getBarang();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light blue background */
            color: #333;
        }
        h2 {
            color: #007bff; /* Bright blue color */
            text-align: center;
        }
        /* Navbar styles */
        .navbar {
            background-color: #87ceeb; /* Sky blue color */
            padding: 20px;
            text-align: center;
        }
        .navbar a {
            margin: 0 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        /* Form and table styles */
        form, table {
            margin: 20px auto;
            width: 80%;
        }
        table {
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #007bff; /* Blue border */
            text-align: left;
        }
        th {
            background-color: #d0e7ff; /* Light blue for header */
        }
        /* Cube Styles */
        .cube {
          
            width: 40px; /* Set to a smaller size */
            height: 40px; /* Set to a smaller size */
            position: absolute; /* Absolute positioning */
            top: 10px; /* Adjust top position */
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


    </style>
</head>
<body>
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

<!-- Tombol untuk navigasi -->
    <div class="navbar">
    <a href="home.php">Home</a>
    <a href="cst.php">Daftar Cst</a>
    <a href="penjualan.php">Penjualan</a>
    <a href="nota.php">Nota</a>
    <a href="histori.php">Histori</a>
    </div>

    <h2>Input Barang</h2>

    <?php if ($message): ?>
        <div><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Form untuk menambahkan dan mengedit barang -->
    <form method="post">
        <input type="text" name="nama_barang" placeholder="Nama Barang" value="<?php echo $current_item ? htmlspecialchars($current_item['nama_barang']) : ''; ?>" required>
        <input type="number" name="harga" placeholder="Harga" value="<?php echo $current_item ? htmlspecialchars($current_item['harga']) : ''; ?>" required>
        <input type="number" name="jumlah" placeholder="Jumlah" value="<?php echo $current_item ? htmlspecialchars($current_item['jumlah']) : ''; ?>" required>
        <input type="url" name="link_pembelian" placeholder="Link Pembelian (URL)" value="<?php echo $current_item ? htmlspecialchars($current_item['link_pembelian']) : ''; ?>">
        <input type="text" name="keterangan" placeholder="Keterangan" value="<?php echo $current_item ? htmlspecialchars($current_item['keterangan']) : ''; ?>">
        <button type="submit" name="<?php echo $current_item ? 'edit_barang' : 'add_barang'; ?>">
            <?php echo $current_item ? 'Edit Barang' : 'Tambah Barang'; ?>
        </button>
        <?php if ($current_item): ?>
            <input type="hidden" name="nomor_urut" value="<?php echo htmlspecialchars($current_item['nomor_urut']); ?>">
            <button type="submit" name="cancel">Batal</button>
        <?php endif; ?>
    </form>

    <!-- Tabel untuk menampilkan barang -->
    <h2>Daftar Barang</h2>
    <table>
        <tr>
            <th>Nomor Urut</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Link Pembelian</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($barang as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['nomor_urut']); ?></td>
                <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                <td><?php echo htmlspecialchars($item['harga']); ?></td>
                <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                <td><a href="<?php echo htmlspecialchars($item['link_pembelian']); ?>" target="_blank">Beli</a></td> <!-- Updated link display -->
                <td><?php echo htmlspecialchars($item['keterangan']); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="nomor_urut" value="<?php echo htmlspecialchars($item['nomor_urut']); ?>">
                        <button type="submit" name="edit_item">Edit</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="nomor_urut" value="<?php echo htmlspecialchars($item['nomor_urut']); ?>">
                        <button type="submit" name="delete_barang" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
