<?php
include '../includes/config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 🔍 Vérifier si email existe déjà
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "❌ Cet email est déjà utilisé";
    } else {

        // ✅ Insérer
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $email, $password);
        $stmt->execute();

        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page">

<div class="auth-card">
    <h2>📝 Inscription</h2>

    <!-- MESSAGE ERREUR -->
    <?php if ($error != "") echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p style="margin-top:10px;">
        Déjà un compte ? <a href="login.php">Se connecter</a>
    </p>
</div>

</body>
</html>