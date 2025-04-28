<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Récupérer l'utilisateur depuis la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->execute([$username, $role]);
    $user = $stmt->fetch();

    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Redirection en fonction du rôle
        if ($user['role'] === 'student') {
            header('Location: student.php');
        } elseif ($user['role'] === 'professor') {
            header('Location: professor.php');
        }
        exit();
    } else {
        header('Location: index.php?error=1');
    }
}
?>