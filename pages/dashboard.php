<?php
include '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<header>
    📅 Mon emploi du temps
</header>

<div class="container">

    <!-- FORMULAIRE -->
    <div class="card">
        <h3>➕ Ajouter une activité</h3>

        <form action="add_task.php" method="POST">
            <input type="text" name="unite" placeholder="Unité d'enseignement" required>

            <select name="type_activite" required>
                <option value="">Type</option>
                <option value="Cours">Cours</option>
                <option value="Lecture">Lecture</option>
                <option value="TD">TD</option>
                <option value="TP">TP</option>
            </select>

            <input type="text" name="titre" placeholder="Titre" required>

            <input type="datetime-local" name="date_debut" required>
            <input type="datetime-local" name="date_fin" required>

            <button type="submit">Ajouter</button>
        </form>
    </div>

    <!-- TABLEAU -->
    <div class="card table-container">
        <h3>📊 Mes activités</h3>

        <table>
            <tr>
                <th>Unité</th>
                <th>Type</th>
                <th>Titre</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Action</th>
            </tr>

        <?php
        $user_id = $_SESSION['user_id'];
        $result = $conn->query("SELECT * FROM taches WHERE utilisateur_id=$user_id ORDER BY date_debut");

        $tasks_js = [];

        while ($row = $result->fetch_assoc()) {

            echo "<tr>";
            echo "<td>".$row['unite']."</td>";
            echo "<td>".$row['type_activite']."</td>";
            echo "<td>".$row['titre']."</td>";
            echo "<td>".$row['date_debut']."</td>";
            echo "<td>".$row['date_fin']."</td>";
            echo "<td>
                <a href='edit_task.php?id=".$row['id']."'>✏️</a> 
                <a href='delete_task.php?id=".$row['id']."'>❌</a>
            </td>";
            echo "</tr>";

            $tasks_js[] = [
                "unite" => $row['unite'],
                "type" => $row['type_activite'],
                "titre" => $row['titre'],
                "date_debut" => $row['date_debut'],
                "date_fin" => $row['date_fin'],
                "date" => $row['date_debut']
            ];
        }
        ?>
        </table>
    </div>

</div>

<!-- 📅 GRILLE SEMAINE POUR LE COURS -->
<h3 style="text-align:center;">📅 Emploi du temps des cours</h3>

<table class="week-table">
<tr>
    <th>Heure</th>
    <th>Lundi</th>
    <th>Mardi</th>
    <th>Mercredi</th>
    <th>Jeudi</th>
    <th>Vendredi</th>
    <th>Samedi</th>
    <th>Dimanche</th>
</tr>

<?php
$jours = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

$start = strtotime("07:30");
$end = strtotime("17:30");

for ($time = $start; $time <= $end; $time += 7200) { // 2h

    $heure = date("H:i", $time);

    echo "<tr>";
    echo "<td>$heure</td>";

    foreach ($jours as $jour) {

        echo "<td style='cursor:pointer;'>";

        // 🔍 Recherche du cours
        $query = $conn->query("
            SELECT * FROM taches 
            WHERE utilisateur_id=$user_id 
            AND type_activite='Cours'
            AND DAYNAME(date_debut)='$jour'
            AND TIME(date_debut) = '$heure:00'
        ");

        if ($t = $query->fetch_assoc()) {

            echo "<div style='background:#16a34a;color:white;padding:5px;border-radius:5px;'>
                    ".$t['unite']."<br>

                    <a href='delete_task.php?id=".$t['id']."' style='color:white;'>❌</a>
                    <a href='edit_task.php?id=".$t['id']."' style='color:white;'>✏️</a>
                  </div>";

        } else {

            // ➕ Ajouter cours
            echo "<a href='add_course.php?jour=$jour&heure=$heure' 
                    style='display:block;height:60px;'>
                    ➕
                  </a>";
        }

        echo "</td>";
    }

    echo "</tr>";
}
?>
</table>

<h3 style="text-align:center; margin-top:40px;">📅 Cours du jour</h3>

<?php
$today = date('l');

$query = $conn->query("
    SELECT * FROM taches 
    WHERE utilisateur_id=$user_id 
    AND type_activite='Cours'
    AND jour_semaine='$today'
    ORDER BY date_debut
");

if ($query->num_rows == 0) {
    echo "<p style='text-align:center;'>Aucun cours aujourd'hui</p>";
}

while ($t = $query->fetch_assoc()) {

    echo "<div style='
        width:300px;
        margin:10px auto;
        background:#16a34a;
        color:white;
        padding:15px;
        border-radius:10px;
        text-align:center;
    '>
        ⏰ ".date('H:i', strtotime($t['date_debut']))."<br>
        📘 ".$t['unite']."<br>
        📝 ".$t['titre']."<br><br>

        <a href='edit_task.php?id=".$t['id']."' style='color:white;'>✏️</a>
        <a href='delete_task.php?id=".$t['id']."' style='color:white;'>❌</a>
    </div>";
}
?>
<!-- 🚪 DÉCONNEXION -->
<div style="text-align:center; margin:30px;">
    <a href="logout.php" style="
        background:#dc2626;
        color:white;
        padding:10px 20px;
        border-radius:10px;
        text-decoration:none;
        font-weight:bold;
    ">
        🚪 Déconnexion
    </a>
</div>

<!-- 🔔 ALARME -->
<audio id="alarmSound" src="../assets/audio/alarm.mp3"></audio>

<script>
document.addEventListener("click", () => {
    document.getElementById("alarmSound").play().then(() => {
        document.getElementById("alarmSound").pause();
    });
}, { once: true });
</script>

<script>
let tasks = <?php echo json_encode($tasks_js); ?>;

// Pour éviter les répétitions
let alreadyNotified = [];

function checkAlarms() {
    let now = new Date();

    // Jour actuel (ex: Monday)
    let today = now.toLocaleDateString('en-US', { weekday: 'long' });

    tasks.forEach((task, index) => {

        // ❌ Ignore si ce n'est pas aujourd'hui
        if (task.jour !== today) return;

        let taskTime = new Date(task.date_debut);

        // ⏰ 15 minutes avant
        let alertTime = new Date(taskTime.getTime() - 15 * 60000);

        // Vérifie heure + minute
        if (
            now.getHours() === alertTime.getHours() &&
            now.getMinutes() === alertTime.getMinutes()
        ) {

            // ❌ Évite répétition
            if (alreadyNotified.includes(index)) return;

            alreadyNotified.push(index);

            let message =
                "🔔 Rappel d'activité\n\n" +
                "📘 Unité: " + task.unite + "\n" +
                "📌 Type: " + task.type + "\n" +
                "📝 Titre: " + task.titre + "\n" +
                "⏰ Début: " + task.date_debut + "\n" +
                "⏳ Fin: " + task.date_fin;

            // 🔔 Notification
            alert(message);

            // 🔊 Son après 5 secondes
            setTimeout(() => {
                document.getElementById("alarmSound").play();
            }, 5000);
        }
    });
}

// Vérifie chaque minute
setInterval(checkAlarms, 60000);
</script>

</body>
</html>