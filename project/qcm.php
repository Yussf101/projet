<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit();
}
// Récupérer l'ID du professeur sélectionné
$professorId = $_POST['professor_id'];

// Récupérer les questions du professeur
$questions = $pdo->prepare("SELECT * FROM questions WHERE professor_id = ?");
$questions->execute([$professorId]);
$questions = $questions->fetchAll();
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
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Conteneur du QCM */
        .qcm-container {
            background: linear-gradient(to bottom, #00630D, #FFFFFF);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: left;
        }

        /* Titre */
        .qcm-container h2 {
            margin-bottom: 1.5rem;
            color: #FFFFFF;
            text-align: center;
        }

        /* Question */
        .question {
            margin-bottom: 1.5rem;
        }

        .question h3 {
            margin-bottom: 0.5rem;
            color: #333333;
        }

        /* Options */
        .options label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555555;
        }

        /* Bouton de soumission */
        .qcm-container button {
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

        .qcm-container button:hover {
            background-color: #004309;
        }
    </style>
</head>
<body>
    <!-- Conteneur du QCM -->
    <div class="qcm-container">
        <h2>QCM de Géographie</h2>

        <!-- Formulaire du QCM -->
        <form method="POST" action="submit_qcm.php">
            <?php foreach ($questions as $question): ?>
                <div class="question">
                    <h3><?= htmlspecialchars($question['question_text']) ?></h3>
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