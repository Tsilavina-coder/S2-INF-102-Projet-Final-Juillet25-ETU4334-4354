<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

require_once __DIR__ . '/../inc/fonctions/fonctions.php';

$id_objet = $_POST['id_objet'] ?? null;
$duree = $_POST['duree'] ?? null;
$id_membre = $_SESSION['id_membre'] ?? null;

if ($id_objet === null || $duree === null || $id_membre === null) {
    die('Données manquantes.');
}

$id_objet = (int)$id_objet;
$duree = (int)$duree;
$id_membre = (int)$id_membre;

if ($duree < 1 || $duree > 365) {
    die('Durée d\'emprunt invalide.');
}

$conn = getDbConnection();

$date_emprunt = date('Y-m-d');
$date_retour = date('Y-m-d', strtotime("+$duree days"));

$stmt = $conn->prepare("INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $id_objet, $id_membre, $date_emprunt, $date_retour);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: listeobj.php?message=Emprunt enregistré avec succès');
    exit();
} else {
    $stmt->close();
    $conn->close();
    die('Erreur lors de l\'enregistrement de l\'emprunt.');
}
?>
