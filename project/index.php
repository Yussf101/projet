<?php
session_start(); // Démarre la session
include 'config.php'; // Inclut la configuration de la base de données

// Gestion des erreurs de connexion
$error = '';
if (isset($_GET['error'])) {
    $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /* Style général */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Logo centré sur la page */
        .page-logo {
            width: 150px; /* Taille du logo */
            height: auto;
            margin-bottom: 1.5rem;
        }

        /* Conteneur du formulaire */
        .login-container {
            background: linear-gradient(to bottom, #00630D, #FFFFFF); /* Dégradé de vert à blanc */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Titre */
        .login-container h2 {
            margin-bottom: 1.5rem;
            color: #FFFFFF; /* Texte en blanc pour contraster avec le vert */
        }

        /* Champs de formulaire */
        .login-container input,
        .login-container select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 1rem;
            background-color: #FFFFFF; /* Fond blanc pour les champs */
        }

        /* Boutons */
        .login-container button {
            width: 100%;
            padding: 0.75rem;
            background-color: #00630D; /* Vert foncé */
            color: #FFFFFF; /* Texte en blanc */
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Animation de survol */
        }

        .login-container button:hover {
            background-color: #004309; /* Vert plus foncé au survol */
        }

        /* Liens d'inscription */
        .register-links {
            margin-top: 1rem;
            text-align: center;
        }

        .register-links a {
            color: #00630D; /* Vert foncé */
            text-decoration: none;
            font-weight: bold;
        }

        .register-links a:hover {
            text-decoration: underline;
        }

        /* Message d'erreur */
        .error-message {
            color: #ff0000;
            margin-bottom: 1rem;
            background-color: #FFFFFF; /* Fond blanc pour le message d'erreur */
            padding: 0.5rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Logo centré sur la page -->
    <img src="logo.png" alt="Logo de l'institut" class="page-logo">

    <!-- Conteneur du formulaire -->
    <div class="login-container">
        <h2>Connexion</h2>

        <!-- Affichage des erreurs -->
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="POST" action="auth.php">
            <select name="role" required>
                <option value="student">Étudiant</option>
                <option value="professor">Professeur</option>
            </select>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>

        <!-- Liens d'inscription -->
        <div class="register-links">
            <p>Pas encore inscrit ?</p>
            <a href="register_student.php">Inscription Étudiant</a> |
            <a href="register_professor.php">Inscription Professeur</a>
        </div>
    </div>
</body>
</html>