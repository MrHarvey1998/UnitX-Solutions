<?php
// Konfiguriere deine E-Mail-Adresse hier
$empfaenger = "info@unitxsolutions.de";

// Eingaben filtern
$name = htmlspecialchars($_POST['name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$nachricht = htmlspecialchars($_POST['message']);

// Header und Nachricht aufbauen
$betreff = "Neue Kontaktanfrage von UnitX Webseite";
$inhalt = "Name: $name\nE-Mail: $email\n\nNachricht:\n$nachricht";

$header = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=utf-8";

// Mail senden
if (mail($empfaenger, $betreff, $inhalt, $header)) {
    echo "Vielen Dank für deine Nachricht!";
} else {
    echo "Es ist ein Fehler beim Senden aufgetreten.";
}
?>
