<?php
function getDbConnection() {
    $servername = "localhost";
    $username = "root";
    $password = ""; // à adapter selon votre configuration
    $dbname = "emprunt";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connexion échouée : " . $conn->connect_error);
    }
    return $conn;
}

function getListeObjets($id_categorie = null) {
    $conn = getDbConnection();

    $sql = "SELECT o.id_objet, o.nom_objet, e.date_retour, 
            CASE 
                WHEN e.date_retour IS NULL OR e.date_retour >= CURDATE() THEN 'Oui' 
                ELSE 'Non' 
            END AS emprunt_en_cours
            FROM objet o
            LEFT JOIN emprunt e ON o.id_objet = e.id_objet";

    if ($id_categorie !== null && $id_categorie !== '') {
        $id_categorie = (int)$id_categorie;
        $sql .= " WHERE o.id_categorie = $id_categorie";
    }

    $sql .= " ORDER BY o.nom_objet";

    $result = $conn->query($sql);

    $objets = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Format date_retour to JJ/MM/AAAA if not null
            if (!empty($row['date_retour'])) {
                $date = DateTime::createFromFormat('Y-m-d', $row['date_retour']);
                if ($date !== false) {
                    $row['date_retour'] = $date->format('d/m/Y');
                }
            }
            $objets[] = $row;
        }
    }
    $conn->close();
    return $objets;
}

function getCategories() {
    $conn = getDbConnection();

    $sql = "SELECT id_categorie, nom_categorie FROM categorie_objet ORDER BY nom_categorie";

    $result = $conn->query($sql);

    $categories = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    $conn->close();
    return $categories;
}
function getUserProfile($id_membre) {
    $conn = getDbConnection();

    $id_membre = (int)$id_membre;
    // Try to select image field, handle error if column does not exist
    $sql = "SELECT id_membre, nom, email, 
            (SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'membre' AND COLUMN_NAME = 'image') AS has_image
            FROM membre WHERE id_membre = $id_membre";

    $result = $conn->query($sql);

    $profile = null;
    if ($result) {
        $profile = $result->fetch_assoc();
        if ($profile['has_image']) {
            // If image column exists, fetch image value
            $sql_img = "SELECT image FROM membre WHERE id_membre = $id_membre";
            $res_img = $conn->query($sql_img);
            if ($res_img) {
                $img_row = $res_img->fetch_assoc();
                $profile['image'] = $img_row['image'];
            }
        } else {
            $profile['image'] = null;
        }
    }
    $conn->close();
    return $profile;
}
?>
