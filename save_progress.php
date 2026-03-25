<?php
/**
 * save_progress.php
 * dashboard.php-аас fetch('save_progress.php') дуудагдана
 */
session_start();
include 'db_connect.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username'])) {
    echo json_encode(['ok' => false, 'error' => 'not_logged_in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['ok' => false, 'error' => 'invalid_json']);
    exit;
}

$username      = $_SESSION['username'];
$quest         = max(1, min(21, (int)($input['quest']  ?? 1)));
$xp            = max(0, min(99,  (int)($input['xp']    ?? 0)));
$level         = max(1,          (int)($input['level'] ?? 1));
$completed     = array_map('intval', $input['completed'] ?? []);
$completed_str = implode(',', $completed);

// Хэрэглэгч ID авах
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo json_encode(['ok' => false, 'error' => 'user_not_found']);
    exit;
}
$user_id = $user['id'];

// UPSERT
$stmt2 = $conn->prepare(
    "INSERT INTO word_progress (user_id, current_quest, xp, level, completed_quests, updated_at)
     VALUES (?, ?, ?, ?, ?, NOW())
     ON DUPLICATE KEY UPDATE
       current_quest    = VALUES(current_quest),
       xp               = VALUES(xp),
       level            = VALUES(level),
       completed_quests = VALUES(completed_quests),
       updated_at       = NOW()"
);
$stmt2->bind_param("iiiss", $user_id, $quest, $xp, $level, $completed_str);

if ($stmt2->execute()) {
    echo json_encode(['ok' => true, 'quest' => $quest, 'xp' => $xp, 'level' => $level]);
} else {
    echo json_encode(['ok' => false, 'error' => $stmt2->error]);
}
?>