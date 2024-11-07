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

// Fungsi untuk menyimpan data ke histori
function saveToHistori($nama_pembeli, $no_telp, $alamat, $keterangan, $items) {
    global $db;

    // Mengubah items menjadi format string
    $items_string = json_encode($items);

    $stmt = $db->prepare("INSERT INTO histori (nama_pembeli, no_telp, alamat, keterangan, items) VALUES (:nama_pembeli, :no_telp, :alamat, :keterangan, :items)");
    $stmt->bindParam(':nama_pembeli', $nama_pembeli);
    $stmt->bindParam(':no_telp', $no_telp);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':keterangan', $keterangan);
    $stmt->bindParam(':items', $items_string);

    return $stmt->execute();
}

// Ambil data dari session
$items_to_sell = $_SESSION['items_to_sell'] ?? [];
$nama_pembeli = $_SESSION['nama_pembeli'] ?? "Tidak ada nama";
$no_telp = $_SESSION['no_telp'] ?? "Tidak ada nomor telepon";
$alamat = $_SESSION['alamat'] ?? "Tidak ada alamat";
$keterangan = $_SESSION['keterangan'] ?? "Tidak ada keterangan";

// Proses penyimpanan data ketika tombol 'Simpan ke Histori' ditekan
if (isset($_POST['save_to_histori'])) {
    saveToHistori($nama_pembeli, $no_telp, $alamat, $keterangan, $items_to_sell);
    echo "<script>alert('Data berhasil disimpan ke histori.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive Meta Tag -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8; /* Warna latar belakang */
            margin: 0;
            padding: 0; /* Menghapus padding di body */
            display: flex;
            flex-direction: column; /* Mengatur orientasi flex ke kolom */
            min-height: 100vh; /* Memastikan body memenuhi tinggi layar */
        }

        .container {
            flex: 1; /* Membiarkan kontainer mengambil ruang yang tersisa */
            width: 90%; /* Lebar konten 90% untuk perangkat kecil */
            max-width: 600px; /* Maksimum lebar konten */
            background-color: white; /* Latar belakang putih untuk konten */
            border-radius: 8px; /* Sudut melengkung */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Bayangan */
            padding: 20px; /* Ruang di dalam kontainer */
            margin: 20px auto; /* Menjaga konten tetap di tengah */
        }

        .header {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
        }

        .circle {
            width: 50px;
            height: 50px;
            background-color: #00796b; /* Lingkaran biru */
            color: white;
            border-radius: 50%;
            position: absolute;
            left: 20px;
            top: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px; /* Ukuran font */
            font-weight: bold; /* Ketebalan font */
        }

        .title {
            color: #00796b; /* Warna judul */
            font-size: 20px; /* Ukuran font judul yang lebih kecil */
            margin: 0; /* Menghilangkan margin */
        }

        .date {
            font-size: 12px; /* Ukuran font untuk tanggal */
            color: #004d40; /* Warna teks untuk tanggal */
            margin: 5px 0 15px 0; /* Jarak dari elemen lain */
        }

        .data-pembeli {
            font-size: 12px;
            margin-left: 0px; /* Jarak kiri untuk nomor */
            line-height: 0.5; /* Jarak antar baris yang lebih baik */
            color: #004d40; /* Warna teks */
            padding: 4px; /* Padding untuk ruang */
            border: 1px solid #00796b; /* Garis batas */
            border-radius: 5px; /* Sudut melengkung */
            background-color: #ffffff; /* Latar belakang putih */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th, td {
            font-size: 12px;
            border: 1px solid #00796b;
            padding: 5px;
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

        button {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px; /* Jarak antar tombol */
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #004d40;
        }

        .navigation-footer {
            margin-top: 10px;
            display: block;
            justify-content: space-between;
        }

        

       
        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .title {
                font-size: 24px; /* Ukuran font judul untuk perangkat kecil */
            }
        }

        @media (max-width: 480px) {
            .circle {
                width: 40px; /* Ukuran lingkaran lebih kecil */
                height: 40px;
                font-size: 20px; /* Ukuran font lebih kecil */
            }

            .title {
                font-size: 20px; /* Ukuran font judul lebih kecil */
            }

            button {
                padding: 8px 12px; /* Padding tombol lebih kecil */
            }

            .navigation-footer {
                padding: 8px 12px; /* Padding tautan footer lebih kecil */
            }
        }
        .atas-footer {
    font-family: Arial, sans-serif; /* Font yang lebih modern dan bersih */
    color:#778899; /* Warna teks yang lebih gelap untuk kontras yang lebih baik */
    background-color: #f7f9fb; /* Latar belakang lembut untuk footer */
    padding: 10px; /* Ruang di dalam footer */
    border-radius: 5px; /* Sudut melengkung untuk footer */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan lembut di sekitar footer */
}

.atas-footer h4 {
    font-weight: bold; /* Menebalkan judul */
    font-size: 12px; /* Ukuran font judul yang lebih besar */
    color: #00796b; /* Warna judul yang lebih cerah */
    margin-bottom: 15px; /* Jarak di bawah judul */
}
.atas-footer h5 {
    line-height: 0;
    font-weight: bold; /* Menebalkan judul */
    font-size: 10px; /* Ukuran font judul yang lebih besar */
    color: red; /* Warna judul yang lebih cerah */
    margin-bottom: 15px; /* Jarak di bawah judul */
}
.atas-footer ol {
    font-size: 10px;
    margin-left: 5px; /* Jarak kiri untuk nomor */
    line-height: 1.0; /* Jarak antar baris yang lebih baik */
}

.atas-footer ol li {
    margin-bottom: 5px; /* Jarak antar item dalam daftar */
}

    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="circle">EZ</div>
        <h2 class="title">EZ-COMPUTER</h2>
        <div class="date"><?php echo date('d-m-Y'); ?></div> <!-- Display current date -->
    </div>
    <div class="data-pembeli">
        <p>Nama: <?php echo htmlspecialchars($nama_pembeli); ?></p>
        <p>Nomor Telepon: <?php echo htmlspecialchars($no_telp); ?></p>
        <p>Alamat: <?php echo htmlspecialchars($alamat); ?></p>
        <p>Keterangan: <?php echo htmlspecialchars($keterangan); ?></p>
    </div>

    <h3>Barang yang Dibeli</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($items_to_sell) > 0): ?>
                <?php $total = 0; ?>
                <?php foreach ($items_to_sell as $sell_item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sell_item['nama_barang']); ?></td>
                        <td><?php echo number_format($sell_item['harga'], 2); ?></td>
                        <td><?php echo htmlspecialchars($sell_item['jumlah']); ?></td>
                        <td>
                            <?php 
                                $item_total = $sell_item['harga'] * $sell_item['jumlah'];
                                $total += $item_total; 
                                echo number_format($item_total, 2); 
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3">Total</td>
                    <td><?php echo number_format($total, 2); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="4">Tidak ada barang yang dibeli.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tombol untuk cetak nota -->
    <button onclick="window.print();">Print</button>

    <!-- Tombol untuk menyimpan ke histori -->
    <form method="post" style="margin-top: 20px; display: inline;">
        <button type="submit" name="save_to_histori">Save</button>
    </form>

   <!-- Footer -->
<div class="atas-footer">
    <h4>Syarat dan Ketentuan</h4>
    <h5>Silkan dibaca dahulu sebelum meninggalkan kios.!!</h5>
   
    <ol>
        <li>Semua barang elektronik yang diterima harus diperiksa dan diverifikasi dengan customer.</li>
        <li>Segala bentuk kerusakan setelah meninggalkan kios bukan menjadi tanggung jawab kami.</li>
        <li>Customer diharapkan memberikan informasi kontak yang benar untuk komunikasi.</li>
        <li>Pembayaran yang sudah dilakukan tidak dapat dikembalikan, kecuali dalam kondisi yang telah disepakati.</li>
        <li>Pembayaran melalui transfer bank harus menyertakan bukti pembayaran dengan mengirimkan bukti ke WA.</li>
        <li>Barang/unit yang tidak di ambil lebih dari 2bulan hilang,rusak,cacat tidak ada garansi atau tanggungjawab apapun dari kami.</li>
        <li>Barang/unit tidak di ambil lebih dari 3bulan, akan di masukan ke gudang penyimpanan dan akan dikenakan biaya penyimpanan Rp.2500/hari, terhitung dari tanggal masuk gudang.</li>
        <li>Barang/unit tidak diambil lebih dari 4bulan terhitung sejak tanggal masuk atau tanggal tertera pada nota ini, maka dianggap di serahkan ke kios dan menjadi hak kios tanpa syarat atau di anggap hilang .</li>
        <li>Dengan menerima nota ini custumer telah menyetujui SYARAT DAN KETENTUAN yg telampir dari NO 1 s/d No 10.</li>
        <li>Silakan mengecek kembali barang,unit,kembalian,sturk nota dll sebleum mninggalkan kios.</li>
    </ol>
</div>

<!-- Footer -->
<footer>
    <a href="histori.php">⭕</a>
    <a href="home.php">🏠</a>
   
   
</footer>

</body>
</html>
