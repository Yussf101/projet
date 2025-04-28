<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit();
}

// Récupérer les réponses soumises
$answers = $_POST['answers'];

// Récupérer les questions et les réponses correctes
$questions = $pdo->query("SELECT * FROM questions")->fetchAll();

// Calculer le score
$score = 0;
foreach ($answers as $questionId => $answer) {
    foreach ($questions as $question) {
        if ($question['id'] == $questionId && $answer === $question['correct_answer']) {
            $score++;
            break;
        }
    }
}

// Enregistrer le score dans la base de données
$stmt = $pdo->prepare("INSERT INTO results (student_id, score) VALUES (?, ?)");
$stmt->execute([$_SESSION['user_id'], $score]);

// Nombre total de questions
$totalQuestions = count($questions);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du QCM</title>
    <style>
        /* Style général */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            margin: 0;
        }

        /* Conteneur du résultat */
        .result-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }

        /* Titre */
        .result-container h2 {
            margin-bottom: 1.5rem;
            color: #007bff;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Score */
        .score {
            font-size: 3rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 1.5rem;
            animation: fadeIn 1s ease;
        }

        /* Message */
        .message {
            font-size: 1.25rem;
            color: #555555;
            margin-bottom: 2rem;
            animation: fadeIn 1.5s ease;
        }

        /* Réponses détaillées */
        .answers {
            text-align: left;
        }

        .answer-card {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .answer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .answer-card h4 {
            margin-bottom: 0.75rem;
            color: #333333;
            font-size: 1.25rem;
        }

        .answer-card p {
            margin-bottom: 0.5rem;
            color: #555555;
        }

        .answer-card .correct {
            color: #28a745;
            font-weight: bold;
        }

        .answer-card .incorrect {
            color: #dc3545;
            font-weight: bold;
        }

        /* Bouton de retour */
        .result-container a {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .result-container a:hover {
            background-color: #0056b3;
        }

        /* Animation d'apparition */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Conteneur du résultat -->
    <div class="result-container">
        <h2>Résultat du QCM</h2>

        <!-- Affichage du score -->
        <div class="score">
            Votre score : <?= $score ?> / <?= $totalQuestions ?>
        </div>

        <!-- Message personnalisé -->
        <div class="message">
            <?php
            if ($score == $totalQuestions) {
                echo "Félicitations, vous avez réussi toutes les questions ! 🎉";
            } elseif ($score >= ($totalQuestions / 2)) {
                echo "Bon travail, vous avez réussi plus de la moitié des questions. 👍";
            } else {
                echo "Continuez à vous entraîner, vous pouvez faire mieux ! 💪";
            }
            ?>
        </div>

        <!-- Réponses détaillées -->
        <div class="answers">
            <?php foreach ($questions as $index => $question): ?>
                <div class="answer-card">
                    <h4>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['question_text']) ?></h4>
                    <p><strong>Votre réponse :</strong> 
                        <span class="<?= ($answers[$question['id']] === $question['correct_answer']) ? 'correct' : 'incorrect' ?>">
                            <?= htmlspecialchars($answers[$question['id']] ?? 'Non répondue') ?>
                        </span>
                    </p>
                    <p><strong>Réponse correcte :</strong> 
                        <span class="correct"><?= htmlspecialchars($question['correct_answer']) ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bouton de retour -->
        <a href="index.php">Retour à l'accueil</a>
    </div>
</body>
</html>