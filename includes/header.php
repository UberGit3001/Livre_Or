<?php
// includes/header.php
require_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Livre d'or</title>
  <!-- <link rel="stylesheet" href="/public/style.css"> -->
  <link rel="stylesheet" href="./style.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <h1><a href="./index.php">Mon Livre d'or</a></h1>
    <nav>
      <!-- <a href="/public/livre-or.php">Livre d'or</a> -->
      <a href="./livre-or.php">Livre d'or</a>
      <?php if (is_logged()): ?>
        <!-- <a href="/public/commentaire.php">Ajouter un commentaire</a> -->
        <a href="./commentaire.php">Ajouter un commentaire</a>
        <a href="./profil.php">Profil (<?php echo e($_SESSION['login'] ?? ''); ?>)</a>
        <!-- <a href="/public/logout.php">Déconnexion</a> -->
        <a href="./logout.php">Déconnexion</a>
      <?php else: ?>
        <!-- <a href="/public/inscription.php">Inscription</a> -->
        <a href="./inscription.php">Inscription</a>
        <!-- <a href="/public/connexion.php">Connexion</a> -->
        <a href="./connexion.php">Connexion</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
