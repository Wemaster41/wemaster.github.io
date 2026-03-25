<?php
session_start();
include 'db_connect.php';

// Аль хэдийн нэвтэрсэн бол шууд явуул
if (isset($_SESSION['username'])) {
    header("Location: word_game.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: word_game.php");
            exit;
        } else {
            $error = "Нууц үг буруу байна!";
        }
    } else {
        $error = "Ийм хэрэглэгч байхгүй байна!";
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нэвтрэх – Word Master</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap');
        :root {
            --accent: #3b82f6; --accent2: #10b981; --gold: #f59e0b;
            --bg: #1c1e26; --surface: #23263a; --sidebar-bg: #181b27;
            --text: #e8eaf0; --muted: #8b90a7; --border: rgba(255,255,255,0.07);
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
        }
        .card {
            background: var(--sidebar-bg);
            border: 1px solid var(--border);
            border-radius: 18px; padding: 40px 36px;
            width: 100%; max-width: 380px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .brand {
            display: flex; align-items: center; gap: 10px;
            font-weight: 800; font-size: 18px; margin-bottom: 28px;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #2b579a, #1a3a6d);
            border-radius: 9px; display: flex; align-items: center;
            justify-content: center; font-size: 18px; color: white;
        }
        h2 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .sub { font-size: 13px; color: var(--muted); margin-bottom: 26px; }

        label { font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 6px; }
        .field { margin-bottom: 16px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; }
        input[type="text"], input[type="password"] {
            width: 100%; background: var(--surface);
            border: 1px solid var(--border); border-radius: 9px;
            color: var(--text); font-family: 'Sora', sans-serif;
            font-size: 14px; padding: 12px 14px 12px 40px;
            outline: none; transition: border-color 0.2s;
        }
        input:focus { border-color: var(--accent); }

        .error {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
            color: #fca5a5; padding: 10px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 16px;
        }
        .btn-submit {
            width: 100%; background: linear-gradient(135deg, #2b579a, #1a3a6d);
            color: white; border: none; border-radius: 9px;
            padding: 13px; font-family: 'Sora', sans-serif;
            font-weight: 700; font-size: 15px; cursor: pointer;
            transition: all 0.2s; display: flex; align-items: center;
            justify-content: center; gap: 8px; margin-top: 4px;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,87,154,0.5); }
        .footer { text-align: center; margin-top: 20px; font-size: 13px; color: var(--muted); }
        .footer a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-icon"><i class="fab fa-microsoft"></i></div>
        Word Master
    </div>
    <h2>Нэвтрэх</h2>
    <p class="sub">Суралцаж эхлэхийн тулд нэвтэрнэ үү</p>

    <?php if (!empty($error)): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="field">
            <label>Хэрэглэгчийн нэр</label>
            <div class="input-wrap">
                <i class="fas fa-user"></i>
                <input type="text" name="username" required autocomplete="username" placeholder="username">
            </div>
        </div>
        <div class="field">
            <label>Нууц үг</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
        </div>
        <button type="submit" class="btn-submit">
            <i class="fas fa-sign-in-alt"></i> Нэвтрэх
        </button>
    </form>
    <div class="footer">
        Шинэ хэрэглэгч үү? <a href="register.php">Бүртгүүлэх</a>
    </div>
</div>
</body>
</html>