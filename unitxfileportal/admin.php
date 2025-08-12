<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

// Zahlung manuell setzen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_paid'])) {
  $upload_id = $_POST['upload_id'];
  $pdo->prepare("UPDATE uploads SET bezahlt = 'ja' WHERE id = ?")->execute([$upload_id]);
}

// Datei freigeben + Mail senden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_id']) && isset($_FILES['responsefile'])) {
  $upload_id = $_POST['upload_id'];
  $response_path = '';

  if ($_FILES['responsefile']['error'] === 0) {
    $filename = time() . "_" . basename($_FILES['responsefile']['name']);
    $response_path = "uploads/responses/" . $filename;
    move_uploaded_file($_FILES['responsefile']['tmp_name'], $response_path);

    $stmt = $pdo->prepare("UPDATE uploads SET status = 'freigegeben', file_response = ? WHERE id = ?");
    $stmt->execute([$response_path, $upload_id]);

    // Kunden-Mail
    $stmtUser = $pdo->prepare("SELECT users.email FROM uploads JOIN users ON uploads.user_id = users.id WHERE uploads.id = ?");
    $stmtUser->execute([$upload_id]);
    $customer = $stmtUser->fetch();

    if ($customer) {
      $to = $customer['email'];
      $subject = "Deine Datei bei UnitX ist fertig";
      $message = "Hallo,\n\ndeine Datei wurde bearbeitet und steht jetzt im Kundenbereich zum Download bereit.\n\nViele Grüße\nUnitX Solutions";
      $headers = "From: info@unitxsolutions.de\r\n";

      mail($to, $subject, $message, $headers);
    }
  }
}

// Uploads abrufen
$stmt = $pdo->query("SELECT uploads.*, users.email FROM uploads JOIN users ON uploads.user_id = users.id ORDER BY uploads.created_at DESC");
$uploads = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Adminbereich - UnitX</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark text-white p-4">
  <div class="container">
    <h2>Admin-Dateifreigabe</h2>
    <a href="logout.php" class="btn btn-outline-light float-end mb-3">Logout</a>
    <table class="table table-dark table-bordered table-sm">
      <thead>
        <tr>
          <th>Datum</th>
          <th>Nutzer</th>
          <th>Service</th>
          <th>Status</th>
          <th>Bezahlt</th>
          <th>BIN</th>
          <th>Antwort hochladen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($uploads as $upload): ?>
        <tr>
          <td><?= $upload['created_at'] ?></td>
          <td><?= $upload['email'] ?></td>
          <td><?= $upload['service'] ?></td>
          <td><?= $upload['status'] ?></td>
          
          <!-- Bezahlt -->
          <td>
            <?php if ($upload['bezahlt'] === 'ja'): ?>
              <span class="text-success">Ja</span>
            <?php else: ?>
              <form method="post" style="display:inline;">
                <input type="hidden" name="upload_id" value="<?= $upload['id'] ?>">
                <button type="submit" name="mark_paid" class="btn btn-warning btn-sm">Zahlung erhalten</button>
              </form>
            <?php endif; ?>
          </td>

          <!-- Download -->
          <td>
            <a href="<?= $upload['file_path'] ?>" download class="btn btn-sm btn-info">BIN</a>
          </td>

          <!-- Antwort hochladen -->
          <td>
            <?php if ($upload['status'] === 'offen' && $upload['bezahlt'] === 'ja'): ?>
              <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="upload_id" value="<?= $upload['id'] ?>" />
                <input type="file" name="responsefile" required />
                <button type="submit" class="btn btn-sm btn-success">Freigeben</button>
              </form>
            <?php elseif ($upload['status'] === 'freigegeben'): ?>
              <span class="text-success">✓ freigegeben</span>
            <?php else: ?>
              <span class="text-warning">Warten auf Zahlung</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
