<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = ""; // à adapter selon votre configuration
    $dbname = "emprunt";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Préparer et exécuter la requête pour vérifier les identifiants
    $stmt = $conn->prepare("SELECT id_membre, nom FROM membre WHERE email = ? AND mdp = ?");
    $stmt->bind_param("ss", $email, $mdp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Identifiants valides
        $user = $result->fetch_assoc();
        $_SESSION['id_membre'] = $user['id_membre'];
        $_SESSION['nom'] = $user['nom'];
        header("Location: ../ListeObjet/listeobj.php");
        exit();
    } else {
        // Identifiants invalides
        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        header("Location: Login/Index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Accès direct interdit
    header("Location: Login/Index.php");
    exit();
}
?>
