<?php 
include '../includes/config.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page">

<div class="auth-card">

    <h2>🔐 Connexion</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Connexion</button>
    </form>

    <p style="margin-top:10px;">
        <a href="register.php">Créer un compte</a>
    </p>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {

            if (password_verify($password, $user['mot_de_passe'])) {

                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();

            } else {
                echo "<p style='color:red;'>Mot de passe incorrect</p>";
            }

        } else {
            echo "<p style='color:red;'>Utilisateur introuvable</p>";
        }
    }
    ?>

</div>

</body>
</html>