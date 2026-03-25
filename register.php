<?php
session_start();
include 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email    = trim($_POST['email']);

    // Нууц үгийн шалгалт: дор хаяж 8 тэмдэгт, том жижиг үсэг, тоо, тусгай тэмдэгт
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($pattern, $password)) {
        $error = "Нууц үг дор хаяж 8 тэмдэгттэй, том жижиг үсэг, тоо, тусгай тэмдэгт агуулсан байх ёстой!";
    } else {
        // Email давхцаж байгаа эсэхийг шалгах
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Энэ и-мэйл аль хэдийн бүртгэлтэй байна!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $hashedPassword, $email);

            if ($stmt->execute()) {
                $success = "Бүртгэл амжилттай! Одоо нэвтэрнэ үү.";
            } else {
                $error = "Алдаа гарлаа!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <title>Бүртгүүлэх WE MASTER</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Sora', sans-serif;
            background: linear-gradient(135deg, #00c6ff, #00ff94);
            min-height: 100vh; display: flex;
            align-items: center; justify-content: center;
        }
        .card {
            background: #2c5364;
            border-radius: 18px; padding: 40px 50px;
            width: 100%; max-width: 380px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.7);
            color: #fff;
        }
        h2 { margin-bottom: 20px; }
        .field { margin-bottom: 16px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 8px; top: 50%; transform: translateY(-50%); color: #ec1d1d; }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%; padding: 12px 14px 12px 30px;
            border-radius: 9px; border: none;
            background: #203a43; color: #fff;
        }
        .btn-submit {
            width: 100%; background: linear-gradient(135deg, #00c6ff, #00ff94);
            border: none; border-radius: 9px;
            padding: 13px; font-weight: 700; cursor: pointer;
            color: #fff;
        }
        .error { color: #fca5a5; margin-bottom: 10px; }
        .success { color: #6ee7b7; margin-bottom: 10px; }
        .footer { margin-top: 20px; font-size: 13px; }
        .footer a { color: #00c6ff; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>Бүртгүүлэх</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="field">
            <div class="input-wrap">
                <i class="fas fa-user"></i>
                <input type="text" name="username" required placeholder="Хэрэглэгчийн нэр">
            </div>
        </div>
        <div class="field">
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" required placeholder="И-мэйл">
            </div>
        </div>
        <div class="field">
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" required placeholder="Нууц үг">
            </div>
        </div>
        
        <button type="submit" class="btn-submit">
            <i class="fas fa-user-plus"></i> Бүртгүүлэх 
        </button>
    </form>
    <div class="footer">
        Аль хэдийн бүртгэлтэй юу? <a href="index.php">Нэвтрэх</a>
    </div>
</div>
</body>
</html>
