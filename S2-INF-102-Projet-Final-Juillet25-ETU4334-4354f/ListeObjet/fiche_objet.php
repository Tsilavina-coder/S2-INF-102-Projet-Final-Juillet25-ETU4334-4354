<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

require_once __DIR__ . '/../inc/fonctions/fonctions.php';

$id_objet = $_GET['id_objet'] ?? null;
if ($id_objet === null) {
    header('Location: listeobj.php');
    exit();
}

$objet = getObjetDetails($id_objet);
if (!$objet) {
    die('Objet non trouvé.');
}

$historique = getHistoriqueEmprunts($id_objet);

// Pour les autres images, supposons qu'elles sont stockées dans un dossier spécifique avec une convention de nommage
// Ici, on affiche uniquement l'image principale (image_objet) pour simplifier

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Fiche de l'objet - <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link rel="stylesheet" href="../CSS/Style.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Fiche de l'objet : <?= htmlspecialchars($objet['nom_objet']) ?></h2>
        <div class="mb-4">
            <?php if (!empty($objet['image_objet']) && file_exists(__DIR__ . '/../assets/' . $objet['image_objet'])): ?>
                <img src="../assets/<?= htmlspecialchars($objet['image_objet']) ?>" alt="<?= htmlspecialchars($objet['nom_objet']) ?>" style="max-width: 300px; max-height: 300px; object-fit: cover; border-radius: 8px;">
            <?php else: ?>
                <p>Pas d'image disponible</p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <h4>Catégorie : <?= htmlspecialchars($objet['nom_categorie']) ?></h4>
        </div>
        <div class="mb-4">
            <h4>Historique des emprunts</h4>
            <?php if (count($historique) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historique as $emprunt): ?>
                            <tr>
                                <td><?= htmlspecialchars($emprunt['nom_membre']) ?></td>
                                <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                                <td><?= htmlspecialchars($emprunt['date_retour'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun emprunt enregistré pour cet objet.</p>
            <?php endif; ?>
        </div>
        <p><a href="listeobj.php" class="btn btn-primary">Retour à la liste des objets</a></p>
    </div>
</body>
</html>
