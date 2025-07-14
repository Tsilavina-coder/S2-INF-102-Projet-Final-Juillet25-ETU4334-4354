<?php
session_start();
if (!isset($_SESSION['id_membre'])) {
    header('Location: ../Login/Index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un nouvel objet</title>
    <link rel="stylesheet" href="../CSS/Style.css" />
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un nouvel objet</h2>
        <form action="trait_ajout_objet.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nom_objet" class="form-label">Nom de l'objet :</label>
                <input type="text" id="nom_objet" name="nom_objet" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="image_objet" class="form-label">Image de l'objet :</label>
                <input type="file" id="image_objet" name="image_objet" accept="image/jpeg,image/png" required />
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
        <p><a href="listeobj.php">Retour à la liste des objets</a></p>
    </div>
</body>
</html>
