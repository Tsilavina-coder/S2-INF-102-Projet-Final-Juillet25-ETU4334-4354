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
?>
