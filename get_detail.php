<?php
require_once('connectdb.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $query = "SELECT m.nom AS medecin_nom, m.prenom AS medecin_prenom, 
                     p.nom AS patient_nom, p.prenom AS patient_prenom, 
                     p.taille, p.poids, p.age, p.photo, 
                     c.probleme AS probleme_sante, c.solution 
              FROM consultations c
              JOIN medecins m ON c.id_medecin = m.id_medecin
              JOIN patients p ON c.id_patient = p.id_patient
              WHERE c.id_consultation = :id";

    try{              
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit;
    }
    
    if ($consultation) {
        // Ici, nous renvoyons simplement le chemin de l'image au lieu de l'encodage base64
        echo json_encode($consultation);
    } else {
        echo json_encode([]);
    }
}
?>
