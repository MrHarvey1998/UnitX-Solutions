<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    // ✔️ Korrekte Weiterleitung nach Benutzerrolle
    if ($user['role'] === 'admin') {
      header("Location: admin.php");
      exit;
    } else {
      header("Location: dashboard.php");
      exit;
    }
  } else {
    $error = "Falsche Zugangsdaten.";
  }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Login - UnitX Solutions</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark text-white">
  <div class="container py-5">
  <center><img src="unitx-logo.png" alt="UnitX Logo"></center>
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">E-Mail</label>
        <input type="email" name="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Passwort</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary">Einloggen</button>
    </form>
    <p class="mt-3">Noch kein Konto? <a href="register.php" class="text-info">Registrieren</a></p>
  </div>
</body>
</html>
