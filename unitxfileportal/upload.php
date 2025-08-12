<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Prüfen ob AGB-Checkbox gesetzt wurde
  if (!isset($_POST['agb_akzeptiert'])) {
    die("Du musst die AGB und den Haftungsausschluss akzeptieren, um fortzufahren.");
  }

  $fahrzeugart = $_POST['fahrzeugart'];
  $marke = $_POST['marke'];
  $modell = $_POST['modell'];
  $baujahr = $_POST['baujahr'];
  $steuergeraet = $_POST['steuergeraet'];
  $seriennummer = $_POST['seriennummer'];
  $service = $_POST['service'];
  $hinweis = $_POST['hinweis'];
  $uploadPath = '';

  // Benutzer-E-Mail holen
  $stmtUser = $pdo->prepare("SELECT email FROM users WHERE id = ?");
  $stmtUser->execute([$user_id]);
  $userInfo = $stmtUser->fetch();
  $email = $userInfo ? $userInfo['email'] : 'unbekannt';

  if (isset($_FILES['binfile']) && $_FILES['binfile']['error'] === 0) {
    $filename = time() . "_" . basename($_FILES['binfile']['name']);
    $uploadPath = "uploads/" . $filename;
    move_uploaded_file($_FILES['binfile']['tmp_name'], $uploadPath);

    $stmt = $pdo->prepare("INSERT INTO uploads (user_id, file_path, fahrzeugart, marke, modell, baujahr, steuergeraet, seriennummer, service, hinweis, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'offen')");
    $stmt->execute([$user_id, $uploadPath, $fahrzeugart, $marke, $modell, $baujahr, $steuergeraet, $seriennummer, $service, $hinweis]);

    // Admin-Benachrichtigung
    $adminEmail = "info@unitxsolutions.de";
    $subject = "Neue Datei hochgeladen von $email";
    $message = "Eine neue Datei wurde hochgeladen.\n\n"
             . "Fahrzeugart: $fahrzeugart\nMarke: $marke\nModell: $modell\nBaujahr: $baujahr\n"
             . "Steuergerät: $steuergeraet\nSeriennummer: $seriennummer\n"
             . "Service: $service\nHinweis: $hinweis\n\n"
             . "Dateipfad: $uploadPath\nKunden-ID: $user_id\nE-Mail: $email\n\n"
             . "✅ Der Kunde hat die AGB und den Haftungsausschluss akzeptiert.";
             
    $headers = "From: no-reply@unitxsolutions.de\r\n";

    mail($adminEmail, $subject, $message, $headers);
  }

  header("Location: dashboard.php");
  exit;
}
?>
