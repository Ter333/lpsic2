<?php
// Connexion à la base de données
session_start();
// echo " bien <br>";
// print_r($_POST); 
// echo " <br> file <br>";
// print_r($_FILES);

require_once('connectdb.php');

// Récupérer les données du formulaire
$medecin_nom = isset($_POST['medecin_nom']) ? htmlspecialchars($_POST['medecin_nom']) : '';
$medecin_prenom = isset($_POST['medecin_prenom']) ? htmlspecialchars($_POST['medecin_prenom']) : '';
$patient_nom =  isset($_POST['patient_nom']) ? htmlspecialchars($_POST['patient_nom']) : '';
$patient_prenom =  isset($_POST['patient_prenom']) ? htmlspecialchars($_POST['patient_prenom']) : '';
$patient_email =  isset($_POST['patient_email']) ? htmlspecialchars($_POST['patient_email']) : '';
$patient_taille =  isset($_POST['patient_taille']) ? htmlspecialchars($_POST['patient_taille']) : '';
$patient_poids =  isset($_POST['patient_poids']) ? htmlspecialchars($_POST['patient_poids']) : '';
$patient_age = isset($_POST['patient_age']) ? htmlspecialchars($_POST['patient_age']) : '';
$date_consultation =  isset($_POST['consultation_date']) ? htmlspecialchars($_POST['consultation_date']) : '';
$probleme =  isset($_POST['probleme']) ? htmlspecialchars($_POST['probleme']) : '';
$solution =  isset($_POST['solution']) ? htmlspecialchars($_POST['solution']) : '';


// print_r($_POST);
// echo '<br>';
// echo '<br>';
// print_r($_FILES);

if (isset($_FILES['patient_photo']) && isset($_POST['valide']) && $_FILES['patient_photo']['error'] === UPLOAD_ERR_OK && $_POST['valide'] === 'enregistrer') {
    $upload_dir = 'images/'; // Dossier de destination

    // Vérifier et créer le dossier si nécessaire
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $photo_name = basename($_FILES['patient_photo']['name']); // le nom de l'image
    $photo_tmp = $_FILES['patient_photo']['tmp_name']; // le nom temporaire
    $extension = pathinfo($photo_name, PATHINFO_EXTENSION); // l'extension

    // Générer un nom unique pour l'image
    $new_photo_name = pathinfo($photo_name, PATHINFO_FILENAME) . "." . $extension;
    $increment = 1;

    while (file_exists($upload_dir . $new_photo_name)) {
        $new_photo_name = pathinfo($photo_name, PATHINFO_FILENAME) . "_" . $increment . "." . $extension;
        $increment++;
    }

    // Déplacement de la photo vers le dossier final
    if (move_uploaded_file($photo_tmp, $upload_dir . $new_photo_name)) {
        echo "Image uploaded successfully: " . $new_photo_name;
    } else {
        die("Erreur lors du chargement de l'image.");
    }
} 






// Gestion de l'upload de la photo

if (isset($_POST['valide']) && $_POST['valide'] === 'enregistrer') {
    // Récupération des données du formulaire
    $patient_email = $_POST['patient_email'];
    $medecin_email = $_POST['medecin_email'];
    
    // Vérification et récupération de l'ID du médecin
    $stmt = $pdo->prepare("SELECT id_medecin FROM medecins WHERE email = :email");
    $stmt->execute(['email' => $medecin_email]);
    $medecin = $stmt->fetch();
    
    if (!$medecin) {
        // Si le médecin n'est pas dans la table `medecins`, on vérifie dans `utilisateurs`
        $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = :email AND role = 'admin'");
        $stmt->execute(['email' => $medecin_email]);
        $utilisateur_medecin = $stmt->fetch();
        
        if ($utilisateur_medecin) {
            // Ajout du médecin dans `medecins`
            $stmt = $pdo->prepare("INSERT INTO medecins (nom, prenom, email) VALUES (:nom, :prenom, :email)");
            $stmt->execute([
                'nom' => $medecin_nom,
                'prenom' => $medecin_prenom,
                'email' => $medecin_email
            ]);
            $id_medecin = $pdo->lastInsertId();
        } else {
            echo "Ce médecin n'existe pas dans la base de données. <a href='inscription.php'>Inscrire un utilisateur</a>";
            exit;
        }
    } else {
        $id_medecin = $medecin['id'];
    }
    
    // Vérification et récupération de l'ID du patient
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE email = :email");
    $stmt->execute(['email' => $patient_email]);
    $patient = $stmt->fetch();
    
    if (!$patient) {
        // Si le patient n'est pas dans la table `patients`, on vérifie dans `utilisateurs`
        $stmt = $pdo->prepare("SELECT id_patient FROM utilisateurs WHERE email = :email AND role = 'user'");
        $stmt->execute(['email' => $patient_email]);
        $utilisateur_patient = $stmt->fetch();
        
        if ($utilisateur_patient) {
            // Ajout du patient dans `patients`
            $stmt = $pdo->prepare("INSERT INTO patients (nom, prenom, email, taille, poids, age, photo) VALUES (:nom, :prenom, :email, :taille, :poids, :age, :photo)");
            $stmt->execute([
                'nom' => $patient_nom,
                'prenom' => $patient_prenom,
                'email' => $patient_email,
                'taille' => $patient_taille,
                'poids' => $patient_poids,
                'age' => $patient_age,
                'photo' => $new_photo_name
            ]);
            $id_patient = $pdo->lastInsertId();
        } else {
            echo "Cet utilisateur n'est pas enregistré comme patient. <a href='inscription.php'>Inscrire un utilisateur</a>";
            exit;
        }
    } else {
        $id_patient = $patient['id'];
    }

    // Insertion de la consultation
    $stmt = $pdo->prepare("INSERT INTO consultations (date_consultation, id_patient, id_medecin, probleme, solution) VALUES (:date_consultation, :id_patient, :id_medecin, :probleme, :solution)");
    $stmt->execute([
        'date_consultation' => $date_consultation,
        'id_patient' => $id_patient,
        'id_medecin' => $id_medecin,
        'probleme' => $probleme,
        'solution' => $solution
    ]);

    echo "Consultation enregistrée avec succès !";
}

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement d'une Consultation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Enregistrement d'une Consultation</h2>
        
        <!-- Section Médecin -->
        <form action="enre.php" method="post" enctype="multipart/form-data">

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Informations du Médecin
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="medecinNom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="medecinNom" name="medecin_nom" placeholder="Nom du médecin" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="medecinPrenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="medecinPrenom" name="medecin_prenom" placeholder="Prénom du médecin" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="medecinEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="medecinEmail" name="medecin_email" placeholder="Email du médecin" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Patient -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    Informations du Patient
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patientNom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="patientNom" name="patient_nom" placeholder="Nom du patient" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="patientPrenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="patientPrenom" name="patient_prenom" placeholder="Prénom du patient" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patientEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="patientEmail" name="patient_email" placeholder="Email du patient" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="patientTaille" class="form-label">Taille (cm)</label>
                            <input type="number" class="form-control" id="patientTaille" name="patient_taille" placeholder="Ex : 170" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="patientPoids" class="form-label">Taille (cm)</label>
                            <input type="number" class="form-control" id="patientPoids" name="patient_poids" placeholder="Ex : 50" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="patientAge" class="form-label">Âge</label>
                            <input type="number" class="form-control" id="patientAge" name="patient_age" placeholder="Ex : 30" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="patientPhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="patientPhoto" name="patient_photo">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Consultation -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    Détails de la Consultation
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="consultationDate" class="form-label">Date et Heure de Consultation</label>
                        <input type="datetime-local" class="form-control" id="consultationDate" name="consultation_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="consultationProbleme" class="form-label">Problème</label>
                        <textarea class="form-control" id="consultationProbleme" name="probleme" rows="3" placeholder="Décrivez le problème" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="consultationSolution" class="form-label">Solution</label>
                        <textarea class="form-control" id="consultationSolution" name="solution" rows="3" placeholder="Décrivez la solution proposée" required></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Boutons Enregistrer et Annuler -->
            <div class="text-center">
                <button type="submit"  name="valide" value="enregistrer" class="btn btn-primary btn-lg">Enregistrer</button>
                <button type="button" onclick="location.href='accueil.php'" name="solution" class="btn btn-secondary btn-lg" >Annuler</button>
            </div>
        </form>
        
    <!-- </div>onclick="location.href='accueil.php'" -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
