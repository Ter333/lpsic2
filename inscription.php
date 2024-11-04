<?php
session_start();
require_once('connectdb.php'); 

@$valider = $_POST['valider'];

if (isset($valider)) {
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $password =  isset($_POST['mot_de_passe']) ? htmlspecialchars($_POST['mot_de_passe']) : '';
    $repassword = isset($_POST['confirm_mot_de_passe']) ? htmlspecialchars($_POST['confirm_mot_de_passe']) : '';
    $role =  isset($_POST['role']) ? htmlspecialchars($_POST['role']) : '';
    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '';
    $error = "";


// Vérification de l'email existant
    $queryCheckEmail = "SELECT COUNT(*) FROM Utilisateurs WHERE email = :email";
    $stmtCheckEmail = $pdo->prepare($queryCheckEmail);
    $stmtCheckEmail->execute([':email' => $email]);
    $emailExists = $stmtCheckEmail->fetchColumn();
    
    if ($emailExists) {
        $error = "L'email existe déjà. Veuillez utiliser un autre email";
    } elseif ($password === $repassword && !$emailExists) {
        $error = "";
        // Hachage du mot de passe directement ici
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
         // Insertion des données utilisateur dans la base de données
        $query = "INSERT INTO Utilisateurs (email, mot_de_passe, role, nom, prenom) VALUES (:email, :mot_de_passe, :role, :nom, :prenom)";
        $stmt = $pdo->prepare($query);

        try {
            $stmt->execute([
                ':email' => $email,
                ':mot_de_passe' => hash('sha256', $password), // Hachage du mot de passe directement ici
                ':role' => $role,
                ':nom' => $nom,
                ':prenom' => $prenom
            ]);

            $_SESSION['flag'] = True;
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $email;

            header('Location: index.php');
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }else{
        $error = "Les mots de passe ne correspondent pas.";
    }
}
   


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Inscription</h2>
        <?php if (!empty($error)){ ?>
            <div class="alert alert-danger" role="alert" style="color: red; background-color: #f8d7da; border-color: #f5c6cb;">
                <div> <?=@$error?> </div>
            </div>
        <?php } ?>

        <form method="POST" action="" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo @$email ?>" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" value="<?php echo @$password ?>" required>
            </div>
            <div class="mb-3">
                <label for="confirm_mot_de_passe" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_mot_de_passe" name="confirm_mot_de_passe" value="<?php echo @$repassword ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select" id="role" name="role">
                    <option value="user">Patient</option>
                    <option value="admin">Médecin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo @$nom ?>" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo @$prenom ?>" required>
            </div>
            <button type="submit" name="valider" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="mt-3 text-center">
            Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.
        </p>
    </div>
</body>
</html>
