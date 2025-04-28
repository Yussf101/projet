<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est un professeur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header('Location: index.php');
    exit();
}

// Récupérer les filières éduquées par le professeur
$stmt = $pdo->prepare("SELECT fields FROM professors WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$professorFields = $stmt->fetchColumn();
$professorFields = explode(',', $professorFields);

// Récupérer les résultats des étudiants regroupés par filière
$resultsByField = [];
foreach ($professorFields as $field) {
    $stmt = $pdo->prepare("
        SELECT u.first_name, u.last_name, r.score, r.date 
        FROM results r 
        JOIN users u ON r.student_id = u.id
        JOIN students s ON u.id = s.user_id
        WHERE s.field = ?
    ");
    $stmt->execute([$field]);
    $resultsByField[$field] = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des étudiants</title>
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

        /* Conteneur principal */
        .view-scores-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
        }

        /* Titre */
        .view-scores-container h2 {
            margin-bottom: 1.5rem;
            color: #007bff;
            font-size: 2rem;
            font-weight: bold;
        }

        /* Tableau des résultats */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .results-table th,
        .results-table td {
            padding: 1rem;
            border-bottom: 1px solid #dddddd;
            text-align: left;
        }

        .results-table th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
        }

        .results-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .results-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Titre de la filière */
        .field-title {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #333333;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Conteneur principal -->
    <div class="view-scores-container">
        <h2>Résultats des étudiants</h2>

        <!-- Affichage des résultats par filière -->
        <?php foreach ($resultsByField as $field => $results): ?>
            <div class="field-title">Filière : <?= htmlspecialchars($field) ?></div>
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Nom complet</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) ?></td>
                            <td><?= htmlspecialchars($result['score']) ?></td>
                            <td><?= htmlspecialchars($result['date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</body>
</html>