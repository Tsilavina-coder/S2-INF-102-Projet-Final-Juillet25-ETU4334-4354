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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Emprunter l'objet - <?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link rel="stylesheet" href="../CSS/Style.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Emprunter l'objet : <?= htmlspecialchars($objet['nom_objet']) ?></h2>
        <?php
        $date_disponible = getDateDisponibiliteProche($id_objet);
        ?>
        <?php if ($date_disponible): ?>
            <div class="alert alert-info">
                <strong>Disponible à partir du :</strong> <?= date('d/m/Y', strtotime($date_disponible)) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <strong>Disponible dès maintenant</strong>
            </div>
        <?php endif; ?>
        <form action="trait_emprunt_objet.php" method="POST" id="formEmprunt">
            <input type="hidden" name="id_objet" value="<?= htmlspecialchars($id_objet) ?>" />
            <div class="mb-3">
                <label for="duree" class="form-label">Durée d'emprunt (en jours) :</label>
                <input type="number" id="duree" name="duree" class="form-control" min="1" max="365" value="1" required />
            </div>
            <div class="mb-3">
                <label for="date_disponibilite" class="form-label">Date de disponibilité après emprunt :</label>
                <input type="text" id="date_disponibilite" class="form-control" readonly />
            </div>
            <button type="submit" class="btn btn-primary">Emprunter</button>
            <a href="listeobj.php" class="btn btn-secondary ms-2">Annuler</a>
        </form>
        <script>
            function updateDisponibilite() {
                const duree = parseInt(document.getElementById('duree').value) || 1;
                const today = new Date();
                today.setHours(0,0,0,0);
                const dispoDate = new Date(today);
                dispoDate.setDate(dispoDate.getDate() + duree);
                const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
                document.getElementById('date_disponibilite').value = dispoDate.toLocaleDateString('fr-FR', options);
            }
            document.getElementById('duree').addEventListener('input', updateDisponibilite);
            window.addEventListener('load', updateDisponibilite);
        </script>
    </div>
</body>
</html>
