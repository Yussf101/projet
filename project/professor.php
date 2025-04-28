<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un professeur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header('Location: index.php');
    exit();
}

// Redirection en fonction du choix
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_questions'])) {
        header('Location: add_questions.php');
        exit();
    } elseif (isset($_POST['view_scores'])) {
        header('Location: view_scores.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Professeur</title>
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
        .professor-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }

        /* Titre */
        .professor-container h2 {
            margin-bottom: 2rem;
            color: #007bff;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Cartes d'options */
        .options-container {
            display: flex;
            justify-content: space-around;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .option-card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 45%;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        /* Carte "Ajouter des questions" */
        .option-card.add-questions {
            background: linear-gradient(135deg, #28a745, #218838);
            color: #ffffff;
        }

        /* Carte "Voir les notes" */
        .option-card.view-scores {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #ffffff;
        }

        /* Icônes */
        .option-card i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .option-card h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .option-card p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
        }

        .option-card button {
            padding: 0.75rem 1.5rem;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .option-card button:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Animation d'apparition */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .option-card {
            animation: fadeIn 0.5s ease;
        }
    </style>
    <!-- Lien vers FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Conteneur principal -->
    <div class="professor-container">
        <h2>Espace Professeur</h2>

        <!-- Cartes d'options -->
        <div class="options-container">
            <!-- Carte pour ajouter des questions -->
            <form method="POST" class="option-card add-questions">
                <i class="fas fa-pencil-alt"></i>
                <h3>Ajouter des questions</h3>
                <p>Créez de nouvelles questions pour le QCM.</p>
                <button type="submit" name="add_questions">Commencer</button>
            </form>

            <!-- Carte pour voir les notes -->
            <form method="POST" class="option-card view-scores">
                <i class="fas fa-chart-bar"></i>
                <h3>Voir les notes des étudiants</h3>
                <p>Consultez les résultats des étudiants.</p>
                <button type="submit" name="view_scores">Voir</button>
            </form>
        </div>
    </div>
</body>
</html>