<?php

require_once('connectdb.php'); // Assurez-vous que ce fichier initialise correctement la connexion PDO dans $pdo

if (isset($_POST['query'])) {
    $query = $_POST['query'];

    // Requête pour récupérer les consultations en filtrant sur les noms et prénoms
    $stmt = $pdo->prepare("
        SELECT c.id_consultation AS id_consultation, 
               c.date_consultation, 
               p.nom AS patient_nom, 
               p.prenom AS patient_prenom, 
               m.nom AS medecin_nom, 
               m.prenom AS medecin_prenom 
        FROM consultations c
        JOIN patients p ON c.id_patient = p.id_patient 
        JOIN medecins m ON c.id_medecin = m.id_medecin
        WHERE p.nom LIKE :query 
           OR p.prenom LIKE :query
           OR m.nom LIKE :query 
           OR m.prenom LIKE :query
    ");

    // Utiliser un paramètre de type LIKE pour la recherche
    $likeQuery = "%" . $query . "%";
    $stmt->bindParam(':query', $likeQuery, PDO::PARAM_STR);

    // Exécuter la requête
    $stmt->execute();

    // Récupérer les résultats
    $consultations = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $consultations[] = $row; // Ajouter chaque résultat au tableau des consultations
    }

    // Encoder les consultations en JSON
    echo json_encode($consultations);
}
?>
