<?php
require_once '../config/db.php';

$action = $_GET['action'] ?? '';

if ($action == 'fetch') {
    $stmt = $pdo->query("SELECT * FROM members ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
}

if ($action == 'insert') {
    $sql = "INSERT INTO members (member_id, fullname, faculty) VALUES (?, ?, ?)";
    $pdo->prepare($sql)->execute([$_POST['member_id'], $_POST['fullname'], $_POST['faculty']]);
    echo "success";
}

if ($action == 'update') {
    $sql = "UPDATE members SET member_id=?, fullname=?, faculty=? WHERE id=?";
    $pdo->prepare($sql)->execute([$_POST['member_id'], $_POST['fullname'], $_POST['faculty'], $_POST['id']]);
    echo "success";
}

if ($action == 'delete') {
    $pdo->prepare("DELETE FROM members WHERE id=?")->execute([$_GET['id']]);
    echo "success";
}
?>