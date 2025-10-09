<?php
// public/logout.php

require_once __DIR__ . '/../includes/functions.php';

// Démarre la session pour pouvoir utiliser $_SESSION
session_start();

// Stocke le message avant de détruire la session
$_SESSION['logout_message'] = "Déconnexion réussie.";

// Détruit la session
session_unset();
session_destroy();

// Redirige vers index.php
header('Location: ./index.php');
exit;
?>