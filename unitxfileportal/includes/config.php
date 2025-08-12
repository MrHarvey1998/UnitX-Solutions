<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db   = 'unitxsolutions';
$user = 'unitxusr';
$pass = 'sAR#nPjw#s2l2o1f';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>
