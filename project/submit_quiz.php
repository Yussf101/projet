<?php
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit();
}

$answers = $_POST['answers'];
$score = 0;

$questions = $pdo->query("SELECT id, correct_answer FROM questions")->fetchAll(PDO::FETCH_KEY_PAIR);

foreach ($answers as $qId => $answer) {
    if (isset($questions[$qId]) && $answer === $questions[$qId]) {
        $score++;
    }
}

$stmt = $pdo->prepare("INSERT INTO results (student_id, score) VALUES (?, ?)");
$stmt->execute([$_SESSION['user_id'], $score]);

echo "Votre score : $score/" . count($questions);
?>