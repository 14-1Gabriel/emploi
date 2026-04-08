<?php
include '../includes/config.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM taches WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>