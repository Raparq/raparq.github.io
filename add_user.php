<?php
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

// Fungsi untuk menambah pengguna
function addUser($username, $password) {
    global $db;

    // Memasukkan pengguna baru ke database
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Pengguna '$username' berhasil ditambahkan.</p>";
    } else {
        echo "<p style='color: red;'>Gagal menambahkan pengguna.</p>";
    }
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Mengambil data dari form
        $username = $_POST['username'];
        $password = $_POST['password'];
        addUser($username, $password);
    }
}
?>

<!-- Form untuk tambah pengguna -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Tambah Pengguna</h2>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="add_user">Tambah Pengguna</button>
</form>

</body>
</html>
