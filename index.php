<?php
session_start();
require_once('connectdb.php');

if (!isset($_SESSION['flag'])) {
    header('Location: login.php');
    exit;
}

// Requête pour récupérer toutes les consultations avec les informations des patients et des médecins
$query = "SELECT c.id_consultation AS id_consultation, c.date_consultation, p.nom AS patient_nom, p.prenom AS patient_prenom, p.email AS patient_email, 
                m.nom AS medecin_nom, m.prenom AS medecin_prenom 
          FROM consultations c
          JOIN patients p ON c.id_patient = p.id_patient 
          JOIN medecins m ON c.id_medecin = m.id_medecin";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            position: relative;
            padding: 10px;
            padding-bottom: 60px;
        }
        .search-bar-container {
            width: 300px;
        }
        .suggestion {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            z-index: 10;
            display: none;
            max-height: 200px;
            overflow-y: auto;
        }
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        /* Style du bouton de retour en haut */
        #backToTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }

        #backToTop:hover {
            background-color: #0056b3;
        }
    </style>


</head>
<body>
    <h1 class="mb-4">Gestion des Consultations</h1>

    <div class="mb-4 position-sticky search-bar-container" style="top: 0; z-index: 10; background-color: white; padding-top: 10px;">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un nom ou un prénom">
        <div id="suggestions" class="suggestion"></div>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="deconnexion.php" class="btn btn-danger">Déconnexion</a>
    </div>


    <?php if ($_SESSION['role'] == "admin"){ ?>
        <div class="d-flex justify-content-end mb-4">
            <a href="enre.php" class="btn btn-primary">Nouvelle Enregistrement</a>
        </div>
    <?php } ?>
            <!-- Liste des consultations -->
        <div class="list-group" id="consultationList">
            <?php foreach ($consultations as $consultation): ?>
                <div class="list-group-item consultation-item" id="consultation_<?php echo $consultation['id_consultation']; ?>">
                    <h5><?php echo htmlspecialchars($consultation['patient_nom'] . " " . $consultation['patient_prenom']); ?></h5>
                    <p>Médecin : <?php echo htmlspecialchars($consultation['medecin_nom'] . " " . $consultation['medecin_prenom']); ?></p>
                    <p>Date de consultation : <?php echo htmlspecialchars($consultation['date_consultation']); ?></p>
                    
                    <?php if ($_SESSION['role'] == "admin"){ ?>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-info btn-sm" onclick="showDetails(<?php echo $consultation['id_consultation']; ?>, this)">Détails</button>
                    <?php }elseif ($_SESSION['role'] == "user" && $_SESSION['email'] == $consultation['patient_email']){ ?>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-info btn-sm" onclick="showDetails(<?php echo $consultation['id_consultation']; ?>, this)">Détails</button>
                        </div>
                    <?php } ?>
                    
                    <?php if ($_SESSION['role'] == "admin"){ ?>
                            <button class="btn btn-danger btn-sm" onclick="deleteConsultation(<?php echo $consultation['id_consultation']; ?>)">Supprimer</button>
                        </div>
                    <?php } ?>

                    <?php if ($_SESSION['role'] == "admin" || $_SESSION['email'] == $consultation['patient_email']){ ?>
                        <!-- Conteneur de détails masqué au départ -->
                        <div class="consultation-details mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-body d-flex flex-column">
                                    <h5 id="patient-name-<?php echo $consultation['id_consultation']; ?>"></h5>
                                    <p><strong>Médecin :</strong> <span id="medecin-name-<?php echo $consultation['id_consultation']; ?>"></span></p>
                                    <p><strong>Taille :</strong> <span id="patient-height-<?php echo $consultation['id_consultation']; ?>"></span> cm</p>
                                    <p><strong>Poids :</strong> <span id="patient-weight-<?php echo $consultation['id_consultation']; ?>"></span> kg</p>
                                    <p><strong>Âge :</strong> <span id="patient-age-<?php echo $consultation['id_consultation']; ?>"></span> ans</p>
                                    <p><strong>Problème de Santé :</strong> <span id="patient-problem-<?php echo $consultation['id_consultation']; ?>"></span></p>
                                    <p><strong>Solution :</strong> <span id="patient-solution-<?php echo $consultation['id_consultation']; ?>"></span></p>
                                    
                                    <!-- Conteneur de l'image placé en bas à gauche -->
                                    <div class="d-flex justify-content-start mt-4">
                                        <div class="image-container">
                                            <img id="patient-photo-<?php echo $consultation['id_consultation']; ?>" src="" alt="Photo du patient">
                                        </div>
                                    </div>
                                    <button class="btn btn-secondary mt-3 align-self-end" onclick="$(this).closest('.consultation-details').hide();">Fermer</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>


                </div>
            <?php endforeach; ?>
        </div>
    </div>


<style>
    /* Style pour le conteneur d'image */
    .image-container {
        width: 150px; /* Largeur fixe pour le conteneur */
        height: 150px; /* Hauteur fixe pour le conteneur */
        overflow: hidden; /* Masque les parties de l'image qui dépassent */
    }

    /* Style pour l'image elle-même */
    .image-container img {
        width: 100%; /* Adapte l'image à la largeur du conteneur */
        height: auto; /* Garde le ratio de l'image */
    }
</style>



<!-- Bouton Retour en haut -->
<button id="backToTop" onclick="scrollToTop()" aria-label="Retour en haut">
    <i class="fas fa-arrow-up"></i>
</button>


<script>

// Afficher le bouton quand on défile vers le bas
window.onscroll = function() {
        let backToTopButton = document.getElementById("backToTop");
        if (document.documentElement.scrollTop > 200) { // 200 pixels avant d'afficher
            backToTopButton.style.display = "block";
        } else {
            backToTopButton.style.display = "none";
        }
    };

    // Fonction de défilement vers le haut
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }


$(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        console.log("Recherche pour :", value);

        $("#suggestions").empty();
        
        if (value.length > 0) {
            $.ajax({
                url: 'search_consultations.php',
                type: 'POST',
                data: { query: value },

                success: function(response) {
                    const results = JSON.parse(response);
                    console.log("Réponse AJAX reçue :", response); // Débogage : afficher la réponse brute

                    try {
                        results.forEach(result => {
                            const role = 'Consultation'; // Vous pouvez ajuster cela si vous souhaitez inclure un rôle spécifique
                            $("#suggestions").append(`
                                <div class="suggestion-item" onclick="goToConsultation(${result.id_consultation})">
                                    ${result.patient_nom} ${result.patient_prenom} - Médecin : ${result.medecin_nom} ${result.medecin_prenom}
                                </div>
                            `);
                        });
                        $("#suggestions").show();
                    } catch (e) {
                        console.error("Erreur de parsing JSON :", e); // Débogage : afficher l'erreur de parsing si elle existe
                    }

                },
                error: function(xhr, status, error) {
                    console.error("Erreur AJAX :", status, error);
                }
            });
        } else {
            $("#suggestions").hide();
        }
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#searchInput, #suggestions').length) {
            $("#suggestions").hide();
        }
    });
});




function goToConsultation(id) {
    console.log("Redirection vers consultation ID :", id);
    // Faites défiler vers l'élément de consultation
    const consultationElement = document.getElementById(`consultation_${id}`);
    if (consultationElement) {
        consultationElement.scrollIntoView({ behavior: 'smooth' });
    }
}





function showDetails(id, element) {
    const detailsContainer = $(element).closest('.list-group-item').find('.consultation-details');

    $.ajax({
        url: 'get_detail.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            const data = JSON.parse(response);

            // Remplissage des informations dans le conteneur spécifique
            $('#patient-name-' + id).text(data.patient_nom + " " + data.patient_prenom);
            $('#medecin-name-' + id).text(data.medecin_nom + " " + data.medecin_prenom);
            $('#patient-height-' + id).text(data.taille);
            $('#patient-weight-' + id).text(data.poids);
            $('#patient-age-' + id).text(data.age);
            $('#patient-problem-' + id).text(data.probleme_sante);
            $('#patient-solution-' + id).text(data.solution);
            $('#patient-photo-' + id).attr('src', data.photo).show();

            detailsContainer.show();
        },
        error: function(xhr, status, error) {
            console.error("Erreur AJAX showDetails :", error);
        }
    });
}


function deleteConsultation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette consultation ?')) {
        $.ajax({
            url: 'delete_consultation.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload(true);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX deleteConsultation :", error);
                alert("Une erreur est survenue lors de la suppression.");
            }
        });
    }
}





</script>
</body>
</html>
