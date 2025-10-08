<?php
require_once __DIR__ . '/../includes/header.php';

// récupération commentaires + join utilisateur
$stmt = $pdo->query('SELECT c.commentaire, c.date, u.login FROM commentaires c JOIN utilisateurs u ON c.id_utilisateur = u.id ORDER BY c.date DESC');
$comments = $stmt->fetchAll();
?>

<h2>Livre d'or</h2>

<?php if (is_logged()): ?>
  <p><a href="commentaire.php" class="btn">Ajouter un commentaire</a></p>
<?php else: ?>
  <p>Vous devez être connecté pour poster un commentaire. <a href="connexion.php">Se connecter</a> ou <a href="inscription.php">s'inscrire</a>.</p>
<?php endif; ?>

<?php if (empty($comments)): ?>
  <p>Aucun commentaire pour l'instant.</p>
<?php else: ?>
  <section class="comments">
    <?php foreach ($comments as $c): ?>
      <article class="comment">
        <header>
          <strong><?php echo e($c['login']); ?></strong>
          <time><?php echo (new DateTime($c['date']))->format('d/m/Y'); ?></time>
        </header>
        <p><?php echo nl2br(e($c['commentaire'])); ?></p>
      </article>
    <?php endforeach; ?>
  </section>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
