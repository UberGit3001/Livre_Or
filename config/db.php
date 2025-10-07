<?php
// config/db.php
// adapte host / dbname / user / pass selon ton environnement
$host = '127.0.0.1';
$db   = 'livreor';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // en dev tu peux afficher l'erreur, en prod logger et message générique
    exit('Erreur connexion BDD : ' . $e->getMessage());
}
