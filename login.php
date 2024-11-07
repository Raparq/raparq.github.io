<?php
session_start(); // Memulai sesi

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

// Fungsi untuk login
function login($username, $password) {
    global $db;

    // Memeriksa apakah username dan password ada di database
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Ambil data pengguna
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Memverifikasi password
    if ($user && $user['password'] === $password) {
        // Memperbarui waktu login
        $stmt = $db->prepare("UPDATE users SET last_login = datetime('now') WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        // Menyimpan username ke dalam sesi
        $_SESSION['username'] = $username;

        // Arahkan ke halaman selamat datang
        header('Location: welcome.php'); // Mengarahkan ke halaman selamat datang
        exit();
    } else {
        return "Username atau password salah.";
    }
}

// Proses form
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Mengambil data dari form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $error_message = login($username, $password);
    }
}
?>

<!-- Form untuk login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Login</h2>
<?php if (!empty($error_message)): // Hanya tampilkan pesan jika ada error ?>
    <p class="error"><?php echo $error_message; ?></p>
<?php endif; ?>
<form id="loginForm" method="post">
    <input type="text" name="username" placeholder="Masukkan username kamu di sini" required>
    <input type="password" name="password" placeholder="Masukkan key kamu di sini" required>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
