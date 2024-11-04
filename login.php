<?php
session_start();

require_once('connectdb.php');


if (isset($_POST['valider']) && $_POST['valider'] === 'connexion') {
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $password =  isset($_POST['mot_de_passe']) ? htmlspecialchars($_POST['mot_de_passe']) : '';
    $role =  isset($_POST['role']) ? htmlspecialchars($_POST['role']) : '';
    $error = "";


    // Recherche de l'utilisateur
    $query = "SELECT * FROM Utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();


    if ($user && hash('sha256', $password)===$user['mot_de_passe']) {
        $_SESSION['flag'] = True;
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        
        // Redirection vers la page d'accueil après une courte pause pour afficher les données
        header("Location: index.php");
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Connexion</h2>
        <?php if (!empty($error)){ ?>
            <div class="alert alert-danger" role="alert" style="color: red; background-color: #f8d7da; border-color: #f5c6cb;">
                <div> <?=@$error?> </div>
            </div>
        <?php } ?>
        <form method="POST" action="login.php" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-select" id="role" name="role">
                    <option value="user">Patient</option>
                    <option value="admin">Médecin</option>
                </select>
            </div>
            <button type="submit"  name="valider" value="connexion" class="btn btn-primary">Se connecter</button>
        </form>
        <p class="mt-3 text-center">
            Vous n'avez pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a>.
        </p>
    </div>
</body>
</html>
