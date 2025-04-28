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
    $subjects = implode(',', $_POST['subjects']); // Convertir en chaîne séparée par des virgules
    $fields = implode(',', $_POST['fields']); // Convertir en chaîne séparée par des virgules

    // Validation des données
    if ($password !== $confirmPassword) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertion dans la table `users`
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, first_name, last_name, email, gender) VALUES (?, ?, 'professor', ?, ?, ?, ?)");
    $stmt->execute([$email, $hashedPassword, $firstName, $lastName, $email, $gender]);

    // Récupération de l'ID de l'utilisateur
    $userId = $pdo->lastInsertId();

    // Insertion dans la table `professors`
    $stmt = $pdo->prepare("INSERT INTO professors (user_id, subjects, fields) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $subjects, $fields]);

    // Redirection vers la page de connexion
    header('Location: index.php');
    exit();
}

// Récupération des matières depuis la base de données
$subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();

// Liste des filières
$fieldsList = ['DSE', 'DS', 'SDRO', 'STAT ECO', 'DEMO', 'AF'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Professeur</title>
    <style>
        /* Style général */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            margin: 0;
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
        .register-container select,
        .register-container textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 1rem;
            background-color: #FFFFFF;
        }

        /* Cases à cocher pour les filières */
        .fields-section {
            text-align: left;
            margin-bottom: 1rem;
        }

        .fields-section h4 {
            margin-bottom: 0.5rem;
            color: #FFFFFF;
        }

        .fields-section label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333333;
        }

        /* Sélection multiple des matières */
        .register-container select[multiple] {
            height: 100px;
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
    </style>
</head>
<body>
    <!-- Logo centré sur la page -->
    <img src="logo.png" alt="Logo de l'institut" class="page-logo">

    <!-- Conteneur du formulaire -->
    <div class="register-container">
        <h2>Inscription Professeur</h2>

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

            <!-- Section des filières -->
            <div class="fields-section">
                <h4>Filières enseignées</h4>
                <?php foreach ($fieldsList as $field): ?>
                    <label>
                        <input type="checkbox" name="fields[]" value="<?= htmlspecialchars($field) ?>">
                        <?= htmlspecialchars($field) ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <!-- Sélection des matières -->
            <select name="subjects[]" multiple required>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= htmlspecialchars($subject['name']) ?>"><?= htmlspecialchars($subject['name']) ?></option>
                <?php endforeach; ?>
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