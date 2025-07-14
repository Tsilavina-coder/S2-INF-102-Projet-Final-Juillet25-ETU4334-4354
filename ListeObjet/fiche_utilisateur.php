<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}

require_once __DIR__ . '/../inc/fonctions/fonctions.php';

$id_membre = $_GET['id_membre'] ?? $_SESSION['id_membre'];
if ($id_membre === null) {
    die('Utilisateur non spécifié.');
}

$profile = getUserProfile($id_membre);
if (!$profile) {
    die('Utilisateur non trouvé.');
}

$emprunts = getUserEmprunts($id_membre);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Fiche utilisateur - <?= htmlspecialchars($profile['nom']) ?></title>
    <link rel="stylesheet" href="../CSS/Style.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Fiche utilisateur : <?= htmlspecialchars($profile['nom']) ?></h2>
        <div class="d-flex align-items-center mb-4">
            <?php
            $imagePath = "../assets/" . htmlspecialchars($profile['nom']) . ".jpg";
            if (file_exists($imagePath)) {
                echo '<div class="me-3 text-center">';
                echo '<img src="' . $imagePath . '" alt="Photo de profil" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">';
                echo '</div>';
            } else {
                echo '<div class="rounded-circle bg-secondary me-3" style="width: 100px; height: 100px;"></div>';
            }
            ?>
            <div>
                <p><strong>Email :</strong> <?= htmlspecialchars($profile['email']) ?></p>
                <p><strong>Genre :</strong> <?= htmlspecialchars($profile['genre'] ?? 'Non renseigné') ?></p>
                <p><strong>Date de naissance :</strong> <?= !empty($profile['date_naissance']) ? date('d/m/Y', strtotime($profile['date_naissance'])) : 'Non renseignée' ?></p>
            </div>
        </div>
        <div class="mb-4">
            <h4>Objets empruntés</h4>
            <?php if (count($emprunts) > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nom de l'objet</th>
                            <th>Date d'emprunt</th>
                            <th>Date de retour</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emprunts as $emprunt): ?>
                            <tr>
                                <td><a href="fiche_objet.php?id_objet=<?= htmlspecialchars($emprunt['id_objet']) ?>"><?= htmlspecialchars($emprunt['nom_objet']) ?></a></td>
                                <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
                                <td><?= htmlspecialchars($emprunt['date_retour']) ?></td>
                                <td>
                                    <a href="retour_objet.php?id_objet=<?= htmlspecialchars($emprunt['id_objet']) ?>&id_membre=<?= htmlspecialchars($id_membre) ?>" class="btn btn-warning btn-sm">Retourner l'objet</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun emprunt enregistré pour cet utilisateur.</p>
            <?php endif; ?>
        </div>
        <p><a href="listeobj.php" class="btn btn-primary">Retour à la liste des objets</a></p>
    </div>
</body>
</html>
