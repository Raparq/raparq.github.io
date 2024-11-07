<?php
session_start(); // Memulai sesi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUBE</title>
   

    <style>
        body {
            background-color:#808080;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            
            overflow: hidden; /* Prevent scroll */
        }
        .navbar {
            background-color: #1E90FF; /* Navbar background color */
            display: flex;
            top: 10px; /* Adjust top position */
            align-items: center; /* Center items vertically */
            padding: 20px; /* Add some padding */
            position: relative; /* To position the cube inside */
        }
        .navbar h1 {
            margin: 0; /* Remove default margin */
            color: white; /* Text color */
            font-size: 24px; /* Font size */
            flex-grow: 1; /* Grow to fill available space */
            text-align: center; /* Center the title */
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



</body>
</html>
