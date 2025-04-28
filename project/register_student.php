<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $field = $_POST['field'];
    $level = $_POST['level'];

    // Validation des données
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header('Location: register_student.php');
        exit();
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['error'] = "Cette adresse email est déjà utilisée.";
        header('Location: register_student.php');
        exit();
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertion dans la table `users`
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, first_name, last_name, email, gender) VALUES (?, ?, 'student', ?, ?, ?, ?)");
        $stmt->execute([$email, $hashedPassword, $firstName, $lastName, $email, $gender]);

        // Récupération de l'ID de l'utilisateur
        $userId = $pdo->lastInsertId();

        // Insertion dans la table `students`
        $stmt = $pdo->prepare("INSERT INTO students (user_id, field, level) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $field, $level]);

        // Redirection vers la page de connexion
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Une erreur s'est produite lors de l'inscription.";
        header('Location: register_student.php');
        exit();
    }
}

// Récupération des filières depuis la base de données
$fields = $pdo->query("SELECT * FROM fields")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étudiant</title>
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

        /* Logo */
        .page-logo {
            width: 150px;
            height: auto;
            margin-bottom: 1.5rem;
        }

        /* Conteneur du formulaire */
        .register-container {
            background: linear-gradient(to bottom, #00630D, #FFFFFF);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Titre */
        .register-container h2 {
            margin-bottom: 1.5rem;
            color: #FFFFFF;
        }

        /* Champs de formulaire */
        .register-container input,
        .register-container select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 1rem;
            background-color: #FFFFFF;
        }

        /* Bouton d'inscription */
        .register-container button {
            width: 100%;
            padding: 0.75rem;
            background-color: #00630D;
            color: #FFFFFF;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .register-container button:hover {
            background-color: #004309;
        }

        /* Lien de retour */
        .back-link {
            margin-top: 1rem;
            text-align: center;
        }

        .back-link a {
            color: #00630D;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Message d'erreur */
        .error-message {
            color: #ff0000;
            margin-bottom: 1rem;
            background-color: #FFFFFF;
            padding: 0.5rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Logo centré sur la page -->
    <img src="logo.png" alt="Logo de l'institut" class="page-logo">

    <!-- Conteneur du formulaire -->
    <div class="register-container">
        <h2>Inscription Étudiant</h2>

        <!-- Affichage des erreurs -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form method="POST">
            <input type="text" name="first_name" placeholder="Prénom" required>
            <input type="text" name="last_name" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            <select name="gender" required>
                <option value="male">Homme</option>
                <option value="female">Femme</option>
                <option value="other">Autre</option>
            </select>
            <select name="field" required>
                <option value="" disabled selected>Choisissez votre filière</option>
                <?php foreach ($fields as $field): ?>
                    <option value="<?= htmlspecialchars($field['name']) ?>"><?= htmlspecialchars($field['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="level" required>
                <option value="1">1ére année</option>
                <option value="2">2éme année</option>
                <option value="3">3éme année</option>
            </select>
            <button type="submit">S'inscrire</button>
        </form>

        <!-- Lien de retour à la page de connexion -->
        <div class="back-link">
            <a href="index.php">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>