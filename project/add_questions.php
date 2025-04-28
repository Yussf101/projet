<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un professeur
if (!isset($_SESSION['utilisateurs_id']) || $_SESSION['role'] !== 'professor') {
    header('Location: index.php');
    exit();
}

// Ajouter une nouvelle question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionText = $_POST['question_text'];
    $optionA = $_POST['option_a'];
    $optionB = $_POST['option_b'];
    $optionC = $_POST['option_c'];
    $optionD = $_POST['option_d'];
    $correctAnswer = $_POST['correct_answer'];

    // Insérer la question dans la base de données
    $stmt = $pdo->prepare("INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$questionText, $optionA, $optionB, $optionC, $optionD, $correctAnswer]);

    // Message de succès
    $_SESSION['success'] = "La question a été ajoutée avec succès !";
    header('Location: add_questions.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des questions</title>
    <style>
        /* Style général */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        /* Conteneur principal */
        .add-question-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        /* Titre */
        .add-question-container h2 {
            margin-bottom: 1.5rem;
            color: #28a745;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Formulaire */
        .add-question-form input,
        .add-question-form textarea,
        .add-question-form select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #dddddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .add-question-form input:focus,
        .add-question-form textarea:focus,
        .add-question-form select:focus {
            border-color: #28a745;
            outline: none;
        }

        .add-question-form button {
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

        .add-question-form button:hover {
            background-color: #218838;
        }

        /* Message de succès */
        .success-message {
            margin-bottom: 1rem;
            color: #28a745;
            font-weight: bold;
        }

        /* Animation d'apparition */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .add-question-container {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body>
    <!-- Conteneur principal -->
    <div class="add-question-container">
        <h2>Ajouter des questions</h2>

        <!-- Message de succès -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Formulaire pour ajouter une question -->
        <form method="POST" class="add-question-form">
            <textarea name="question_text" placeholder="Texte de la question" required></textarea>
            <input type="text" name="option_a" placeholder="Option A" required>
            <input type="text" name="option_b" placeholder="Option B" required>
            <input type="text" name="option_c" placeholder="Option C" required>
            <input type="text" name="option_d" placeholder="Option D" required>
            <select name="correct_answer" required>
                <option value="" disabled selected>Choisissez la réponse correcte</option>
                <option value="a">Option A</option>
                <option value="b">Option B</option>
                <option value="c">Option C</option>
                <option value="d">Option D</option>
            </select>
            <button type="submit">Ajouter la question</button>
        </form>
    </div>
</body>
</html>