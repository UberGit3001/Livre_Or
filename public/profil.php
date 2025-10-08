<?php
require_once __DIR__ . '/../includes/header.php';
if (!is_logged()) {
    header('Location: connexion.php');
    exit;
}

$errors = [];
$user = get_user_by_id($pdo, (int)$_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Requête invalide (CSRF).";
    }

    $new_login = trim($_POST['login'] ?? '');
    $new_password = $_POST['password'] ?? '';
    $new_password_confirm = $_POST['password_confirm'] ?? '';

    if ($new_login === '') $errors[] = "Le login est requis.";

    // si login changé, vérifier unicité
    if ($new_login !== $user['login']) {
        $exists = get_user_by_login($pdo, $new_login);
        if ($exists) $errors[] = "Ce login est déjà utilisé.";
    }

    if ($new_password !== '') {
        if ($new_password !== $new_password_confirm) $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        if ($new_password !== '') {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE utilisateurs SET login = :login, password = :password WHERE id = :id');
            $stmt->execute([':login'=>$new_login, ':password'=>$hash, ':id'=>$user['id']]);
        } else {
            $stmt = $pdo->prepare('UPDATE utilisateurs SET login = :login WHERE id = :id');
            $stmt->execute([':login'=>$new_login, ':id'=>$user['id']]);
        }
        // mettre à jour session
        $_SESSION['login'] = $new_login;
        $success = "Profil mis à jour.";
    }
}
?>

<h2>Mon profil</h2>

<?php if (!empty($errors)): ?>
  <div class="errors"><ul><?php foreach ($errors as $err) echo "<li>".e($err)."</li>"; ?></ul></div>
<?php endif; ?>

<?php if (!empty($success)): ?><p class="success"><?php echo e($success); ?></p><?php endif; ?>

<form method="post" novalidate>
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>
    Login
    <input type="text" name="login" value="<?php echo e($_POST['login'] ?? $user['login']); ?>" required>
  </label>
  <label>
    Nouveau mot de passe (laissez vide pour conserver l'actuel)
    <input type="password" name="password">
  </label>
  <label>
    Confirmer le nouveau mot de passe
    <input type="password" name="password_confirm">
  </label>
  <button type="submit">Enregistrer</button>
</form>

<?php
require_once __DIR__ . '/../includes/footer.php';
