<?php
session_start(); // Memulai sesi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent scroll */
            background: rgb(169, 169, 169);
        }

        /* Video background styling */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
           
        }

        .navbar {
            background-color: rgba(30, 144, 255, 0.8); /* Semi-transparent navbar */
            display: flex;
            top: 10px;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .navbar h1 {
            margin: 0;
            color: white;
            font-size: 24px;
            flex-grow: 1;
            text-align: center;
        }

        /* Cube Styles */
        .cube {
            width: 40px;
            height: 40px;
            position: absolute;
            top: 10px;
            left: 60px;
            transform-style: preserve-3d;
            animation: rotate 5s infinite linear;
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

        /* Content Styles */
        .content {
            text-align: center; /* Center the content */
            margin-top: 60px; /* Add some margin from the navbar */
            transform: scale(0.7); /* Reduce size to 70% */
            transform-origin: top; /* Ensure scaling is from the top */
        }
        @keyframes blink {
    0%, 100% {
        opacity: 1; /* Sepenuhnya terlihat */
    }
    50% {
        opacity: 0; /* Tidak terlihat */
    }
}


.content h2 {
    font-size: 60px; /* Ukuran lebih besar */
    font-family: "Sixtyfour Convergence", sans-serif;
    font-weight: bold; /* Membuat font tebal */
    color: blue; /* Warna biru */
    white-space: nowrap; /* Mencegah teks terbungkus */
    overflow: hidden; /* Mencegah overflow */
    animation: blink 1.0s infinite; /* Menggunakan animasi blink */
    animation-fill-mode: forwards; /* Memastikan animasi mempertahankan status akhir */
    text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.8), /* Bayangan untuk outline hitam */
                 -2px -2px 0px rgba(0, 0, 0, 0.8), 
                 2px -2px 0px rgba(0, 0, 0, 0.8), 
                 -2px 2px 0px rgba(0, 0, 0, 0.8); /* Menambahkan bayangan di semua arah */
}
.jam {
            font-family: "Rubik Wet Paint", system-ui;
            text-align: center; /* Center the text */
            margin-top: -140px; /* Margin above the clock */
            font-size: 90px; /* Size of the clock text */
            color: blue; /* Color of the clock text */
            text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.8), /* Bayangan untuk outline hitam */
                 -2px -2px 0px rgba(0, 0, 0, 0.8), 
                 2px -2px 0px rgba(0, 0, 0, 0.8), 
                 -2px 2px 0px rgba(0, 0, 0, 0.8); /* Menambahkan bayangan di semua arah */
        }

 .navbar a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50; /* Tombol background color */
            color: white; /* Tombol text color */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s; /* Transisi untuk background dan transformasi */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan untuk efek 3D */
        }

      
        .navbar a:hover {
            background-color: #45a049; /* Warna tombol saat hover */
            transform: translateY(-2px); /* Efek mengangkat tombol saat hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Bayangan lebih dalam saat hover */
        } 

        .content a {
            display: inline-block; /* Menjadikan tombol sebagai blok */
            padding: 30px 35px; /* Memberikan padding pada tombol */
            margin: 50px; /* Memberikan jarak antar tombol */
            background-color:blue; /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            text-decoration: none; /* Menghilangkan garis bawah */
            border-radius: 5px; /* Membuat sudut tombol menjadi bulat */
            transition: background-color 0.3s, transform 0.3s; /* Transisi halus untuk perubahan warna dan transformasi */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Menambahkan bayangan untuk efek 3D */
        }

        .content a:hover {
            background-color: #45a049; /* Warna tombol saat hover */
            transform: scale(1.05); /* Efek membesar tombol saat hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Bayangan lebih dalam saat hover */
        }
       
    </style>
</head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sixtyfour+Convergence&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik+Wet+Paint&family=Sixtyfour+Convergence&display=swap" rel="stylesheet">
<body>

<!-- Video Background -->
<video class="background">
    <source src="bg.jpg" type="img">
    Your browser does not support the video tag.
</video>

<!-- Navbar -->
<div class="navbar">
    <h1>EZ-Computer</h1>
    <div class="cube">
        <div class="front">EZ-Computer</div>
        <div class="back">EZ-Computer</div>
        <div class="right">EZ-Computer</div>
        <div class="left">EZ-Computer</div>
        <div class="top">EZ-Computer</div>
        <div class="bottom">EZ-Computer</div>
    </div>
</div>

<!-- Content -->
<div class="content">
    <h2>SELAMAT DATANG DI EZ</h2>
    <!-- Tombol untuk navigasi -->
    <a href="home.php">Home</a>
    <a href="login.php">Login</a>
    <a href="input_barang.php">Input Barang</a>
    <a href="cst.php">Daftar Cst</a>
    <a href="penjualan.php">Penjualan</a>
    <a href="nota.php">Nota</a>
    <a href="histori.php">Histori Nota</a>
</div>
 <!-- Jam Digital -->
    <div class="jam">
        <p id="digital-clock"></p> <!-- Tempat untuk menampilkan jam digital -->
    </div>
</div>
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0'); // Get hours
        const minutes = String(now.getMinutes()).padStart(2, '0'); // Get minutes
        const seconds = String(now.getSeconds()).padStart(2, '0'); // Get seconds
        const clock = `${hours}:${minutes}:${seconds}`; // Format the time
        document.getElementById('digital-clock').textContent = clock; // Display the time
    }

    setInterval(updateClock, 1000); // Update clock every second
    updateClock(); // Call the function to display clock immediately
</script>

</body>
</html>
