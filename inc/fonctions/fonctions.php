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

function getListeObjets() {
    $conn = getDbConnection();

    $sql = "SELECT o.id_objet, o.nom_objet, e.date_retour, 
            CASE 
                WHEN e.date_retour IS NULL OR e.date_retour >= CURDATE() THEN 'Oui' 
                ELSE 'Non' 
            END AS emprunt_en_cours
            FROM objet o
            LEFT JOIN emprunt e ON o.id_objet = e.id_objet
            ORDER BY o.nom_objet";

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
?>
