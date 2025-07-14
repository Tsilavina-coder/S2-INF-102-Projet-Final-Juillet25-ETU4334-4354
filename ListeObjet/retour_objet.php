<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

require_once __DIR__ . '/../inc/fonctions/fonctions.php';

$id_objet = $_GET['id_objet'] ?? null;
$id_membre = $_GET['id_membre'] ?? null;

if (!$id_objet || !$id_membre) {
    die('Paramètres manquants.');
}

$profile = getUserProfile($id_membre);
if (!$profile) {
    die('Utilisateur non trouvé.');
}

// Récupérer les infos de l'objet emprunté (optionnel, peut être étendu)
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Retour de l'objet</title>
    <link rel="stylesheet" href="../CSS/Style.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Retour de l'objet</h2>
        <form method="POST" action="trait_retour_objet.php">
            <input type="hidden" name="id_objet" value="<?= htmlspecialchars($id_objet) ?>" />
            <input type="hidden" name="id_membre" value="<?= htmlspecialchars($id_membre) ?>" />
            <div class="mb-3">
                <label>État de l'objet :</label><br />
                <input type="checkbox" id="etat_ok" name="etat[]" value="OK" />
                <label for="etat_ok">OK</label><br />
                <input type="checkbox" id="etat_abime" name="etat[]" value="ABIME" />
                <label for="etat_abime">ABÎMÉ</label>
            </div>
            <button type="submit" class="btn btn-primary">Valider le retour</button>
        </form>
        <?php if (isset($_GET['retour_valide']) && $_GET['retour_valide'] == 1): ?>
            <div class="mt-4">
                <a href="liste_retours.php" class="btn btn-info">Voir la liste des objets retournés</a>
            </div>
        <?php endif; ?>
        <p><a href="fiche_utilisateur.php?id_membre=<?= htmlspecialchars($id_membre) ?>" class="btn btn-secondary mt-3">Annuler</a></p>
    </div>
</body>
</html>
