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

// Fungsi untuk mengambil pengguna berdasarkan username
function getUser($username) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengupdate pengguna
function updateUser($username, $newPassword) {
    global $db;

    // Memperbarui password pengguna
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $newPassword);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Pengguna '$username' berhasil diperbarui.</p>";
    } else {
        echo "<p style='color: red;'>Gagal memperbarui pengguna.</p>";
    }
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_user'])) {
        // Mengambil data dari form
        $username = $_POST['username'];
        $newPassword = $_POST['new_password'];
        updateUser($username, $newPassword);
    }
}

// Mendapatkan username dari query string
$username = isset($_GET['username']) ? $_GET['username'] : '';
$user = getUser($username);
?>

<!-- Form untuk edit pengguna -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
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

<h2>Edit Pengguna</h2>
<?php if ($user): ?>
    <form method="post">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        <input type="password" name="new_password" placeholder="Password Baru" required>
        <button type="submit" name="edit_user">Update Pengguna</button>
    </form>
<?php else: ?>
    <p style="color: red;">Pengguna tidak ditemukan.</p>
<?php endif; ?>

</body>
</html>
