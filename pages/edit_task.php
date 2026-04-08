<?php
include '../includes/config.php';

$id = $_GET['id'];

// Récupérer la tâche
$result = $conn->query("SELECT * FROM taches WHERE id=$id");
$data = $result->fetch_assoc();
?>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="card" style="margin:auto; margin-top:50px;">
<h3>✏️ Modifier l'activité</h3>

<form method="POST">

    <input type="text" name="unite" value="<?php echo $data['unite']; ?>" required>

    <select name="type_activite">
        <option <?php if($data['type_activite']=="Cours") echo "selected"; ?>>Cours</option>
        <option <?php if($data['type_activite']=="Lecture") echo "selected"; ?>>Lecture</option>
        <option <?php if($data['type_activite']=="TD") echo "selected"; ?>>TD</option>
        <option <?php if($data['type_activite']=="TP") echo "selected"; ?>>TP</option>
    </select>

    <input type="text" name="titre" value="<?php echo $data['titre']; ?>" required>

    <input type="datetime-local" name="date_debut" 
        value="<?php echo date('Y-m-d\TH:i', strtotime($data['date_debut'])); ?>">

    <input type="datetime-local" name="date_fin" 
        value="<?php echo date('Y-m-d\TH:i', strtotime($data['date_fin'])); ?>">

    <button type="submit">Mettre à jour</button>

</form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $unite = $_POST['unite'];
    $type = $_POST['type_activite'];
    $titre = $_POST['titre'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    $stmt = $conn->prepare("UPDATE taches SET unite=?, type_activite=?, titre=?, date_debut=?, date_fin=? WHERE id=?");
    $stmt->bind_param("sssssi", $unite, $type, $titre, $date_debut, $date_fin, $id);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>