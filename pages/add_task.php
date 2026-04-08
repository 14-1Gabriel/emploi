<?php
include '../includes/config.php';

$unite = $_POST['unite'];
$type = $_POST['type_activite'];
$titre = $_POST['titre'];
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];
$user_id = $_SESSION['user_id'];

// 🔁 Calcul du jour (Lundi, Mardi…)
$jour_semaine = date('l', strtotime($date_debut));

// ⚠️ Vérifier conflit UNIQUEMENT avec les cours
if ($type != "Cours") {

    $stmt = $conn->prepare("
        SELECT * FROM taches 
        WHERE utilisateur_id=? 
        AND type_activite='Cours'
        AND jour_semaine=?
        AND (
            (TIME(date_debut) <= TIME(?) AND TIME(date_fin) > TIME(?)) OR
            (TIME(date_debut) < TIME(?) AND TIME(date_fin) >= TIME(?))
        )
    ");

    $stmt->bind_param("isssss",
        $user_id,
        $jour_semaine,
        $date_debut, $date_debut,
        $date_fin, $date_fin
    );

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('⚠️ Tu as cours à cette heure !'); window.location='dashboard.php';</script>";
        exit();
    }
}

// ✅ Insertion
$stmt = $conn->prepare("INSERT INTO taches 
(utilisateur_id, unite, type_activite, titre, date_debut, date_fin, jour_semaine)
VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("issssss", 
    $user_id, $unite, $type, $titre, $date_debut, $date_fin, $jour_semaine
);

$stmt->execute();

header("Location: dashboard.php");
?>