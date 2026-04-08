<?php
include '../includes/config.php';

$jour = $_GET['jour'];
$heure = $_GET['heure'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $unite = $_POST['unite'];
    $titre = $_POST['titre'];
    $user_id = $_SESSION['user_id'];

    $date = date('Y-m-d');

    // Construire datetime
    $date_debut = date("Y-m-d H:i:s", strtotime("$date $heure"));
    $date_fin = date("Y-m-d H:i:s", strtotime("$date $heure +1 hour"));

    $stmt = $conn->prepare("INSERT INTO taches 
    (utilisateur_id, unite, type_activite, titre, date_debut, date_fin)
    VALUES (?, ?, 'Cours', ?, ?, ?)");

    $stmt->bind_param("issss", $user_id, $unite, $titre, $date_debut, $date_fin);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="card" style="margin:auto; margin-top:50px;">
<h3>➕ Ajouter un cours</h3>

<form method="POST">
    <input type="text" name="unite" placeholder="Unité" required>
    <input type="text" name="titre" placeholder="Titre" required>
    <button type="submit">Ajouter</button>
</form>
</div>