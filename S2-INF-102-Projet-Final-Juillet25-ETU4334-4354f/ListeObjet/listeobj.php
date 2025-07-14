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
                    <p>Email : <?= htmlspecialchars($profile['email']) ?></p>
                    <p>Genre : <?php
                        if (isset($profile['genre'])) {
                            echo $profile['genre'] === 'F' ? 'Féminin' : ($profile['genre'] === 'M' ? 'Masculin' : htmlspecialchars($profile['genre']));
                        } else {
                            echo 'Non renseigné';
                        }
                    ?></p>
                    <p>Date de naissance : <?= !empty($profile['date_naissance']) ? date('d/m/Y', strtotime($profile['date_naissance'])) : 'Non renseignée' ?></p>
                </div>
            </div>
        <?php endif; ?>

        <h2>Liste des objets</h2>
        <?php

        $categories = getCategories();

        $selected_categorie = $_GET['categorie'] ?? '';
        $search_nom = $_GET['nom_objet'] ?? '';
        $search_disponible = isset($_GET['disponible']) ? ($_GET['disponible'] === 'on') : null;

        $objets = getListeObjets($selected_categorie, $search_nom, $search_disponible);

        // Mapping category names to image filenames
        $categoryImages = [
            'Bricolage' => 'bricolage.jpg',
            'Cuisine' => 'cuisine.jpg',
            'Esthetic' => 'esthetic.jpeg',
            'Mecanique' => 'mecanique.jpg'
        ];
        ?>

        <div class="category-images mb-4 d-flex flex-wrap gap-3">
            <?php foreach ($categories as $categorie): 
                $catName = $categorie['nom_categorie'];
                $imageFile = $categoryImages[$catName] ?? null;
                if ($imageFile && file_exists(__DIR__ . '/../assets/categorie/' . $imageFile)):
            ?>
                <div class="category-item text-center" style="width: 150px;">
                    <img src="../assets/categorie/<?= htmlspecialchars($imageFile) ?>" alt="<?= htmlspecialchars($catName) ?>" style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;">
                    <div class="category-attribution mt-2" style="font-size: 0.9em; color: #555;">
                        <?= htmlspecialchars($catName) ?>
                    </div>
                </div>
            <?php endif; endforeach; ?>
        </div>

        <form method="GET" class="mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="categorie" class="form-label">Catégorie :</label>
                    <select name="categorie" id="categorie" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>" <?= ($selected_categorie == $categorie['id_categorie']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categorie['nom_categorie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="nom_objet" class="form-label">Nom de l'objet :</label>
                    <input type="text" id="nom_objet" name="nom_objet" class="form-control" value="<?= htmlspecialchars($search_nom) ?>" placeholder="Recherche par nom" />
                </div>
                <div class="col-auto form-check mt-4">
                    <input type="checkbox" id="disponible" name="disponible" class="form-check-input" <?= $search_disponible ? 'checked' : '' ?> onchange="this.form.submit()" />
                    <label for="disponible" class="form-check-label">Disponible</label>
                </div>
            </div>
        </form>

        <?php
        if (count($objets) > 0) {
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>Nom de l\'objet</th><th>Date de retour</th><th>Emprunt en cours</th><th>Action</th></tr></thead><tbody>';
            foreach ($objets as $row) {
                echo '<tr>';
                echo '<td><a href="fiche_objet.php?id_objet=' . htmlspecialchars($row["id_objet"]) . '">' . htmlspecialchars($row["nom_objet"]) . '</a></td>';
                echo '<td>' . ($row["date_retour"] ? htmlspecialchars($row["date_retour"]) : 'N/A') . '</td>';
                echo '<td>' . $row["emprunt_en_cours"] . '</td>';
                echo '<td><a href="emprunt_objet.php?id_objet=' . htmlspecialchars($row["id_objet"]) . '" class="btn btn-success btn-sm">Emprunter</a></td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "<p>Aucun objet trouvé.</p>";
        }
        ?>
            <div class="mt-4">
                <a href="ajout_objet.php" class="btn btn-primary">Ajouter objet</a>
            </div>
    </div>
</body>
</html>
