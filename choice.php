<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <title>Excel & Word Quest - Сонголт</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            perspective: 1000px;
        }

        .container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 50px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            text-align: center;
            width: 100%;
            max-width: 420px;
            transform: rotateY(0deg) translateZ(30px);
            transition: transform 0.6s ease;
        }

        .container:hover {
            transform: rotateY(10deg) translateZ(50px);
        }

        h1 {
            color: #fff;
            margin-bottom: 30px;
            font-size: 26px;
            font-weight: 700;
            text-shadow: 0 3px 8px rgba(0,0,0,0.4);
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: inset 0 0 10px rgba(255,255,255,0.3),
                        0 8px 20px rgba(0,0,0,0.25);
        }

        .btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: inset 0 0 15px rgba(255,255,255,0.5),
                        0 12px 25px rgba(0,0,0,0.35);
        }

        .btn-word {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
        }

        .btn-excel {
            background: linear-gradient(135deg, #00ff94, #00c6ff);
        }

        .user-info {
            margin-top: 25px;
            font-size: 15px;
            color: #e0f7fa;
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .logout {
            margin-top: 15px;
            display: inline-block;
            font-size: 14px;
            color: #ff5252;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .logout:hover {
            color: #ff1744;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Хөтөлбөр сонгоно уу</h1>
    <div class="btn-group">
        <a href="word_game.php" class="btn btn-word">
            <i class="fa-solid fa-file-word"></i> Word Даалгавар
        </a>
        <a href="excel_game.php" class="btn btn-excel">
            <i class="fa-solid fa-file-excel"></i> Excel Даалгавар
        </a>
    </div>
    <p class="user-info">
        <i class="fa-solid fa-user"></i> Нэвтэрсэн: <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
    </p>
    <a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Гарах</a>
</div>
</body>
</html>
