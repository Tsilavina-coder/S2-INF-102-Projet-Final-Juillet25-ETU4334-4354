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

function getListeObjets($id_categorie = null, $nom_objet = null, $disponible = null) {
    $conn = getDbConnection();

    $sql = "SELECT o.id_objet, o.nom_objet, o.image_objet, c.nom_categorie,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM emprunt e2 
                    WHERE e2.id_objet = o.id_objet 
                    AND e2.date_retour >= CURDATE()
                ) THEN 'Non'
                ELSE 'Oui'
            END AS emprunt_en_cours,
            (SELECT MIN(e3.date_retour) FROM emprunt e3 WHERE e3.id_objet = o.id_objet AND e3.date_retour >= CURDATE()) AS date_retour
            FROM objet o
            LEFT JOIN categorie_objet c ON o.id_categorie = c.id_categorie";

    $conditions = [];

    if ($id_categorie !== null && $id_categorie !== '') {
        $id_categorie = (int)$id_categorie;
        $conditions[] = "o.id_categorie = $id_categorie";
    }

    if ($nom_objet !== null && $nom_objet !== '') {
        $nom_objet_escaped = $conn->real_escape_string($nom_objet);
        $conditions[] = "o.nom_objet LIKE '%$nom_objet_escaped%'";
    }

    if ($disponible !== null) {
        if ($disponible) {
            // Exclude objects currently borrowed
            $conditions[] = "NOT EXISTS (
                SELECT 1 FROM emprunt e 
                WHERE e.id_objet = o.id_objet 
                AND e.date_retour >= CURDATE()
            )";
        } else {
            $conditions[] = "EXISTS (
                SELECT 1 FROM emprunt e 
                WHERE e.id_objet = o.id_objet 
                AND e.date_retour >= CURDATE()
            )";
        }
    }

    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
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
function getObjetDetails($id_objet) {
    $conn = getDbConnection();
    $id_objet = (int)$id_objet;

    $sql = "SELECT o.id_objet, o.nom_objet, o.image_objet, o.id_categorie, c.nom_categorie
            FROM objet o
            LEFT JOIN categorie_objet c ON o.id_categorie = c.id_categorie
            WHERE o.id_objet = $id_objet";

    $result = $conn->query($sql);
    $objet = null;
    if ($result) {
        $objet = $result->fetch_assoc();
    }
    $conn->close();
    return $objet;
}

function getHistoriqueEmprunts($id_objet) {
    $conn = getDbConnection();
    $id_objet = (int)$id_objet;

    $sql = "SELECT e.id_emprunt, e.date_emprunt, e.date_retour, m.nom AS nom_membre
            FROM emprunt e
            LEFT JOIN membre m ON e.id_membre = m.id_membre
            WHERE e.id_objet = $id_objet
            ORDER BY e.date_emprunt DESC";

    $result = $conn->query($sql);
    $historique = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $historique[] = $row;
        }
    }
    $conn->close();
    return $historique;
}
function getDateDisponibiliteProche($id_objet) {
    $conn = getDbConnection();
    $id_objet = (int)$id_objet;

    $sql = "SELECT MIN(date_retour) AS date_disponible
            FROM emprunt
            WHERE id_objet = $id_objet
            AND date_retour >= CURDATE()";

    $result = $conn->query($sql);
    $date_disponible = null;
    if ($result) {
        $row = $result->fetch_assoc();
        $date_disponible = $row['date_disponible'];
    }
    $conn->close();
    return $date_disponible;
}
?>
