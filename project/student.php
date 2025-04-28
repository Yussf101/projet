<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit();
}

// Récupérer la date de la dernière modification du QCM
$lastModified = $pdo->query("SELECT MAX(last_modified) AS last_modified FROM questions")->fetch()['last_modified'];

// Récupérer la date de la dernière tentative de l'étudiant
$stmt = $pdo->prepare("SELECT last_attempt FROM results WHERE student_id = ? ORDER BY last_attempt DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$lastAttempt = $stmt->fetch()['last_attempt'];

// Vérifier si l'étudiant peut refaire le QCM
$canRetake = false;
if (!$lastAttempt || $lastModified > $lastAttempt) {
    $canRetake = true;
}
// Si l'étudiant ne peut pas refaire le QCM, redirigez-le vers une page d'information
if (!$canRetake) {
    // Afficher un message stylisé
    echo '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>QCM déjà passé</title>
        <style>
            /* Style général */
            body {
                font-family: "Arial", sans-serif;
                background-color: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            /* Conteneur du message */
            .message-container {
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 400px;
                animation: fadeIn 1s ease;
            }

            /* Icône */
            .message-container .icon {
                font-size: 3rem;
                color: #ffc107;
                margin-bottom: 1rem;
            }

            /* Titre */
            .message-container h2 {
                margin-bottom: 1rem;
                color: #333333;
                font-size: 1.5rem;
            }

            /* Texte */
            .message-container p {
                color: #555555;
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }

            /* Bouton de retour */
            .message-container a {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background-color: #007bff;
                color: #ffffff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.2s ease;
            }

            .message-container a:hover {
                background-color: #0056b3;
            }

            /* Animation dapparition */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <!-- Conteneur du message -->
        <div class="message-container">
            <!-- Icône -->
            <div class="icon">📚</div>

            <!-- Titre -->
            <h2>Merci d\'avoir participé !</h2>

            <!-- Texte -->
            <p>Vous avez déjà passé ce QCM. Vous ne pouvez le passer qu\'une seule fois.</p>

            <!-- Bouton de retour -->
            <a href="index.php">Retour à l\'accueil</a>
        </div>
    </body>
    </html>
    ';
    exit();
}

// Récupérer les questions depuis la base de données
$questions = $pdo->query("SELECT * FROM questions")->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCM de Géographie</title>
    <style>
        /* Style général */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            margin: 0;
        }

        /* Conteneur du QCM */
        .qcm-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        /* Titre */
        .qcm-container h2 {
            margin-bottom: 2rem;
            color: #28a745;
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Question */
        .question {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .question:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .question h3 {
            margin-bottom: 1rem;
            color: #333333;
            font-size: 1.25rem;
        }

        /* Options */
        .options label {
            display: block;
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .options label:hover {
            background-color: #f1f1f1;
            border-color: #28a745;
        }

        .options input[type="radio"] {
            margin-right: 0.75rem;
        }

        /* Bouton de soumission */
        .qcm-container button {
            width: 100%;
            padding: 1rem;
            background-color: #28a745;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .qcm-container button:hover {
            background-color: #218838;
        }

        /* Animation pour le bouton */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .qcm-container button:active {
            animation: pulse 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Conteneur du QCM -->
    <div class="qcm-container">
        <h2>QCM</h2>

        <!-- Formulaire du QCM -->
        <form method="POST" action="submit_qcm.php">
            <?php foreach ($questions as $index => $question): ?>
                <div class="question">
                    <h3>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['question_text']) ?></h3>
                    <div class="options">
                        <label>
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="a" required>
                            <?= htmlspecialchars($question['option_a']) ?>
                        </label>
                        <label>
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="b">
                            <?= htmlspecialchars($question['option_b']) ?>
                        </label>
                        <label>
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="c">
                            <?= htmlspecialchars($question['option_c']) ?>
                        </label>
                        <label>
                            <input type="radio" name="answers[<?= $question['id'] ?>]" value="d">
                            <?= htmlspecialchars($question['option_d']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit">Soumettre</button>
        </form>
    </div>
</body>
</html>