<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

require_once __DIR__ . '/../inc/fonctions/fonctions.php';

$objetsRetournes = null;
if (function_exists('getObjetsRetournes')) {
    $objetsRetournes = getObjetsRetournes();
} else {
    $objetsRetournes = [];
}

$counts = ['OK' => 0, 'ABIME' => 0];

foreach ($objetsRetournes as $item) {
    if ($item['etat'] === 'OK') {
        $counts['OK'] += (int)$item['count_etat'];
    } elseif ($item['etat'] === 'ABIME') {
        $counts['ABIME'] += (int)$item['count_etat'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des objets retournés</title>
    <link rel="stylesheet" href="../CSS/Style.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Liste des objets retournés</h2>
        <?php if (empty($objetsRetournes)): ?>
            <p>Aucun objet retourné trouvé.</p>
        <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'objet</th>
                    <th>État</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objetsRetournes as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($item['etat']) ?></td>
                        <td><?= htmlspecialchars($item['count_etat']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <h4>Résumé</h4>
            <p>Nombre d'objets OK : <?= $counts['OK'] ?></p>
            <p>Nombre d'objets ABÎMÉ : <?= $counts['ABIME'] ?></p>
        </div>
        <?php endif; ?>
        <p><a href="retour_objet.php" class="btn btn-secondary mt-3">Retour</a></p>
    </div>
</body>
</html>
