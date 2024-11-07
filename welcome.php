<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: sql-database-db.php'); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Fungsi untuk logout
function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('Location: sql-database-db.php'); // Arahkan ke halaman login
    exit();
}

// Proses logout jika tombol logout ditekan
if (isset($_POST['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
            background-color: #f9f9f9; /* Background color */
        }
        h1 {
            color: #4CAF50;
            font-size: 48px; /* Larger font size */
            font-weight: bold; /* Bold text */
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2); /* Soft shadow */
            animation: slideIn 1s ease; /* Slide-in animation */
        }
        .congratulations {
            font-size: 24px;
            color: #4CAF50;
            margin: 20px 0;
            animation: fadeIn 2s ease;
        }
        .countdown {
            font-size: 80px; /* Large font size for the countdown */
            color: pink; /* Pink color */
            text-shadow: 0 0 20px pink, 0 0 30px lightpink; /* Glowing effect */
            animation: glow 1.5s infinite alternate; /* Animation */
            margin: 20px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px; /* Button font size */
        }
        button:hover {
            background-color: #d32f2f;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes glow {
            from { text-shadow: 0 0 20px pink, 0 0 30px lightpink; }
            to { text-shadow: 0 0 30px lightpink, 0 0 40px hotpink; }
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
          /* Running text styles */
          .running-text-container {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            overflow: hidden;
            font-size: 20px;
        }
        .running-text {
            display: inline-block;
            white-space: nowrap;
            animation: runText 10s linear infinite;
        }
        @keyframes runText {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
    </style>
    <script>
        // Function to start countdown
        function startCountdown(duration) {
            let countdown = duration; // Set the countdown duration
            const countdownElement = document.getElementById('countdown'); // Get countdown element
            countdownElement.innerText = countdown; // Display initial countdown value
            
            const interval = setInterval(() => {
                countdown--; // Decrease countdown
                countdownElement.innerText = countdown; // Update countdown display
                if (countdown <= 0) {
                    clearInterval(interval); // Clear the interval
                    window.location.href = 'home.php'; // Redirect after countdown ends
                }
            }, 1000); // Update every second
        }

        window.onload = function() {
            startCountdown(5); // Start countdown with 5 seconds on page load
        };
    </script>
</head>
<body>

<h1>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
<p class="congratulations">Anda telah berhasil login ke sistem.</p>
<div class="countdown" id="countdown">5</div> <!-- Countdown timer -->
<!-- Running text at the bottom of the page -->
<div class="running-text-container">
    <div class="running-text">Selamat datang di EZ-COMPUTER, semoga hari Anda menyenangkan! 😊</div>
</div>
</body>
</html>
