<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM uploads WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$uploads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Kundenbereich - UnitX</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark text-white p-4">
  <div class="container">

    <!-- 🔧 LOGO oben zentriert -->
    <div class="text-center mb-4">
      <img src="unitx-logo.png" alt="UnitX Logo" style="width: 150px;">
      <!-- Optional: <p class="mt-2">Ihr persönlicher File-Service Bereich</p> -->
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Meine Uploads</h2>
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>

    <table class="table table-dark table-bordered table-sm">
      <thead>
        <tr>
          <th>Datum</th>
          <th>Fahrzeug</th>
          <th>Service</th>
          <th>Status</th>
          <th>Download</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($uploads as $upload): ?>
          <tr>
            <td><?= $upload['created_at'] ?></td>
            <td><?= $upload['marke'] ?> <?= $upload['modell'] ?> (<?= $upload['baujahr'] ?>)</td>
            <td><?= $upload['service'] ?></td>
            <td><?= $upload['status'] ?></td>
            <td>
              <?php if ($upload['status'] === 'freigegeben' && $upload['bezahlt'] === 'ja' && $upload['file_response']): ?>
                <a href="<?= $upload['file_response'] ?>" download class="btn btn-sm btn-success">Download</a>
              <?php else: ?>–<?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="upload-form.php" class="btn btn-primary mt-3">Neue Datei hochladen</a>
  </div>
</body>
</html>

