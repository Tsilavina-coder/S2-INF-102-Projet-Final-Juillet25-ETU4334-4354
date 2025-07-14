<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Liste des objets</title>
    <link rel="stylesheet" href="../CSS/Style.css" />
    <!-- <link rel="stylesheet" href="../Login/bootstrap-5.3.5-dist/css/bootstrap.min.css"> -->
</head>
<body>
    <?php
    session_start();
    require_once __DIR__ . '/../inc/fonctions/fonctions.php';

    $id_membre = $_SESSION['id_membre'] ?? null;
    $profile = null;
    if ($id_membre !== null) {
        $profile = getUserProfile($id_membre);
    }
    ?>
    <div class="container mt-5">
        <?php if ($profile): ?>
            <div class="user-profile mb-4 d-flex align-items-center">
                <?php
                $imagePath = "../assets/" . htmlspecialchars($profile['nom']) . ".jpg";
                if (file_exists($imagePath)) {
echo '<img src="' . $imagePath . '" alt="Photo de profil" class="rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">';
                } else {
                    echo '<div class="rounded-circle bg-secondary me-3" style="width: 80px; height: 80px;"></div>';
                }
                ?>
                <div>
                    <h4><?= htmlspecialchars($profile['nom']) ?></h4>
                    <p><?= htmlspecialchars($profile['email']) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <h2>Liste des objets</h2>
        <?php

        $categories = getCategories();

        $selected_categorie = $_GET['categorie'] ?? '';

        $objets = getListeObjets($selected_categorie);
        ?>

        <form method="GET" class="mb-3">
            <label for="categorie" class="form-label">Filtrer par catégorie :</label>
            <select name="categorie" id="categorie" class="form-select" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>" <?= ($selected_categorie == $categorie['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categorie['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php
        if (count($objets) > 0) {
            echo '<table class="table table-bordered styled-table">';
            echo '<thead><tr><th>Image</th><th>Nom de l\'objet</th><th>Date de retour</th><th>Emprunt en cours</th></tr></thead><tbody>';
            foreach ($objets as $row) {
                echo '<tr>';
                echo '<td>';
                if (!empty($row["image_objet"]) && file_exists(__DIR__ . '/../assets/' . $row["image_objet"])) {
                    echo '<img src="../assets/' . htmlspecialchars($row["image_objet"]) . '" alt="Image objet" style="width: 80px; height: 80px; object-fit: cover;">';
                } else {
                    echo 'Pas d\'image';
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($row["nom_objet"]) . '</td>';
                echo '<td>' . ($row["date_retour"] ? htmlspecialchars($row["date_retour"]) : 'N/A') . '</td>';
                echo '<td>' . $row["emprunt_en_cours"] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "<p>Aucun objet trouvé.</p>";
        }
        ?>
    </div>
</body>
</html>
