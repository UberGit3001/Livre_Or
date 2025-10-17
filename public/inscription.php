<?php
require_once __DIR__ . '/../includes/header.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // --- 1. Vérification du token CSRF ---
    if (!check_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = "Requête invalide (CSRF).";
    }

    // --- 2. Récupération et nettoyage des champs ---
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // --- 3. Vérifications basiques ---
    if ($login === '') $errors[] = "Le login est requis.";
    if ($password === '') $errors[] = "Le mot de passe est requis.";
    if ($password !== $password_confirm) $errors[] = "Les mots de passe ne correspondent pas.";

    // --- 4. Vérifications avancées de la complexité du mot de passe ---
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_,&,-,.,@]).{8,}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, 
        avec une majuscule, une minuscule, un chiffre et/ou l'un de ces quatre symboles.";
    }

      // --- 5. vérifier unicité du login ---
    if (empty($errors)) {
        $existing = get_user_by_login($pdo, $login);
        if ($existing) {
            $errors[] = "Le login est déjà pris.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (login, password) VALUES (:login, :password)');
            $stmt->execute([':login' => $login, ':password' => $hash]);
            header('Location: connexion.php?registered=1');
            exit;
        }
    }

}
?>

<h2>Inscription</h2>

<?php if ($errors): ?>
  <div class="errors">
    <ul>
      <?php foreach ($errors as $err): ?>
      <li><?php echo e($err); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" novalidate>
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>
    Login
    <input type="text" name="login" value="<?php echo e($_POST['login'] ?? ''); ?>" maxlength="255" required>
  </label>
  <label>
    Mot de passe
    <input type="password" name="password" required>
  </label>
  <label>
    Confirmer le mot de passe
    <input type="password" name="password_confirm" required>
  </label>
  <button type="submit">S'inscrire</button>
</form>

<?php
require_once __DIR__ . '/../includes/footer.php';
