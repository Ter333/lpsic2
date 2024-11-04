
<?php
require_once('connectdb.php');

// Supposons que vous ayez déjà connecté à la base de données
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    try {
        $query = "DELETE FROM consultations WHERE id_consultation = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        // Renvoie une réponse JSON
        echo json_encode(['status' => 'success', 'message' => 'Consultation supprimée avec succès.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aucun ID fourni.']);
}
