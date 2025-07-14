<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

require_once __DIR__ . '/../inc/fonctions/fonctions.php';

$id_objet = $_POST['id_objet'] ?? null;
$id_membre = $_POST['id_membre'] ?? null;
$etat = $_POST['etat'] ?? [];

if (!$id_objet || !$id_membre) {
    die('Paramètres manquants.');
}

$conn = getDbConnection();

// Mettre à jour la table emprunt pour marquer le retour (exemple, à adapter selon schéma)
$sql = "UPDATE emprunt SET date_retour = CURDATE() WHERE id_objet = ? AND id_membre = ? AND date_retour IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_objet, $id_membre);
$stmt->execute();

// Enregistrer l'état de l'objet retourné (exemple, à adapter selon schéma)
// Supposons qu'il y a une table etat_objet_retour (id_retour, id_objet, id_membre, etat, date_retour)
foreach ($etat as $etat_val) {
    $sqlEtat = "INSERT INTO etat_objet_retour (id_objet, id_membre, etat, date_retour) VALUES (?, ?, ?, CURDATE())";
    $stmtEtat = $conn->prepare($sqlEtat);
    $stmtEtat->bind_param("iis", $id_objet, $id_membre, $etat_val);
    $stmtEtat->execute();
}

$stmt->close();
$conn->close();

header("Location: fiche_utilisateur.php?id_membre=$id_membre");
exit();
?>
