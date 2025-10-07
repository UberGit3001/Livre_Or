<?php
require_once __DIR__ . '/../includes/header.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Requête invalide (CSRF).";
    }

    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $errors[] = "Login et mot de passe requis.";
    } else {
        $user = get_user_by_login($pdo, $login);
        if ($user && password_verify($password, $user['password'])) {
            // login ok
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            header('Location: livre-or.php');
            exit;
        } else {
            $errors[] = "Identifiants invalides.";
        }
    }
}
?>

<h2>Connexion</h2>

<?php if (isset($_GET['registered'])): ?>
  <p class="success">Inscription réussie. Vous pouvez vous connecter.</p>
<?php endif; ?>

<?php if ($errors): ?>
  <div class="errors"><ul><?php foreach ($errors as $err) echo "<li>".e($err)."</li>"; ?></ul></div>
<?php endif; ?>

<form method="post" novalidate>
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>
    Login
    <input type="text" name="login" value="<?php echo e($_POST['login'] ?? ''); ?>" required>
  </label>
  <label>
    Mot de passe
    <input type="password" name="password" required>
  </label>
  <button type="submit">Se connecter</button>
</form>

<?php
require_once __DIR__ . '/../includes/footer.php';
