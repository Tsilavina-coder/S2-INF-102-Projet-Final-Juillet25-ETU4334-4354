<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

$uploadDir = __DIR__ . '/../assets/';
$maxSize = 2 * 1024 * 1024; // 2 Mo
$allowedMimeTypes = ['image/jpeg', 'image/png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nom_objet']) || empty(trim($_POST['nom_objet']))) {
        die('Le nom de l\'objet est requis.');
    }
    if (!isset($_FILES['image_objet'])) {
        die('Aucune image reçue.');
    }

    $nomObjet = trim($_POST['nom_objet']);
    $file = $_FILES['image_objet'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('Erreur lors de l\'upload : ' . $file['error']);
    }

    if ($file['size'] > $maxSize) {
        die('Le fichier est trop volumineux.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimeTypes)) {
        die('Type de fichier non autorisé : ' . $mime);
    }

    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = $originalName . '_' . uniqid() . '.' . $extension;

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        die('Erreur lors du déplacement du fichier.');
    }

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = ""; // à adapter selon votre configuration
    $dbname = "emprunt";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }

    // Insertion dans la table objet
    $stmt = $conn->prepare("INSERT INTO objet (nom_objet, image_objet) VALUES (?, ?)");
    $stmt->bind_param("ss", $nomObjet, $newName);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: listeobj.php?ajout=success');
        exit();
    } else {
        $stmt->close();
        $conn->close();
        die('Erreur lors de l\'insertion en base de données.');
    }
} else {
    header('Location: ajout_objet.php');
    exit();
}
?>
