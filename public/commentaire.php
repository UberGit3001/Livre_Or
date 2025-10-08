<?php
require_once __DIR__ . '/../includes/header.php';
if (!is_logged()) {
    header('Location: connexion.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Requête invalide (CSRF).";
    }

    $comment = trim($_POST['commentaire'] ?? '');
    if ($comment === '') $errors[] = "Le commentaire ne peut pas être vide.";

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO commentaires (commentaire, id_utilisateur, date) VALUES (:commentaire, :id_utilisateur, NOW())');
        $stmt->execute([
            ':commentaire' => $comment,
            ':id_utilisateur' => $_SESSION['user_id']
        ]);
        header('Location: livre-or.php?posted=1');
        exit;
    }
}
?>

<h2>Ajouter un commentaire</h2>

<?php if ($errors): ?>
  <div class="errors"><ul><?php foreach ($errors as $err) echo "<li>".e($err)."</li>"; ?></ul></div>
<?php endif; ?>

<form method="post" novalidate>
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>
    Votre commentaire
    <textarea name="commentaire" rows="6" required><?php echo e($_POST['commentaire'] ?? ''); ?></textarea>
  </label>
  <button type="submit">Poster</button>
</form>

<?php
require_once __DIR__ . '/../includes/footer.php';