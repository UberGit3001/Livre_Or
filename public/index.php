<?php

// session_start();
if (isset($_SESSION['logout_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['logout_message'] . '</div>';
    unset($_SESSION['logout_message']);
}

require_once __DIR__ . '/../includes/header.php';
?>
<h2>Bienvenue sur le livre d'or</h2>
<p>Ce site permet à vos visiteurs de laisser un avis. Consultez les commentaires ou connectez-vous pour en poster un.</p>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
