<?php
// includes/functions.php
session_start();

require_once __DIR__ . '/../config/db.php';

// nettoyage simple
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// vérifie si utilisateur connecté
function is_logged(): bool {
    return !empty($_SESSION['user_id']);
}

// récupère utilisateur par id
function get_user_by_id(PDO $pdo, int $id) {
    $stmt = $pdo->prepare('SELECT id, login FROM utilisateurs WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

// récupère utilisateur par login
function get_user_by_login(PDO $pdo, string $login) {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE login = :login');
    $stmt->execute([':login' => $login]);
    return $stmt->fetch();
}

// crée token CSRF simple
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function check_csrf($token) {
    return !empty($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
