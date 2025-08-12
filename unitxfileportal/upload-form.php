<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Datei hochladen - UnitX</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark text-white p-4">
  <div class="container">
    <h2>Datei hochladen</h2>
    <form method="post" action="upload.php" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Fahrzeugart</label>
          <select class="form-select" name="fahrzeugart" required>
            <option value="PKW">PKW</option>
            <option value="LKW">LKW</option>
          </select>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Marke</label>
          <input type="text" name="marke" class="form-control" required />
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Modell</label>
          <input type="text" name="modell" class="form-control" required />
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Baujahr</label>
          <input type="text" name="baujahr" class="form-control" required />
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Steuergerät</label>
          <input type="text" name="steuergeraet" class="form-control" required />
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Seriennummer</label>
          <input type="text" name="seriennummer" class="form-control" required />
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Service</label>
        <select class="form-select" name="service" required>
          <option value="Stage 1">Stage 1</option>
          <option value="Stage 2">Stage 2</option>
          <option value="EGR Off">EGR Off</option>
          <option value="DPF Off">DPF Off</option>
          <option value="AdBlue Off">AdBlue Off</option>
          <option value="Vmax Off">Vmax Off</option>
          <option value="Pop&Bang / Hardcut">Pop&Bang / Hardcut</option>
          <option value="Sonstiges">Sonstiges</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">BIN-Datei</label>
        <input type="file" name="binfile" class="form-control" accept=\".bin,.ori,.frf,.hex\" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Hinweis</label>
        <textarea name="hinweis" class="form-control" rows="3"></textarea>
      </div>
	  <div class="form-check mt-4">
		<input class="form-check-input" type="checkbox" id="agbCheck" name="agb_akzeptiert" required>
		<label class="form-check-label" for="agbCheck">
		Ich akzeptiere die <a href="https://unitxsolutions.de/agb.html" target="_blank">AGB</a> sowie den <a href="https://unitxsolutions.de/haftungsausschluss.html" target="_blank">Haftungsausschluss</a>.
		</label>
		</div>
      <button type="submit" class="btn btn-primary">Upload starten</button>
    </form>
    <a href="dashboard.php" class="btn btn-outline-light mt-3">Zurück zum Dashboard</a>
  </div>
</body>
</html>
